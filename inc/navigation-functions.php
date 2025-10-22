<?php
/**
 * Navigation helper functions for McMaster theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display primary navigation menu
 */
function mcmaster_primary_navigation() {
    $menu_args = array(
        'theme_location' => 'primary',
        'menu_class'     => 'nav-menu primary-menu',
        'container'      => 'nav',
        'container_class' => 'main-navigation',
        'walker'         => new McMaster_Walker(),
        'fallback_cb'    => 'mcmaster_fallback_menu',
    );
    
    wp_nav_menu($menu_args);
}

/**
 * Display secondary navigation menu
 */
function mcmaster_secondary_navigation() {
    $menu_args = array(
        'theme_location' => 'secondary',
        'menu_class'     => 'nav-menu secondary-menu',
        'container'      => 'nav',
        'container_class' => 'secondary-navigation',
        'walker'         => new McMaster_Walker(),
        'depth'          => 2,
    );
    
    wp_nav_menu($menu_args);
}

/**
 * Display footer navigation menu
 */
function mcmaster_footer_navigation() {
    $menu_args = array(
        'theme_location' => 'footer',
        'menu_class'     => 'nav-menu footer-menu',
        'container'      => 'nav',
        'container_class' => 'footer-navigation',
        'depth'          => 1,
    );
    
    wp_nav_menu($menu_args);
}

/**
 * Fallback menu if no menu is assigned
 */
function mcmaster_fallback_menu() {
    echo '<nav class="main-navigation">';
    echo '<ul class="nav-menu">';
    
    // Get pages for fallback menu
    $pages = get_pages(array(
        'sort_column' => 'menu_order',
        'parent' => 0,
    ));
    
    foreach ($pages as $page) {
        echo '<li class="menu-item">';
        echo '<a href="' . get_permalink($page->ID) . '">' . esc_html($page->post_title) . '</a>';
        echo '</li>';
    }
    
    echo '</ul>';
    echo '</nav>';
}

/**
 * Get menu items with category data
 */
function mcmaster_get_menu_with_categories($location) {
    $menu_items = wp_get_nav_menu_items(get_nav_menu_locations()[$location]);
    
    if (!$menu_items) {
        return array();
    }
    
    foreach ($menu_items as &$item) {
        // Add category data if this is a category menu item
        if ($item->object === 'category') {
            $category = get_category($item->object_id);
            if ($category) {
                $item->category_data = array(
                    'id' => $category->term_id,
                    'slug' => $category->slug,
                    'parent' => $category->parent,
                    'count' => $category->count,
                    'description' => $category->description,
                );
                
                // Get child categories
                $children = get_categories(array(
                    'parent' => $category->term_id,
                    'hide_empty' => false,
                ));
                
                $item->category_data['children'] = $children;
            }
        }
    }
    
    return $menu_items;
}

/**
 * Generate breadcrumb navigation
 */
function mcmaster_breadcrumb_navigation() {
    if (is_front_page()) {
        return;
    }
    
    echo '<nav class="breadcrumb-navigation" aria-label="Breadcrumb">';
    echo '<ol class="breadcrumb-list">';
    
    // Home link
    echo '<li class="breadcrumb-item">';
    echo '<a href="' . home_url() . '">' . __('Home', 'mcmaster') . '</a>';
    echo '</li>';
    
    if (is_category()) {
        $category = get_queried_object();
        $parents = get_category_parents($category->term_id, true, '</li><li class="breadcrumb-item">');
        if ($parents && !is_wp_error($parents)) {
            echo '<li class="breadcrumb-item">' . rtrim($parents, '</li><li class="breadcrumb-item">');
        }
    } elseif (is_single()) {
        $categories = get_the_category();
        if ($categories) {
            $category = $categories[0];
            echo '<li class="breadcrumb-item">';
            echo '<a href="' . get_category_link($category->term_id) . '">' . esc_html($category->name) . '</a>';
            echo '</li>';
        }
        echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
    } elseif (is_page()) {
        $ancestors = get_post_ancestors(get_the_ID());
        $ancestors = array_reverse($ancestors);
        
        foreach ($ancestors as $ancestor) {
            echo '<li class="breadcrumb-item">';
            echo '<a href="' . get_permalink($ancestor) . '">' . get_the_title($ancestor) . '</a>';
            echo '</li>';
        }
        
        echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
    }
    
    echo '</ol>';
    echo '</nav>';
}

