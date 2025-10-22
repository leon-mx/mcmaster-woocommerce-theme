<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * @package McMaster_WC_Theme
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
}
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'mcmaster-single-product', $product ); ?>>

    <!-- Product Breadcrumb -->
    <div class="product-breadcrumb-wrapper">
        <?php
        /**
         * Hook: woocommerce_before_single_product_summary
         * We'll move breadcrumb here for better positioning
         */
        if ( function_exists( 'woocommerce_breadcrumb' ) ) {
            woocommerce_breadcrumb( array(
                'delimiter'   => ' <span class="breadcrumb-separator">/</span> ',
                'wrap_before' => '<nav class="woocommerce-breadcrumb breadcrumb-nav">',
                'wrap_after'  => '</nav>',
                'before'      => '<span class="breadcrumb-item">',
                'after'       => '</span>',
                'home'        => _x( 'Home', 'breadcrumb', 'mcmaster-wc-theme' ),
            ) );
        }
        ?>
    </div>

    <div class="mcmaster-product-layout">
        <div class="product-images-section">
            <?php
            /**
             * Hook: woocommerce_before_single_product_summary
             *
             * @hooked woocommerce_show_product_sale_flash - 10
             * @hooked woocommerce_show_product_images - 20
             */
            do_action( 'woocommerce_before_single_product_summary' );
            ?>
        </div>

        <div class="product-summary-section">
            <div class="summary entry-summary">
                <?php
                /**
                 * Hook: woocommerce_single_product_summary
                 *
                 * @hooked woocommerce_template_single_title - 5
                 * @hooked woocommerce_template_single_rating - 10
                 * @hooked woocommerce_template_single_price - 10
                 * @hooked woocommerce_template_single_excerpt - 20
                 * @hooked woocommerce_template_single_add_to_cart - 30
                 * @hooked woocommerce_template_single_meta - 40
                 * @hooked woocommerce_template_single_sharing - 50
                 * @hooked WC_Structured_Data::generate_product_data() - 60
                 */
                do_action( 'woocommerce_single_product_summary' );
                ?>
                
                <!-- Custom Product Features -->
                <?php mcmaster_display_product_features(); ?>
            </div>
        </div>
    </div>

    <div class="product-details-tabs">
        <?php
        /**
         * Hook: woocommerce_after_single_product_summary
         *
         * @hooked woocommerce_output_product_data_tabs - 10
         * @hooked woocommerce_upsell_display - 15
         * @hooked woocommerce_output_related_products - 20
         */
        do_action( 'woocommerce_after_single_product_summary' );
        ?>
    </div>

</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>

<style>
/* McMaster Single Product Styles */
.mcmaster-single-product {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 20px rgba(0,0,0,0.1);
    margin-bottom: 40px;
}

.product-breadcrumb-wrapper {
    padding: 20px 30px;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.breadcrumb-nav {
    font-size: 14px;
    color: #6c757d;
}

.breadcrumb-nav a {
    color: #0066cc;
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb-nav a:hover {
    color: #0052a3;
    text-decoration: underline;
}

.breadcrumb-separator {
    margin: 0 8px;
    color: #adb5bd;
}

.mcmaster-product-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    min-height: 500px;
}

.product-images-section {
    padding: 40px;
    background: #fafafa;
    border-right: 1px solid #e9ecef;
}

.product-summary-section {
    padding: 40px;
}

/* Product Images Enhancements */
.woocommerce-product-gallery {
    position: relative;
}

.woocommerce-product-gallery__wrapper {
    position: relative;
}

.woocommerce-product-gallery__image {
    margin-bottom: 20px;
    border-radius: 8px;
    overflow: hidden;
}

.woocommerce-product-gallery__image img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    transition: transform 0.3s ease;
}

.woocommerce-product-gallery__image:hover img {
    transform: scale(1.02);
}

/* Thumbnails styling */
.flex-control-thumbs {
    display: flex;
    gap: 10px;
    margin-top: 20px;
    justify-content: center;
}

.flex-control-thumbs li {
    flex: 0 0 80px;
    list-style: none;
}

.flex-control-thumbs li img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 6px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s ease;
    opacity: 0.7;
}

.flex-control-thumbs li img:hover,
.flex-control-thumbs li img.flex-active {
    border-color: #0066cc;
    opacity: 1;
    transform: scale(1.1);
}

/* Product Summary Enhancements */
.product_title {
    font-size: 28px;
    margin-bottom: 15px;
    color: #212529;
    line-height: 1.3;
    font-weight: 600;
}

