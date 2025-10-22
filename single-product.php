<?php
/**
 * The Template for displaying all single products
 *
 * @package McMaster_WC_Theme
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' ); ?>

<main id="primary" class="site-main single-product-main">
    <div class="container">
        <?php while ( have_posts() ) : ?>
            <?php the_post(); ?>
            
            <div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
                
                <!-- Breadcrumb -->
                <?php if ( function_exists( 'woocommerce_breadcrumb' ) ) : ?>
                    <div class="product-breadcrumb">
                        <?php woocommerce_breadcrumb(); ?>
                    </div>
                <?php endif; ?>
                
                <div class="product-main-content">
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
                        </div>
                    </div>
                </div>
                
                <div class="product-details-section">
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

        <?php endwhile; ?>
    </div>
</main>

<style>
/* Single Product Styles */
.single-product-main {
    padding: 40px 0;
}

.product-breadcrumb {
    margin-bottom: 30px;
    padding: 15px 0;
    border-bottom: 1px solid #eee;
}

.product-main-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
    margin-bottom: 60px;
}

.product-images-section {
    position: relative;
}

.woocommerce-product-gallery {
    position: relative;
}

.woocommerce-product-gallery__wrapper {
    position: relative;
}

.woocommerce-product-gallery__image {
    margin-bottom: 20px;
}

.woocommerce-product-gallery__image img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.flex-control-thumbs {
    display: flex;
    gap: 10px;
    margin-top: 20px;
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
    transition: border-color 0.3s ease;
}

.flex-control-thumbs li img:hover,
.flex-control-thumbs li img.flex-active {
    border-color: #0066cc;
}

.product-summary-section {
    padding-left: 20px;
}

.product_title {
    font-size: 32px;
    margin-bottom: 15px;
    color: #333;
    line-height: 1.2;
}

.woocommerce-product-rating {
    margin-bottom: 20px;
}

.price {
    font-size: 28px;
    font-weight: bold;
    color: #0066cc;
    margin-bottom: 20px;
}

.price del {
    color: #999;
    font-size: 20px;
    margin-right: 10px;
}

.woocommerce-product-details__short-description {
    font-size: 16px;
    line-height: 1.6;
    color: #666;
    margin-bottom: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.cart {
    margin-bottom: 30px;
    padding: 25px;
    background: white;
    border: 2px solid #f0f0f0;
    border-radius: 8px;
}

.cart .quantity {
    margin-right: 15px;
}

.cart .quantity input {
    width: 80px;
    padding: 12px;
    border: 2px solid #ddd;
    border-radius: 6px;
    text-align: center;
    font-size: 16px;
}

.single_add_to_cart_button {
    background: #0066cc;
    color: white;
    padding: 15px 30px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.single_add_to_cart_button:hover {
    background: #0052a3;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,102,204,0.3);
}

.single_add_to_cart_button:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.product_meta {
    margin-top: 30px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    font-size: 14px;
}

.product_meta > span {
    display: block;
    margin-bottom: 10px;
    color: #666;
}

.product_meta a {
    color: #0066cc;
    text-decoration: none;
}

.product_meta a:hover {
    text-decoration: underline;
}

/* Product Tabs */
.woocommerce-tabs {
    margin-top: 60px;
}

.woocommerce-tabs .wc-tabs {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    border-bottom: 2px solid #f0f0f0;
    background: #f8f9fa;
    border-radius: 8px 8px 0 0;
}

.woocommerce-tabs .wc-tabs li {
    margin: 0;
    position: relative;
}

.woocommerce-tabs .wc-tabs li a {
    display: block;
    padding: 20px 30px;
    color: #666;
    text-decoration: none;
    font-weight: 500;
    border-bottom: 3px solid transparent;
    transition: all 0.3s ease;
    position: relative;
}

.woocommerce-tabs .wc-tabs li.active a,
.woocommerce-tabs .wc-tabs li a:hover {
    color: #0066cc;
    background: white;
    border-bottom-color: #0066cc;
}

.woocommerce-tabs .wc-tab {
    padding: 40px;
    background: white;
    border-radius: 0 0 8px 8px;
    border: 1px solid #f0f0f0;
    border-top: none;
}

/* PDF Downloads Tab Styles */
.pdf-downloads-section {
    background: white;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
}

.pdf-downloads-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.pdf-download-item {
    border: 2px solid #f0f0f0;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
}

.pdf-download-item:hover {
    border-color: #0066cc;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.pdf-icon {
    font-size: 48px;
    color: #e74c3c;
    margin-bottom: 15px;
}

.pdf-download-item h4 {
    margin-bottom: 10px;
    color: #333;
    font-size: 16px;
}

.pdf-download-item .file-info {
    font-size: 14px;
    color: #666;
    margin-bottom: 15px;
}

.pdf-download-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #0066cc;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.3s ease;
}

.pdf-download-btn:hover {
    background: #0052a3;
    color: white;
}

.no-pdfs-message {
    text-align: center;
    padding: 40px;
    color: #666;
    font-style: italic;
}

/* Responsive Design */
@media (max-width: 968px) {
    .product-main-content {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .product-summary-section {
        padding-left: 0;
    }
}

@media (max-width: 768px) {
    .product_title {
        font-size: 24px;
    }
    
    .price {
        font-size: 22px;
    }
    
    .woocommerce-tabs .wc-tabs {
        flex-direction: column;
    }
    
    .woocommerce-tabs .wc-tabs li a {
        padding: 15px 20px;
    }
    
    .flex-control-thumbs {
        justify-content: center;
    }
    
    .pdf-downloads-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php
get_footer( 'shop' );
?>