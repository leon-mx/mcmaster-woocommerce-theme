<?php
/**
 * Cart Page
 *
 * @package McMaster_WC_Theme
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="mcmaster-cart-page">
    
    <div class="cart-header">
        <h1 class="cart-title"><?php esc_html_e( 'Shopping Cart', 'mcmaster-wc-theme' ); ?></h1>
        <div class="cart-progress">
            <div class="progress-step active">
                <span class="step-number">1</span>
                <span class="step-label"><?php esc_html_e( 'Cart', 'mcmaster-wc-theme' ); ?></span>
            </div>
            <div class="progress-step">
                <span class="step-number">2</span>
                <span class="step-label"><?php esc_html_e( 'Checkout', 'mcmaster-wc-theme' ); ?></span>
            </div>
            <div class="progress-step">
                <span class="step-number">3</span>
                <span class="step-label"><?php esc_html_e( 'Complete', 'mcmaster-wc-theme' ); ?></span>
            </div>
        </div>
    </div>

    <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
        <?php do_action( 'woocommerce_before_cart_table' ); ?>

        <div class="cart-content">
            <div class="cart-items-section">
                <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="product-thumbnail"><?php esc_html_e( 'Product', 'mcmaster-wc-theme' ); ?></th>
                            <th class="product-name"><?php esc_html_e( 'Details', 'mcmaster-wc-theme' ); ?></th>
                            <th class="product-price"><?php esc_html_e( 'Price', 'mcmaster-wc-theme' ); ?></th>
                            <th class="product-quantity"><?php esc_html_e( 'Quantity', 'mcmaster-wc-theme' ); ?></th>
                            <th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'mcmaster-wc-theme' ); ?></th>
                            <th class="product-remove">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                        <?php
                        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                            $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                            $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                                ?>
                                <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                                    <td class="product-thumbnail">
                                        <div class="product-image-wrapper">
                                            <?php
                                            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                                            if ( ! $product_permalink ) {
                                                echo $thumbnail; // PHPCS: XSS ok.
                                            } else {
                                                printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
                                            }
                                            ?>
                                        </div>
                                    </td>

                                    <td class="product-name" data-title="<?php esc_attr_e( 'Product', 'mcmaster-wc-theme' ); ?>">
                                        <div class="product-details">
                                            <?php
                                            if ( ! $product_permalink ) {
                                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
                                            } else {
                                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="product-link">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                            }

                                            do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                                            // Meta data.
                                            echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

                                            // Backorder notification.
                                            if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'mcmaster-wc-theme' ) . '</p>', $product_id ) );
                                            }
                                            ?>
                                        </div>
                                    </td>

                                    <td class="product-price" data-title="<?php esc_attr_e( 'Price', 'mcmaster-wc-theme' ); ?>">
                                        <div class="price-wrapper">
                                            <?php
                                            echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                            ?>
                                        </div>
                                    </td>

                                    <td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'mcmaster-wc-theme' ); ?>">
                                        <div class="quantity-wrapper">
                                            <?php
                                            if ( $_product->is_sold_individually() ) {
                                                $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                            } else {
                                                $product_quantity = woocommerce_quantity_input(
                                                    array(
                                                        'input_name'   => "cart[{$cart_item_key}][qty]",
                                                        'input_value'  => $cart_item['quantity'],
                                                        'max_value'    => $_product->get_max_purchase_quantity(),
                                                        'min_value'    => '0',
                                                        'product_name' => $_product->get_name(),
                                                    ),
                                                    $_product,
                                                    false
                                                );
                                            }

                                            echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
                                            ?>
                                        </div>
                                    </td>

                                    <td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'mcmaster-wc-theme' ); ?>">
                                        <div class="subtotal-wrapper">
                                            <?php
                                            echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                                            ?>
                                        </div>
                                    </td>

                                    <td class="product-remove">
                                        <?php
                                        echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                            'woocommerce_cart_item_remove_link',
                                            sprintf(
                                                '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s" title="%s">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"/>
                                                    </svg>
                                                </a>',
                                                esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                                esc_html__( 'Remove this item', 'mcmaster-wc-theme' ),
                                                esc_attr( $product_id ),
                                                esc_attr( $_product->get_sku() ),
                                                esc_attr__( 'Remove this item', 'mcmaster-wc-theme' )
                                            ),
                                            $cart_item_key
                                        );
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>

                        <?php do_action( 'woocommerce_cart_contents' ); ?>

                        <tr>
                            <td colspan="6" class="actions">
                                <div class="cart-actions-wrapper">
                                    <div class="coupon-section">
                                        <?php if ( wc_coupons_enabled() ) { ?>
                                            <div class="coupon">
                                                <label for="coupon_code"><?php esc_html_e( 'Coupon:', 'mcmaster-wc-theme' ); ?></label>
                                                <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'mcmaster-wc-theme' ); ?>" />
                                                <button type="submit" class="button apply-coupon" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'mcmaster-wc-theme' ); ?>"><?php esc_attr_e( 'Apply coupon', 'mcmaster-wc-theme' ); ?></button>
                                                <?php do_action( 'woocommerce_cart_coupon' ); ?>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="update-cart-section">
                                        <button type="submit" class="button update-cart" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'mcmaster-wc-theme' ); ?>"><?php esc_html_e( 'Update cart', 'mcmaster-wc-theme' ); ?></button>
                                        <?php do_action( 'woocommerce_cart_actions' ); ?>
                                        <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                    </tbody>
                </table>
                <?php do_action( 'woocommerce_after_cart_table' ); ?>
            </div>

            <div class="cart-sidebar">
                <div class="cart-collaterals">
                    <?php
                    /**
                     * Cart collaterals hook.
                     *
                     * @hooked woocommerce_cross_sell_display
                     * @hooked woocommerce_cart_totals - 10
                     */
                    do_action( 'woocommerce_cart_collaterals' );
                    ?>
                </div>
            </div>
        </div>
    </form>

    <?php do_action( 'woocommerce_after_cart' ); ?>
    
