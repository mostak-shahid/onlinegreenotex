<?php
/*
Plugin Name: Online Greenotex
Plugin URI: https://github.com/mostak-shahid/onlinegreenotex
Description: This is a plugin for Online Greenotex.
Author: Md. Mostak Shahid
Version: 1.0.1
Author URI: http://www.mdmostakshahid.me
*/
require_once ( plugin_dir_path( __FILE__ ) . 'functions.php' );
require_once ( plugin_dir_path( __FILE__ ) . 'hooks.php' );
require_once ( plugin_dir_path( __FILE__ ) . 'ajax.php' );
//require_once ( plugin_dir_path( __FILE__ ) . 'post-types.php' );
require_once ( plugin_dir_path( __FILE__ ) . 'taxonomy.php' );
require_once ( plugin_dir_path( __FILE__ ) . 'user-functionality.php' );


require_once(plugin_dir_path( __FILE__ ) . 'metabox/init.php'); 
require_once(plugin_dir_path( __FILE__ ) . 'metaboxes.php'); 