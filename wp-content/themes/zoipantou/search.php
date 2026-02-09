<?php
get_header();
?>
<main class="site-main container">
	<header class="page-header">
		<h1>
			<?php
			printf(
				esc_html__( 'Αποτελέσματα αναζήτησης για: %s', 'zoipantou' ),
				'<span>' . esc_html( get_search_query() ) . '</span>'
			);
			?>
		</h1>
		<?php get_search_form(); ?>
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
		<?php get_template_part( 'template-parts/content', 'none' ); ?>
	<?php endif; ?>
</main>
<?php
get_footer();
