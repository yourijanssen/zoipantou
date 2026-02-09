<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Supported frontend languages.
 *
 * @return string[]
 */
function zoipantou_get_supported_languages() {
	return array( 'el', 'en' );
}

/**
 * Validate requested language.
 *
 * @param mixed $language Requested language value.
 * @return string
 */
function zoipantou_sanitize_language( $language ) {
	$language = strtolower( sanitize_key( (string) $language ) );

	return in_array( $language, zoipantou_get_supported_languages(), true ) ? $language : '';
}

/**
 * Get active language for the current visitor.
 *
 * @return string
 */
function zoipantou_get_language() {
	static $language = null;

	if ( null !== $language ) {
		return $language;
	}

	if ( isset( $_GET['lang'] ) ) {
		$query_language = zoipantou_sanitize_language( wp_unslash( $_GET['lang'] ) );
		if ( $query_language ) {
			$language = $query_language;

			return $language;
		}
	}

	if ( isset( $_COOKIE['zoipantou_lang'] ) ) {
		$cookie_language = zoipantou_sanitize_language( wp_unslash( $_COOKIE['zoipantou_lang'] ) );
		if ( $cookie_language ) {
			$language = $cookie_language;

			return $language;
		}
	}

	$language = 'el';

	return $language;
}

/**
 * Persist language selection from query parameter.
 */
function zoipantou_persist_language_selection() {
	if ( ! isset( $_GET['lang'] ) ) {
		return;
	}

	$language = zoipantou_sanitize_language( wp_unslash( $_GET['lang'] ) );
	if ( ! $language ) {
		return;
	}

	setcookie(
		'zoipantou_lang',
		$language,
		time() + YEAR_IN_SECONDS,
		COOKIEPATH ? COOKIEPATH : '/',
		COOKIE_DOMAIN,
		is_ssl(),
		true
	);
	$_COOKIE['zoipantou_lang'] = $language;
}
add_action( 'init', 'zoipantou_persist_language_selection', 1 );

/**
 * Build a URL to switch language while staying on the same page.
 *
 * @param string $language Target language.
 * @return string
 */
function zoipantou_get_language_switch_url( $language ) {
	$language = zoipantou_sanitize_language( $language );
	if ( ! $language ) {
		$language = 'el';
	}

	return add_query_arg( 'lang', $language, remove_query_arg( 'lang' ) );
}

/**
 * Keep language codes accurate in <html> attributes.
 *
 * @param string $output Current attributes output.
 * @return string
 */
function zoipantou_filter_language_attributes( $output ) {
	if ( 'en' !== zoipantou_get_language() ) {
		return $output;
	}

	return (string) preg_replace( '/\blang=("|\')[^"\']*("|\')/i', 'lang="en-US"', $output );
}
add_filter( 'language_attributes', 'zoipantou_filter_language_attributes' );

/**
 * English dictionary for theme strings.
 *
 * @return array<string,string>
 */
