<?php
/**
 * WWB V2 — My Account Dashboard (Figma node 21:2)
 *
 * @see https://woocommerce.github.io/code-reference/files/woocommerce-templates-myaccount-dashboard.html
 */

defined( 'ABSPATH' ) || exit;

$current_user = wp_get_current_user();
$first_name   = $current_user->first_name ?: $current_user->display_name;

// Get order stats
$orders       = wc_get_orders( array( 'customer_id' => get_current_user_id(), 'limit' => -1, 'status' => array_keys( wc_get_order_statuses() ) ) );
$total_orders = count( $orders );
$in_transit   = 0;
$recent_order = null;

foreach ( $orders as $order ) {
	if ( in_array( $order->get_status(), array( 'processing', 'on-hold' ) ) ) {
		$in_transit++;
	}
	if ( ! $recent_order && $order->get_status() !== 'cancelled' ) {
		$recent_order = $order;
	}
}

// Recent orders for table
$recent_orders = wc_get_orders( array( 'customer_id' => get_current_user_id(), 'limit' => 3, 'orderby' => 'date', 'order' => 'DESC' ) );
?>

<!-- Greeting -->
<div class="wwb-dashboard__greeting">
	<h1 class="wwb-dashboard__title">Bonjour <?php echo esc_html( $first_name ); ?></h1>
	<p class="wwb-dashboard__subtitle">Voici un résumé de votre activité</p>
</div>

<!-- Stats -->
<div class="wwb-dashboard__stats">
	<div class="wwb-dashboard__stat">
		<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
		<span class="wwb-dashboard__stat-value"><?php echo $total_orders; ?></span>
		<span class="wwb-dashboard__stat-label">commandes</span>
	</div>
	<div class="wwb-dashboard__stat">
		<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
		<span class="wwb-dashboard__stat-value"><?php echo $in_transit; ?></span>
		<span class="wwb-dashboard__stat-label">en cours de livraison</span>
	</div>
	<div class="wwb-dashboard__stat">
		<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
		<span class="wwb-dashboard__stat-value">0</span>
		<span class="wwb-dashboard__stat-label">favoris</span>
	</div>
</div>

<!-- Current order -->
<?php if ( $recent_order ) :
	$order_id     = $recent_order->get_order_number();
	$order_status = $recent_order->get_status();
	$order_date   = $recent_order->get_date_created()->date_i18n( 'j F Y' );
	$order_total  = $recent_order->get_formatted_order_total();
	$status_label = wc_get_order_status_name( $order_status );

	// Map status to step
	$steps = array( 'pending' => 0, 'on-hold' => 1, 'processing' => 2, 'completed' => 4 );
	$current_step = isset( $steps[ $order_status ] ) ? $steps[ $order_status ] : 1;
	$step_labels  = array( 'Commandé', 'Préparation', 'Expédié', 'Livraison', 'Livré' );
?>
<div class="wwb-dashboard__section">
	<h2 class="wwb-dashboard__section-title">
		<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
		Commande en cours
	</h2>

	<div class="wwb-dashboard__order-card">
		<div class="wwb-dashboard__order-header">
			<div>
				<strong>#<?php echo esc_html( $order_id ); ?></strong>
				<span class="wwb-dashboard__order-status wwb-dashboard__order-status--<?php echo esc_attr( $order_status ); ?>"><?php echo esc_html( $status_label ); ?></span>
			</div>
			<span class="wwb-dashboard__order-meta"><?php echo esc_html( $order_date ); ?> · <?php echo $order_total; ?></span>
		</div>

		<!-- Progress tracker -->
		<div class="wwb-dashboard__tracker">
			<?php foreach ( $step_labels as $i => $label ) : ?>
				<div class="wwb-dashboard__tracker-step <?php echo $i <= $current_step ? 'wwb-dashboard__tracker-step--done' : ''; ?> <?php echo $i === $current_step ? 'wwb-dashboard__tracker-step--current' : ''; ?>">
					<div class="wwb-dashboard__tracker-dot">
						<?php if ( $i < $current_step ) : ?>
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
						<?php endif; ?>
					</div>
					<span><?php echo esc_html( $label ); ?></span>
				</div>
				<?php if ( $i < 4 ) : ?>
					<div class="wwb-dashboard__tracker-line <?php echo $i < $current_step ? 'wwb-dashboard__tracker-line--done' : ''; ?>"></div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>

		<!-- Order items preview -->
		<div class="wwb-dashboard__order-items">
			<?php foreach ( $recent_order->get_items() as $item ) :
				$product = $item->get_product();
				if ( ! $product ) continue;
			?>
			<div class="wwb-dashboard__order-item">
				<?php echo $product->get_image( array( 48, 48 ) ); ?>
				<div>
					<strong><?php echo esc_html( $item->get_name() ); ?></strong>
					<span>x<?php echo $item->get_quantity(); ?></span>
				</div>
			</div>
			<?php endforeach; ?>

			<a href="<?php echo esc_url( $recent_order->get_view_order_url() ); ?>" class="wwb-dashboard__order-track">
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
				Suivre ma livraison
			</a>
		</div>
	</div>
</div>
<?php endif; ?>

<!-- Recent orders table -->
<?php if ( ! empty( $recent_orders ) ) : ?>
<div class="wwb-dashboard__section">
	<div class="wwb-dashboard__section-header">
		<h2 class="wwb-dashboard__section-title">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
			Dernières commandes
		</h2>
		<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" class="wwb-dashboard__section-link">
			Voir toutes les commandes
			<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
		</a>
	</div>

	<table class="wwb-dashboard__orders-table">
		<thead>
			<tr>
				<th>Commande</th>
				<th>Date</th>
				<th>Montant</th>
				<th>Statut</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $recent_orders as $order ) : ?>
			<tr>
				<td><strong>#<?php echo esc_html( $order->get_order_number() ); ?></strong></td>
				<td><?php echo esc_html( $order->get_date_created()->date_i18n( 'd/m/Y' ) ); ?></td>
				<td><strong><?php echo $order->get_formatted_order_total(); ?></strong></td>
				<td>
					<span class="wwb-dashboard__status-badge wwb-dashboard__status-badge--<?php echo esc_attr( $order->get_status() ); ?>">
						<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
					</span>
				</td>
				<td>
					<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="wwb-dashboard__btn-small">Voir</a>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php endif; ?>

<?php
/**
 * My Account dashboard.
 *
 * @since 2.6.0
 */
do_action( 'woocommerce_account_dashboard' );
