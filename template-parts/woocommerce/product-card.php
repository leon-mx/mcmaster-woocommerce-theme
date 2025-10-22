<?php
/**
 * Product Card Partial - McMaster-inspired design
 * 
 * @package McMaster_WC_Theme
 * @param WC_Product $product
 * @param array $args Additional configuration arguments
 */

if (!defined('ABSPATH')) {
    exit;
}

global $product;

if (!$product || !is_a($product, 'WC_Product')) {
    return;
}

$args = wp_parse_args($args ?? [], [
    'show_rating' => true,
    'show_attributes' => true,
    'show_stock' => true,
    'card_size' => 'standard', // standard, compact, large
    'lazy_load' => true
]);

$product_id = $product->get_id();
$permalink = $product->get_permalink();
$title = $product->get_name();
$image_id = $product->get_image_id();
$stock_status = $product->get_stock_status();
$rating = $product->get_average_rating();
$review_count = $product->get_review_count();

// Performance optimization: Get all meta data in one query
$product_meta = get_post_meta($product_id);
$features = $product_meta['_product_features'][0] ?? [];
if (is_string($features)) {
    $features = maybe_unserialize($features);
}

$card_classes = ['mcmaster-product-card', 'product-card-' . $args['card_size']];
if ($stock_status !== 'instock') {
    $card_classes[] = 'out-of-stock';
}
?>

<div class="<?php echo esc_attr(implode(' ', $card_classes)); ?>" data-product-id="<?php echo esc_attr($product_id); ?>">
    <div class="product-card-inner">
        
        <!-- Product Image -->
        <div class="product-card-image">
            <a href="<?php echo esc_url($permalink); ?>" class="product-image-link">
                <?php if ($image_id): ?>
                    <?php 
                    $image_size = $args['card_size'] === 'large' ? 'woocommerce_single' : 'woocommerce_thumbnail';
                    $loading = $args['lazy_load'] ? 'lazy' : 'eager';
                    echo wp_get_attachment_image($image_id, $image_size, false, [
                        'class' => 'product-card-img',
                        'loading' => $loading,
                        'alt' => $title
                    ]);
                    ?>
                <?php else: ?>
                    <div class="product-placeholder-image">
                        <svg width="120" height="120" viewBox="0 0 120 120" fill="none">
                            <rect width="120" height="120" fill="#f3f4f6"/>
                            <path d="M30 90L45 75L60 90L75 75L90 90V30H30V90Z" fill="#d1d5db"/>
                            <circle cx="45" cy="45" r="7" fill="#d1d5db"/>
                        </svg>
                    </div>
                <?php endif; ?>
            </a>
            
            <!-- Product Badges -->
            <div class="product-badges">
                <?php if ($product->is_on_sale()): ?>
                    <span class="badge sale-badge"><?php esc_html_e('Sale', 'mcmaster-wc-theme'); ?></span>
                <?php endif; ?>
                
                <?php if ($stock_status !== 'instock'): ?>
                    <span class="badge stock-badge out-of-stock"><?php esc_html_e('Out of Stock', 'mcmaster-wc-theme'); ?></span>
                <?php endif; ?>
                
                <?php if ($product->is_featured()): ?>
                    <span class="badge featured-badge"><?php esc_html_e('Featured', 'mcmaster-wc-theme'); ?></span>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Product Info -->
        <div class="product-card-content">
            
            <!-- Product Title -->
            <h3 class="product-card-title">
                <a href="<?php echo esc_url($permalink); ?>">
                    <?php echo esc_html($title); ?>
                </a>
            </h3>
            
            <!-- Product Rating -->
            <?php if ($args['show_rating'] && $rating > 0): ?>
                <div class="product-card-rating">
                    <div class="star-rating" title="<?php echo sprintf(__('Rated %s out of 5', 'mcmaster-wc-theme'), $rating); ?>">
                        <span class="rating-stars" style="width: <?php echo ($rating / 5) * 100; ?>%">
                            <span class="screen-reader-text"><?php echo sprintf(__('Rated %s out of 5', 'mcmaster-wc-theme'), $rating); ?></span>
                        </span>
                    </div>
                    <span class="review-count">(<?php echo esc_html($review_count); ?>)</span>
                </div>
            <?php endif; ?>
            
            <!-- Product Price -->
            <div class="product-card-price">
                <?php get_template_part('template-parts/woocommerce/pricing-display', null, ['product' => $product]); ?>
            </div>
            
            <!-- Product Attributes/Features -->
            <?php if ($args['show_attributes'] && !empty($features) && is_array($features)): ?>
                <div class="product-card-features">
                    <ul class="features-list">
                        <?php 
                        // Show only first 3 features in card view for performance
                        $display_features = array_slice($features, 0, 3);
                        foreach ($display_features as $feature): ?>
                            <li class="feature-item">
                                <svg class="feature-checkmark" width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                </svg>
                                <?php echo esc_html($feature); ?>
                            </li>
                        <?php endforeach; ?>
                        
                        <?php if (count($features) > 3): ?>
                            <li class="feature-item more-features">
                                <span class="more-count">+<?php echo count($features) - 3; ?> <?php esc_html_e('more features', 'mcmaster-wc-theme'); ?></span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <!-- Stock Status -->
            <?php if ($args['show_stock']): ?>
                <div class="product-card-stock">
                    <?php if ($stock_status === 'instock'): ?>
                        <span class="stock-status in-stock">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                <circle cx="12" cy="12" r="10"/>
                            </svg>
                            <?php esc_html_e('In Stock', 'mcmaster-wc-theme'); ?>
                        </span>
                    <?php else: ?>
                        <span class="stock-status out-of-stock">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                <circle cx="12" cy="12" r="10"/>
                            </svg>
                            <?php 
                            echo $stock_status === 'outofstock' 
                                ? esc_html__('Out of Stock', 'mcmaster-wc-theme')
                                : esc_html__('On Backorder', 'mcmaster-wc-theme');
                            ?>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Product Actions -->
        <div class="product-card-actions">
            <?php if ($product->is_purchasable() && $stock_status === 'instock'): ?>
                <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" 
                   class="add-to-cart-btn btn-primary" 
                   data-product-id="<?php echo esc_attr($product_id); ?>"
                   data-quantity="1">
                    <?php echo esc_html($product->add_to_cart_text()); ?>
                </a>
            <?php else: ?>
                <a href="<?php echo esc_url($permalink); ?>" class="view-product-btn btn-secondary">
                    <?php esc_html_e('View Details', 'mcmaster-wc-theme'); ?>
                </a>
            <?php endif; ?>
            
            <button class="quick-view-btn" 
                    data-product-id="<?php echo esc_attr($product_id); ?>"
                    title="<?php esc_attr_e('Quick View', 'mcmaster-wc-theme'); ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                </svg>
            </button>
        </div>
    </div>
</div>