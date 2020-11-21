<?php

/**
 * Registers the guestpost taxonomies: guestpost-venue, guestpost-category and optinally guestpost-tag
 * Hooked onto init
 *
 * @ignore
 * @access private
 * @since 1.0
 */
/**
 * Registers the guestpost post type.
 */
function wpt_guestpost_post_type() {

	$labels = array(
		'name'               => __( 'Guestposts','guestpost' ),
		'singular_name'      => __( 'guestpost','guestpost' ),
		'add_new'            => __( 'Add New guestpost','guestpost' ),
		'add_new_item'       => __( 'Add New guestpost','guestpost' ),
		'edit_item'          => __( 'Edit guestpost','guestpost' ),
		'new_item'           => __( 'Add New guestpost','guestpost' ),
		'view_item'          => __( 'View guestpost','guestpost' ),
		'search_items'       => __( 'Search guestpost','guestpost' ),
		'not_found'          => __( 'No guestposts found','guestpost' ),
		'not_found_in_trash' => __( 'No guestposts found in trash','guestpost' )
	);

	$supports = array(
		'title',
		'editor',
		'thumbnail',
		'comments',
		'revisions',
		'excerpt',
		'author'
	);

	$args = array(
		'labels'               => $labels,
		'supports'             => $supports,
		'public'               => true,
		'capability_type'      => 'post',
		'rewrite'              => array( 'slug' => 'guestposts' ),
		'has_archive'          => true,
		'menu_position'        => 30
	);
	register_post_type( 'guestposts', $args );
}
add_action( 'init', 'wpt_guestpost_post_type' );  
	