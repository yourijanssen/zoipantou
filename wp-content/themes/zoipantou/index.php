<?php
get_header();
?>
<main class="site-main container">
	<?php if ( have_posts() ) : ?>
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content', get_post_type() );
		endwhile;
		?>

		<?php zoipantou_posts_pagination(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Δεν βρέθηκε περιεχόμενο.', 'zoipantou' ); ?></p>
	<?php endif; ?>
</main>
<?php
get_footer();
