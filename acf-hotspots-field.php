<?php
/**
 * Hotspots sur les images de la médiathèque
 *
 * Ajoute un éditeur visuel drag & drop directement sur les images
 * de la médiathèque WordPress. Les hotspots sont stockés dans les
 * métadonnées de l'attachment.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Ajouter le champ personnalisé dans la médiathèque
 */
add_filter( 'attachment_fields_to_edit', 'wwb_hotspots_attachment_field', 10, 2 );
function wwb_hotspots_attachment_field( $form_fields, $post ) {
    // Seulement pour les images
    if ( ! wp_attachment_is_image( $post->ID ) ) {
        return $form_fields;
    }

    $hotspots_data = get_post_meta( $post->ID, '_wwb_hotspots', true );
    $hotspots_json = $hotspots_data ? $hotspots_data : '[]';

    $form_fields['wwb_hotspots'] = array(
        'label' => 'Points interactifs',
        'input' => 'html',
        'html'  => '
            <div id="wwb-hotspots-attachment-' . $post->ID . '" class="wwb-hotspots-attachment-editor" data-attachment-id="' . $post->ID . '">
                <button type="button" class="button wwb-open-hotspots-editor" data-attachment-id="' . $post->ID . '">
                    Gérer les hotspots
                </button>
                <span class="wwb-hotspots-count"></span>
                <input type="hidden" name="attachments[' . $post->ID . '][wwb_hotspots]" value="' . esc_attr( $hotspots_json ) . '" class="wwb-hotspots-data">
            </div>
        ',
        'helps' => 'Cliquez pour ajouter des points interactifs sur cette image',
    );

    return $form_fields;
}

/**
 * Sauvegarder les hotspots
 */
add_filter( 'attachment_fields_to_save', 'wwb_hotspots_attachment_save', 10, 2 );
function wwb_hotspots_attachment_save( $post, $attachment ) {
    if ( isset( $attachment['wwb_hotspots'] ) ) {
        update_post_meta( $post['ID'], '_wwb_hotspots', $attachment['wwb_hotspots'] );
    }
    return $post;
}

/**
 * Charger les scripts dans l'admin
 */
add_action( 'admin_enqueue_scripts', 'wwb_hotspots_media_scripts' );
function wwb_hotspots_media_scripts( $hook ) {
    // Charger sur les pages média et édition
    if ( ! in_array( $hook, array( 'post.php', 'post-new.php', 'upload.php' ) ) ) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_script( 'jquery-ui-draggable' );

    // Ajouter les styles inline
    wp_add_inline_style( 'media-views', wwb_hotspots_media_css() );
}

/**
 * Ajouter le script dans le footer admin
 */
