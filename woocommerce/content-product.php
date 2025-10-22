<?php
/**
 * The template for displaying product content within loops
 *
 * @package McMaster_WC_Theme
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}
?>

<li <?php wc_product_class( 'mcmaster-product-item', $product ); ?>>
    <div class="product-item-wrapper">
        
        <!-- Product Image -->
        <div class="product-image-wrapper">
            <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="product-image-link">
                <?php
                /**
                 * Hook: woocommerce_before_shop_loop_item_title
                 *
                 * @hooked woocommerce_show_product_loop_sale_flash - 10
                 * @hooked woocommerce_template_loop_product_thumbnail - 10
                 */
                do_action( 'woocommerce_before_shop_loop_item_title' );
                ?>
                
                <!-- Product badges -->
                <div class="product-badges">
                    <?php if ( $product->is_on_sale() ) : ?>
                        <span class="sale-badge"><?php esc_html_e( 'Sale', 'mcmaster-wc-theme' ); ?></span>
                    <?php endif; ?>
                    
                    <?php if ( $product->is_featured() ) : ?>
                        <span class="featured-badge"><?php esc_html_e( 'Featured', 'mcmaster-wc-theme' ); ?></span>
                    <?php endif; ?>
                    
                    <?php if ( ! $product->is_in_stock() ) : ?>
                        <span class="out-of-stock-badge"><?php esc_html_e( 'Out of Stock', 'mcmaster-wc-theme' ); ?></span>
                    <?php endif; ?>
                </div>
                
                <!-- Quick view button -->
                <div class="product-quick-actions">
                    <button type="button" class="quick-view-btn" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" title="<?php esc_attr_e( 'Quick View', 'mcmaster-wc-theme' ); ?>">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"/>
                        </svg>
                    </button>
                </div>
            </a>
        </div>
        
        <!-- Product Info -->
        <div class="product-info-wrapper">
            
            <!-- Product Category -->
            <?php
            $product_categories = wp_get_post_terms( $product->get_id(), 'product_cat' );
            if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
                $primary_category = $product_categories[0];
                echo '<div class="product-category">';
                echo '<a href="' . esc_url( get_term_link( $primary_category ) ) . '">' . esc_html( $primary_category->name ) . '</a>';
                echo '</div>';
            }
            ?>
            
            <!-- Product Title -->
            <h3 class="product-title">
                <a href="<?php echo esc_url( $product->get_permalink() ); ?>">
                    <?php echo wp_kses_post( $product->get_name() ); ?>
                </a>
            </h3>
            
            <!-- Product Rating -->
            <?php if ( wc_review_ratings_enabled() ) : ?>
                <div class="product-rating-wrapper">
                    <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
                    <span class="rating-count">
                        (<?php echo esc_html( $product->get_review_count() ); ?>)
                    </span>
                </div>
            <?php endif; ?>
            
            <!-- Product Excerpt -->
            <div class="product-excerpt">
                <?php
                $excerpt = $product->get_short_description();
                if ( $excerpt ) {
                    echo '<p>' . wp_trim_words( wp_strip_all_tags( $excerpt ), 15, '...' ) . '</p>';
                }
                ?>
            </div>
            
            <!-- Product Price -->
            <div class="product-price-wrapper">
                <?php
                /**
                 * Hook: woocommerce_after_shop_loop_item_title
                 *
                 * @hooked woocommerce_template_loop_rating - 5
                 * @hooked woocommerce_template_loop_price - 10
                 */
                echo '<div class="price-container">' . $product->get_price_html() . '</div>';
                ?>
            </div>
            
            <!-- Stock Status -->
            <div class="stock-status">
                <?php if ( $product->is_in_stock() ) : ?>
                    <span class="in-stock">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z"/>
                        </svg>
                        <?php esc_html_e( 'In Stock', 'mcmaster-wc-theme' ); ?>
                    </span>
                <?php else : ?>
                    <span class="out-of-stock">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"/>
                        </svg>
                        <?php esc_html_e( 'Out of Stock', 'mcmaster-wc-theme' ); ?>
                    </span>
                <?php endif; ?>
            </div>
            
            <!-- Add to Cart Button -->
            <div class="product-actions">
                <?php
                /**
                 * Hook: woocommerce_after_shop_loop_item
                 *
                 * @hooked woocommerce_template_loop_add_to_cart - 10
                 */
                do_action( 'woocommerce_after_shop_loop_item' );
                ?>
            </div>
            
        </div>
    </div>
</li>

