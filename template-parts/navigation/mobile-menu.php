<?php
/**
 * Template part for displaying mobile navigation
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="mobile-navigation" id="mobile-navigation">
    <div class="mobile-menu-header">
        <h3 class="mobile-menu-title"><?php esc_html_e('Navigation', 'mcmaster'); ?></h3>
        <button class="mobile-menu-close" aria-label="<?php esc_attr_e('Close mobile menu', 'mcmaster'); ?>">
            <span class="close-icon">&times;</span>
        </button>
    </div>
    
    <?php if (has_nav_menu('primary')) : ?>
        <nav class="mobile-primary-navigation" aria-label="<?php esc_attr_e('Mobile Primary Navigation', 'mcmaster'); ?>">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_class'     => 'mobile-nav-menu',
                'container'      => false,
                'walker'         => new McMaster_Mobile_Walker(),
                'depth'          => 3,
            ));
            ?>
        </nav>
    <?php endif; ?>
    
    <?php if (has_nav_menu('secondary')) : ?>
        <nav class="mobile-secondary-navigation" aria-label="<?php esc_attr_e('Mobile Secondary Navigation', 'mcmaster'); ?>">
            <h4 class="mobile-nav-section-title"><?php esc_html_e('Quick Links', 'mcmaster'); ?></h4>
            <?php
            wp_nav_menu(array(
                'theme_location' => 'secondary',
                'menu_class'     => 'mobile-nav-menu secondary',
                'container'      => false,
                'depth'          => 1,
            ));
            ?>
        </nav>
    <?php endif; ?>
    
    <!-- Mobile Search -->
    <div class="mobile-search">
        <h4 class="mobile-nav-section-title"><?php esc_html_e('Search', 'mcmaster'); ?></h4>
        <?php get_search_form(); ?>
    </div>
    
    <!-- Mobile Contact Info -->
    <?php
    $contact_info = apply_filters('mcmaster_mobile_contact_info', array());
    if (!empty($contact_info)) :
        ?>
        <div class="mobile-contact-info">
            <h4 class="mobile-nav-section-title"><?php esc_html_e('Contact', 'mcmaster'); ?></h4>
            <ul class="contact-list">
                <?php foreach ($contact_info as $item) : ?>
                    <li class="contact-item">
                        <?php if (isset($item['icon'])) : ?>
                            <span class="contact-icon <?php echo esc_attr($item['icon']); ?>"></span>
                        <?php endif; ?>
                        
                        <?php if (isset($item['url'])) : ?>
                            <a href="<?php echo esc_url($item['url']); ?>">
                                <?php echo esc_html($item['text']); ?>
                            </a>
                        <?php else : ?>
                            <span><?php echo esc_html($item['text']); ?></span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
</div><!-- .mobile-navigation -->

<?php
/**
 * Custom Walker for Mobile Navigation
 */
class McMaster_Mobile_Walker extends Walker_Nav_Menu {
    
    /**
     * Start the list before the elements are added
     */
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"mobile-submenu depth-$depth\">\n";
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
        $classes[] = 'mobile-menu-item-' . $item->ID;
        
        if ($this->has_children) {
            $classes[] = 'mobile-menu-item-has-children';
        }
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'mobile-menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names . '>';
        
        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
        
        // Add data attributes for mobile handling
        if ($this->has_children) {
            $attributes .= ' data-has-children="true"';
            $attributes .= ' aria-expanded="false"';
        }
        
        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes . '>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        
        // Add toggle button for items with children
        if ($this->has_children) {
            $item_output .= '<button class="mobile-submenu-toggle" aria-label="' . 
                esc_attr__('Toggle submenu', 'mcmaster') . '"><span class="toggle-icon">+</span></button>';
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
}
?>