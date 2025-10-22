<?php
/**
 * McMaster Navigation Theme functions and definitions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup
 */
function mcmaster_theme_setup() {
    // Add theme support for menus
    add_theme_support('menus');
    
    // Add theme support for post thumbnails
    add_theme_support('post-thumbnails');
    
    // Add theme support for title tag
    add_theme_support('title-tag');
    
    // Add theme support for HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
}
add_action('after_setup_theme', 'mcmaster_theme_setup');

/**
 * Register navigation menus
 */
function mcmaster_register_menus() {
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'mcmaster'),
        'secondary' => __('Secondary Menu', 'mcmaster'),
        'footer' => __('Footer Menu', 'mcmaster'),
    ));
}
add_action('init', 'mcmaster_register_menus');

/**
 * Enqueue scripts and styles
 */
function mcmaster_enqueue_scripts() {
    // Enqueue theme stylesheet
    wp_enqueue_style('mcmaster-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Enqueue navigation script
    wp_enqueue_script('mcmaster-navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery'), '1.0.0', true);
    
    // Localize script for AJAX
    wp_localize_script('mcmaster-navigation', 'mcmaster_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('mcmaster_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'mcmaster_enqueue_scripts');

/**
 * Add customizer options for navigation
 */
function mcmaster_customize_register($wp_customize) {
    // Add navigation section
    $wp_customize->add_section('mcmaster_navigation', array(
        'title' => __('Navigation Settings', 'mcmaster'),
        'priority' => 30,
    ));
    
    // Enable mega menu option
    $wp_customize->add_setting('enable_mega_menu', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('enable_mega_menu', array(
        'label' => __('Enable Mega Menu', 'mcmaster'),
        'section' => 'mcmaster_navigation',
        'type' => 'checkbox',
    ));
    
    // Maximum menu depth
    $wp_customize->add_setting('max_menu_depth', array(
        'default' => 3,
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('max_menu_depth', array(
        'label' => __('Maximum Menu Depth', 'mcmaster'),
        'section' => 'mcmaster_navigation',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 5,
        ),
    ));
    
    // Category data source
    $wp_customize->add_setting('category_data_source', array(
        'default' => 'wordpress',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('category_data_source', array(
        'label' => __('Category Data Source', 'mcmaster'),
        'section' => 'mcmaster_navigation',
        'type' => 'select',
        'choices' => array(
            'wordpress' => __('WordPress Categories', 'mcmaster'),
            'custom' => __('Custom Categories', 'mcmaster'),
            'external_api' => __('External API', 'mcmaster'),
        ),
    ));
}
add_action('customize_register', 'mcmaster_customize_register');

/**
 * Custom Walker Class for McMaster Navigation
 */
require_once get_template_directory() . '/inc/class-mcmaster-walker.php';

/**
 * Navigation helper functions
 */
require_once get_template_directory() . '/inc/navigation-functions.php';

/**
 * Add custom meta box for menu items
 */
function mcmaster_add_menu_meta_boxes() {
    add_meta_box(
        'mcmaster-menu-options',
        __('McMaster Menu Options', 'mcmaster'),
        'mcmaster_menu_meta_box_callback',
        'nav-menus',
        'side',
        'default'
    );
}
add_action('admin_init', 'mcmaster_add_menu_meta_boxes');

/**
 * Menu meta box callback
 */
function mcmaster_menu_meta_box_callback() {
    ?>
    <p class="description">
        <?php _e('Configure special menu item behaviors for the McMaster navigation system.', 'mcmaster'); ?>
    </p>
    <div id="mcmaster-menu-options">
        <p>
            <label for="enable-mega-menu">
                <input type="checkbox" id="enable-mega-menu" name="enable-mega-menu" />
                <?php _e('Enable mega menu for this item', 'mcmaster'); ?>
            </label>
        </p>
        <p>
            <label for="menu-columns">
                <?php _e('Number of columns:', 'mcmaster'); ?>
                <select id="menu-columns" name="menu-columns">
                    <option value="2">2</option>
                    <option value="3" selected>3</option>
                    <option value="4">4</option>
                </select>
            </label>
        </p>
    </div>
    <?php
}

/**
 * Hook for processing category data
 */
function mcmaster_get_category_data($source = 'wordpress') {
    $categories = array();
    
    switch ($source) {
        case 'wordpress':
            $terms = get_categories(array(
                'hide_empty' => false,
                'hierarchical' => true,
            ));
            
            foreach ($terms as $term) {
                $categories[] = array(
                    'id' => $term->term_id,
                    'name' => $term->name,
                    'slug' => $term->slug,
                    'parent' => $term->parent,
                    'count' => $term->count,
                    'url' => get_category_link($term->term_id),
                );
            }
            break;
            
        case 'custom':
            // Allow themes/plugins to filter custom category data
            $categories = apply_filters('mcmaster_custom_categories', array());
            break;
            
        case 'external_api':
            // Allow themes/plugins to provide external API data
            $categories = apply_filters('mcmaster_external_categories', array());
            break;
    }
    
    return apply_filters('mcmaster_category_data', $categories, $source);
}

/**
 * AJAX handler for dynamic menu loading
 */
function mcmaster_load_menu_content() {
    check_ajax_referer('mcmaster_nonce', 'nonce');
    
    $menu_id = sanitize_text_field($_POST['menu_id']);
    $category_id = intval($_POST['category_id']);
    
    $content = mcmaster_get_dynamic_menu_content($menu_id, $category_id);
    
    wp_send_json_success($content);
}
add_action('wp_ajax_mcmaster_load_menu', 'mcmaster_load_menu_content');
add_action('wp_ajax_nopriv_mcmaster_load_menu', 'mcmaster_load_menu_content');

/**
 * AJAX handler for getting menu item meta
 */
function mcmaster_get_menu_item_meta() {
    $item_id = intval($_POST['item_id']);
    $meta_key = sanitize_text_field($_POST['meta_key']);
    
    if (!$item_id || !$meta_key) {
        wp_die();
    }
    
    $meta_value = get_post_meta($item_id, $meta_key, true);
    echo $meta_value;
    wp_die();
}
add_action('wp_ajax_get_menu_item_meta', 'mcmaster_get_menu_item_meta');

/**
 * AJAX handler for saving menu item meta
 */
function mcmaster_save_menu_item_meta() {
    $item_id = intval($_POST['item_id']);
    $meta_key = sanitize_text_field($_POST['meta_key']);
    $meta_value = sanitize_text_field($_POST['meta_value']);
    
    if (!$item_id || !$meta_key) {
        wp_die();
    }
    
    update_post_meta($item_id, $meta_key, $meta_value);
    echo 'success';
    wp_die();
}
add_action('wp_ajax_save_menu_item_meta', 'mcmaster_save_menu_item_meta');

/**
 * Get dynamic menu content
 */
function mcmaster_get_dynamic_menu_content($menu_id, $category_id) {
    $source = get_theme_mod('category_data_source', 'wordpress');
    $categories = mcmaster_get_category_data($source);
    
    // Filter categories by parent
    $child_categories = array_filter($categories, function($cat) use ($category_id) {
        return $cat['parent'] == $category_id;
    });
    
    ob_start();
    if (!empty($child_categories)) {
        echo '<div class="mega-menu-content">';
        foreach (array_chunk($child_categories, 10) as $column) {
            echo '<div class="mega-menu-column">';
            echo '<ul class="mega-menu-items">';
            foreach ($column as $category) {
                echo '<li><a href="' . esc_url($category['url']) . '">' . esc_html($category['name']) . '</a></li>';
            }
            echo '</ul>';
            echo '</div>';
        }
        echo '</div>';
    }
    
    return ob_get_clean();
}

/**
 * Add admin styles for menu customization
 */
function mcmaster_admin_enqueue_scripts($hook) {
    if ($hook !== 'nav-menus.php') {
        return;
    }
    
    wp_enqueue_script('mcmaster-admin-menu', get_template_directory_uri() . '/js/admin-menu.js', array('jquery'), '1.0.0', true);
    wp_enqueue_style('mcmaster-admin-menu', get_template_directory_uri() . '/css/admin-menu.css', array(), '1.0.0');
}
add_action('admin_enqueue_scripts', 'mcmaster_admin_enqueue_scripts');

/**
 * Save custom menu item fields
 */
function mcmaster_save_menu_item_fields($menu_id, $menu_item_db_id, $args) {
    $fields = array(
        'mega_menu' => '_menu_item_mega_menu',
        'columns' => '_menu_item_columns',
        'icon' => '_menu_item_icon',
        'description' => '_menu_item_description',
        'highlight' => '_menu_item_highlight',
    );
    
    foreach ($fields as $field => $meta_key) {
        $field_name = "menu-item-{$field}";
        
        if (isset($_POST[$field_name][$menu_item_db_id])) {
            $value = sanitize_text_field($_POST[$field_name][$menu_item_db_id]);
            update_post_meta($menu_item_db_id, $meta_key, $value);
        } else {
            delete_post_meta($menu_item_db_id, $meta_key);
        }
    }
}
add_action('wp_update_nav_menu_item', 'mcmaster_save_menu_item_fields', 10, 3);

/**
 * Register footer widget area
 */
function mcmaster_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Footer Widgets', 'mcmaster'),
        'id'            => 'footer-widgets',
        'description'   => esc_html__('Add widgets here to appear in the footer.', 'mcmaster'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'mcmaster_widgets_init');