<style>
/* McMaster Product Item Styles */
.mcmaster-product-item {
    list-style: none;
    margin: 0;
    padding: 0;
}

.product-item-wrapper {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    transition: all 0.4s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
}

.product-item-wrapper:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.15);
}

/* Product Image Section */
.product-image-wrapper {
    position: relative;
    overflow: hidden;
    background: #f8f9fa;
    aspect-ratio: 1;
}

.product-image-link {
    display: block;
    position: relative;
    width: 100%;
    height: 100%;
}

.product-image-link img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.product-item-wrapper:hover .product-image-link img {
    transform: scale(1.05);
}

/* Product Badges */
.product-badges {
    position: absolute;
    top: 12px;
    left: 12px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    z-index: 2;
}

.sale-badge,
.featured-badge,
.out-of-stock-badge {
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: white;
}

.sale-badge {
    background: linear-gradient(135deg, #ff4757 0%, #ff3838 100%);
}

.featured-badge {
    background: linear-gradient(135deg, #ffa502 0%, #ff7675 100%);
}

.out-of-stock-badge {
    background: linear-gradient(135deg, #747d8c 0%, #57606f 100%);
}

/* Quick Actions */
.product-quick-actions {
    position: absolute;
    top: 12px;
    right: 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    opacity: 0;
    transform: translateX(10px);
    transition: all 0.3s ease;
    z-index: 2;
}

.product-item-wrapper:hover .product-quick-actions {
    opacity: 1;
    transform: translateX(0);
}

.quick-view-btn {
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 50%;
    background: rgba(255,255,255,0.9);
    color: #0066cc;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.quick-view-btn:hover {
    background: #0066cc;
    color: white;
    transform: scale(1.1);
}

/* Product Info Section */
.product-info-wrapper {
    padding: 20px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.product-category {
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 5px;
}

.product-category a {
    color: #0066cc;
    text-decoration: none;
    transition: color 0.3s ease;
}

.product-category a:hover {
    color: #0052a3;
}

.product-title {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    line-height: 1.3;
    margin-bottom: 8px;
}

.product-title a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-title a:hover {
    color: #0066cc;
}

/* Product Rating */
.product-rating-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}

.star-rating {
    font-size: 14px;
    color: #ffc107;
}

.rating-count {
    font-size: 12px;
    color: #6c757d;
}

/* Product Excerpt */
.product-excerpt {
    flex: 1;
    margin-bottom: 10px;
}

.product-excerpt p {
    margin: 0;
    font-size: 14px;
    color: #6c757d;
    line-height: 1.4;
}

/* Price Section */
.product-price-wrapper {
    margin-bottom: 10px;
}

.price-container {
    font-size: 18px;
    font-weight: bold;
    color: #0066cc;
}

.price-container .price {
    margin: 0;
}

.price-container del {
    color: #dc3545;
    font-weight: normal;
    font-size: 14px;
    margin-right: 8px;
}

.price-container ins {
    text-decoration: none;
    color: #28a745;
}

/* Stock Status */
.stock-status {
    margin-bottom: 15px;
    font-size: 12px;
    font-weight: 500;
}

.in-stock {
    color: #28a745;
    display: flex;
    align-items: center;
    gap: 4px;
}

.out-of-stock {
    color: #dc3545;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Product Actions */
.product-actions {
    margin-top: auto;
}

.product-actions .button {
    width: 100%;
    background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
    overflow: hidden;
}

.product-actions .button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.product-actions .button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,102,204,0.3);
}

.product-actions .button:hover::before {
    left: 100%;
}

.product-actions .button:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.product-actions .added_to_cart {
    display: inline-block;
    margin-top: 8px;
    padding: 6px 12px;
    background: #28a745;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    text-align: center;
    transition: background 0.3s ease;
}

.product-actions .added_to_cart:hover {
    background: #1e7e34;
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .product-info-wrapper {
        padding: 15px;
    }
    
    .product-title {
        font-size: 15px;
    }
    
    .price-container {
        font-size: 16px;
    }
    
    .product-quick-actions {
        opacity: 1;
        transform: none;
        position: static;
        flex-direction: row;
        justify-content: flex-end;
        padding: 10px;
        background: rgba(0,0,0,0.05);
    }
    
    .quick-view-btn {
        width: 35px;
        height: 35px;
    }
}

/* Loading Animation */
.product-item-wrapper.loading {
    opacity: 0.7;
    pointer-events: none;
}

.product-item-wrapper.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 30px;
    height: 30px;
    margin: -15px 0 0 -15px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #0066cc;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    z-index: 10;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>