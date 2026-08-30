<?php
/**
 * Material Blog – Theme Functions
 *
 * @package Material_Blog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme Setup
 */
function material_blog_setup() {
	// Let WordPress manage the document title
	add_theme_support( 'title-tag' );

	// Enable featured images
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 720, 405, true ); // 16:9

	// Custom logo
	add_theme_support( 'custom-logo', array(
		'height'      => 36,
		'width'       => 180,
		'flex-width'  => true,
		'flex-height' => true,
	) );

	// HTML5 support
	add_theme_support( 'html5', array(
		'search-form',
		'gallery',
		'caption',
		'style',
		'script',
	) );

	// Register navigation menus
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'material-blog' ),
		'footer'  => __( 'Footer Menu', 'material-blog' ),
	) );

	// Content width
	if ( ! isset( $GLOBALS['content_width'] ) ) {
		$GLOBALS['content_width'] = 840;
	}
}
add_action( 'after_setup_theme', 'material_blog_setup' );

/**
 * Enqueue Styles & Fonts
 */
function material_blog_scripts() {
	// Google Fonts: Roboto + Roboto Slab + Material Symbols
	wp_enqueue_style(
		'material-blog-fonts',
		'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Roboto+Slab:wght@400;500;700&family=Roboto+Mono:wght@400;500&display=swap',
		array(),
		null
	);

	// Material Symbols Outlined (icons)
	wp_enqueue_style(
		'material-symbols',
		'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0',
		array(),
		null
	);

	// Main stylesheet
	wp_enqueue_style(
		'material-blog-style',
		get_stylesheet_uri(),
		array( 'material-blog-fonts', 'material-symbols' ),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'material_blog_scripts' );

/**
 * Register Sidebar / Widget Area
 */
function material_blog_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Blog Sidebar', 'material-blog' ),
		'id'            => 'blog-sidebar',
		'description'   => __( 'Widgets hiển thị ở sidebar bên phải.', 'material-blog' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'material_blog_widgets_init' );

/**
 * Disable comments entirely
 */
function material_blog_disable_comments() {
	// Remove comment support from all post types
	$post_types = get_post_types();
	foreach ( $post_types as $post_type ) {
		if ( post_type_supports( $post_type, 'comments' ) ) {
			remove_post_type_support( $post_type, 'comments' );
			remove_post_type_support( $post_type, 'trackbacks' );
		}
	}
}
add_action( 'admin_init', 'material_blog_disable_comments' );

// Close comments on the front-end
add_filter( 'comments_open', '__return_false', 20, 2 );
add_filter( 'pings_open', '__return_false', 20, 2 );

// Hide existing comments
add_filter( 'comments_array', '__return_empty_array', 10, 2 );

// Remove comments from admin menu
function material_blog_remove_comments_menu() {
	remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'material_blog_remove_comments_menu' );

// Remove comments from admin bar
function material_blog_remove_comments_adminbar() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'comments' );
}
add_action( 'wp_before_admin_bar_render', 'material_blog_remove_comments_adminbar' );

/**
 * Helper: Estimate reading time
 */
function material_blog_reading_time() {
	$content    = get_post_field( 'post_content', get_the_ID() );
	$word_count = str_word_count( strip_tags( $content ) );
	$minutes    = max( 1, ceil( $word_count / 200 ) );
	return sprintf( _n( '%d phút đọc', '%d phút đọc', $minutes, 'material-blog' ), $minutes );
}

/**
 * Custom excerpt length
 */
function material_blog_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'material_blog_excerpt_length' );

/**
 * Custom excerpt "more" text
 */
function material_blog_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'material_blog_excerpt_more' );

/**
 * Custom pagination
 */
function material_blog_pagination() {
	$args = array(
		'prev_text' => '<span class="material-symbols-outlined">chevron_left</span>',
		'next_text' => '<span class="material-symbols-outlined">chevron_right</span>',
		'type'      => 'list',
	);

	$pagination = paginate_links( $args );

	if ( $pagination ) {
		// Convert <ul> list to simple links
		$pagination = str_replace( array( "<ul class='page-numbers'>", '</ul>' ), '', $pagination );
		$pagination = str_replace( array( '<li>', '</li>' ), '', $pagination );
		echo '<nav class="md-pagination" aria-label="Pagination">' . $pagination . '</nav>';
	}
}

/**
 * Add body classes
 */
function material_blog_body_classes( $classes ) {
	if ( is_singular() ) {
		$classes[] = 'md-singular';
	}
	if ( is_active_sidebar( 'blog-sidebar' ) ) {
		$classes[] = 'has-sidebar';
	}
	return $classes;
}
add_filter( 'body_class', 'material_blog_body_classes' );

/**
 * Register reading_time REST field
 */
function material_blog_register_rest_fields() {
	register_rest_field( 'post', 'reading_time', array(
		'get_callback' => function( $post_arr ) {
			// Get post ID from array
			$post_id = $post_arr['id'];
			// Temporarily override active ID to calculate reading time
			$content    = get_post_field( 'post_content', $post_id );
			$word_count = str_word_count( strip_tags( $content ) );
			$minutes    = max( 1, ceil( $word_count / 200 ) );
			return sprintf( _n( '%d phút đọc', '%d phút đọc', $minutes, 'material-blog' ), $minutes );
		}
	) );
}
add_action( 'rest_api_init', 'material_blog_register_rest_fields' );

