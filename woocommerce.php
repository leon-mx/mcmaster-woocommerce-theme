<?php
/**
 * The template for displaying WooCommerce pages
 *
 * @package McMaster_WC_Theme
 */

get_header(); ?>

<main id="primary" class="site-main woocommerce-main">
    <div class="container">
        <?php if (function_exists('woocommerce_breadcrumb')) : ?>
            <?php woocommerce_breadcrumb(); ?>
        <?php endif; ?>

        <div class="woocommerce-content">
            <?php woocommerce_content(); ?>
        </div>
    </div>
</main>

<style>
/* WooCommerce Specific Styles */
.woocommerce-main {
    padding: 40px 0;
}

.woocommerce-breadcrumb {
    margin-bottom: 30px;
    padding: 15px 0;
    border-bottom: 1px solid #eee;
}

.woocommerce-breadcrumb a {
    color: #0066cc;
    text-decoration: none;
}

.woocommerce-breadcrumb a:hover {
    text-decoration: underline;
}

/* Shop Page Styles */
.woocommerce .woocommerce-result-count,
.woocommerce .woocommerce-ordering {
    margin-bottom: 20px;
}

.woocommerce .woocommerce-products-header {
    margin-bottom: 30px;
}

.woocommerce .woocommerce-products-header__title {
    font-size: 28px;
    margin-bottom: 10px;
    color: #333;
}

/* Product Grid */
.woocommerce ul.products {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 30px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.woocommerce ul.products li.product {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
}

.woocommerce ul.products li.product:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.woocommerce ul.products li.product img {
    width: 100%;
    height: auto;
    border-radius: 6px;
    margin-bottom: 15px;
}

.woocommerce ul.products li.product .woocommerce-loop-product__title {
    font-size: 16px;
    margin-bottom: 10px;
    color: #333;
    line-height: 1.4;
}

.woocommerce ul.products li.product .price {
    font-size: 18px;
    font-weight: bold;
    color: #0066cc;
    margin-bottom: 15px;
}

.woocommerce ul.products li.product .price del {
    color: #999;
    font-weight: normal;
}

.woocommerce ul.products li.product .button {
    background: #0066cc;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    display: inline-block;
    transition: background 0.3s ease;
    font-weight: 500;
}

.woocommerce ul.products li.product .button:hover {
    background: #0052a3;
    color: white;
}

/* Single Product Page */
.woocommerce div.product {
    background: white;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
}

.woocommerce div.product .product_title {
    font-size: 32px;
    margin-bottom: 15px;
    color: #333;
}

.woocommerce div.product .price {
    font-size: 24px;
    font-weight: bold;
    color: #0066cc;
    margin-bottom: 20px;
}

.woocommerce div.product .woocommerce-product-details__short-description {
    font-size: 16px;
    line-height: 1.6;
    color: #666;
    margin-bottom: 25px;
}

.woocommerce div.product form.cart {
    margin-bottom: 30px;
}

.woocommerce div.product form.cart .quantity {
    margin-right: 15px;
}

.woocommerce div.product form.cart .quantity input {
    width: 80px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-align: center;
}

.woocommerce div.product form.cart .single_add_to_cart_button {
    background: #0066cc;
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.3s ease;
}

.woocommerce div.product form.cart .single_add_to_cart_button:hover {
    background: #0052a3;
}

/* Product Gallery */
.woocommerce div.product .woocommerce-product-gallery {
    margin-bottom: 30px;
}

.woocommerce div.product .woocommerce-product-gallery img {
    border-radius: 8px;
}

/* Product Tabs */
.woocommerce div.product .woocommerce-tabs {
    margin-top: 40px;
}

.woocommerce div.product .woocommerce-tabs ul.tabs {
    list-style: none;
    padding: 0;
    margin: 0 0 20px 0;
    border-bottom: 2px solid #eee;
    display: flex;
}

.woocommerce div.product .woocommerce-tabs ul.tabs li {
    margin-right: 30px;
}

.woocommerce div.product .woocommerce-tabs ul.tabs li a {
    display: block;
    padding: 15px 0;
    color: #666;
    text-decoration: none;
    border-bottom: 2px solid transparent;
    transition: all 0.3s ease;
}

.woocommerce div.product .woocommerce-tabs ul.tabs li.active a,
.woocommerce div.product .woocommerce-tabs ul.tabs li a:hover {
    color: #0066cc;
    border-bottom-color: #0066cc;
}

/* Cart Page */
.woocommerce table.cart {
    width: 100%;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.woocommerce table.cart th,
.woocommerce table.cart td {
    padding: 20px;
    border-bottom: 1px solid #eee;
}

.woocommerce table.cart th {
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
}

/* Checkout Page */
.woocommerce #order_review {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.woocommerce .checkout_coupon {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
}

/* Pagination */
.woocommerce .woocommerce-pagination {
    text-align: center;
    margin-top: 40px;
}

.woocommerce .woocommerce-pagination ul {
    display: inline-flex;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 10px;
}

.woocommerce .woocommerce-pagination ul li a,
.woocommerce .woocommerce-pagination ul li span {
    display: block;
    padding: 10px 15px;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
}

.woocommerce .woocommerce-pagination ul li a:hover,
.woocommerce .woocommerce-pagination ul li span.current {
    background: #0066cc;
    color: white;
    border-color: #0066cc;
}

/* Responsive Design */
@media (max-width: 768px) {
    .woocommerce ul.products {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }
    
    .woocommerce div.product {
        padding: 25px 20px;
    }
    
    .woocommerce div.product .product_title {
        font-size: 24px;
    }
    
    .woocommerce div.product .price {
        font-size: 20px;
    }
    
    .woocommerce div.product .woocommerce-tabs ul.tabs {
        flex-direction: column;
    }
    
    .woocommerce div.product .woocommerce-tabs ul.tabs li {
        margin-right: 0;
        margin-bottom: 10px;
    }
    
    .woocommerce table.cart th,
    .woocommerce table.cart td {
        padding: 15px 10px;
        font-size: 14px;
    }
}
</style>

<?php
get_footer();