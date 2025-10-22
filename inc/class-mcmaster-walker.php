<?php
/**
 * Custom Walker for McMaster Navigation
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class McMaster_Walker extends Walker_Nav_Menu {
    
    /**
     * What the class handles
     */
    public $tree_type = array('post_type', 'taxonomy', 'custom');
    
    /**
     * Database fields to use
     */
    public $db_fields = array(
        'parent' => 'menu_item_parent',
        'id'     => 'db_id'
    );
    
    /**
     * Start the list before the elements are added
     */
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        
        if ($depth === 0) {
            // Check if this should be a mega menu
            $classes = 'mega-menu';
        } else {
            $classes = 'dropdown-menu';
        }
        
        $output .= "\n$indent<ul class=\"$classes\">\n";
    }
    
    /**
     * End the list after the elements are added
     */
    public function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
    
    /**
     * Start the element output
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $args = (object) $args;
        
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        // Add special classes for mega menu items
        if ($this->has_children && $depth === 0) {
            $classes[] = 'has-mega-menu';
        }
        
        if ($this->has_children) {
            $classes[] = 'menu-item-has-children';
        }
        
        // Check for custom meta fields
        $enable_mega_menu = get_post_meta($item->ID, '_menu_item_mega_menu', true);
        $menu_columns = get_post_meta($item->ID, '_menu_item_columns', true);
        
        if ($enable_mega_menu && $depth === 0) {
            $classes[] = 'mega-menu-enabled';
            if ($menu_columns) {
                $classes[] = 'columns-' . $menu_columns;
            }
        }
        
        /**
         * Filter the CSS class(es) applied to a menu item's list item element.
         */
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        /**
         * Filter the ID applied to a menu item's list item element.
         */
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names . '>';
        
        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
        
        // Add data attributes for JavaScript handling
        if ($this->has_children) {
            $attributes .= ' data-has-children="true"';
        }
        
        if ($enable_mega_menu && $depth === 0) {
            $attributes .= ' data-mega-menu="true"';
            $attributes .= ' data-category-id="' . esc_attr($item->object_id) . '"';
        }
        
        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes . '>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        
        // Add arrow for items with children
        if ($this->has_children) {
            $item_output .= ' <span class="menu-arrow">▼</span>';
        }
        
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    /**
     * End the element output
     */
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
    
    /**
     * Traverse elements to create list from elements.
     */
    public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output) {
        if (!$element) {
            return;
        }
        
        $id_field = $this->db_fields['id'];
        
        // Display this element
        $this->has_children = !empty($children_elements[$element->$id_field]);
        
        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
    
    /**
     * Custom method to generate mega menu content
     */
    public function generate_mega_menu_content($item, $children_elements, $args) {
        $output = '';
        
        if (empty($children_elements[$item->ID])) {
            return $output;
        }
        
        $columns = get_post_meta($item->ID, '_menu_item_columns', true) ?: 3;
        $child_items = $children_elements[$item->ID];
        
        $output .= '<div class="mega-menu-content" data-columns="' . $columns . '">';
        
        // Group children into columns
        $items_per_column = ceil(count($child_items) / $columns);
        $column_items = array_chunk($child_items, $items_per_column);
        
        foreach ($column_items as $column) {
            $output .= '<div class="mega-menu-column">';
            
            foreach ($column as $child_item) {
                $output .= '<div class="mega-menu-section">';
                
                // Add section title
                $output .= '<h4 class="mega-menu-title">';
                $output .= '<a href="' . esc_url($child_item->url) . '">' . esc_html($child_item->title) . '</a>';
                $output .= '</h4>';
                
                // Add child items if any
                if (!empty($children_elements[$child_item->ID])) {
                    $output .= '<ul class="mega-menu-items">';
                    foreach ($children_elements[$child_item->ID] as $grandchild) {
                        $output .= '<li><a href="' . esc_url($grandchild->url) . '">' . esc_html($grandchild->title) . '</a></li>';
                    }
                    $output .= '</ul>';
                }
                
                $output .= '</div>';
            }
            
            $output .= '</div>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
}