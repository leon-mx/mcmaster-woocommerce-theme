<?php
/**
 * Pricing Display Partial - McMaster-inspired pricing
 *
 * @package McMaster_WC_Theme
 * @param WC_Product $product
 * @param array $args Configuration arguments
 */

if (!defined('ABSPATH')) {
    exit;
}

$product = $args['product'] ?? $GLOBALS['product'] ?? null;

if (!$product || !is_a($product, 'WC_Product')) {
    return;
}

$args = wp_parse_args($args, [
    'show_tax_notice' => true,
    'show_quantity_breaks' => true,
    'currency_position' => 'left',
    'size' => 'standard' // standard, large, small
]);

$price_html = $product->get_price_html();
$regular_price = $product->get_regular_price();
$sale_price = $product->get_sale_price();
$price = $product->get_price();
$currency_symbol = get_woocommerce_currency_symbol();

// Check for quantity-based pricing (if using a plugin)
$quantity_breaks = [];
if (function_exists('get_quantity_breaks')) {
    $quantity_breaks = get_quantity_breaks($product->get_id());
}

$pricing_classes = ['mcmaster-pricing-display', 'pricing-' . $args['size']];
if ($product->is_on_sale()) {
    $pricing_classes[] = 'has-sale-price';
}
?>

<div class="<?php echo esc_attr(implode(' ', $pricing_classes)); ?>">
    
    <!-- Main Price Display -->
    <div class="price-main">
        <?php if ($product->is_on_sale() && $regular_price): ?>
            
            <!-- Sale Price Layout -->
            <div class="price-sale-layout">
                <span class="price-current sale-price">
                    <span class="currency"><?php echo esc_html($currency_symbol); ?></span>
                    <span class="amount"><?php echo esc_html(number_format((float) $sale_price, 2)); ?></span>
                </span>
                <span class="price-regular was-price">
                    <span class="was-label"><?php esc_html_e('Was:', 'mcmaster-wc-theme'); ?></span>
                    <span class="currency"><?php echo esc_html($currency_symbol); ?></span>
                    <span class="amount"><?php echo esc_html(number_format((float) $regular_price, 2)); ?></span>
                </span>
                
                <?php 
                $savings_amount = (float) $regular_price - (float) $sale_price;
                $savings_percent = round(($savings_amount / (float) $regular_price) * 100);
                ?>
                <span class="savings-badge">
                    <?php echo sprintf(
                        /* translators: %1$s: savings percentage, %2$s: currency symbol, %3$s: savings amount */
                        __('Save %1$s%% (%2$s%3$s)', 'mcmaster-wc-theme'),
                        $savings_percent,
                        esc_html($currency_symbol),
                        esc_html(number_format($savings_amount, 2))
                    ); ?>
                </span>
            </div>
            
        <?php else: ?>
            
            <!-- Regular Price Layout -->
            <span class="price-current regular-price">
                <span class="currency"><?php echo esc_html($currency_symbol); ?></span>
                <span class="amount"><?php echo esc_html(number_format((float) $price, 2)); ?></span>
            </span>
            
        <?php endif; ?>
        
        <!-- Price Unit (per piece, per foot, etc.) -->
        <?php 
        $price_unit = get_post_meta($product->get_id(), '_price_unit', true);
        if ($price_unit): ?>
            <span class="price-unit">/ <?php echo esc_html($price_unit); ?></span>
        <?php endif; ?>
    </div>
    
    <!-- Quantity Break Pricing -->
    <?php if ($args['show_quantity_breaks'] && !empty($quantity_breaks)): ?>
        <div class="quantity-breaks">
            <h4 class="quantity-breaks-title"><?php esc_html_e('Quantity Pricing', 'mcmaster-wc-theme'); ?></h4>
            <div class="quantity-breaks-table">
                <?php foreach ($quantity_breaks as $break): ?>
                    <div class="quantity-break-row">
                        <span class="quantity-range">
                            <?php echo sprintf(
                                /* translators: %1$s: minimum quantity, %2$s: maximum quantity or + */
                                __('%1$s%2$s', 'mcmaster-wc-theme'),
                                esc_html($break['min_qty']),
                                isset($break['max_qty']) ? '–' . esc_html($break['max_qty']) : '+'
                            ); ?>
                        </span>
                        <span class="quantity-price">
                            <span class="currency"><?php echo esc_html($currency_symbol); ?></span>
                            <span class="amount"><?php echo esc_html(number_format((float) $break['price'], 2)); ?></span>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Tax Notice -->
    <?php if ($args['show_tax_notice']): ?>
        <div class="price-tax-notice">
            <?php if (wc_tax_enabled() && !wc_prices_include_tax()): ?>
                <span class="tax-notice excluding-tax">
                    <svg class="info-icon" width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,17A1.5,1.5 0 0,1 10.5,15.5A1.5,1.5 0 0,1 12,14A1.5,1.5 0 0,1 13.5,15.5A1.5,1.5 0 0,1 12,17M12,10.5A1.5,1.5 0 0,1 10.5,9A1.5,1.5 0 0,1 12,7.5A1.5,1.5 0 0,1 13.5,9A1.5,1.5 0 0,1 12,10.5Z"/>
                    </svg>
                    <?php esc_html_e('Excl. tax', 'mcmaster-wc-theme'); ?>
                </span>
            <?php elseif (wc_tax_enabled() && wc_prices_include_tax()): ?>
                <span class="tax-notice including-tax">
                    <svg class="info-icon" width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,17A1.5,1.5 0 0,1 10.5,15.5A1.5,1.5 0 0,1 12,14A1.5,1.5 0 0,1 13.5,15.5A1.5,1.5 0 0,1 12,17M12,10.5A1.5,1.5 0 0,1 10.5,9A1.5,1.5 0 0,1 12,7.5A1.5,1.5 0 0,1 13.5,9A1.5,1.5 0 0,1 12,10.5Z"/>
                    </svg>
                    <?php esc_html_e('Incl. tax', 'mcmaster-wc-theme'); ?>
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <!-- Bulk Discount Notice -->
    <?php 
    $bulk_discount_threshold = get_option('mcmaster_bulk_discount_threshold', 100);
    if ($bulk_discount_threshold && (float) $price >= $bulk_discount_threshold): ?>
        <div class="bulk-discount-notice">
            <svg class="discount-icon" width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4M14.5,9L13.09,10.41L11.5,8.83L9.91,10.41L8.5,9L10.09,7.41L11.5,6L12.91,7.41L14.5,9M8.5,15L9.91,13.59L11.5,15.17L13.09,13.59L14.5,15L12.91,16.41L11.5,18L10.09,16.41L8.5,15Z"/>
            </svg>
            <?php esc_html_e('Eligible for bulk pricing', 'mcmaster-wc-theme'); ?>
        </div>
    <?php endif; ?>
    
    <!-- Stock-based pricing notice -->
    <?php if ($product->managing_stock() && $product->get_stock_quantity() <= 5 && $product->get_stock_quantity() > 0): ?>
        <div class="low-stock-pricing">
            <span class="low-stock-notice">
                <?php echo sprintf(
                    /* translators: %d: stock quantity remaining */
                    _n('Only %d left in stock', 'Only %d left in stock', $product->get_stock_quantity(), 'mcmaster-wc-theme'),
                    $product->get_stock_quantity()
                ); ?>
            </span>
        </div>
    <?php endif; ?>
    
</div>