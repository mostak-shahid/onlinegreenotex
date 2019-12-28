<?php
function onlinegreenotex_enqueue_scripts(){
	// wp_enqueue_style( 'bootstrap.min', plugins_url( 'css/bootstrap.min.css', __FILE__ ) );
	wp_enqueue_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' );
	wp_enqueue_style( 'font-awesome', plugins_url( 'fonts/font-awesome/css/font-awesome.min.css', __FILE__ ) );
	wp_enqueue_style( 'onlinegreenotex', plugins_url( 'css/onlinegreenotex.css', __FILE__ ) );

	wp_enqueue_script( 'jquery' );
	// wp_enqueue_script( 'bootstrap.min', plugins_url( 'js/bootstrap.min.js', __FILE__ ), array('jquery') );
	wp_enqueue_script( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js' );
	wp_enqueue_script( 'onlinegreenotex', plugins_url( 'js/onlinegreenotex.js', __FILE__ ), array('jquery') );

	$ajax_params = array(
		'ajax_url' => admin_url('admin-ajax.php'),
	);
	wp_localize_script( 'onlinegreenotex', 'ajax_obj', $ajax_params );
}
add_action( 'wp_enqueue_scripts', 'onlinegreenotex_enqueue_scripts' );

if (!function_exists('mos_get_terms')){
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