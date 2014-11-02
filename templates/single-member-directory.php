<?php
/**
 * Template for displaying single member directory entries.
 *
 * @package wimp
 */

// Start by assembling the meta data and taxonomy information
//  we want displayed
$post_id = get_the_ID();
$post_meta_fields = get_post_custom( $post_id );
$post_taxonomies = wp_get_post_terms( $post_id, array(
	WMD_Taxonomies::PRICE,
	WMD_Taxonomies::LOCATION,
	WMD_Taxonomies::INDUSTRY,
	WMD_Taxonomies::TECHNOLOGY,
	WMD_Taxonomies::TYPE,
	WMD_Taxonomies::LEVEL,
) );

// Set them up so they are easier to dereference for display
$post_lists = array();
if ( $post_taxonomies && ! is_wp_error( $post_taxonomies ) ) {
	foreach( $post_taxonomies as $post_term ) {
		$post_lists[ $post_term->taxonomy ][] = array(
			'slug' => $post_term->slug,
			'name' => $post_term->name
		);
	}
}

// Set up the member's company logo, assuming they have one
$logo_url_array = $post_meta_fields[ '_wmd_company_logo' ];
if (! empty( $logo_url_array ) ) {
	$logo_url = $logo_url_array[ 0 ];
}
if ( ! empty( $logo_url ) ) {
	$member_title = '<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( get_the_title( $post_id ) ) . '"/>';
} else {
	$member_title = the_title();
}

// Set up the member level

// Every member directory should have a member level, but we will be
// robust and not barf all over everything if the data isn't there
$member_level = '';
$member_level_array = $post_lists[ WMD_Taxonomies::LEVEL ];
if ( ! empty( $member_level_array ) ) {

	// We only print the first one assigned ("there can be only one"),
	// so let's just make that clear
	$member_level_first = $member_level_array[ 0 ];
	$member_level = ' <small class="' . esc_attr( "wmd-" . $member_level_first[ 'slug' ] )
		. '">' . esc_html( strtoupper( $member_level_first[ 'name' ] ) ) . '</small>';
}

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<? echo esc_attr( $post_id ); ?>" <?php post_class(); ?>>

			<header class="entry-header">
				<h1 class="entry-title"><?php echo $member_title . $member_level; ?></h1>
			</header><!-- .entry-header -->
			<div class="entry-content">
				<!-- carousel/hero/portfolio -->
				<!-- website -->
				<!-- member-entry-description -->
				<!-- price-range -->
				<!-- location -->
				<!-- industry -->
				<!-- type -->
				<!-- technology -->
			</div>
		</article>

		<?php endwhile; // end of the loop. ?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>