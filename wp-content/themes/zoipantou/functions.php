<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme setup.
 */
function zoipantou_setup() {
	load_theme_textdomain( 'zoipantou', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Κεντρικό μενού', 'zoipantou' ),
			'footer'  => __( 'Μενού υποσέλιδου', 'zoipantou' ),
		)
	);
}
add_action( 'after_setup_theme', 'zoipantou_setup' );

/**
 * Enqueue assets.
 */
function zoipantou_enqueue_assets() {
	$theme = wp_get_theme();

	wp_enqueue_style(
		'zoipantou-main',
		get_template_directory_uri() . '/assets/css/main.css',
		array(),
		$theme->get( 'Version' )
	);

	wp_enqueue_script(
		'zoipantou-main',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),
		$theme->get( 'Version' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'zoipantou_enqueue_assets' );

/**
 * Register widget areas.
 */
function zoipantou_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Πλευρική στήλη', 'zoipantou' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Κύρια περιοχή πλευρικής στήλης.', 'zoipantou' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'zoipantou_widgets_init' );

/**
 * Fallback for primary navigation when no custom menu is assigned.
 */
function zoipantou_get_common_navigation_tabs() {
	$home_url      = trailingslashit( home_url( '/' ) );
	$posts_page_id = (int) get_option( 'page_for_posts' );
	$posts_url     = $posts_page_id ? get_permalink( $posts_page_id ) : $home_url . '#arthra';

	return array(
		array(
			'slug'       => 'home',
			'label'      => __( 'Αρχική', 'zoipantou' ),
			'url'        => $home_url,
			'is_current' => is_front_page(),
		),
		array(
			'slug'       => 'about',
			'label'      => __( 'Σχετικά', 'zoipantou' ),
			'url'        => $home_url . '#sxetika',
			'is_current' => false,
		),
		array(
			'slug'       => 'services',
			'label'      => __( 'Υπηρεσίες', 'zoipantou' ),
			'url'        => $home_url . '#ypiresies',
			'is_current' => false,
		),
		array(
			'slug'       => 'faq',
			'label'      => __( 'Συχνές ερωτήσεις', 'zoipantou' ),
			'url'        => $home_url . '#syxnes-erotiseis',
			'is_current' => false,
		),
		array(
			'slug'       => 'articles',
			'label'      => __( 'Άρθρα', 'zoipantou' ),
			'url'        => $posts_url,
			'is_current' => is_home() || is_archive() || is_singular( 'post' ),
		),
		array(
			'slug'       => 'contact',
			'label'      => __( 'Επικοινωνία', 'zoipantou' ),
			'url'        => $home_url . '#epikoinonia',
			'is_current' => false,
		),
	);
}

function zoipantou_primary_menu_fallback() {
	echo '<ul class="menu">';
	foreach ( zoipantou_get_common_navigation_tabs() as $tab ) {
		$item_class = 'menu-item';

		if ( ! empty( $tab['is_current'] ) ) {
			$item_class .= ' current-menu-item';
		}

		echo '<li class="' . esc_attr( $item_class ) . '"><a href="' . esc_url( $tab['url'] ) . '">' . esc_html( $tab['label'] ) . '</a></li>';
	}

	echo '</ul>';
}

/**
 * Fallback for footer navigation when no custom menu is assigned.
 */
function zoipantou_footer_menu_fallback() {
	$tabs        = zoipantou_get_common_navigation_tabs();
	$privacy_url = get_privacy_policy_url();

	echo '<ul class="menu footer-menu">';

	foreach ( $tabs as $tab ) {
		// Keep footer concise by showing only key utility links.
		if ( ! in_array( $tab['slug'], array( 'home', 'articles', 'contact' ), true ) ) {
			continue;
		}

		echo '<li class="menu-item"><a href="' . esc_url( $tab['url'] ) . '">' . esc_html( $tab['label'] ) . '</a></li>';
	}

	if ( $privacy_url ) {
		echo '<li class="menu-item"><a href="' . esc_url( $privacy_url ) . '">' . esc_html__( 'Πολιτική απορρήτου', 'zoipantou' ) . '</a></li>';
	}

	echo '</ul>';
}

/**
 * Render pagination with Greek labels.
 */
