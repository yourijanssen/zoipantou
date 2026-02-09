<?php
get_header();

$posts_page_id = (int) get_option( 'page_for_posts' );
$blog_title    = $posts_page_id ? get_the_title( $posts_page_id ) : __( 'Άρθρα', 'zoipantou' );
?>
<main class="site-main container">
	<header class="page-header">
		<h1><?php echo esc_html( $blog_title ); ?></h1>
	</header>

	<div class="content-layout">
		<section class="content-feed">
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
		</section>

		<?php get_sidebar(); ?>
	</div>
</main>
<?php
get_footer();
