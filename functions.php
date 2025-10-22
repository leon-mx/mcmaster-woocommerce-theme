<?php
/**
 * McMaster-Carr Inspired WooCommerce Theme functions and definitions
 *
 * @package McMaster_WC_Theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme constants
define('MCMASTER_THEME_VERSION', '1.0.0');
define('MCMASTER_THEME_DIR', get_template_directory());
define('MCMASTER_THEME_URI', get_template_directory_uri());

/**
 * Theme setup
 */
function mcmaster_theme_setup() {
    // Add theme support for various features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    
    // Add theme support for responsive embeds
    add_theme_support('responsive-embeds');
    
    // Add theme support for editor styles
    add_theme_support('editor-styles');
    
    // Add theme support for wide alignment
    add_theme_support('align-wide');
    
    // Add theme support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 50,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'mcmaster-wc-theme'),
        'footer'  => __('Footer Menu', 'mcmaster-wc-theme'),
    ));
    
    // Set content width
    if (!isset($content_width)) {
        $content_width = 1200;
    }
}
add_action('after_setup_theme', 'mcmaster_theme_setup');

/**
 * WooCommerce support
 */
function mcmaster_woocommerce_support() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'mcmaster_woocommerce_support');

/**
 * Enqueue scripts and styles
 */
function mcmaster_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style(
        'mcmaster-style',
        get_stylesheet_uri(),
        array(),
        MCMASTER_THEME_VERSION
    );
    
    // Enqueue custom CSS
    wp_enqueue_style(
        'mcmaster-custom',
        MCMASTER_THEME_URI . '/assets/css/custom.css',
        array('mcmaster-style'),
        MCMASTER_THEME_VERSION
    );
    
    // Enqueue main JavaScript
    wp_enqueue_script(
        'mcmaster-main',
        MCMASTER_THEME_URI . '/assets/js/main.js',
        array('jquery'),
        MCMASTER_THEME_VERSION,
        true
    );
    
    // Localize script for AJAX
    wp_localize_script('mcmaster-main', 'mcmaster_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('mcmaster_nonce'),
    ));
    
    // Enqueue comment reply script on single posts
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'mcmaster_scripts');

/**
 * Register widget areas
 */
function mcmaster_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'mcmaster-wc-theme'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'mcmaster-wc-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer Area 1', 'mcmaster-wc-theme'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here to appear in the first footer area.', 'mcmaster-wc-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer Area 2', 'mcmaster-wc-theme'),
        'id'            => 'footer-2',
        'description'   => __('Add widgets here to appear in the second footer area.', 'mcmaster-wc-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => __('Footer Area 3', 'mcmaster-wc-theme'),
        'id'            => 'footer-3',
        'description'   => __('Add widgets here to appear in the third footer area.', 'mcmaster-wc-theme'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'mcmaster_widgets_init');

/**
 * Custom WooCommerce modifications
 */
// Remove WooCommerce default styles
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Change number of products per row
function mcmaster_woocommerce_loop_columns() {
    return 4;
}
add_filter('loop_shop_columns', 'mcmaster_woocommerce_loop_columns');

// Change number of products per page
function mcmaster_woocommerce_products_per_page() {
    return 12;
}
add_filter('loop_shop_per_page', 'mcmaster_woocommerce_products_per_page');

/**
 * Custom search functionality
 */
function mcmaster_search_filter($query) {
    if (!is_admin() && $query->is_main_query()) {
        if ($query->is_search()) {
            $query->set('post_type', array('product', 'post', 'page'));
        }
    }
}
add_action('pre_get_posts', 'mcmaster_search_filter');

/**
 * Add custom body classes
 */
function mcmaster_body_classes($classes) {
    // Add page slug class
    if (is_page()) {
        $classes[] = 'page-' . get_post_field('post_name');
    }
    
    // Add WooCommerce classes
    if (function_exists('is_woocommerce') && is_woocommerce()) {
        $classes[] = 'woocommerce-page';
    }
    
    return $classes;
}
add_filter('body_class', 'mcmaster_body_classes');

/**
 * Custom excerpt length
 */
function mcmaster_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'mcmaster_excerpt_length');

/**
 * Custom excerpt more
 */
function mcmaster_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'mcmaster_excerpt_more');

/**
 * Breadcrumb function
 */
function mcmaster_breadcrumb() {
    if (function_exists('woocommerce_breadcrumb')) {
        woocommerce_breadcrumb(array(
            'delimiter'   => ' / ',
            'wrap_before' => '<nav class="breadcrumb-nav"><div class="container">',
            'wrap_after'  => '</div></nav>',
            'before'      => '',
            'after'       => '',
            'home'        => _x('Home', 'breadcrumb', 'mcmaster-wc-theme'),
        ));
    }
}

/**
 * Custom cart count for header
 */
function mcmaster_cart_count() {
    if (function_exists('WC')) {
        $count = WC()->cart->get_cart_contents_count();
        echo '<span class="cart-count">' . esc_html($count) . '</span>';
    }
}

/**
 * AJAX cart count update
 */
function mcmaster_update_cart_count() {
    if (function_exists('WC')) {
        echo WC()->cart->get_cart_contents_count();
    }
    wp_die();
}
add_action('wp_ajax_update_cart_count', 'mcmaster_update_cart_count');
add_action('wp_ajax_nopriv_update_cart_count', 'mcmaster_update_cart_count');

/**
 * Customizer settings
 */
function mcmaster_customize_register($wp_customize) {
    // Header section
    $wp_customize->add_section('mcmaster_header', array(
        'title'    => __('Header Settings', 'mcmaster-wc-theme'),
        'priority' => 30,
    ));
    
    // Header top bar text
    $wp_customize->add_setting('header_top_text', array(
        'default'           => 'Free shipping on orders over $100',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('header_top_text', array(
        'label'   => __('Header Top Bar Text', 'mcmaster-wc-theme'),
        'section' => 'mcmaster_header',
        'type'    => 'text',
    ));
    
    // Footer section
    $wp_customize->add_section('mcmaster_footer', array(
        'title'    => __('Footer Settings', 'mcmaster-wc-theme'),
        'priority' => 31,
    ));
    
    // Footer copyright text
    $wp_customize->add_setting('footer_copyright', array(
        'default'           => '© 2024 McMaster-Carr Inspired Theme. All rights reserved.',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('footer_copyright', array(
        'label'   => __('Footer Copyright Text', 'mcmaster-wc-theme'),
        'section' => 'mcmaster_footer',
        'type'    => 'text',
    ));
}
add_action('customize_register', 'mcmaster_customize_register');