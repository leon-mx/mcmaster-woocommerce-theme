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

/**
 * ============================================
 * PRODUCT PDF DOWNLOADS FUNCTIONALITY
 * ============================================
 */

/**
 * Add product PDF fields to admin
 */
function mcmaster_add_product_pdf_fields() {
    echo '<div class="options_group">';
    
    echo '<p class="form-field">';
    echo '<strong>' . __('Product PDF Downloads', 'mcmaster-wc-theme') . '</strong>';
    echo '</p>';
    
    // Add PDF upload fields (we'll use multiple file upload)
    woocommerce_wp_textarea_input(array(
        'id'          => '_product_pdfs',
        'label'       => __('PDF Files (JSON format)', 'mcmaster-wc-theme'),
        'desc_tip'    => true,
        'description' => __('This field stores PDF file data in JSON format. Use the upload buttons below to manage files.', 'mcmaster-wc-theme'),
        'rows'        => 3,
    ));
    
    echo '<div id="pdf-upload-section" style="margin: 15px 0;">';
    echo '<button type="button" class="button upload-pdf-button">' . __('Add PDF File', 'mcmaster-wc-theme') . '</button>';
    echo '<div id="pdf-files-list" style="margin-top: 15px;"></div>';
    echo '</div>';
    
    // Product Features
    echo '<p class="form-field">';
    echo '<strong>' . __('Product Features', 'mcmaster-wc-theme') . '</strong>';
    echo '</p>';
    
    woocommerce_wp_textarea_input(array(
        'id'          => '_product_features',
        'label'       => __('Product Features (one per line)', 'mcmaster-wc-theme'),
        'desc_tip'    => true,
        'description' => __('Enter product features, one per line. These will be displayed with checkmarks on the product page.', 'mcmaster-wc-theme'),
        'rows'        => 4,
        'placeholder' => __("High-quality materials\nFast shipping available\nExpert customer support\nSatisfaction guarantee", 'mcmaster-wc-theme'),
    ));
    
    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'mcmaster_add_product_pdf_fields');

/**
 * Save product PDF data
 */
function mcmaster_save_product_pdf_fields($post_id) {
    // Save PDF data
    $pdf_data = $_POST['_product_pdfs'] ?? '';
    
    if (!empty($pdf_data)) {
        // Validate JSON data
        $decoded_data = json_decode($pdf_data, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            update_post_meta($post_id, '_product_pdfs', sanitize_textarea_field($pdf_data));
        }
    } else {
        delete_post_meta($post_id, '_product_pdfs');
    }
    
    // Save product features
    $features_data = $_POST['_product_features'] ?? '';
    
    if (!empty($features_data)) {
        // Convert textarea input to array (one feature per line)
        $features_lines = explode("\n", $features_data);
        $features_array = array();
        
        foreach ($features_lines as $line) {
            $feature = trim($line);
            if (!empty($feature)) {
                $features_array[] = sanitize_text_field($feature);
            }
        }
        
        if (!empty($features_array)) {
            update_post_meta($post_id, '_product_features', $features_array);
        }
    } else {
        delete_post_meta($post_id, '_product_features');
    }
}
add_action('woocommerce_process_product_meta', 'mcmaster_save_product_pdf_fields');

/**
 * Enqueue admin scripts for PDF management
 */
function mcmaster_admin_scripts($hook) {
    global $post_type;
    
    if ($hook === 'post.php' || $hook === 'post-new.php') {
        if ($post_type === 'product') {
            wp_enqueue_media();
            wp_enqueue_script(
                'mcmaster-product-pdfs',
                MCMASTER_THEME_URI . '/assets/js/product-pdfs.js',
                array('jquery'),
                MCMASTER_THEME_VERSION,
                true
            );
            
            wp_localize_script('mcmaster-product-pdfs', 'mcmaster_pdf_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('mcmaster_pdf_nonce'),
            ));
        }
    }
}
add_action('admin_enqueue_scripts', 'mcmaster_admin_scripts');

/**
 * Add PDF Downloads tab to product page
 */
function mcmaster_add_pdf_downloads_tab($tabs) {
    global $product;
    
    // Check if product has PDFs
    $pdf_data = get_post_meta($product->get_id(), '_product_pdfs', true);
    
    if (!empty($pdf_data)) {
        $pdf_files = json_decode($pdf_data, true);
        
        if (!empty($pdf_files) && is_array($pdf_files)) {
            $tabs['pdf_downloads'] = array(
                'title'    => __('Downloads', 'mcmaster-wc-theme'),
                'priority' => 25,
                'callback' => 'mcmaster_pdf_downloads_tab_content',
            );
        }
    }
    
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'mcmaster_add_pdf_downloads_tab');

