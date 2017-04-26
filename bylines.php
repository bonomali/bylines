<?php
/**
 * Plugin Name:     Bylines
 * Plugin URI:      https://bylines.io
 * Description:     Modern multi-author publishing for WordPress
 * Author:          Daniel Bachhuber, Hand Built
 * Author URI:      https://handbuilt.co
 * Text Domain:     bylines
 * Domain Path:     /languages
 * Version:         0.1.0-alpha
 *
 * @package         Bylines
 */

add_action( 'init', array( 'Bylines\Content_Model', 'action_init_register_taxonomies' ) );
add_action( 'init', array( 'Bylines\Content_Model', 'action_init_late_register_taxonomy_for_object_type' ), 100 );

// Query modifications.
add_action( 'pre_get_posts', array( 'Bylines\Query', 'action_pre_get_posts' ) );
add_filter( 'posts_where', array( 'Bylines\Query', 'filter_posts_where' ), 10, 2 );
add_filter( 'posts_join', array( 'Bylines\Query', 'filter_posts_join' ), 10, 2 );
add_filter( 'posts_groupby', array( 'Bylines\Query', 'filter_posts_groupby' ), 10, 2 );

add_action( 'wp_ajax_bylines_search', array( 'Bylines\Admin_Ajax', 'handle_bylines_search' ) );
add_action( 'admin_enqueue_scripts', array( 'Bylines\Assets', 'action_admin_enqueue_scripts' ) );
add_action( 'add_meta_boxes', array( 'Bylines\Editor', 'action_add_meta_boxes_late' ), 100 );
add_action( 'save_post', array( 'Bylines\Editor', 'action_save_post_bylines_metabox' ), 10, 2 );

/**
 * Autoload without Composer
 */
spl_autoload_register( function( $class ) {
	$class = ltrim( $class, '\\' );
	if ( 0 !== stripos( $class, 'Bylines\\' ) ) {
		return;
	}

	$parts = explode( '\\', $class );
	array_shift( $parts ); // Don't need "Bylines".
	$last = array_pop( $parts ); // File should be 'class-[...].php'.
	$last = 'class-' . $last . '.php';
	$parts[] = $last;
	$file = dirname( __FILE__ ) . '/inc/' . str_replace( '_', '-', strtolower( implode( $parts, '/' ) ) );
	if ( file_exists( $file ) ) {
		require $file;
	}

});

// Template tags should always be available.
require_once dirname( __FILE__ ) . '/template-tags.php';
