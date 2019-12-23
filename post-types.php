<?php
//Brands
add_action( 'init', 'post_type_init' );
function post_type_init() {
	$labels = array(
		'name'               => _x( 'Brands', 'post type general name', 'excavator-template' ),
		'singular_name'      => _x( 'Brand', 'post type singular name', 'excavator-template' ),
		'menu_name'          => _x( 'Brands', 'admin menu', 'excavator-template' ),
		'name_admin_bar'     => _x( 'Brand', 'add new on admin bar', 'excavator-template' ),
		'add_new'            => _x( 'Add New', 'brand', 'excavator-template' ),
		'add_new_item'       => __( 'Add New Brand', 'excavator-template' ),
		'new_item'           => __( 'New Brand', 'excavator-template' ),
		'edit_item'          => __( 'Edit Brand', 'excavator-template' ),
		'view_item'          => __( 'View Brand', 'excavator-template' ),
		'all_items'          => __( 'All Brands', 'excavator-template' ),
		'search_items'       => __( 'Search Brands', 'excavator-template' ),
		'parent_item_colon'  => __( 'Parent Brands:', 'excavator-template' ),
		'not_found'          => __( 'No Brands found.', 'excavator-template' ),
		'not_found_in_trash' => __( 'No Brands found in Trash.', 'excavator-template' )
	);

	$args = array(
		'labels'             => $labels,
        'description'        => __( 'Description.', 'excavator-template' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'brand' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 6,
		'menu_icon' => 'dashicons-networking',
		'supports'           => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
	);

	register_post_type( 'brand', $args );
}
add_action( 'after_switch_theme', 'flush_rewrite_rules' );