/**
 * PDF Downloads tab content
 */
function mcmaster_pdf_downloads_tab_content() {
    global $product;
    
    $pdf_data = get_post_meta($product->get_id(), '_product_pdfs', true);
    $pdf_files = json_decode($pdf_data, true);
    
    if (empty($pdf_files) || !is_array($pdf_files)) {
        echo '<div class="no-pdfs-message">';
        echo '<p>' . __('No download files available for this product.', 'mcmaster-wc-theme') . '</p>';
        echo '</div>';
        return;
    }
    
    echo '<div class="pdf-downloads-section">';
    echo '<h3>' . __('Download Files', 'mcmaster-wc-theme') . '</h3>';
    echo '<p>' . __('Click on any file below to download it to your device.', 'mcmaster-wc-theme') . '</p>';
    
    echo '<div class="pdf-downloads-grid">';
    
    foreach ($pdf_files as $index => $pdf) {
        $file_url = wp_get_attachment_url($pdf['id']);
        $file_name = $pdf['name'];
        $file_size = $pdf['size'] ?? 'Unknown';
        
        if ($file_url) {
            echo '<div class="pdf-download-item">';
            echo '<div class="pdf-icon">📄</div>';
            echo '<h4>' . esc_html($file_name) . '</h4>';
            echo '<div class="file-info">';
            echo '<span class="file-size">' . esc_html($file_size) . '</span>';
            echo '</div>';
            
            echo '<a href="' . esc_url($file_url) . '" class="pdf-download-btn" download="' . esc_attr($file_name) . '" target="_blank">';
            echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">';
            echo '<path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />';
            echo '</svg>';
            echo __('Download', 'mcmaster-wc-theme');
            echo '</a>';
            echo '</div>';
        }
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * AJAX handler for PDF file management
 */
function mcmaster_handle_pdf_upload() {
    check_ajax_referer('mcmaster_pdf_nonce', 'nonce');
    
    if (!current_user_can('edit_products')) {
        wp_die(__('You do not have permission to perform this action.', 'mcmaster-wc-theme'));
    }
    
    $action = $_POST['pdf_action'] ?? '';
    $response = array('success' => false);
    
    switch ($action) {
        case 'get_files':
            $product_id = intval($_POST['product_id'] ?? 0);
            if ($product_id) {
                $pdf_data = get_post_meta($product_id, '_product_pdfs', true);
                $pdf_files = json_decode($pdf_data, true);
                $response['files'] = $pdf_files ?: array();
                $response['success'] = true;
            }
            break;
            
        default:
            $response['message'] = __('Invalid action.', 'mcmaster-wc-theme');
            break;
    }
    
    wp_send_json($response);
}
add_action('wp_ajax_mcmaster_pdf_upload', 'mcmaster_handle_pdf_upload');

/**
 * Helper function to format file size
 */
function mcmaster_format_file_size($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}

/**
 * ============================================
 * PERFORMANCE OPTIMIZATION FUNCTIONS FOR LARGE CATALOGS
 * ============================================
 */

/**
 * Batch load product meta data for performance
 */
function mcmaster_batch_load_product_meta($product_ids, $meta_keys) {
    if (empty($product_ids) || empty($meta_keys)) {
        return;
    }
    
    global $wpdb;
    
    // Create placeholders for the IN clause
    $placeholders = implode(',', array_fill(0, count($product_ids), '%d'));
    $meta_keys_placeholders = implode(',', array_fill(0, count($meta_keys), '%s'));
    
    // Prepare the query
    $query = $wpdb->prepare(
        "SELECT post_id, meta_key, meta_value 
         FROM {$wpdb->postmeta} 
         WHERE post_id IN ($placeholders) 
         AND meta_key IN ($meta_keys_placeholders)",
        array_merge($product_ids, $meta_keys)
    );
    
    // Execute and cache results
    $results = $wpdb->get_results($query);
    
    // Group by post_id for easy access
    $meta_cache = [];
    foreach ($results as $row) {
        $meta_cache[$row->post_id][$row->meta_key] = $row->meta_value;
    }
    
    // Store in object cache if available
    if (function_exists('wp_cache_set')) {
        wp_cache_set('mcmaster_batch_meta_' . md5(serialize($product_ids)), $meta_cache, 'mcmaster', 3600);
    }
    
    return $meta_cache;
}

/**
 * Get category average rating (cached)
 */
function mcmaster_get_category_average_rating($category_id) {
    // Check cache first
    $cache_key = 'mcmaster_cat_rating_' . $category_id;
    $cached_rating = wp_cache_get($cache_key, 'mcmaster');
    
    if ($cached_rating !== false) {
        return $cached_rating;
    }
    
    global $wpdb;
    
    // Get average rating for products in this category
    $query = $wpdb->prepare("
        SELECT AVG(pm.meta_value) as avg_rating
        FROM {$wpdb->postmeta} pm
        INNER JOIN {$wpdb->term_relationships} tr ON pm.post_id = tr.object_id
        INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        WHERE tt.term_id = %d 
        AND tt.taxonomy = 'product_cat'
        AND pm.meta_key = '_wc_average_rating'
        AND pm.meta_value > 0
    ", $category_id);
    
    $result = $wpdb->get_var($query);
    $avg_rating = $result ? floatval($result) : 0;
    
    // Cache for 1 hour
    wp_cache_set($cache_key, $avg_rating, 'mcmaster', 3600);
    
    return $avg_rating;
}

/**
 * Get category attributes for filtering
 */
function mcmaster_get_category_attributes($category_id) {
    // Check cache first
    $cache_key = 'mcmaster_cat_attrs_' . $category_id;
    $cached_attrs = wp_cache_get($cache_key, 'mcmaster');
    
    if ($cached_attrs !== false) {
        return $cached_attrs;
    }
    
    global $wpdb;
    
    // Get product attributes used in this category
    $query = $wpdb->prepare("
        SELECT DISTINCT pa.attribute_name, pa.attribute_label
        FROM {$wpdb->prefix}woocommerce_attribute_taxonomies pa
        WHERE EXISTS (
            SELECT 1 FROM {$wpdb->postmeta} pm
            INNER JOIN {$wpdb->term_relationships} tr ON pm.post_id = tr.object_id
            INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
            WHERE tt.term_id = %d 
            AND tt.taxonomy = 'product_cat'
            AND pm.meta_key = CONCAT('_product_attributes')
        )
        LIMIT 10
    ", $category_id);
    
    $attributes = $wpdb->get_results($query);
    $category_attributes = [];
    
    foreach ($attributes as $attribute) {
        $taxonomy = 'pa_' . $attribute->attribute_name;
        
        // Get terms for this attribute in this category
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => true,
            'number' => 20,
        ]);
        
        if (!empty($terms) && !is_wp_error($terms)) {
            $category_attributes[] = [
                'slug' => $attribute->attribute_name,
                'label' => $attribute->attribute_label,
                'terms' => $terms
            ];
        }
    }
    
    // Cache for 2 hours
    wp_cache_set($cache_key, $category_attributes, 'mcmaster', 7200);
    
    return $category_attributes;
}

/**
 * Optimize WooCommerce queries for large catalogs
 */
function mcmaster_optimize_woocommerce_queries($query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_shop() || is_product_category() || is_product_tag()) {
            // Use smaller posts per page for initial load for performance
            if (!isset($_GET['posts_per_page'])) {
                $query->set('posts_per_page', 20);
            }
            
            // Optimize ordering for large catalogs
            $orderby = $query->get('orderby');
            if ($orderby === 'popularity') {
                $query->set('orderby', 'meta_value_num');
                $query->set('meta_key', 'total_sales');
            }
            
            // Limit tax queries for performance
            $tax_query = $query->get('tax_query', []);
            if (count($tax_query) > 5) {
                $query->set('tax_query', array_slice($tax_query, 0, 5));
            }
        }
    }
}
add_action('pre_get_posts', 'mcmaster_optimize_woocommerce_queries');

