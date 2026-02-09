<?php
?>
<article <?php post_class( 'content-card' ); ?> id="post-<?php the_ID(); ?>">
	<?php if ( has_post_thumbnail() ) : ?>
		<?php if ( is_singular() ) : ?>
			<div class="entry-media">
				<?php the_post_thumbnail( 'large' ); ?>
			</div>
		<?php else : ?>
			<a class="entry-media" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php the_post_thumbnail( 'large' ); ?>
			</a>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( is_singular() ) : ?>
		<h1><?php the_title(); ?></h1>
	<?php else : ?>
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<?php endif; ?>

	<div class="entry-meta">
		<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
			<?php echo esc_html( get_the_date() ); ?>
		</time>
	</div>

	<div class="entry-content">
		<?php
		if ( is_singular() ) {
			the_content();
		} else {
			the_excerpt();
		}
		?>
	</div>
</article>