/**
 * Get navigation structure for JavaScript
 */
function mcmaster_get_navigation_structure($location = 'primary') {
    $menu_items = mcmaster_get_menu_with_categories($location);
    
    if (!$menu_items) {
        return array();
    }
    
    $structure = array();
    $menu_array = array();
    
    // Convert menu items to array structure
    foreach ($menu_items as $item) {
        $menu_array[$item->ID] = array(
            'id' => $item->ID,
            'title' => $item->title,
            'url' => $item->url,
            'parent' => $item->menu_item_parent,
            'object' => $item->object,
            'object_id' => $item->object_id,
            'classes' => $item->classes,
            'category_data' => isset($item->category_data) ? $item->category_data : null,
        );
    }
    
    // Build hierarchical structure
    foreach ($menu_array as $item) {
        if ($item['parent'] == 0) {
            $structure[] = mcmaster_build_menu_tree($item, $menu_array);
        }
    }
    
    return $structure;
}

/**
 * Build menu tree recursively
 */
function mcmaster_build_menu_tree($item, $menu_array) {
    $item['children'] = array();
    
    foreach ($menu_array as $child) {
        if ($child['parent'] == $item['id']) {
            $item['children'][] = mcmaster_build_menu_tree($child, $menu_array);
        }
    }
    
    return $item;
}

/**
 * Add mobile menu toggle button
 */
function mcmaster_mobile_menu_toggle() {
    echo '<button class="mobile-menu-toggle" aria-label="' . __('Toggle mobile menu', 'mcmaster') . '" aria-expanded="false">';
    echo '<span></span>';
    echo '<span></span>';
    echo '<span></span>';
    echo '</button>';
}

/**
 * Get category hierarchy for mega menu
 */
function mcmaster_get_category_hierarchy($parent_id = 0, $depth = 0, $max_depth = 3) {
    if ($depth >= $max_depth) {
        return array();
    }
    
    $categories = get_categories(array(
        'parent' => $parent_id,
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC',
    ));
    
    $hierarchy = array();
    
    foreach ($categories as $category) {
        $cat_data = array(
            'id' => $category->term_id,
            'name' => $category->name,
            'slug' => $category->slug,
            'url' => get_category_link($category->term_id),
            'count' => $category->count,
            'description' => $category->description,
            'depth' => $depth,
            'children' => mcmaster_get_category_hierarchy($category->term_id, $depth + 1, $max_depth),
        );
        
        $hierarchy[] = $cat_data;
    }
    
    return $hierarchy;
}

/**
 * Render mega menu content for a specific category
 */
function mcmaster_render_mega_menu_content($category_id, $columns = 3) {
    $children = get_categories(array(
        'parent' => $category_id,
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC',
    ));
    
    if (empty($children)) {
        return '';
    }
    
    ob_start();
    
    echo '<div class="mega-menu-content columns-' . $columns . '">';
    
    $items_per_column = ceil(count($children) / $columns);
    $column_items = array_chunk($children, $items_per_column);
    
    foreach ($column_items as $column) {
        echo '<div class="mega-menu-column">';
        
        foreach ($column as $category) {
            echo '<div class="mega-menu-section">';
            echo '<h4 class="mega-menu-title">';
            echo '<a href="' . get_category_link($category->term_id) . '">' . esc_html($category->name) . '</a>';
            echo '</h4>';
            
            // Get grandchildren
            $grandchildren = get_categories(array(
                'parent' => $category->term_id,
                'hide_empty' => false,
                'number' => 8, // Limit to prevent overcrowding
            ));
            
            if (!empty($grandchildren)) {
                echo '<ul class="mega-menu-items">';
                foreach ($grandchildren as $child) {
                    echo '<li><a href="' . get_category_link($child->term_id) . '">' . esc_html($child->name) . '</a></li>';
                }
                echo '</ul>';
            }
            
            echo '</div>';
        }
        
        echo '</div>';
    }
    
    echo '</div>';
    
    return ob_get_clean();
}