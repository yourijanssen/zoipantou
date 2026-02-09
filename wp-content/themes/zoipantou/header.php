<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
	<div class="container header-inner">
		<?php $brand_name = zoipantou_get_brand_name(); ?>
		<a class="site-branding" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php echo esc_html( $brand_name ); ?>
		</a>
		<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-header-panel">
			<span class="menu-toggle-box" aria-hidden="true">
				<span class="menu-toggle-line"></span>
			</span>
			<span class="screen-reader-text"><?php esc_html_e( 'Μενού', 'zoipantou' ); ?></span>
		</button>
		<div class="header-panel" id="site-header-panel">
			<nav class="site-nav" aria-label="<?php esc_attr_e( 'Κεντρικό μενού', 'zoipantou' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'menu',
						'fallback_cb'    => 'zoipantou_primary_menu_fallback',
					)
				);
				?>
			</nav>
			<div class="header-tools">
				<div class="language-switcher" aria-label="<?php esc_attr_e( 'Αλλαγή γλώσσας', 'zoipantou' ); ?>">
					<a
						class="language-option<?php echo 'el' === zoipantou_get_language() ? ' is-active' : ''; ?>"
						href="<?php echo esc_url( zoipantou_get_language_switch_url( 'el' ) ); ?>"
						hreflang="el"
						lang="el"
						title="<?php esc_attr_e( 'Ελληνικά', 'zoipantou' ); ?>"
					>
						ΕΛ
					</a>
					<a
						class="language-option<?php echo 'en' === zoipantou_get_language() ? ' is-active' : ''; ?>"
						href="<?php echo esc_url( zoipantou_get_language_switch_url( 'en' ) ); ?>"
						hreflang="en"
						lang="en"
						title="<?php esc_attr_e( 'Αγγλικά', 'zoipantou' ); ?>"
					>
						EN
					</a>
				</div>
			</div>
		</div>
	</div>
</header>
