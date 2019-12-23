<?php
// hook into the init action and call mosacademy_taxonomies when it fires
add_action( 'init', 'taxonomies_init', 0 );

// create two taxonomies, categories and tags for the post type "book"
function taxonomies_init() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => _x( 'Brands', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Brand', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Brands', 'textdomain' ),
		'all_items'         => __( 'All Brands', 'textdomain' ),
		'parent_item'       => __( 'Parent Brand', 'textdomain' ),
		'parent_item_colon' => __( 'Parent Brand:', 'textdomain' ),
		'edit_item'         => __( 'Edit Brand', 'textdomain' ),
		'update_item'       => __( 'Update Brand', 'textdomain' ),
		'add_new_item'      => __( 'Add New Brand', 'textdomain' ),
		'new_item_name'     => __( 'New Brand Name', 'textdomain' ),
		'menu_name'         => __( 'Brands', 'textdomain' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'product-brand' ),
	);

	register_taxonomy( 'product-brand', array( 'product' ), $args );

	// Add new taxonomy, NOT hierarchical (like tags)
	$labels = array(
		'name'                       => _x( 'Tags', 'taxonomy general name', 'textdomain' ),
		'singular_name'              => _x( 'Tag', 'taxonomy singular name', 'textdomain' ),
		'search_items'               => __( 'Search Tags', 'textdomain' ),
		'popular_items'              => __( 'Popular Tags', 'textdomain' ),
		'all_items'                  => __( 'All Tags', 'textdomain' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Tag', 'textdomain' ),
		'update_item'                => __( 'Update Tag', 'textdomain' ),
		'add_new_item'               => __( 'Add New Tag', 'textdomain' ),
		'new_item_name'              => __( 'New Tag Name', 'textdomain' ),
		'separate_items_with_commas' => __( 'Separate tags with commas', 'textdomain' ),
		'add_or_remove_items'        => __( 'Add or remove tags', 'textdomain' ),
		'choose_from_most_used'      => __( 'Choose from the most used tags', 'textdomain' ),
		'not_found'                  => __( 'No tags found.', 'textdomain' ),
		'menu_name'                  => __( 'Tags', 'textdomain' ),
	);

	$args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'tag' ),
	);

	// register_taxonomy( 'tag', 'product', $args );
}