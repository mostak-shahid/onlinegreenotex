<?php
/*
Plugin Name: Online Greenotex
Plugin URI: https://github.com/mostak-shahid/onlinegreenotex
Description: This is a plugin for Online Greenotex.
Author: Md. Mostak Shahid
Version: 1.0.0
Author URI: http://www.mdmostakshahid.me
*/
//require_once ( plugin_dir_path( __FILE__ ) . 'post-types.php' );
require_once ( plugin_dir_path( __FILE__ ) . 'taxonomy.php' );
require_once ( plugin_dir_path( __FILE__ ) . 'user-functionality.php' );

if (!function_exists(mos_get_terms)){
	function mos_get_terms ($taxonomy = 'category') {
	    global $wpdb;
	    $output = array();
	    $all_taxonomies = $wpdb->get_results( "SELECT {$wpdb->prefix}term_taxonomy.term_id, {$wpdb->prefix}term_taxonomy.taxonomy, {$wpdb->prefix}terms.name, {$wpdb->prefix}terms.slug, {$wpdb->prefix}term_taxonomy.description, {$wpdb->prefix}term_taxonomy.parent, {$wpdb->prefix}term_taxonomy.count, {$wpdb->prefix}terms.term_group FROM {$wpdb->prefix}term_taxonomy INNER JOIN {$wpdb->prefix}terms ON {$wpdb->prefix}term_taxonomy.term_id={$wpdb->prefix}terms.term_id", ARRAY_A);

	    foreach ($all_taxonomies as $key => $value) {
	        if ($value["taxonomy"] == $taxonomy) {
	            $output[] = $value;
	        }
	    }
	    return $output;
	}
}
