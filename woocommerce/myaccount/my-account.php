<?php
/**
 * WWB V2 — My Account page (Figma node 21:2)
 *
 * @see https://woocommerce.github.io/code-reference/files/woocommerce-templates-myaccount-my-account.html
 */

defined( 'ABSPATH' ) || exit;

$current_user = wp_get_current_user();
$first_name   = $current_user->first_name ?: $current_user->display_name;
$initials     = mb_strtoupper( mb_substr( $current_user->first_name ?: $current_user->display_name, 0, 1 ) . mb_substr( $current_user->last_name, 0, 1 ) );
$email        = $current_user->user_email;

$nav_items = wc_get_account_menu_items();
$current   = WC()->query->get_current_endpoint();
if ( empty( $current ) ) $current = 'dashboard';
?>

<div class="wwb-account">

	<!-- ─── Sidebar ─── -->
	<aside class="wwb-account__sidebar">

		<div class="wwb-account__profile">
			<div class="wwb-account__avatar"><?php echo esc_html( $initials ); ?></div>
			<strong class="wwb-account__name"><?php echo esc_html( $current_user->display_name ); ?></strong>
			<span class="wwb-account__email"><?php echo esc_html( $email ); ?></span>
		</div>

		<nav class="wwb-account__nav">
			<?php foreach ( $nav_items as $endpoint => $label ) : ?>
				<?php
				$url    = wc_get_account_endpoint_url( $endpoint );
				$active = ( $endpoint === $current || ( $endpoint === 'dashboard' && empty( $current ) ) ) ? ' wwb-account__nav-item--active' : '';
				$icon   = '';

				switch ( $endpoint ) {
					case 'dashboard':
						$icon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>';
						break;
					case 'orders':
						$icon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>';
						break;
					case 'edit-address':
						$icon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>';
						break;
					case 'edit-account':
						$icon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>';
						break;
					case 'customer-logout':
						$icon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>';
						break;
					default:
						$icon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>';
				}
				?>
				<a href="<?php echo esc_url( $url ); ?>" class="wwb-account__nav-item<?php echo $active; ?>">
					<?php echo $icon; ?>
					<?php echo esc_html( $label ); ?>
				</a>
			<?php endforeach; ?>
		</nav>

	</aside>

	<!-- ─── Main Content ─── -->
	<main class="wwb-account__main">
		<?php do_action( 'woocommerce_account_content' ); ?>
	</main>

</div>
