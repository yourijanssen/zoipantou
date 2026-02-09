<?php
get_header();
?>
<main class="site-main container">
	<header class="archive-header">
		<h1><?php the_archive_title(); ?></h1>
		<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
	</header>

	<?php if ( have_posts() ) : ?>
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content', get_post_type() );
		endwhile;
		?>
		<?php zoipantou_posts_pagination(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Δεν βρέθηκαν αποτελέσματα σε αυτό το αρχείο.', 'zoipantou' ); ?></p>
	<?php endif; ?>
</main>
<?php
get_footer();