/**
 * Register shop filters sidebar
 */
function mcmaster_register_shop_sidebar() {
    register_sidebar([
        'name' => __('Shop Filters', 'mcmaster-wc-theme'),
        'id' => 'shop-filters',
        'description' => __('Widgets for shop page filtering', 'mcmaster-wc-theme'),
        'before_widget' => '<div id="%1$s" class="widget filter-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title filter-title">',
        'after_title' => '</h3>',
    ]);
}
add_action('widgets_init', 'mcmaster_register_shop_sidebar');

/**
 * Enqueue WooCommerce templates CSS and JS
 */
function mcmaster_enqueue_woocommerce_assets() {
    if (function_exists('is_woocommerce') && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
        // Enqueue CSS
        wp_enqueue_style(
            'mcmaster-woocommerce-templates',
            MCMASTER_THEME_URI . '/assets/css/woocommerce-templates.css',
            array('mcmaster-style'),
            MCMASTER_THEME_VERSION
        );
        
        // Enqueue JavaScript
        wp_enqueue_script(
            'mcmaster-woocommerce-templates',
            MCMASTER_THEME_URI . '/assets/js/woocommerce-templates.js',
            array('jquery', 'wc-add-to-cart'),
            MCMASTER_THEME_VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('mcmaster-woocommerce-templates', 'mcmaster_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('mcmaster_ajax_nonce'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'mcmaster_enqueue_woocommerce_assets');

/**
 * Custom pagination for WooCommerce
 */
function mcmaster_woocommerce_pagination() {
    global $wp_query;
    
    if ($wp_query->max_num_pages <= 1) {
        return;
    }
    
    $current_page = max(1, get_query_var('paged'));
    $max_pages = $wp_query->max_num_pages;
    
    // Show fewer pages for performance with large catalogs
    $range = 2; // Show 2 pages on each side of current page
    
    echo '<nav class="woocommerce-pagination mcmaster-pagination" role="navigation">';
    echo '<div class="pagination-info">';
    echo '<span class="pagination-summary">';
    printf(
        /* translators: %1$s: current page, %2$s: total pages */
        __('Page %1$s of %2$s', 'mcmaster-wc-theme'),
        number_format_i18n($current_page),
        number_format_i18n($max_pages)
    );
    echo '</span>';
    echo '</div>';
    
    echo '<ul class="page-numbers">';
    
    // Previous button
    if ($current_page > 1) {
        echo '<li><a class="prev page-numbers" href="' . esc_url(get_pagenum_link($current_page - 1)) . '">';
        echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z"/></svg>';
        echo __('Previous', 'mcmaster-wc-theme') . '</a></li>';
    }
    
    // First page
    if ($current_page > $range + 1) {
        echo '<li><a class="page-numbers" href="' . esc_url(get_pagenum_link(1)) . '">1</a></li>';
        if ($current_page > $range + 2) {
            echo '<li><span class="page-numbers dots">…</span></li>';
        }
    }
    
    // Page range
    for ($i = max(1, $current_page - $range); $i <= min($max_pages, $current_page + $range); $i++) {
        if ($i == $current_page) {
            echo '<li><span aria-current="page" class="page-numbers current">' . $i . '</span></li>';
        } else {
            echo '<li><a class="page-numbers" href="' . esc_url(get_pagenum_link($i)) . '">' . $i . '</a></li>';
        }
    }
    
    // Last page
    if ($current_page < $max_pages - $range) {
        if ($current_page < $max_pages - $range - 1) {
            echo '<li><span class="page-numbers dots">…</span></li>';
        }
        echo '<li><a class="page-numbers" href="' . esc_url(get_pagenum_link($max_pages)) . '">' . $max_pages . '</a></li>';
    }
    
    // Next button
    if ($current_page < $max_pages) {
        echo '<li><a class="next page-numbers" href="' . esc_url(get_pagenum_link($current_page + 1)) . '">';
        echo __('Next', 'mcmaster-wc-theme');
        echo '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z"/></svg>';
        echo '</a></li>';
    }
    
    echo '</ul>';
    echo '</nav>';
}

/**
 * Replace default WooCommerce pagination with our optimized version
 */
function mcmaster_replace_woocommerce_pagination() {
    remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
    add_action('woocommerce_after_shop_loop', 'mcmaster_woocommerce_pagination', 10);
}
add_action('init', 'mcmaster_replace_woocommerce_pagination');

/**
 * ============================================
 * AJAX HANDLERS FOR ENHANCED FUNCTIONALITY
 * ============================================
 */

/**
 * AJAX Quick View handler
 */
function mcmaster_ajax_quick_view() {
    check_ajax_referer('mcmaster_ajax_nonce', 'nonce');
    
    $product_id = intval($_POST['product_id']);
    
    if (!$product_id) {
        wp_send_json_error('Invalid product ID');
    }
    
    $product = wc_get_product($product_id);
    
    if (!$product) {
        wp_send_json_error('Product not found');
    }
    
    // Start output buffering
    ob_start();
    
    // Load quick view template
    ?>
    <div class="quick-view-content">
        <div class="quick-view-images">
            <?php
            $attachment_ids = $product->get_gallery_image_ids();
            if ($product->get_image_id()) {
                array_unshift($attachment_ids, $product->get_image_id());
            }
            
            if (!empty($attachment_ids)) {
                echo '<div class="quick-view-gallery">';
                foreach (array_slice($attachment_ids, 0, 3) as $attachment_id) {
                    echo wp_get_attachment_image($attachment_id, 'woocommerce_single', false, [
                        'class' => 'quick-view-image'
                    ]);
                }
                echo '</div>';
            }
            ?>
        </div>
        
        <div class="quick-view-summary">
            <h2 class="quick-view-title"><?php echo esc_html($product->get_name()); ?></h2>
            
            <div class="quick-view-price">
                <?php echo $product->get_price_html(); ?>
            </div>
            
            <?php if ($product->get_short_description()): ?>
                <div class="quick-view-description">
                    <?php echo wp_kses_post($product->get_short_description()); ?>
                </div>
            <?php endif; ?>
            
            <?php
            // Get product attributes for quick view
            get_template_part('template-parts/woocommerce/attribute-highlights', null, [
                'product' => $product,
                'show_title' => false,
                'max_attributes' => 4,
                'layout' => 'list'
            ]);
            ?>
            
            <form class="cart" method="post" enctype="multipart/form-data">
                <?php if ($product->is_type('simple')): ?>
                    <div class="quantity">
                        <label for="quantity"><?php esc_html_e('Quantity:', 'mcmaster-wc-theme'); ?></label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" step="1">
                    </div>
                    
                    <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" 
                            class="single_add_to_cart_button button alt">
                        <?php echo esc_html($product->single_add_to_cart_text()); ?>
                    </button>
                <?php else: ?>
                    <a href="<?php echo esc_url($product->get_permalink()); ?>" class="button">
                        <?php esc_html_e('View Product', 'mcmaster-wc-theme'); ?>
                    </a>
                <?php endif; ?>
            </form>
            
            <div class="quick-view-meta">
                <?php if ($product->get_sku()): ?>
                    <span class="sku-wrapper">
                        <strong><?php esc_html_e('SKU:', 'mcmaster-wc-theme'); ?></strong> 
                        <span class="sku"><?php echo esc_html($product->get_sku()); ?></span>
                    </span>
                <?php endif; ?>
                
                <div class="stock-status">
                    <?php echo wc_get_stock_html($product); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    
    $content = ob_get_clean();
    
    wp_send_json_success($content);
}
add_action('wp_ajax_mcmaster_quick_view', 'mcmaster_ajax_quick_view');
add_action('wp_ajax_nopriv_mcmaster_quick_view', 'mcmaster_ajax_quick_view');

/**
 * AJAX Live Search handler
 */
function mcmaster_ajax_live_search() {
    check_ajax_referer('mcmaster_ajax_nonce', 'nonce');
    
    $query = sanitize_text_field($_POST['query']);
    
    if (strlen($query) < 3) {
        wp_send_json_error('Query too short');
    }
    
    // Search products
    $args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => 8,
        's' => $query,
        'meta_query' => [
            [
                'key' => '_visibility',
                'value' => ['catalog', 'visible'],
                'compare' => 'IN'
            ]
        ]
    ];
    
    $search_query = new WP_Query($args);
    $results = [];
    
    if ($search_query->have_posts()) {
        while ($search_query->have_posts()) {
            $search_query->the_post();
            $product = wc_get_product(get_the_ID());
            
            $results[] = [
                'title' => get_the_title(),
                'url' => get_permalink(),
                'price' => $product->get_price_html(),
                'image' => get_the_post_thumbnail_url(get_the_ID(), 'woocommerce_thumbnail')
            ];
        }
        wp_reset_postdata();
    }
    
    wp_send_json_success($results);
}
add_action('wp_ajax_mcmaster_live_search', 'mcmaster_ajax_live_search');
add_action('wp_ajax_nopriv_mcmaster_live_search', 'mcmaster_ajax_live_search');

/**
 * Update memory with new information about this codebase
 */
function mcmaster_update_memory() {
    // This function is for memory tracking purposes
    // The codebase now includes:
    // - Custom WooCommerce templates (archive-product.php, taxonomy-product_cat.php, single-product.php)
    // - Reusable partials for product cards, pricing, attributes, breadcrumbs
    // - Performance optimizations for large catalogs (batch loading, caching, efficient pagination)
    // - McMaster-Carr inspired design with technical focus
    // - AJAX functionality for quick view, live search, add to cart
    // - Responsive design with grid/list view toggle
    // - Advanced filtering and category navigation
}