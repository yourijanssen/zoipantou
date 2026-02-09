<?php
$hero_title = trim( (string) get_theme_mod( 'zoipantou_hero_title', '' ) );
if ( '' === $hero_title ) {
	$hero_title = __( 'Υποστήριξη με ηρεμία, σαφήνεια και προσωπική φροντίδα', 'zoipantou' );
}

$hero_text = trim( (string) get_theme_mod( 'zoipantou_hero_text', '' ) );
if ( '' === $hero_text ) {
	$hero_text = __( 'Προσφέρονται συνεδρίες ψυχολογικής υποστήριξης στα ελληνικά, δια ζώσης και online, με έμφαση στην εμπιστοσύνη και στην πρακτική αλλαγή.', 'zoipantou' );
}

$hero_button_label = trim( (string) get_theme_mod( 'zoipantou_hero_button_label', '' ) );
if ( '' === $hero_button_label ) {
	$hero_button_label = __( 'Κλείστε ραντεβού', 'zoipantou' );
}

$hero_button_url = trim( (string) get_theme_mod( 'zoipantou_hero_button_url', '' ) );
if ( '' === $hero_button_url ) {
	$hero_button_url = '#epikoinonia';
}

$contact_email = sanitize_email( get_option( 'admin_email' ) );

if ( have_posts() ) {
	the_post();
}

$front_tabs = array(
	array(
		'id'    => 'sxetika',
		'label' => __( 'Σχετικά', 'zoipantou' ),
	),
	array(
		'id'    => 'ypiresies',
		'label' => __( 'Υπηρεσίες', 'zoipantou' ),
	),
	array(
		'id'    => 'syxnes-erotiseis',
		'label' => __( 'Συχνές ερωτήσεις', 'zoipantou' ),
	),
	array(
		'id'    => 'epikoinonia',
		'label' => __( 'Επικοινωνία', 'zoipantou' ),
	),
);

