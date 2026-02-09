<?php
/**
 * Template Name: Πλήρες πλάτος
 * Template Post Type: page
 */

get_header();
?>
<main class="site-main container full-width-page">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class( 'content-card' ); ?> id="post-<?php the_ID(); ?>">
			<h1><?php the_title(); ?></h1>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