add_action( 'admin_footer', 'wwb_hotspots_media_js' );
function wwb_hotspots_media_js() {
    $screen = get_current_screen();
    if ( ! $screen || ! in_array( $screen->base, array( 'post', 'upload' ) ) ) {
        return;
    }
    ?>
    <div id="wwb-hotspots-modal" class="wwb-modal" style="display:none;">
        <div class="wwb-modal-overlay"></div>
        <div class="wwb-modal-content">
            <div class="wwb-modal-header">
                <h2>Éditer les points interactifs</h2>
                <button type="button" class="wwb-modal-close">&times;</button>
            </div>
            <div class="wwb-modal-body">
                <div class="wwb-hotspots-editor">
                    <div class="wwb-hotspots-image-wrapper" id="wwb-modal-image-wrapper">
                        <img src="" alt="Image" id="wwb-modal-image">
                    </div>
                    <div class="wwb-hotspots-list">
                        <h4>Points interactifs</h4>
                        <div id="wwb-modal-hotspots-items"></div>
                        <div class="wwb-hotspots-help">
                            <strong>Comment utiliser :</strong><br>
                            • Cliquez sur l'image pour ajouter un point<br>
                            • Glissez-déposez pour repositionner<br>
                            • Remplissez titre et description
                        </div>
                    </div>
                </div>
            </div>
            <div class="wwb-modal-footer">
                <button type="button" class="button wwb-modal-cancel">Annuler</button>
                <button type="button" class="button button-primary wwb-modal-save">Enregistrer</button>
            </div>
        </div>
    </div>

    <script>
    (function($) {
        'use strict';

        var WWBHotspotsMedia = {
            currentAttachmentId: null,
            currentDataField: null,
            hotspots: [],
            modal: null,
            imageWrapper: null,
            draggedMarker: null,
            dragOffset: {},

            init: function() {
                var self = this;
                this.modal = $('#wwb-hotspots-modal');
                this.imageWrapper = $('#wwb-modal-image-wrapper');

                // Ouvrir l'éditeur
                $(document).on('click', '.wwb-open-hotspots-editor', function(e) {
                    e.preventDefault();
                    var attachmentId = $(this).data('attachment-id');
                    self.openEditor(attachmentId);
                });

                // Fermer le modal
                this.modal.on('click', '.wwb-modal-close, .wwb-modal-overlay, .wwb-modal-cancel', function() {
                    self.closeEditor();
                });

                // Sauvegarder
                this.modal.on('click', '.wwb-modal-save', function() {
                    self.saveAndClose();
                });

                // Clic sur l'image pour ajouter un hotspot
                this.imageWrapper.on('click', function(e) {
                    if ($(e.target).hasClass('wwb-hotspot-marker')) return;
                    self.addHotspot(e);
                });

                // Drag & drop
                $(document).on('mousemove', function(e) {
                    self.onDrag(e);
                });
                $(document).on('mouseup', function(e) {
                    self.onDragEnd(e);
                });

                // Mettre à jour le compteur au chargement
                this.updateAllCounters();

                // Support pour la vue grille de la médiathèque
                if (typeof wp !== 'undefined' && wp.media) {
                    wp.media.view.Attachment.Details.prototype.on('ready', function() {
                        setTimeout(function() {
                            self.updateAllCounters();
                        }, 100);
                    });
                }
            },

            openEditor: function(attachmentId) {
                var self = this;
                this.currentAttachmentId = attachmentId;

                // Trouver le champ de données
                var container = $('#wwb-hotspots-attachment-' + attachmentId);
                if (!container.length) {
                    container = $('[data-attachment-id="' + attachmentId + '"]').closest('.wwb-hotspots-attachment-editor');
                }
                this.currentDataField = container.find('.wwb-hotspots-data');

                // Charger les hotspots existants
                try {
                    var data = this.currentDataField.val();
                    this.hotspots = data ? JSON.parse(data) : [];
                } catch(e) {
                    this.hotspots = [];
                }

                // Charger l'image via AJAX
                $.post(ajaxurl, {
                    action: 'wwb_get_attachment_url',
                    attachment_id: attachmentId
                }, function(response) {
                    if (response.success) {
                        $('#wwb-modal-image').attr('src', response.data.url);
                        self.modal.show();
                        self.renderMarkers();
                        self.renderList();
                    }
                });
            },

            closeEditor: function() {
                this.modal.hide();
                this.currentAttachmentId = null;
                this.currentDataField = null;
                this.hotspots = [];
                this.imageWrapper.find('.wwb-hotspot-marker').remove();
                $('#wwb-modal-hotspots-items').empty();
            },

            saveAndClose: function() {
                if (this.currentDataField) {
                    this.currentDataField.val(JSON.stringify(this.hotspots)).trigger('change');
                    this.updateCounter(this.currentAttachmentId);

                    // Sauvegarder via AJAX pour la vue grille
                    $.post(ajaxurl, {
                        action: 'wwb_save_hotspots',
                        attachment_id: this.currentAttachmentId,
                        hotspots: JSON.stringify(this.hotspots),
                        nonce: '<?php echo wp_create_nonce('wwb_hotspots_nonce'); ?>'
                    });
                }
                this.closeEditor();
            },

            updateAllCounters: function() {
                var self = this;
                $('.wwb-hotspots-attachment-editor').each(function() {
                    var id = $(this).data('attachment-id');
                    self.updateCounter(id);
                });
            },

            updateCounter: function(attachmentId) {
                var container = $('[data-attachment-id="' + attachmentId + '"]');
                var dataField = container.find('.wwb-hotspots-data');
                var countSpan = container.find('.wwb-hotspots-count');

                try {
                    var hotspots = JSON.parse(dataField.val() || '[]');
                    var count = hotspots.length;
                    if (count > 0) {
                        countSpan.text(count + ' point(s)').show();
                    } else {
                        countSpan.hide();
                    }
                } catch(e) {
                    countSpan.hide();
                }
            },

            addHotspot: function(e) {
                var rect = this.imageWrapper[0].getBoundingClientRect();
                var x = ((e.clientX - rect.left) / rect.width) * 100;
                var y = ((e.clientY - rect.top) / rect.height) * 100;

                x = Math.max(2, Math.min(98, x));
                y = Math.max(2, Math.min(98, y));

                this.hotspots.push({
                    x: Math.round(x * 10) / 10,
                    y: Math.round(y * 10) / 10,
                    title: '',
                    description: ''
                });

                this.renderMarkers();
                this.renderList();
            },

            deleteHotspot: function(index) {
                this.hotspots.splice(index, 1);
                this.renderMarkers();
                this.renderList();
            },

            updateHotspot: function(index, field, value) {
                if (this.hotspots[index]) {
                    this.hotspots[index][field] = value;
                }
            },

            renderMarkers: function() {
                var self = this;
                this.imageWrapper.find('.wwb-hotspot-marker').remove();

                this.hotspots.forEach(function(hotspot, index) {
                    var marker = $('<div class="wwb-hotspot-marker" data-index="' + index + '">' + (index + 1) + '</div>');
                    marker.css({
                        left: hotspot.x + '%',
                        top: hotspot.y + '%'
                    });

                    marker.on('mousedown', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        self.onDragStart(e, $(this), index);
                    });

                    self.imageWrapper.append(marker);
                });
            },

            renderList: function() {
                var self = this;
                var listContainer = $('#wwb-modal-hotspots-items');
                var html = '';

                if (this.hotspots.length === 0) {
                    html = '<p class="wwb-no-hotspots">Aucun point. Cliquez sur l\'image pour en ajouter.</p>';
                } else {
                    this.hotspots.forEach(function(hotspot, index) {
                        html += '<div class="wwb-hotspot-item" data-index="' + index + '">';
                        html += '<div class="wwb-hotspot-item-header">';
                        html += '<span class="wwb-hotspot-number">' + (index + 1) + '</span>';
                        html += '<span class="wwb-hotspot-coords">X: ' + hotspot.x.toFixed(1) + '% | Y: ' + hotspot.y.toFixed(1) + '%</span>';
                        html += '<button type="button" class="wwb-hotspot-delete" data-index="' + index + '">&times;</button>';
                        html += '</div>';
                        html += '<div class="wwb-hotspot-fields">';
                        html += '<input type="text" placeholder="Titre" value="' + self.escapeHtml(hotspot.title || '') + '" data-index="' + index + '" data-field="title">';
                        html += '<textarea placeholder="Description" data-index="' + index + '" data-field="description">' + self.escapeHtml(hotspot.description || '') + '</textarea>';
                        html += '</div>';
                        html += '</div>';
                    });
                }

                listContainer.html(html);

                // Events
                listContainer.find('.wwb-hotspot-delete').on('click', function() {
                    self.deleteHotspot(parseInt($(this).data('index')));
                });

                listContainer.find('input, textarea').on('input', function() {
                    self.updateHotspot(parseInt($(this).data('index')), $(this).data('field'), $(this).val());
                });

                listContainer.find('.wwb-hotspot-item').on('mouseenter', function() {
                    var idx = $(this).data('index');
                    self.imageWrapper.find('.wwb-hotspot-marker[data-index="' + idx + '"]').addClass('highlight');
                }).on('mouseleave', function() {
                    self.imageWrapper.find('.wwb-hotspot-marker').removeClass('highlight');
                });
            },

            onDragStart: function(e, marker, index) {
                this.draggedMarker = { marker: marker, index: index };
                marker.addClass('dragging');

                var rect = this.imageWrapper[0].getBoundingClientRect();
                this.dragOffset = {
                    containerX: rect.left,
                    containerY: rect.top,
                    containerW: rect.width,
                    containerH: rect.height
                };
            },

            onDrag: function(e) {
                if (!this.draggedMarker) return;

                var x = ((e.clientX - this.dragOffset.containerX) / this.dragOffset.containerW) * 100;
                var y = ((e.clientY - this.dragOffset.containerY) / this.dragOffset.containerH) * 100;

                x = Math.max(2, Math.min(98, x));
                y = Math.max(2, Math.min(98, y));

                this.draggedMarker.marker.css({ left: x + '%', top: y + '%' });
                this.hotspots[this.draggedMarker.index].x = Math.round(x * 10) / 10;
                this.hotspots[this.draggedMarker.index].y = Math.round(y * 10) / 10;
            },

            onDragEnd: function(e) {
                if (!this.draggedMarker) return;
                this.draggedMarker.marker.removeClass('dragging');
                this.draggedMarker = null;
                this.renderList();
            },

            escapeHtml: function(str) {
                if (!str) return '';
                return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }
        };

        $(document).ready(function() {
            WWBHotspotsMedia.init();
        });

    })(jQuery);
    </script>
    <?php
}