</div>

<style>
/* McMaster Cart Page Styles */
.mcmaster-cart-page {
    padding: 40px 0;
}

.cart-header {
    text-align: center;
    margin-bottom: 40px;
}

.cart-title {
    font-size: 32px;
    margin-bottom: 30px;
    color: #333;
    font-weight: 600;
}

.cart-progress {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 40px;
    margin-bottom: 40px;
}

.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    position: relative;
}

.progress-step::after {
    content: '';
    position: absolute;
    top: 20px;
    left: 100%;
    width: 40px;
    height: 2px;
    background: #e0e0e0;
}

.progress-step:last-child::after {
    display: none;
}

.progress-step.active::after {
    background: #0066cc;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e0e0e0;
    color: #666;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.3s ease;
}

.progress-step.active .step-number {
    background: #0066cc;
    color: white;
}

.step-label {
    font-size: 14px;
    color: #666;
    font-weight: 500;
}

.progress-step.active .step-label {
    color: #0066cc;
}

.cart-content {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 40px;
}

/* Cart Items Section */
.cart-items-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    overflow: hidden;
}

.shop_table {
    width: 100%;
    margin: 0;
    border-collapse: collapse;
}

.shop_table th {
    background: #f8f9fa;
    padding: 20px 15px;
    font-weight: 600;
    color: #333;
    border-bottom: 2px solid #e9ecef;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.shop_table td {
    padding: 20px 15px;
    border-bottom: 1px solid #f0f0f0;
    vertical-align: middle;
}

.cart_item:last-child td {
    border-bottom: none;
}

/* Product Image */
.product-image-wrapper {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    background: #f8f9fa;
}

.product-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Product Details */
.product-details {
    max-width: 250px;
}

.product-link {
    color: #333;
    text-decoration: none;
    font-weight: 500;
    font-size: 16px;
    transition: color 0.3s ease;
}

.product-link:hover {
    color: #0066cc;
}

