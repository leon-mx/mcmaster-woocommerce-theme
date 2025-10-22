<?php
/**
 * Template part for displaying mega menu content
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get the menu item data
$menu_item = isset($args['menu_item']) ? $args['menu_item'] : null;
$category_id = isset($args['category_id']) ? $args['category_id'] : 0;
$columns = isset($args['columns']) ? $args['columns'] : 3;

if (!$menu_item) {
    return;
}

// Get category data source
$source = get_theme_mod('category_data_source', 'wordpress');
$categories = mcmaster_get_category_data($source);

// Filter child categories
$child_categories = array_filter($categories, function($cat) use ($category_id) {
    return $cat['parent'] == $category_id;
});

if (empty($child_categories)) {
    return;
}

?>

<div class="mega-menu" id="mega-menu-<?php echo esc_attr($menu_item->ID); ?>">
    <div class="mega-menu-wrapper">
        <div class="mega-menu-content columns-<?php echo esc_attr($columns); ?>">
            
            <?php
            // Group categories into columns
            $items_per_column = ceil(count($child_categories) / $columns);
            $column_items = array_chunk($child_categories, $items_per_column);
            
            foreach ($column_items as $column_index => $column) :
                ?>
                <div class="mega-menu-column column-<?php echo esc_attr($column_index + 1); ?>">
                    
                    <?php foreach ($column as $category) : ?>
                        <div class="mega-menu-section">
                            <h4 class="mega-menu-title">
                                <a href="<?php echo esc_url($category['url']); ?>">
                                    <?php echo esc_html($category['name']); ?>
                                    <?php if ($category['count'] > 0) : ?>
                                        <span class="count">(<?php echo esc_html($category['count']); ?>)</span>
                                    <?php endif; ?>
                                </a>
                            </h4>
                            
                            <?php
                            // Get grandchildren for this category
                            $grandchildren = array_filter($categories, function($cat) use ($category) {
                                return $cat['parent'] == $category['id'];
                            });
                            
                            // Limit grandchildren to prevent overcrowding
                            $grandchildren = array_slice($grandchildren, 0, 8);
                            
                            if (!empty($grandchildren)) :
                                ?>
                                <ul class="mega-menu-items">
                                    <?php foreach ($grandchildren as $child) : ?>
                                        <li class="mega-menu-item">
                                            <a href="<?php echo esc_url($child['url']); ?>">
                                                <?php echo esc_html($child['name']); ?>
                                                <?php if ($child['count'] > 0) : ?>
                                                    <span class="count">(<?php echo esc_html($child['count']); ?>)</span>
                                                <?php endif; ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                
                                <?php
                                // Show "View All" link if there are more items
                                $total_children = count(array_filter($categories, function($cat) use ($category) {
                                    return $cat['parent'] == $category['id'];
                                }));
                                
                                if ($total_children > 8) :
                                    ?>
                                    <div class="mega-menu-view-all">
                                        <a href="<?php echo esc_url($category['url']); ?>" class="view-all-link">
                                            <?php
                                            printf(
                                                esc_html__('View All %s (%d)', 'mcmaster'),
                                                esc_html($category['name']),
                                                esc_html($total_children)
                                            );
                                            ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                            <?php endif; ?>
                            
                        </div><!-- .mega-menu-section -->
                    <?php endforeach; ?>
                    
                </div><!-- .mega-menu-column -->
            <?php endforeach; ?>
            
        </div><!-- .mega-menu-content -->
        
        <!-- Optional: Add featured content or promotions -->
        <?php
        $featured_content = apply_filters('mcmaster_mega_menu_featured_content', '', $menu_item, $category_id);
        if ($featured_content) :
            ?>
            <div class="mega-menu-featured">
                <?php echo wp_kses_post($featured_content); ?>
            </div>
        <?php endif; ?>
        
    </div><!-- .mega-menu-wrapper -->
</div><!-- .mega-menu -->