/**
 * AJAX: Récupérer l'URL de l'image
 */
add_action( 'wp_ajax_wwb_get_attachment_url', 'wwb_get_attachment_url_ajax' );
function wwb_get_attachment_url_ajax() {
    $attachment_id = intval( $_POST['attachment_id'] );
    $url = wp_get_attachment_image_url( $attachment_id, 'large' );

    if ( $url ) {
        wp_send_json_success( array( 'url' => $url ) );
    } else {
        wp_send_json_error();
    }
}

/**
 * AJAX: Sauvegarder les hotspots
 */
add_action( 'wp_ajax_wwb_save_hotspots', 'wwb_save_hotspots_ajax' );
function wwb_save_hotspots_ajax() {
    check_ajax_referer( 'wwb_hotspots_nonce', 'nonce' );

    $attachment_id = intval( $_POST['attachment_id'] );
    $hotspots = sanitize_text_field( $_POST['hotspots'] );

    update_post_meta( $attachment_id, '_wwb_hotspots', $hotspots );
    wp_send_json_success();
}

/**
 * CSS pour l'éditeur
 */
function wwb_hotspots_media_css() {
    return '
    .wwb-hotspots-attachment-editor {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .wwb-hotspots-count {
        color: #E91E8C;
        font-weight: 600;
        font-size: 12px;
    }

    /* Modal */
    .wwb-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 160000;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .wwb-modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
    }
    .wwb-modal-content {
        position: relative;
        background: #fff;
        border-radius: 8px;
        width: 90%;
        max-width: 1000px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    }
    .wwb-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 20px;
        border-bottom: 1px solid #ddd;
    }
    .wwb-modal-header h2 {
        margin: 0;
        font-size: 18px;
    }
    .wwb-modal-close {
        background: none;
        border: none;
        font-size: 28px;
        cursor: pointer;
        color: #666;
        line-height: 1;
    }
    .wwb-modal-close:hover {
        color: #000;
    }
    .wwb-modal-body {
        flex: 1;
        overflow: auto;
        padding: 20px;
    }
    .wwb-modal-footer {
        padding: 15px 20px;
        border-top: 1px solid #ddd;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    /* Editor */
    .wwb-hotspots-editor {
        display: flex;
        gap: 20px;
    }
    .wwb-hotspots-image-wrapper {
        position: relative;
        flex: 1;
        max-width: 550px;
        border: 2px dashed #ccc;
        border-radius: 8px;
        overflow: hidden;
        cursor: crosshair;
    }
    .wwb-hotspots-image-wrapper img {
        display: block;
        width: 100%;
        height: auto;
    }
    .wwb-hotspot-marker {
        position: absolute;
        width: 28px;
        height: 28px;
        margin-left: -14px;
        margin-top: -14px;
        background: #E91E8C;
        border: 3px solid #fff;
        border-radius: 50%;
        cursor: move;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: bold;
        font-size: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        transition: transform 0.15s ease;
        z-index: 10;
    }
    .wwb-hotspot-marker:hover,
    .wwb-hotspot-marker.highlight {
        transform: scale(1.2);
    }
    .wwb-hotspot-marker.dragging {
        transform: scale(1.3);
        box-shadow: 0 4px 16px rgba(0,0,0,0.4);
        z-index: 100;
    }

    .wwb-hotspots-list {
        width: 320px;
        flex-shrink: 0;
    }
    .wwb-hotspots-list h4 {
        margin: 0 0 15px 0;
    }
    .wwb-no-hotspots {
        color: #888;
        font-size: 13px;
    }
    .wwb-hotspot-item {
        background: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 12px;
        margin-bottom: 10px;
    }
    .wwb-hotspot-item:hover {
        border-color: #E91E8C;
    }
    .wwb-hotspot-item-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
    }
    .wwb-hotspot-number {
        width: 22px;
        height: 22px;
        background: #E91E8C;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: bold;
    }
    .wwb-hotspot-coords {
        font-size: 11px;
        color: #888;
        flex: 1;
    }
    .wwb-hotspot-delete {
        background: #dc3545;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        cursor: pointer;
        font-size: 16px;
        line-height: 1;
    }
    .wwb-hotspot-delete:hover {
        background: #c82333;
    }
    .wwb-hotspot-fields {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .wwb-hotspot-fields input,
    .wwb-hotspot-fields textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .wwb-hotspot-fields textarea {
        min-height: 50px;
        resize: vertical;
    }
    .wwb-hotspots-help {
        background: #f0f0f1;
        padding: 12px;
        border-radius: 6px;
        margin-top: 15px;
        font-size: 12px;
        color: #666;
    }
    .wwb-hotspots-help strong {
        color: #333;
    }
    ';
}
