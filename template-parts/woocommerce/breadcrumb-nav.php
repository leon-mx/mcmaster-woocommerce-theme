<?php
/**
 * Breadcrumb Navigation - McMaster-inspired navigation
 *
 * @package McMaster_WC_Theme
 * @param array $args Configuration arguments
 */

if (!defined('ABSPATH')) {
    exit;
}

$args = wp_parse_args($args, [
    'show_home' => true,
    'separator' => '/',
    'show_current' => true,
    'max_length' => 50, // Max character length for breadcrumb items
    'schema_markup' => true
]);

// Build breadcrumb trail
$breadcrumbs = [];
$current_page = '';
$home_text = __('Home', 'mcmaster-wc-theme');

// Home breadcrumb
if ($args['show_home']) {
    $breadcrumbs[] = [
        'title' => $home_text,
        'url' => home_url('/'),
        'current' => false
    ];
}

// WooCommerce specific breadcrumbs
if (function_exists('is_woocommerce') && is_woocommerce()) {
    
    // Shop page
    if (is_shop() || is_product_category() || is_product_tag() || is_product()) {
        $shop_page_id = wc_get_page_id('shop');
        if ($shop_page_id > 0 && !is_shop()) {
            $breadcrumbs[] = [
                'title' => get_the_title($shop_page_id),
                'url' => get_permalink($shop_page_id),
                'current' => false
            ];
        }
    }
    
    if (is_product_category()) {
        $current_term = get_queried_object();
        
        // Add parent categories
        if ($current_term && $current_term->parent) {
            $parent_terms = [];
            $parent_id = $current_term->parent;
            
            while ($parent_id) {
                $parent_term = get_term($parent_id, 'product_cat');
                if ($parent_term && !is_wp_error($parent_term)) {
                    $parent_terms[] = $parent_term;
                    $parent_id = $parent_term->parent;
                } else {
                    break;
                }
            }
            
            // Reverse to show in correct order
            $parent_terms = array_reverse($parent_terms);
            
            foreach ($parent_terms as $parent_term) {
                $breadcrumbs[] = [
                    'title' => $parent_term->name,
                    'url' => get_term_link($parent_term),
                    'current' => false
                ];
            }
        }
        
        // Current category
        if ($current_term) {
            $current_page = $current_term->name;
        }
        
    } elseif (is_product_tag()) {
        $current_term = get_queried_object();
        if ($current_term) {
            $current_page = sprintf(__('Products tagged "%s"', 'mcmaster-wc-theme'), $current_term->name);
        }
        
    } elseif (is_product()) {
        $product = wc_get_product();
        if ($product) {
            // Add product categories
            $product_cats = get_the_terms($product->get_id(), 'product_cat');
            if ($product_cats && !is_wp_error($product_cats)) {
                // Get the primary category (first one)
                $primary_cat = $product_cats[0];
                
                // Add parent categories
                if ($primary_cat->parent) {
                    $parent_terms = [];
                    $parent_id = $primary_cat->parent;
                    
                    while ($parent_id) {
                        $parent_term = get_term($parent_id, 'product_cat');
                        if ($parent_term && !is_wp_error($parent_term)) {
                            $parent_terms[] = $parent_term;
                            $parent_id = $parent_term->parent;
                        } else {
                            break;
                        }
                    }
                    
                    $parent_terms = array_reverse($parent_terms);
                    
                    foreach ($parent_terms as $parent_term) {
                        $breadcrumbs[] = [
                            'title' => $parent_term->name,
                            'url' => get_term_link($parent_term),
                            'current' => false
                        ];
                    }
                }
                
                // Add primary category
                $breadcrumbs[] = [
                    'title' => $primary_cat->name,
                    'url' => get_term_link($primary_cat),
                    'current' => false
                ];
            }
            
            $current_page = get_the_title();
        }
        
    } elseif (is_shop()) {
        $current_page = get_the_title(wc_get_page_id('shop'));
        
    }
    
} else {
    // Regular WordPress pages
    if (is_page()) {
        // Add parent pages
        $post = get_queried_object();
        if ($post && $post->post_parent) {
            $parent_ids = [];
            $parent_id = $post->post_parent;
            
            while ($parent_id) {
                $parent_post = get_post($parent_id);
                if ($parent_post) {
                    $parent_ids[] = $parent_post;
                    $parent_id = $parent_post->post_parent;
                } else {
                    break;
                }
            }
            
            $parent_ids = array_reverse($parent_ids);
            
            foreach ($parent_ids as $parent_post) {
                $breadcrumbs[] = [
                    'title' => get_the_title($parent_post),
                    'url' => get_permalink($parent_post),
                    'current' => false
                ];
            }
        }
        
        $current_page = get_the_title();
        
    } elseif (is_category()) {
        $current_term = get_queried_object();
        if ($current_term) {
            $current_page = $current_term->name;
        }
        
    } elseif (is_tag()) {
        $current_term = get_queried_object();
        if ($current_term) {
            $current_page = sprintf(__('Posts tagged "%s"', 'mcmaster-wc-theme'), $current_term->name);
        }
        
    } elseif (is_single()) {
        // Add category for posts
        $categories = get_the_category();
        if ($categories) {
            $category = $categories[0];
            $breadcrumbs[] = [
                'title' => $category->name,
                'url' => get_category_link($category->term_id),
                'current' => false
            ];
        }
        
        $current_page = get_the_title();
        
    } elseif (is_search()) {
        $current_page = sprintf(__('Search results for "%s"', 'mcmaster-wc-theme'), get_search_query());
        
    } elseif (is_404()) {
        $current_page = __('Page not found', 'mcmaster-wc-theme');
    }
}

