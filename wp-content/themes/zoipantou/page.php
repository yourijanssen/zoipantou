<?php
get_header();
?>
<main class="site-main container">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class( 'content-card' ); ?> id="post-<?php the_ID(); ?>">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="entry-media">
					<?php the_post_thumbnail( 'large' ); ?>
				</div>
			<?php endif; ?>
			<h1><?php the_title(); ?></h1>
			<?php the_content(); ?>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
