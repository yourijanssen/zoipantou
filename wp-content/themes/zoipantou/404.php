<?php
get_header();
?>
<main class="site-main container">
	<section class="error-page">
		<h1><?php esc_html_e( 'Η σελίδα δεν βρέθηκε', 'zoipantou' ); ?></h1>
		<p><?php esc_html_e( 'Η σελίδα που αναζητάτε δεν υπάρχει.', 'zoipantou' ); ?></p>
		<a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Επιστροφή στην αρχική', 'zoipantou' ); ?></a>
	</section>
</main>
<?php
get_footer();
