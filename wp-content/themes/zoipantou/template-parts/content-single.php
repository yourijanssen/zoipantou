<?php
?>
<article <?php post_class( 'content-card' ); ?> id="post-<?php the_ID(); ?>">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="entry-media">
			<?php the_post_thumbnail( 'large' ); ?>
		</div>
	<?php endif; ?>

	<h1><?php the_title(); ?></h1>

	<div class="entry-meta">
		<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
			<?php echo esc_html( get_the_date() ); ?>
		</time>
	</div>

	<div class="entry-content">
		<?php the_content(); ?>
	</div>
</article>