// Add current page if showing current and not already added
if ($args['show_current'] && !empty($current_page)) {
    $breadcrumbs[] = [
        'title' => $current_page,
        'url' => '',
        'current' => true
    ];
}

// Don't show breadcrumbs if only home
if (count($breadcrumbs) <= 1) {
    return;
}

// Truncate long titles
foreach ($breadcrumbs as &$breadcrumb) {
    if (strlen($breadcrumb['title']) > $args['max_length']) {
        $breadcrumb['title'] = substr($breadcrumb['title'], 0, $args['max_length']) . '...';
    }
}

$schema_items = [];
?>

<nav class="mcmaster-breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb navigation', 'mcmaster-wc-theme'); ?>">
    <div class="breadcrumbs-container">
        <?php if ($args['schema_markup']): ?>
            <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "BreadcrumbList",
                "itemListElement": [
                    <?php 
                    $schema_items = [];
                    foreach ($breadcrumbs as $index => $breadcrumb) {
                        $schema_item = [
                            '@type' => 'ListItem',
                            'position' => $index + 1,
                            'name' => $breadcrumb['title']
                        ];
                        
                        if (!empty($breadcrumb['url'])) {
                            $schema_item['item'] = $breadcrumb['url'];
                        }
                        
                        $schema_items[] = json_encode($schema_item);
                    }
                    echo implode(',', $schema_items);
                    ?>
                ]
            }
            </script>
        <?php endif; ?>
        
        <ol class="breadcrumbs-list">
            <?php foreach ($breadcrumbs as $index => $breadcrumb): ?>
                <li class="breadcrumb-item <?php echo $breadcrumb['current'] ? 'current' : ''; ?>">
                    
                    <?php if (!$breadcrumb['current'] && !empty($breadcrumb['url'])): ?>
                        <a href="<?php echo esc_url($breadcrumb['url']); ?>" 
                           class="breadcrumb-link">
                            <?php if ($index === 0): ?>
                                <!-- Home icon for first breadcrumb -->
                                <svg class="home-icon" width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z"/>
                                </svg>
                            <?php endif; ?>
                            <span class="breadcrumb-text"><?php echo esc_html($breadcrumb['title']); ?></span>
                        </a>
                    <?php else: ?>
                        <span class="breadcrumb-current">
                            <?php echo esc_html($breadcrumb['title']); ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if (!$breadcrumb['current']): ?>
                        <span class="breadcrumb-separator" aria-hidden="true">
                            <?php if ($args['separator'] === '/'): ?>
                                <svg width="8" height="8" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z"/>
                                </svg>
                            <?php else: ?>
                                <?php echo esc_html($args['separator']); ?>
                            <?php endif; ?>
                        </span>
                    <?php endif; ?>
                    
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</nav>