get_header();
?>
<main class="site-main container">
	<section class="therapist-hero">
		<div class="hero-copy">
			<p class="hero-kicker"><?php esc_html_e( 'Ψυχολόγος στην Ελλάδα', 'zoipantou' ); ?></p>
			<h1><?php echo esc_html( $hero_title ); ?></h1>
			<p class="hero-lead"><?php echo esc_html( $hero_text ); ?></p>
			<div class="hero-actions">
				<a class="button" href="#ypiresies"><?php esc_html_e( 'Υπηρεσίες', 'zoipantou' ); ?></a>
				<a class="button button-outline" href="<?php echo esc_url( $hero_button_url ); ?>"><?php echo esc_html( $hero_button_label ); ?></a>
			</div>
		</div>
		<div class="hero-media">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'full', array( 'class' => 'hero-image', 'loading' => 'eager' ) ); ?>
			<?php else : ?>
				<div class="image-placeholder">
					<p><?php esc_html_e( 'Χώρος για μεγάλη φωτογραφία προφίλ ή χώρου συνεδρίας', 'zoipantou' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<nav class="section-tabs" aria-label="<?php esc_attr_e( 'Βασικές ενότητες ιστοσελίδας ψυχολόγου', 'zoipantou' ); ?>">
		<?php foreach ( $front_tabs as $tab ) : ?>
			<a class="section-tab" href="#<?php echo esc_attr( $tab['id'] ); ?>"><?php echo esc_html( $tab['label'] ); ?></a>
		<?php endforeach; ?>
	</nav>

	<section class="home-search content-card">
		<h2><?php esc_html_e( 'Αναζήτηση περιεχομένου', 'zoipantou' ); ?></h2>
		<p><?php esc_html_e( 'Βρείτε άρθρα και πληροφορίες με λέξεις-κλειδιά.', 'zoipantou' ); ?></p>
		<?php get_search_form(); ?>
	</section>

	<section class="approach" id="sxetika">
		<div class="approach-media">
			<div class="image-placeholder">
				<p><?php esc_html_e( 'Ιδανικό σημείο για μεγάλη φωτογραφία χώρου ή καθημερινής στιγμής', 'zoipantou' ); ?></p>
			</div>
		</div>
		<div class="approach-copy">
			<h2><?php esc_html_e( 'Σχετικά', 'zoipantou' ); ?></h2>
			<p><?php esc_html_e( 'Η θεραπευτική σχέση βασίζεται στην εμπιστοσύνη, στην αποδοχή και στη συνεργασία. Κάθε πλάνο υποστήριξης προσαρμόζεται στις ανάγκες και στους ρυθμούς του ανθρώπου που έχουμε απέναντί μας.', 'zoipantou' ); ?></p>
			<ul class="approach-list">
				<li><?php esc_html_e( 'Πρώτη συνεδρία γνωριμίας και αξιολόγησης', 'zoipantou' ); ?></li>
				<li><?php esc_html_e( 'Προσωποποιημένο πλάνο υποστήριξης', 'zoipantou' ); ?></li>
				<li><?php esc_html_e( 'Τακτική ανατροφοδότηση και πρακτικές ασκήσεις', 'zoipantou' ); ?></li>
			</ul>
		</div>
	</section>

	<section class="services" id="ypiresies">
		<header class="section-heading">
			<h2><?php esc_html_e( 'Υπηρεσίες', 'zoipantou' ); ?></h2>
			<p><?php esc_html_e( 'Στοχευμένη υποστήριξη για καθημερινές προκλήσεις και μακροχρόνια ψυχική ενδυνάμωση.', 'zoipantou' ); ?></p>
		</header>
		<div class="services-grid">
			<article class="service-card">
				<div class="service-media service-media-individual" aria-hidden="true"></div>
				<h3><?php esc_html_e( 'Ατομική ψυχοθεραπεία', 'zoipantou' ); ?></h3>
				<p><?php esc_html_e( 'Ασφαλής χώρος για άγχος, αυτοεκτίμηση, σχέσεις και προσωπικούς στόχους.', 'zoipantou' ); ?></p>
			</article>
			<article class="service-card">
				<div class="service-media service-media-couple" aria-hidden="true"></div>
				<h3><?php esc_html_e( 'Συμβουλευτική σχέσεων', 'zoipantou' ); ?></h3>
				<p><?php esc_html_e( 'Βελτίωση επικοινωνίας, κατανόηση συγκρούσεων και ενίσχυση συναισθηματικής σύνδεσης.', 'zoipantou' ); ?></p>
			</article>
			<article class="service-card">
				<div class="service-media service-media-online" aria-hidden="true"></div>
				<h3><?php esc_html_e( 'Online συνεδρίες', 'zoipantou' ); ?></h3>
				<p><?php esc_html_e( 'Ευέλικτες συνεδρίες από όπου βρίσκεστε, με σταθερότητα και εμπιστευτικότητα.', 'zoipantou' ); ?></p>
			</article>
		</div>
	</section>

	<section class="faq-section content-card" id="syxnes-erotiseis">
		<h2><?php esc_html_e( 'Συχνές ερωτήσεις', 'zoipantou' ); ?></h2>
		<div class="faq-list">
			<details>
				<summary><?php esc_html_e( 'Πόσο διαρκεί μια συνεδρία;', 'zoipantou' ); ?></summary>
				<p><?php esc_html_e( 'Η διάρκεια είναι συνήθως 50 λεπτά. Η συχνότητα συμφωνείται μαζί με βάση τις ανάγκες σας.', 'zoipantou' ); ?></p>
			</details>
			<details>
				<summary><?php esc_html_e( 'Πόσες συνεδρίες χρειάζονται;', 'zoipantou' ); ?></summary>
				<p><?php esc_html_e( 'Δεν υπάρχει ίδιο χρονοδιάγραμμα για όλους. Ο ρυθμός προσαρμόζεται στους στόχους και στην εξέλιξή σας.', 'zoipantou' ); ?></p>
			</details>
			<details>
				<summary><?php esc_html_e( 'Οι συνεδρίες είναι εμπιστευτικές;', 'zoipantou' ); ?></summary>
				<p><?php esc_html_e( 'Ναι. Η εμπιστευτικότητα αποτελεί θεμελιώδη αρχή της θεραπευτικής διαδικασίας.', 'zoipantou' ); ?></p>
			</details>
		</div>
	</section>

	<section class="contact-band" id="epikoinonia">
		<h2><?php esc_html_e( 'Επικοινωνία και ραντεβού', 'zoipantou' ); ?></h2>
		<p><?php esc_html_e( 'Στείλτε μήνυμα για διαθεσιμότητα ή για μια σύντομη τηλεφωνική επικοινωνία γνωριμίας.', 'zoipantou' ); ?></p>
		<?php if ( $contact_email ) : ?>
			<a class="button" href="mailto:<?php echo esc_attr( antispambot( $contact_email ) ); ?>"><?php esc_html_e( 'Στείλτε email', 'zoipantou' ); ?></a>
		<?php endif; ?>
	</section>

	<?php
	$page_content = trim( get_the_content() );
	if ( '' !== $page_content ) :
		?>
		<section class="front-page-content content-card">
			<?php echo wp_kses_post( apply_filters( 'the_content', $page_content ) ); ?>
		</section>
		<?php
	endif;
	?>
</main>
<?php
wp_reset_postdata();
get_footer();