/* Price and Subtotal */
.price-wrapper,
.subtotal-wrapper {
    font-size: 18px;
    font-weight: 600;
    color: #0066cc;
}

/* Quantity Controls */
.quantity-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
}

.quantity .input-text {
    width: 60px;
    padding: 8px;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    text-align: center;
    font-size: 16px;
    font-weight: 500;
}

.quantity .input-text:focus {
    outline: none;
    border-color: #0066cc;
}

/* Remove Button */
.remove {
    color: #dc3545;
    text-decoration: none;
    padding: 8px;
    border-radius: 50%;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.remove:hover {
    background: #dc3545;
    color: white;
    transform: scale(1.1);
}

/* Cart Actions */
.actions td {
    padding: 30px 20px !important;
    background: #f8f9fa;
    border-bottom: none !important;
}

.cart-actions-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.coupon {
    display: flex;
    align-items: center;
    gap: 10px;
}

.coupon label {
    font-weight: 500;
    color: #333;
}

.coupon .input-text {
    padding: 10px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    font-size: 14px;
}

.apply-coupon,
.update-cart {
    background: #0066cc;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.3s ease;
}

.apply-coupon:hover,
.update-cart:hover {
    background: #0052a3;
}

/* Cart Sidebar */
.cart-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.cart-collaterals {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    padding: 30px;
}

/* Cart Totals */
.cart_totals {
    width: 100%;
}

.cart_totals h2 {
    margin-bottom: 20px;
    font-size: 20px;
    color: #333;
    font-weight: 600;
}

.shop_table_responsive {
    border: none;
}

.cart_totals .shop_table th,
.cart_totals .shop_table td {
    padding: 15px 0;
    border-top: 1px solid #f0f0f0;
    border-bottom: none;
    background: none;
}

.cart_totals .shop_table th {
    font-weight: 500;
    color: #666;
    text-align: left;
    text-transform: none;
    letter-spacing: normal;
}

.cart_totals .shop_table td {
    text-align: right;
    font-weight: 600;
    color: #333;
}

.order-total {
    border-top: 2px solid #0066cc !important;
    font-size: 18px;
}

.order-total th,
.order-total td {
    color: #0066cc !important;
    font-size: 20px !important;
    font-weight: bold !important;
}

/* Checkout Button */
.wc-proceed-to-checkout {
    margin-top: 20px;
}

.checkout-button {
    width: 100%;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-decoration: none;
    display: block;
    text-align: center;
}

.checkout-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(40,167,69,0.3);
    color: white;
}

/* Empty Cart State */
.cart-empty {
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
}

.cart-empty h2 {
    font-size: 28px;
    margin-bottom: 20px;
    color: #666;
}

.cart-empty .return-to-shop {
    margin-top: 30px;
}

.return-to-shop .button {
    background: #0066cc;
    color: white;
    padding: 12px 30px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.3s ease;
}

.return-to-shop .button:hover {
    background: #0052a3;
    color: white;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .cart-content {
        grid-template-columns: 1fr;
    }
    
    .cart-sidebar {
        order: -1;
    }
}

@media (max-width: 768px) {
    .cart-progress {
        gap: 20px;
    }
    
    .progress-step::after {
        width: 20px;
    }
    
    .shop_table,
    .shop_table thead,
    .shop_table tbody,
    .shop_table th,
    .shop_table td,
    .shop_table tr {
        display: block;
    }
    
    .shop_table thead tr {
        position: absolute;
        top: -9999px;
        left: -9999px;
    }
    
    .shop_table tr {
        border: 1px solid #ccc;
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 8px;
        background: white;
    }
    
    .shop_table td {
        border: none;
        position: relative;
        padding-left: 50% !important;
        text-align: right;
    }
    
    .shop_table td:before {
        content: attr(data-title) ":";
        position: absolute;
        left: 15px;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
        font-weight: 600;
        color: #333;
    }
    
    .cart-actions-wrapper {
        flex-direction: column;
        align-items: stretch;
    }
    
    .coupon {
        flex-wrap: wrap;
        justify-content: center;
    }
}
</style>