<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php esc_html_e( 'Αναζήτηση για:', 'zoipantou' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Αναζήτηση...', 'placeholder', 'zoipantou' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	</label>
	<button type="submit" class="search-submit"><?php echo esc_html_x( 'Αναζήτηση', 'submit button', 'zoipantou' ); ?></button>
</form>