function zoipantou_get_english_translations() {
	return array(
		'Κεντρικό μενού' => 'Main menu',
		'Μενού υποσέλιδου' => 'Footer menu',
		'Πλευρική στήλη' => 'Sidebar',
		'Κύρια περιοχή πλευρικής στήλης.' => 'Main sidebar area.',
		'Αρχική' => 'Home',
		'Σχετικά' => 'About',
		'Υπηρεσίες' => 'Services',
		'Συχνές ερωτήσεις' => 'FAQ',
		'Άρθρα' => 'Articles',
		'Επικοινωνία' => 'Contact',
		'Πολιτική απορρήτου' => 'Privacy Policy',
		'Πλοήγηση άρθρων' => 'Posts navigation',
		'Προηγούμενη' => 'Previous',
		'Επόμενη' => 'Next',
		'Περιεχόμενο ιστοσελίδας' => 'Website content',
		'Επεξεργασία βασικού περιεχομένου που εμφανίζεται στο frontend.' => 'Edit the core content shown on the frontend.',
		'Τίτλος Hero' => 'Hero title',
		'Κείμενο Hero' => 'Hero text',
		'Κείμενο κουμπιού Hero' => 'Hero button text',
		'Σύνδεσμος κουμπιού Hero' => 'Hero button link',
		'Τελευταία άρθρα' => 'Latest articles',
		'Τίτλος ενότητας άρθρων' => 'Articles section title',
		'Αριθμός άρθρων στην αρχική' => 'Number of homepage articles',
		'Εμφάνιση ενότητας άρθρων στην αρχική' => 'Show articles section on homepage',
		'Κείμενο λογοτύπου' => 'Logo text',
		'Αν μείνει κενό, χρησιμοποιείται το “Ζωή Παντού”.' => 'If left empty, “Zoi Pantou” is used.',
		'Κείμενο υποσέλιδου' => 'Footer text',
		'Αν μείνει κενό, εμφανίζεται αυτόματα έτος + όνομα ιστοσελίδας.' => 'If left empty, year + site name is shown automatically.',
		'Ζωή Παντού' => 'Zoi Pantou',
		'Ψυχολόγος στην Ελλάδα' => 'Psychologist in Greece',
		'Υποστήριξη με ηρεμία, σαφήνεια και προσωπική φροντίδα' => 'Support with calm, clarity and personal care',
		'Προσφέρονται συνεδρίες ψυχολογικής υποστήριξης στα ελληνικά, δια ζώσης και online, με έμφαση στην εμπιστοσύνη και στην πρακτική αλλαγή.' => 'Counseling sessions are offered in Greek, in person and online, with emphasis on trust and practical change.',
		'Κλείστε ραντεβού' => 'Book an appointment',
		'Άρθρα και πόροι αυτοφροντίδας' => 'Articles and self-care resources',
		'Χώρος για μεγάλη φωτογραφία προφίλ ή χώρου συνεδρίας' => 'Space for a large profile or therapy-room photo',
		'Βασικές ενότητες ιστοσελίδας ψυχολόγου' => 'Core sections of a psychologist website',
		'Ιδανικό σημείο για μεγάλη φωτογραφία χώρου ή καθημερινής στιγμής' => 'Ideal spot for a large photo of your practice space or an everyday moment',
		'Η θεραπευτική σχέση βασίζεται στην εμπιστοσύνη, στην αποδοχή και στη συνεργασία. Κάθε πλάνο υποστήριξης προσαρμόζεται στις ανάγκες και στους ρυθμούς του ανθρώπου που έχουμε απέναντί μας.' => 'The therapeutic relationship is built on trust, acceptance, and collaboration. Each support plan is tailored to the needs and pace of the person in front of us.',
		'Πρώτη συνεδρία γνωριμίας και αξιολόγησης' => 'First intake and assessment session',
		'Προσωποποιημένο πλάνο υποστήριξης' => 'Personalized support plan',
		'Τακτική ανατροφοδότηση και πρακτικές ασκήσεις' => 'Regular feedback and practical exercises',
		'Στοχευμένη υποστήριξη για καθημερινές προκλήσεις και μακροχρόνια ψυχική ενδυνάμωση.' => 'Targeted support for everyday challenges and long-term psychological empowerment.',
		'Ατομική ψυχοθεραπεία' => 'Individual therapy',
		'Ασφαλής χώρος για άγχος, αυτοεκτίμηση, σχέσεις και προσωπικούς στόχους.' => 'A safe space for anxiety, self-esteem, relationships, and personal goals.',
		'Συμβουλευτική σχέσεων' => 'Relationship counseling',
		'Βελτίωση επικοινωνίας, κατανόηση συγκρούσεων και ενίσχυση συναισθηματικής σύνδεσης.' => 'Improve communication, understand conflict, and strengthen emotional connection.',
		'Online συνεδρίες' => 'Online sessions',
		'Ευέλικτες συνεδρίες από όπου βρίσκεστε, με σταθερότητα και εμπιστευτικότητα.' => 'Flexible sessions from wherever you are, with consistency and confidentiality.',
		'Πόσο διαρκεί μια συνεδρία;' => 'How long is a session?',
		'Η διάρκεια είναι συνήθως 50 λεπτά. Η συχνότητα συμφωνείται μαζί με βάση τις ανάγκες σας.' => 'Sessions usually last 50 minutes. Frequency is agreed together based on your needs.',
		'Πόσες συνεδρίες χρειάζονται;' => 'How many sessions are needed?',
		'Δεν υπάρχει ίδιο χρονοδιάγραμμα για όλους. Ο ρυθμός προσαρμόζεται στους στόχους και στην εξέλιξή σας.' => 'There is no one-size-fits-all timeline. The pace is adjusted to your goals and progress.',
		'Οι συνεδρίες είναι εμπιστευτικές;' => 'Are sessions confidential?',
		'Ναι. Η εμπιστευτικότητα αποτελεί θεμελιώδη αρχή της θεραπευτικής διαδικασίας.' => 'Yes. Confidentiality is a fundamental principle of therapy.',
		'Σκέψεις και πρακτικές συμβουλές για ψυχική υγεία και καθημερινή ισορροπία.' => 'Thoughts and practical tips for mental health and daily balance.',
		'Δεν υπάρχουν ακόμη άρθρα.' => 'No articles yet.',
		'Επικοινωνία και ραντεβού' => 'Contact and appointments',
		'Στείλτε μήνυμα για διαθεσιμότητα ή για μια σύντομη τηλεφωνική επικοινωνία γνωριμίας.' => 'Send a message for availability or a short introductory call.',
		'Στείλτε email' => 'Send email',
		'Αποτελέσματα αναζήτησης για: %s' => 'Search results for: %s',
		'Δεν βρέθηκαν αποτελέσματα σε αυτό το αρχείο.' => 'No results found in this archive.',
		'Δεν βρέθηκε περιεχόμενο.' => 'No content found.',
		'Η σελίδα δεν βρέθηκε' => 'Page not found',
		'Η σελίδα που αναζητάτε δεν υπάρχει.' => 'The page you are looking for does not exist.',
		'Επιστροφή στην αρχική' => 'Back to home',
		'Δεν βρέθηκαν αποτελέσματα' => 'Nothing found',
		'Δοκιμάστε διαφορετική αναζήτηση ή επιστρέψτε αργότερα.' => 'Try a different search or check back later.',
		'Αναζήτηση για:' => 'Search for:',
		'Αναζήτηση...' => 'Search...',
		'Αναζήτηση' => 'Search',
		'Αλλαγή γλώσσας' => 'Change language',
		'Ελληνικά' => 'Greek',
		'Αγγλικά' => 'English',
	);
}

