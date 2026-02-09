<?php
if ( is_active_sidebar( 'sidebar-1' ) ) :
	?>
	<aside class="site-sidebar" aria-label="<?php esc_attr_e( 'Πλευρική στήλη', 'zoipantou' ); ?>">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</aside>
	<?php
endif;
