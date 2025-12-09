# Thème WWB - Wiwilbild

Thème WordPress/WooCommerce pour boutique de matériaux de rénovation (carrelage, parquet, faïence).

## Architecture

**Type** : Thème hybride FSE (Full Site Editing) + PHP classique
**Framework CSS** : KNACSS V4.4.5
**Préprocesseur** : SASS

## Fonctionnalités WooCommerce

### 1. Swatches de couleurs
**Fichier** : `functions.php` (lignes 164-207)

Convertit les sélecteurs de variation pour l'attribut `pa_couleurs` en boutons visuels avec la couleur réelle. La couleur est récupérée depuis le champ ACF `product_color_pick` associé au terme de taxonomie.

```php
// Hook utilisé
add_filter('woocommerce_dropdown_variation_attribute_options_html', 'custom_color_swatches', 10, 2);
```

### 2. Calculateur de surface
**Fichiers** : `woocommerce/single-product/variable.php`, `style/app.css`

Permet de calculer le nombre de produits nécessaires selon la surface à couvrir. Inclut une option pour ajouter une marge.

Classes CSS : `.surface-calculator-wrapper`

### 3. Prix au m²
**Fichier** : `functions.php` (lignes 96-114)

Ajoute automatiquement le suffixe " /m²" aux prix des produits de la catégorie `carrelage`.

```php
add_filter('woocommerce_get_price_html', 'ajouter_suffixe_m2_prix', 100, 2);
```

### 4. Format "À partir de..."
**Fichier** : `functions.php` (lignes 73-91)

Affiche "À partir de [prix min]" pour les produits variables avec des prix différents.

### 5. Mini panier AJAX
**Fichier** : `functions.php` (lignes 119-133)

Fragment AJAX pour mise à jour du panier dans le header sans rechargement.

## Hotspots interactifs

**Fichiers** : `acf-hotspots-field.php`, `functions.php` (lignes 222-302)

Système complet de points interactifs sur les images de la médiathèque WordPress.

### Fonctionnement
- **Admin** : Bouton "Gérer les hotspots" dans la médiathèque, modal drag & drop
- **Frontend** : Points animés (pulse) sur l'image principale du produit, tooltip au survol

### Structure des données
```json
{
  "x": 50,
  "y": 50,
  "title": "Titre du hotspot",
  "description": "Description"
}
```

Stockage : meta `_wwb_hotspots` sur les attachments

## Full Site Editing (FSE)

### Configuration (theme.json)

**Couleurs** :
- Primary : #FF99DA (Rose Wiwilbild)
- Secondary : #362C49 (Violet foncé)
- Tertiary : #7B74D1 (Violet clair)

**Typographies** :
- Titres : Uniform Rounded Condensed
- Corps : Inter

### Templates FSE (`templates/`)

| Fichier | Usage |
|---------|-------|
| `front-page.html` | Page d'accueil FSE |
| `blog-home.html` | Liste des articles |
| `blog-single.html` | Article individuel |

### Template Parts (`parts/`)

| Fichier | Usage |
|---------|-------|
| `header-fse.html` | En-tête avec pre-header, logo, nav, panier |
| `footer-fse.html` | Pied de page 4 colonnes, newsletter, légal |

### Block Patterns (`patterns/`)

| Pattern | Slug |
|---------|------|
| Hero section | `wwb/hero-section` |
| Produits phares | `wwb/featured-products` |
| Nouvelle collection | `wwb/new-collection` |
| Grille univers | `wwb/rooms-grid` |
| Liens catalogue | `wwb/catalog-links` |
| CTA bas de page | `wwb/bottom-cta` |
| Réassurance | `wwb/reassurance` |

## ACF (Advanced Custom Fields)

### Sections page d'accueil (`acf-homepage-sections.php`)

Flexible Content avec 3 layouts :
1. **Hero** : Slider + infos MEA
2. **Produits phares** : Grille produits featured
3. **Nouvelle gamme** : Section coming soon

### Hotspots (`acf-hotspots-field.php`)

Champ personnalisé pour la médiathèque avec éditeur visuel.

## Structure des fichiers

```
WWB/
├── functions.php              # Hooks, filtres, fonctions
├── acf-hotspots-field.php     # Système hotspots complet
├── acf-homepage-sections.php  # ACF flexible content
├── theme.json                 # Configuration FSE
├── style.css                  # Métadonnées thème
│
├── templates/                 # Templates FSE
│   ├── front-page.html
│   ├── blog-home.html
│   └── blog-single.html
│
├── parts/                     # Template parts FSE
│   ├── header-fse.html
│   └── footer-fse.html
│
├── patterns/                  # Block patterns
│   ├── hero-section.php
│   ├── featured-products.php
│   ├── new-collection.php
│   ├── rooms-grid.php
│   ├── catalog-links.php
│   ├── bottom-cta.php
│   └── reassurance.php
│
├── woocommerce/               # Templates WooCommerce
│   ├── archive-product.php
│   ├── single-product.php
│   └── ...
│
├── style/
│   ├── app.scss              # Source SASS
│   └── app.css               # CSS compilé
│
├── js/
│   ├── script.js             # Scripts custom
│   └── owl.carousel.min.js   # Carousel
│
└── font/uniform_rounded/     # Polices custom
```

## Hooks personnalisés

### Filtres

| Hook | Fonction | Rôle |
|------|----------|------|
| `woocommerce_get_price_html` | `ajouter_suffixe_m2_prix` | Ajoute /m² |
| `woocommerce_dropdown_variation_attribute_options_html` | `custom_color_swatches` | Swatches couleur |
| `woocommerce_add_to_cart_fragments` | `woocommerce_header_add_to_cart_fragment` | Panier AJAX |
| `wpseo_breadcrumb_separator` | `filter_wpseo_breadcrumb_separator` | Séparateur breadcrumb |

### Actions supprimées

- `woocommerce_breadcrumb` : Breadcrumb WC par défaut
- `woocommerce_template_loop_add_to_cart` : Bouton panier en liste

## Build & Développement

### Commandes NPM

```bash
npm run build   # Compile SASS minifié
npm run watch   # Watch mode développement
npm run dev     # Watch mode alternatif
```

### Dépendances

- `sass` : Compilateur SCSS

## Prérequis

- PHP 7.4+
- WordPress 6.0+
- WooCommerce 4.0+
- ACF Pro
- Yoast SEO (optionnel, pour breadcrumbs)

## Classes CSS principales

### Layout
- `.container` : Wrapper 90% max-width
- `.container_fluid` : Container fluide

### Produits
- `.color-swatches` : Conteneur swatches
- `.color-swatch` : Bouton couleur
- `.surface-calculator-wrapper` : Calculateur

### Hotspots
- `.product-hotspots-overlay` : Overlay container
- `.hotspot` : Point individuel
- `.hotspot-pulse` : Animation pulse
- `.hotspot-tooltip` : Tooltip info