/**
 * Translate theme strings when English is selected.
 *
 * @param string $translation Existing translation.
 * @param string $text Original text.
 * @param string $domain Text domain.
 * @return string
 */
function zoipantou_translate_text( $translation, $text, $domain ) {
	if ( 'zoipantou' !== $domain || 'en' !== zoipantou_get_language() ) {
		return $translation;
	}

	$translations = zoipantou_get_english_translations();

	return array_key_exists( $text, $translations ) ? $translations[ $text ] : $translation;
}
add_filter( 'gettext', 'zoipantou_translate_text', 20, 3 );

/**
 * Translate context-based theme strings when English is selected.
 *
 * @param string $translation Existing translation.
 * @param string $text Original text.
 * @param string $context String context.
 * @param string $domain Text domain.
 * @return string
 */
function zoipantou_translate_text_with_context( $translation, $text, $context, $domain ) {
	unset( $context );

	return zoipantou_translate_text( $translation, $text, $domain );
}
add_filter( 'gettext_with_context', 'zoipantou_translate_text_with_context', 20, 4 );

/**
 * Translate custom menu item labels when English is selected.
 *
 * @param WP_Post[] $items Menu items.
 * @return WP_Post[]
 */
function zoipantou_translate_menu_items( $items ) {
	if ( 'en' !== zoipantou_get_language() ) {
		return $items;
	}

	$translations = zoipantou_get_english_translations();

	foreach ( $items as $item ) {
		if ( isset( $translations[ $item->title ] ) ) {
			$item->title = $translations[ $item->title ];
		}
	}

	return $items;
}
add_filter( 'wp_nav_menu_objects', 'zoipantou_translate_menu_items' );

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
 * Get the displayed brand/logo name.
 *
 * Uses a dedicated Customizer value when set, otherwise falls back to
 * "Ζωή Παντού" when the site title is empty or still set to "Zoi Pantou".
 *
 * @return string
 */
function zoipantou_get_brand_name() {
	$brand_name = trim( (string) get_theme_mod( 'zoipantou_brand_name', '' ) );
	if ( '' !== $brand_name ) {
		return $brand_name;
	}

	$site_name = trim( (string) get_bloginfo( 'name' ) );
	if ( '' !== $site_name && 0 !== strcasecmp( $site_name, 'Zoi Pantou' ) ) {
		return $site_name;
	}

	return __( 'Ζωή Παντού', 'zoipantou' );
}

/**
 * Fallback for primary navigation when no custom menu is assigned.
 */
function zoipantou_get_common_navigation_tabs() {
	$home_url      = trailingslashit( home_url( '/' ) );
	$posts_page_id = (int) get_option( 'page_for_posts' );
	$posts_url     = $posts_page_id ? get_permalink( $posts_page_id ) : $home_url . '#arthra';
	$is_front      = is_front_page();
	$is_blog_index = is_home();
	$is_articles   = ( $is_blog_index && ! $is_front ) || is_archive() || is_singular( 'post' );

	return array(
		array(
			'slug'       => 'home',
			'label'      => __( 'Αρχική', 'zoipantou' ),
			'url'        => $home_url,
			'is_current' => $is_front,
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
			'is_current' => $is_articles,
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
		'zoipantou_brand_name',
		array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'zoipantou_brand_name',
		array(
			'label'       => __( 'Κείμενο λογοτύπου', 'zoipantou' ),
			'description' => __( 'Αν μείνει κενό, χρησιμοποιείται το “Ζωή Παντού”.', 'zoipantou' ),
			'section'     => 'zoipantou_content_section',
			'type'        => 'text',
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
