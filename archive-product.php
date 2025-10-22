<?php
/**
 * The Template for displaying product archives, including the main shop page
 * McMaster-Carr inspired layout with performance optimizations
 *
 * @package McMaster_WC_Theme
 */

defined('ABSPATH') || exit;

get_header('shop');

// Performance optimization: Get total products count for pagination
$total_products = wc_get_loop_prop('total');
$current_page = max(1, get_query_var('paged', 1));
$products_per_page = wc_get_loop_prop('per_page');
?>

<main id="primary" class="site-main archive-product-main">
    <div class="container">
        
        <!-- Breadcrumb Navigation -->
        <?php get_template_part('template-parts/woocommerce/breadcrumb-nav'); ?>
        
        <!-- Page Header -->
        <header class="archive-header">
            <div class="archive-header-content">
                <div class="archive-title-section">
                    <?php if (apply_filters('woocommerce_show_page_title', true)): ?>
                        <h1 class="page-title archive-title">
                            <?php woocommerce_page_title(); ?>
                        </h1>
                    <?php endif; ?>
                    
                    <?php
                    /**
                     * Hook: woocommerce_archive_description
                     * 
                     * @hooked woocommerce_taxonomy_archive_description - 10
                     * @hooked woocommerce_product_archive_description - 10
                     */
                    do_action('woocommerce_archive_description');
                    ?>
                </div>
                
                <!-- Product Count and Sorting -->
                <div class="archive-controls">
                    <div class="products-found">
                        <?php if ($total_products > 0): ?>
                            <span class="results-count">
                                <?php
                                $start = ($current_page - 1) * $products_per_page + 1;
                                $end = min($current_page * $products_per_page, $total_products);
                                
                                echo sprintf(
                                    /* translators: %1$s: start number, %2$s: end number, %3$s: total products */
                                    __('Showing %1$s–%2$s of %3$s results', 'mcmaster-wc-theme'),
                                    '<strong>' . number_format_i18n($start) . '</strong>',
                                    '<strong>' . number_format_i18n($end) . '</strong>',
                                    '<strong>' . number_format_i18n($total_products) . '</strong>'
                                );
                                ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <?php
                    /**
                     * Hook: woocommerce_before_shop_loop
                     *
                     * @hooked woocommerce_output_all_notices - 10
                     * @hooked woocommerce_result_count - 20
                     * @hooked woocommerce_catalog_ordering - 30
                     */
                    // We'll customize this to show only ordering
                    woocommerce_catalog_ordering();
                    ?>
                </div>
            </div>
        </header>
        
        <div class="archive-content-wrapper">
            
            <!-- Sidebar/Filters -->
            <aside class="archive-sidebar">
                <div class="sidebar-inner">
                    
                    <!-- Active Filters Display -->
                    <?php if (is_active_sidebar('shop-filters')): ?>
                        <div class="active-filters-section">
                            <h3 class="filters-title"><?php esc_html_e('Active Filters', 'mcmaster-wc-theme'); ?></h3>
                            <?php dynamic_sidebar('shop-filters'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Categories Navigation -->
                    <?php if (is_shop() || is_product_category()): ?>
                        <div class="categories-navigation">
                            <h3 class="categories-title"><?php esc_html_e('Categories', 'mcmaster-wc-theme'); ?></h3>
                            <?php
                            $current_cat = is_product_category() ? get_queried_object() : null;
                            $current_cat_id = $current_cat ? $current_cat->term_id : 0;
                            
                            wp_list_categories([
                                'taxonomy' => 'product_cat',
                                'orderby' => 'name',
                                'show_count' => true,
                                'hierarchical' => true,
                                'title_li' => '',
                                'hide_empty' => true,
                                'current_category' => $current_cat_id,
                                'walker' => new McMaster_Category_Walker()
                            ]);
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Price Filter -->
                    <div class="price-filter-section">
                        <h3 class="filter-title"><?php esc_html_e('Price Range', 'mcmaster-wc-theme'); ?></h3>
                        <div class="price-filter-content">
                            <!-- This would integrate with WooCommerce price filter widget -->
                            <?php the_widget('WC_Widget_Price_Filter'); ?>
                        </div>
                    </div>
                    
                    <!-- Stock Status Filter -->
                    <div class="stock-filter-section">
                        <h3 class="filter-title"><?php esc_html_e('Availability', 'mcmaster-wc-theme'); ?></h3>
                        <div class="stock-filter-content">
                            <label class="filter-checkbox">
                                <input type="checkbox" name="in_stock" value="1" <?php checked(isset($_GET['in_stock'])); ?>>
                                <span class="checkmark"></span>
                                <?php esc_html_e('In Stock', 'mcmaster-wc-theme'); ?>
                            </label>
                            <label class="filter-checkbox">
                                <input type="checkbox" name="on_sale" value="1" <?php checked(isset($_GET['on_sale'])); ?>>
                                <span class="checkmark"></span>
                                <?php esc_html_e('On Sale', 'mcmaster-wc-theme'); ?>
                            </label>
                        </div>
                    </div>
                </div>
            </aside>
            
            <!-- Main Products Area -->
            <div class="archive-main-content">
                
                <!-- View Toggle (Grid/List) -->
                <div class="view-toggle-section">
                    <div class="view-options">
                        <button class="view-toggle active" data-view="grid" title="<?php esc_attr_e('Grid View', 'mcmaster-wc-theme'); ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3,11H11V3H3M3,21H11V13H3M13,21H21V13H13M13,3V11H21V3"/>
                            </svg>
                        </button>
                        <button class="view-toggle" data-view="list" title="<?php esc_attr_e('List View', 'mcmaster-wc-theme'); ?>">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9,5V9H21V5M9,19H21V15H9M9,14H21V10H9M4,9H8V5H4M4,19H8V15H4M4,14H8V10H4"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <?php if (woocommerce_product_loop()): ?>
                    
                    <?php
                    /**
                     * Hook: woocommerce_before_shop_loop
                     *
                     * @hooked woocommerce_output_all_notices - 10
                     */
                    do_action('woocommerce_before_shop_loop');
                    ?>
                    
                    <!-- Products Grid -->
                    <div class="products-grid mcmaster-products-grid" 
                         data-columns="<?php echo esc_attr(wc_get_loop_prop('columns')); ?>">
                        
                        <?php
                        // Performance optimization: Use custom loop with batch processing
                        $product_ids = [];
                        woocommerce_product_loop_start();
                        
                        // Collect all product IDs first for batch meta queries
                        while (have_posts()) {
                            the_post();
                            $product_ids[] = get_the_ID();
                        }
                        
                        // Batch load product meta for performance
                        if (!empty($product_ids)) {
                            $meta_keys = ['_price', '_sale_price', '_stock_status', '_product_features'];
                            mcmaster_batch_load_product_meta($product_ids, $meta_keys);
                        }
                        
                        // Reset query and display products
                        rewind_posts();
                        
                        while (have_posts()) {
                            the_post();
                            
                            /**
                             * Hook: woocommerce_shop_loop
                             */
                            do_action('woocommerce_shop_loop');
                            
                            // Use our custom product card partial
                            get_template_part('template-parts/woocommerce/product-card', null, [
                                'show_rating' => true,
                                'show_attributes' => true,
                                'show_stock' => true,
                                'card_size' => 'standard',
                                'lazy_load' => true
                            ]);
                        }
                        
                        woocommerce_product_loop_end();
                        ?>
                        
                    </div>
                    
                    <?php
                    /**
                     * Hook: woocommerce_after_shop_loop
                     *
                     * @hooked woocommerce_pagination - 10
                     */
                    do_action('woocommerce_after_shop_loop');
                    ?>
                    
                <?php else: ?>
                    
                    <!-- No Products Found -->
                    <div class="no-products-found">
                        <div class="no-products-content">
                            <div class="no-products-icon">
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <circle cx="11" cy="11" r="8"/>
                                    <path d="m21 21-4.35-4.35"/>
                                </svg>
                            </div>
                            <h2 class="no-products-title"><?php esc_html_e('No products found', 'mcmaster-wc-theme'); ?></h2>
                            <p class="no-products-message">
                                <?php esc_html_e('Sorry, no products matched your criteria. Try adjusting your filters or search terms.', 'mcmaster-wc-theme'); ?>
                            </p>
                            
                            <div class="no-products-actions">
                                <?php if (is_search()): ?>
                                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn-primary">
                                        <?php esc_html_e('Browse All Products', 'mcmaster-wc-theme'); ?>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (isset($_GET) && !empty($_GET)): ?>
                                    <a href="<?php echo esc_url(strtok($_SERVER['REQUEST_URI'], '?')); ?>" class="btn-secondary">
                                        <?php esc_html_e('Clear Filters', 'mcmaster-wc-theme'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                <?php endif; ?>
                
            </div>
        </div>
        
        <!-- Performance Stats (for debugging in development) -->
        <?php if (defined('WP_DEBUG') && WP_DEBUG): ?>
            <div class="performance-debug" style="margin-top: 40px; padding: 20px; background: #f0f0f0; font-size: 12px;">
                <strong>Performance Stats:</strong>
                Products loaded: <?php echo count($product_ids ?? []); ?> | 
                Query time: <?php echo get_num_queries(); ?> queries | 
                Memory: <?php echo size_format(memory_get_peak_usage()); ?>
            </div>
        <?php endif; ?>
        
    </div>
</main>

<?php

/**
 * Custom Category Walker for enhanced category display
 */
if (!class_exists('McMaster_Category_Walker')) {
    class McMaster_Category_Walker extends Walker_Category {
        
        public function start_el(&$output, $category, $depth = 0, $args = array(), $id = 0) {
            $cat_name = apply_filters('list_cats', esc_attr($category->name), $category);
            
            $indent = str_repeat("\t", $depth);
            $css_classes = array('cat-item', 'cat-item-' . $category->term_id);
            
            if (!empty($args['current_category'])) {
                $_current_category = get_term($args['current_category'], $category->taxonomy);
                if ($category->term_id == $args['current_category']) {
                    $css_classes[] = 'current-cat';
                } elseif ($category->term_id == $_current_category->parent) {
                    $css_classes[] = 'current-cat-parent';
                }
                
                while ($_current_category->parent) {
                    if ($category->term_id == $_current_category->parent) {
                        $css_classes[] = 'current-cat-ancestor';
                        break;
                    }
                    $_current_category = get_term($_current_category->parent, $category->taxonomy);
                }
            }
            
            $css_classes = implode(' ', apply_filters('category_css_class', $css_classes, $category, $depth, $args));
            
            $link = '<a class="category-link" href="' . esc_url(get_term_link($category)) . '">';
            $link .= '<span class="category-name">' . $cat_name . '</span>';
            
            if (!empty($args['show_count'])) {
                $link .= '<span class="category-count">(' . number_format_i18n($category->count) . ')</span>';
            }
            
            $link .= '</a>';
            
            $output .= $indent . '<li class="' . $css_classes . '">' . $link;
        }
    }
}

get_footer('shop');
?>