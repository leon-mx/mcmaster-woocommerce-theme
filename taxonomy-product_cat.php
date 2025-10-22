<?php
/**
 * The Template for displaying products in a specific category
 * McMaster-Carr inspired category layout with enhanced performance
 *
 * @package McMaster_WC_Theme
 */

defined('ABSPATH') || exit;

get_header('shop');

$queried_object = get_queried_object();
$category = $queried_object;
$category_id = $category->term_id;

// Performance optimization: Get category data in single query
$category_meta = get_term_meta($category_id);
$category_image_id = $category_meta['thumbnail_id'][0] ?? '';
$category_featured_products = $category_meta['featured_products'][0] ?? '';

// Get subcategories for this category
$subcategories = get_terms([
    'taxonomy' => 'product_cat',
    'parent' => $category_id,
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC',
    'number' => 12 // Limit for performance
]);

// Get total products count for this category
$total_products = wc_get_loop_prop('total');
$current_page = max(1, get_query_var('paged', 1));
$products_per_page = wc_get_loop_prop('per_page');
?>

<main id="primary" class="site-main category-archive-main">
    <div class="container">
        
        <!-- Breadcrumb Navigation -->
        <?php get_template_part('template-parts/woocommerce/breadcrumb-nav'); ?>
        
        <!-- Category Header -->
        <header class="category-header">
            <div class="category-header-content">
                
                <!-- Category Image and Title -->
                <div class="category-hero-section">
                    
                    <?php if ($category_image_id): ?>
                        <div class="category-image">
                            <?php echo wp_get_attachment_image($category_image_id, 'large', false, [
                                'class' => 'category-hero-image',
                                'alt' => $category->name
                            ]); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="category-title-section">
                        <h1 class="category-title"><?php echo esc_html($category->name); ?></h1>
                        
                        <?php if (!empty($category->description)): ?>
                            <div class="category-description">
                                <?php echo wp_kses_post(wpautop($category->description)); ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Category Stats -->
                        <div class="category-stats">
                            <div class="stats-grid">
                                <div class="stat-item">
                                    <span class="stat-number"><?php echo number_format_i18n($category->count); ?></span>
                                    <span class="stat-label"><?php esc_html_e('Products', 'mcmaster-wc-theme'); ?></span>
                                </div>
                                
                                <?php if (!empty($subcategories)): ?>
                                    <div class="stat-item">
                                        <span class="stat-number"><?php echo count($subcategories); ?></span>
                                        <span class="stat-label"><?php esc_html_e('Subcategories', 'mcmaster-wc-theme'); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Average rating for category products -->
                                <?php 
                                $avg_rating = mcmaster_get_category_average_rating($category_id);
                                if ($avg_rating > 0): ?>
                                    <div class="stat-item">
                                        <span class="stat-number"><?php echo number_format($avg_rating, 1); ?></span>
                                        <span class="stat-label"><?php esc_html_e('Avg Rating', 'mcmaster-wc-theme'); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Category Controls -->
                <div class="category-controls">
                    <div class="products-found">
                        <?php if ($total_products > 0): ?>
                            <span class="results-count">
                                <?php
                                $start = ($current_page - 1) * $products_per_page + 1;
                                $end = min($current_page * $products_per_page, $total_products);
                                
                                echo sprintf(
                                    /* translators: %1$s: start number, %2$s: end number, %3$s: total products */
                                    __('Showing %1$s–%2$s of %3$s products in %4$s', 'mcmaster-wc-theme'),
                                    '<strong>' . number_format_i18n($start) . '</strong>',
                                    '<strong>' . number_format_i18n($end) . '</strong>',
                                    '<strong>' . number_format_i18n($total_products) . '</strong>',
                                    '<strong>' . esc_html($category->name) . '</strong>'
                                );
                                ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <?php woocommerce_catalog_ordering(); ?>
                </div>
            </div>
        </header>
        
        <!-- Subcategories Section -->
        <?php if (!empty($subcategories) && $current_page === 1): ?>
            <section class="subcategories-section">
                <h2 class="subcategories-title"><?php esc_html_e('Browse Subcategories', 'mcmaster-wc-theme'); ?></h2>
                
                <div class="subcategories-grid">
                    <?php foreach ($subcategories as $subcategory): ?>
                        <?php 
                        $sub_image_id = get_term_meta($subcategory->term_id, 'thumbnail_id', true);
                        $sub_link = get_term_link($subcategory);
                        ?>
                        
                        <div class="subcategory-card">
                            <a href="<?php echo esc_url($sub_link); ?>" class="subcategory-link">
                                
                                <div class="subcategory-image">
                                    <?php if ($sub_image_id): ?>
                                        <?php echo wp_get_attachment_image($sub_image_id, 'woocommerce_thumbnail', false, [
                                            'class' => 'subcategory-img',
                                            'loading' => 'lazy',
                                            'alt' => $subcategory->name
                                        ]); ?>
                                    <?php else: ?>
                                        <div class="subcategory-placeholder">
                                            <svg width="60" height="60" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M5,4H19A2,2 0 0,1 21,6V18A2,2 0 0,1 19,20H5A2,2 0 0,1 3,18V6A2,2 0 0,1 5,4M5,8V12H11V8H5M13,8V12H19V8H13M5,14V18H11V14H5M13,14V18H19V14H13Z"/>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="subcategory-content">
                                    <h3 class="subcategory-name"><?php echo esc_html($subcategory->name); ?></h3>
                                    <span class="subcategory-count">
                                        <?php echo sprintf(
                                            /* translators: %d: number of products */
                                            _n('%d product', '%d products', $subcategory->count, 'mcmaster-wc-theme'),
                                            $subcategory->count
                                        ); ?>
                                    </span>
                                    
                                    <?php if (!empty($subcategory->description)): ?>
                                        <p class="subcategory-description">
                                            <?php echo esc_html(wp_trim_words($subcategory->description, 12)); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                
                            </a>
                        </div>
                        
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
        
        <!-- Main Content Wrapper -->
        <div class="category-content-wrapper">
            
            <!-- Sidebar/Filters -->
            <aside class="category-sidebar">
                <div class="sidebar-inner">
                    
                    <!-- Category Navigation -->
                    <div class="category-navigation">
                        <h3 class="nav-title">
                            <?php 
                            if ($category->parent) {
                                $parent_cat = get_term($category->parent, 'product_cat');
                                echo esc_html($parent_cat->name);
                            } else {
                                esc_html_e('Categories', 'mcmaster-wc-theme');
                            }
                            ?>
                        </h3>
                        
                        <?php
                        // Show parent category if this is a subcategory
                        if ($category->parent):
                            $parent_cat = get_term($category->parent, 'product_cat');
                        ?>
                            <div class="parent-category">
                                <a href="<?php echo esc_url(get_term_link($parent_cat)); ?>" class="parent-category-link">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z"/>
                                    </svg>
                                    <?php esc_html_e('All', 'mcmaster-wc-theme'); ?> <?php echo esc_html($parent_cat->name); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        // Show sibling categories or subcategories
                        $nav_categories = $category->parent 
                            ? get_terms(['taxonomy' => 'product_cat', 'parent' => $category->parent, 'hide_empty' => true])
                            : $subcategories;
                        
                        if (!empty($nav_categories)):
                        ?>
                            <ul class="category-nav-list">
                                <?php foreach ($nav_categories as $nav_cat): ?>
                                    <li class="category-nav-item <?php echo $nav_cat->term_id === $category_id ? 'current' : ''; ?>">
                                        <a href="<?php echo esc_url(get_term_link($nav_cat)); ?>" class="category-nav-link">
                                            <span class="nav-category-name"><?php echo esc_html($nav_cat->name); ?></span>
                                            <span class="nav-category-count">(<?php echo $nav_cat->count; ?>)</span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Attribute Filters -->
                    <?php
                    $category_attributes = mcmaster_get_category_attributes($category_id);
                    if (!empty($category_attributes)):
                    ?>
                        <div class="attribute-filters">
                            <h3 class="filters-title"><?php esc_html_e('Filter by Attributes', 'mcmaster-wc-theme'); ?></h3>
                            
                            <?php foreach ($category_attributes as $attribute): ?>
                                <div class="attribute-filter-group">
                                    <h4 class="attribute-filter-title"><?php echo esc_html($attribute['label']); ?></h4>
                                    <div class="attribute-filter-options">
                                        <?php foreach ($attribute['terms'] as $term): ?>
                                            <label class="filter-checkbox">
                                                <input type="checkbox" 
                                                       name="filter_<?php echo esc_attr($attribute['slug']); ?>" 
                                                       value="<?php echo esc_attr($term->slug); ?>"
                                                       <?php checked(in_array($term->slug, $_GET['filter_' . $attribute['slug']] ?? [])); ?>>
                                                <span class="checkmark"></span>
                                                <?php echo esc_html($term->name); ?>
                                                <span class="term-count">(<?php echo $term->count; ?>)</span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Price Filter -->
                    <div class="price-filter-section">
                        <h3 class="filter-title"><?php esc_html_e('Price Range', 'mcmaster-wc-theme'); ?></h3>
                        <?php the_widget('WC_Widget_Price_Filter'); ?>
                    </div>
                    
                </div>
            </aside>
            
            <!-- Products Area -->
            <div class="category-main-content">
                
                <!-- View Toggle -->
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
                    
                    <!-- Products Grid -->
                    <div class="products-grid mcmaster-products-grid category-products" 
                         data-columns="<?php echo esc_attr(wc_get_loop_prop('columns')); ?>">
                        
                        <?php
                        // Performance optimization: Batch load product data
                        $product_ids = [];
                        while (have_posts()) {
                            the_post();
                            $product_ids[] = get_the_ID();
                        }
                        
                        if (!empty($product_ids)) {
                            mcmaster_batch_load_product_meta($product_ids, ['_price', '_sale_price', '_stock_status', '_product_features']);
                        }
                        
                        rewind_posts();
                        woocommerce_product_loop_start();
                        
                        while (have_posts()) {
                            the_post();
                            
                            do_action('woocommerce_shop_loop');
                            
                            get_template_part('template-parts/woocommerce/product-card', null, [
                                'show_rating' => true,
                                'show_attributes' => true,
                                'show_stock' => true,
                                'card_size' => 'standard',
                                'lazy_load' => $current_page > 1 // Lazy load on subsequent pages
                            ]);
                        }
                        
                        woocommerce_product_loop_end();
                        ?>
                        
                    </div>
                    
                    <?php do_action('woocommerce_after_shop_loop'); ?>
                    
                <?php else: ?>
                    
                    <!-- No Products Found -->
                    <div class="no-products-found">
                        <div class="no-products-content">
                            <h2 class="no-products-title">
                                <?php echo sprintf(
                                    /* translators: %s: category name */
                                    __('No products found in %s', 'mcmaster-wc-theme'),
                                    esc_html($category->name)
                                ); ?>
                            </h2>
                            <p class="no-products-message">
                                <?php esc_html_e('This category is currently empty. Check back later for new products.', 'mcmaster-wc-theme'); ?>
                            </p>
                            
                            <?php if (!empty($subcategories)): ?>
                                <div class="suggested-categories">
                                    <h3><?php esc_html_e('Try these subcategories:', 'mcmaster-wc-theme'); ?></h3>
                                    <ul class="suggested-categories-list">
                                        <?php foreach (array_slice($subcategories, 0, 5) as $suggested_cat): ?>
                                            <li>
                                                <a href="<?php echo esc_url(get_term_link($suggested_cat)); ?>">
                                                    <?php echo esc_html($suggested_cat->name); ?>
                                                    (<?php echo $suggested_cat->count; ?>)
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                <?php endif; ?>
                
            </div>
        </div>
        
    </div>
</main>

<?php
get_footer('shop');
?>