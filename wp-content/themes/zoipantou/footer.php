<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$footer_text = trim( (string) get_theme_mod( 'zoipantou_footer_text', '' ) );
$brand_name  = zoipantou_get_brand_name();
?>
<footer class="site-footer">
	<div class="container footer-inner">
		<p class="footer-copy">
			<?php
			if ( '' !== $footer_text ) {
				echo esc_html( $footer_text );
			} else {
				echo esc_html( gmdate( 'Y' ) . ' ' . $brand_name );
			}
			?>
		</p>
		<nav class="footer-nav" aria-label="<?php esc_attr_e( 'Μενού υποσέλιδου', 'zoipantou' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'menu footer-menu',
					'fallback_cb'    => 'zoipantou_footer_menu_fallback',
				)
			);
			?>
		</nav>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