function zoipantou_posts_pagination() {
	the_posts_pagination(
		array(
			'mid_size'  => 1,
			'screen_reader_text' => __( 'Πλοήγηση άρθρων', 'zoipantou' ),
			'prev_text' => __( 'Προηγούμενη', 'zoipantou' ),
			'next_text' => __( 'Επόμενη', 'zoipantou' ),
		)
	);
}

/**
 * Force Greek as the default site language when the theme is activated.
 */
function zoipantou_set_greek_language() {
	update_option( 'WPLANG', 'el' );
}
add_action( 'after_switch_theme', 'zoipantou_set_greek_language' );

/**
 * Customizer options for admin-managed website content.
 *
 * @param WP_Customize_Manager $wp_customize The Customizer object.
 */
function zoipantou_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'zoipantou_content_section',
		array(
			'title'       => __( 'Περιεχόμενο ιστοσελίδας', 'zoipantou' ),
			'description' => __( 'Επεξεργασία βασικού περιεχομένου που εμφανίζεται στο frontend.', 'zoipantou' ),
			'priority'    => 30,
		)
	);

	$wp_customize->add_setting(
		'zoipantou_hero_title',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'zoipantou_hero_title',
		array(
			'label'   => __( 'Τίτλος Hero', 'zoipantou' ),
			'section' => 'zoipantou_content_section',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'zoipantou_hero_text',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'zoipantou_hero_text',
		array(
			'label'   => __( 'Κείμενο Hero', 'zoipantou' ),
			'section' => 'zoipantou_content_section',
			'type'    => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'zoipantou_hero_button_label',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'zoipantou_hero_button_label',
		array(
			'label'   => __( 'Κείμενο κουμπιού Hero', 'zoipantou' ),
			'section' => 'zoipantou_content_section',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'zoipantou_hero_button_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'zoipantou_hero_button_url',
		array(
			'label'   => __( 'Σύνδεσμος κουμπιού Hero', 'zoipantou' ),
			'section' => 'zoipantou_content_section',
			'type'    => 'url',
		)
	);

	$wp_customize->add_setting(
		'zoipantou_home_posts_title',
		array(
			'default'           => __( 'Τελευταία άρθρα', 'zoipantou' ),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'zoipantou_home_posts_title',
		array(
			'label'   => __( 'Τίτλος ενότητας άρθρων', 'zoipantou' ),
			'section' => 'zoipantou_content_section',
			'type'    => 'text',
		)
	);

	$wp_customize->add_setting(
		'zoipantou_home_posts_count',
		array(
			'default'           => 5,
			'sanitize_callback' => 'zoipantou_sanitize_posts_count',
		)
	);
	$wp_customize->add_control(
		'zoipantou_home_posts_count',
		array(
			'label'       => __( 'Αριθμός άρθρων στην αρχική', 'zoipantou' ),
			'section'     => 'zoipantou_content_section',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 20,
				'step' => 1,
			),
		)
	);

	$wp_customize->add_setting(
		'zoipantou_show_home_posts',
		array(
			'default'           => 1,
			'sanitize_callback' => 'zoipantou_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'zoipantou_show_home_posts',
		array(
			'label'   => __( 'Εμφάνιση ενότητας άρθρων στην αρχική', 'zoipantou' ),
			'section' => 'zoipantou_content_section',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'zoipantou_footer_text',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'zoipantou_footer_text',
		array(
			'label'       => __( 'Κείμενο υποσέλιδου', 'zoipantou' ),
			'description' => __( 'Αν μείνει κενό, εμφανίζεται αυτόματα έτος + όνομα ιστοσελίδας.', 'zoipantou' ),
			'section'     => 'zoipantou_content_section',
			'type'        => 'text',
		)
	);
}
add_action( 'customize_register', 'zoipantou_customize_register' );

/**
 * Sanitize checkbox values.
 *
 * @param mixed $checked Checkbox value.
 * @return int
 */
function zoipantou_sanitize_checkbox( $checked ) {
	return ( isset( $checked ) && ( true === $checked || '1' === $checked || 1 === $checked ) ) ? 1 : 0;
}

/**
 * Sanitize number of posts shown on homepage.
 *
 * @param mixed $value Raw value.
 * @return int
 */
function zoipantou_sanitize_posts_count( $value ) {
	$value = absint( $value );

	return $value > 0 ? $value : 5;
}