.woocommerce-product-rating {
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.star-rating {
    display: inline-flex;
}

.woocommerce-review-link {
    color: #6c757d;
    text-decoration: none;
    font-size: 14px;
}

.woocommerce-review-link:hover {
    color: #0066cc;
}

.price {
    font-size: 32px;
    font-weight: bold;
    color: #0066cc;
    margin-bottom: 25px;
    display: block;
}

.price del {
    color: #dc3545;
    font-size: 24px;
    margin-right: 15px;
    text-decoration: line-through;
}

.price ins {
    text-decoration: none;
    color: #28a745;
}

/* Short Description */
.woocommerce-product-details__short-description {
    font-size: 16px;
    line-height: 1.6;
    color: #495057;
    margin-bottom: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-left: 4px solid #0066cc;
    border-radius: 0 8px 8px 0;
}

/* Add to Cart Section */
.cart {
    margin-bottom: 30px;
    padding: 25px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.cart .quantity {
    margin-right: 15px;
    display: inline-block;
}

.cart .quantity input {
    width: 70px;
    padding: 12px 8px;
    border: 2px solid #ced4da;
    border-radius: 6px;
    text-align: center;
    font-size: 16px;
    font-weight: 500;
}

.cart .quantity input:focus {
    outline: none;
    border-color: #0066cc;
    box-shadow: 0 0 0 0.2rem rgba(0,102,204,0.25);
}

.single_add_to_cart_button {
    background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);
    color: white;
    padding: 15px 30px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
    overflow: hidden;
}

.single_add_to_cart_button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.single_add_to_cart_button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,102,204,0.4);
}

.single_add_to_cart_button:hover::before {
    left: 100%;
}

.single_add_to_cart_button:active {
    transform: translateY(0);
}

.single_add_to_cart_button:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Product Meta */
.product_meta {
    margin-top: 30px;
    padding: 20px;
    background: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    font-size: 14px;
}

.product_meta > span {
    display: block;
    margin-bottom: 8px;
    color: #495057;
}

.product_meta .posted_in,
.product_meta .tagged_as {
    margin-bottom: 10px;
}

.product_meta a {
    color: #0066cc;
    text-decoration: none;
    font-weight: 500;
}

.product_meta a:hover {
    text-decoration: underline;
}

/* Product Features */
.product-features {
    margin-top: 30px;
    padding: 25px;
    background: #e8f4fd;
    border-radius: 8px;
    border: 1px solid #b3d9ff;
}

.product-features h4 {
    margin-bottom: 15px;
    color: #0066cc;
    font-size: 16px;
    font-weight: 600;
}

.features-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.features-list li {
    padding: 8px 0;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 10px;
}

.features-list li::before {
    content: '✓';
    color: #28a745;
    font-weight: bold;
    font-size: 16px;
}

/* Product Tabs */
.product-details-tabs {
    margin-top: 0;
}

.woocommerce-tabs .wc-tabs {
    background: #f8f9fa;
    border-bottom: 2px solid #e9ecef;
    margin: 0;
}

.woocommerce-tabs .wc-tabs li a {
    padding: 20px 30px;
    font-weight: 500;
    color: #495057;
}

.woocommerce-tabs .wc-tabs li.active a {
    background: white;
    color: #0066cc;
    border-bottom-color: #0066cc;
}

.woocommerce-tabs .wc-tab {
    padding: 40px;
    background: white;
}

/* Responsive Design */
@media (max-width: 968px) {
    .mcmaster-product-layout {
        grid-template-columns: 1fr;
    }
    
    .product-images-section {
        border-right: none;
        border-bottom: 1px solid #e9ecef;
        padding: 30px;
    }
    
    .product-summary-section {
        padding: 30px;
    }
}

@media (max-width: 768px) {
    .product-breadcrumb-wrapper,
    .product-images-section,
    .product-summary-section {
        padding: 20px;
    }
    
    .product_title {
        font-size: 24px;
    }
    
    .price {
        font-size: 28px;
    }
    
    .flex-control-thumbs {
        justify-content: flex-start;
        overflow-x: auto;
        padding-bottom: 10px;
    }
    
    .flex-control-thumbs li {
        flex: 0 0 60px;
    }
    
    .flex-control-thumbs li img {
        width: 60px;
        height: 60px;
    }
}
</style>

<?php
/**
 * Display product features
 */
function mcmaster_display_product_features() {
    global $product;
    
    // Get custom product features (can be set in product admin)
    $features = get_post_meta( $product->get_id(), '_product_features', true );
    
    if ( empty( $features ) ) {
        // Default features based on product type or category
        $features = array(
            __( 'High-quality materials', 'mcmaster-wc-theme' ),
            __( 'Fast shipping available', 'mcmaster-wc-theme' ),
            __( 'Expert customer support', 'mcmaster-wc-theme' ),
            __( 'Satisfaction guarantee', 'mcmaster-wc-theme' ),
        );
    }
    
    if ( ! empty( $features ) && is_array( $features ) ) {
        echo '<div class="product-features">';
        echo '<h4>' . __( 'Product Features', 'mcmaster-wc-theme' ) . '</h4>';
        echo '<ul class="features-list">';
        
        foreach ( $features as $feature ) {
            echo '<li>' . esc_html( $feature ) . '</li>';
        }
        
        echo '</ul>';
        echo '</div>';
    }
}
?>