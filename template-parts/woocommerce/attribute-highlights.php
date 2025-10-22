<?php
/**
 * Product Attribute Highlights - McMaster-inspired technical specifications
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
    'show_title' => true,
    'max_attributes' => 6,
    'show_icons' => true,
    'layout' => 'grid', // grid, list, inline
    'highlight_key_specs' => true
]);

// Get product attributes
$attributes = $product->get_attributes();
$product_id = $product->get_id();

// Get custom product features
$features = get_post_meta($product_id, '_product_features', true);
if (is_string($features)) {
    $features = maybe_unserialize($features);
}

// Key specifications mapping (McMaster-style technical focus)
$key_spec_icons = [
    'material' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4Z"/></svg>',
    'size' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M8,2H16V6H20V8H16V16H20V18H16V22H14V18H2V16H14V8H2V6H14V2H16M4,8H12V16H4V8Z"/></svg>',
    'weight' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12,3A4,4 0 0,1 16,7C16,7.73 15.81,8.41 15.46,9H18.5C19.88,9 21,10.12 21,11.5V19A2,2 0 0,1 19,21H5A2,2 0 0,1 3,19V11.5C3,10.12 4.12,9 5.5,9H8.54C8.19,8.41 8,7.73 8,7A4,4 0 0,1 12,3M12,5A2,2 0 0,0 10,7A2,2 0 0,0 12,9A2,2 0 0,0 14,7A2,2 0 0,0 12,5Z"/></svg>',
    'color' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.5,12A1.5,1.5 0 0,1 16,10.5A1.5,1.5 0 0,1 17.5,9A1.5,1.5 0 0,1 19,10.5A1.5,1.5 0 0,1 17.5,12M14.5,8A1.5,1.5 0 0,1 13,6.5A1.5,1.5 0 0,1 14.5,5A1.5,1.5 0 0,1 16,6.5A1.5,1.5 0 0,1 14.5,8M9.5,8A1.5,1.5 0 0,1 8,6.5A1.5,1.5 0 0,1 9.5,5A1.5,1.5 0 0,1 11,6.5A1.5,1.5 0 0,1 9.5,8M6.5,12A1.5,1.5 0 0,1 5,10.5A1.5,1.5 0 0,1 6.5,9A1.5,1.5 0 0,1 8,10.5A1.5,1.5 0 0,1 6.5,12M12,3A9,9 0 0,0 3,12A9,9 0 0,0 12,21A9,9 0 0,0 21,12A9,9 0 0,0 12,3Z"/></svg>',
    'dimension' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M3,5H21V7H3V5M3,11H21V13H3V11M3,17H21V19H3V17Z"/></svg>',
    'capacity' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M6,2V8H6V8L10,12L6,16V16H6V22H18V16H18V16L14,12L18,8V8H18V2H6M16,16.5V20H8V16.5L12,12.5L16,16.5M12,11.5L8,7.5V4H16V7.5L12,11.5Z"/></svg>',
    'power' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M11,2V22H13V16H18V14H13V10H18V8H13V2H11M6,2V4H8V8H6V10H8V14H6V16H8V20H6V22H8A2,2 0 0,0 10,20V4A2,2 0 0,0 8,2H6Z"/></svg>',
    'grade' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.46,13.97L5.82,21L12,17.27Z"/></svg>'
];

// Filter and process attributes
$display_attributes = [];
$attribute_count = 0;

if (!empty($attributes)) {
    foreach ($attributes as $attribute) {
        if ($attribute_count >= $args['max_attributes']) {
            break;
        }
        
        if ($attribute->get_visible() && !empty($attribute->get_options())) {
            $name = $attribute->get_name();
            $taxonomy = $attribute->get_taxonomy();
            
            // Get attribute label
            if ($taxonomy) {
                $attribute_label = wc_attribute_label($taxonomy);
            } else {
                $attribute_label = $name;
            }
            
            // Get attribute values
            $values = [];
            foreach ($attribute->get_options() as $option) {
                if ($taxonomy) {
                    $term = get_term($option, $taxonomy);
                    if ($term && !is_wp_error($term)) {
                        $values[] = $term->name;
                    }
                } else {
                    $values[] = $option;
                }
            }
            
            if (!empty($values)) {
                // Get icon for attribute
                $icon = '';
                if ($args['show_icons']) {
                    $attr_key = strtolower($attribute_label);
                    foreach ($key_spec_icons as $spec_key => $spec_icon) {
                        if (strpos($attr_key, $spec_key) !== false) {
                            $icon = $spec_icon;
                            break;
                        }
                    }
                    
                    // Default icon if no match
                    if (empty($icon)) {
                        $icon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M11,9H13V7H11M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M11,17H13V11H11V17Z"/></svg>';
                    }
                }
                
                $display_attributes[] = [
                    'label' => $attribute_label,
                    'values' => $values,
                    'icon' => $icon,
                    'key_spec' => $args['highlight_key_specs'] && array_key_exists(strtolower($attribute_label), $key_spec_icons)
                ];
                
                $attribute_count++;
            }
        }
    }
}

// Exit if no attributes to show
if (empty($display_attributes) && empty($features)) {
    return;
}

$wrapper_classes = [
    'mcmaster-attribute-highlights',
    'layout-' . $args['layout']
];
?>

<div class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>">
    
    <?php if ($args['show_title']): ?>
        <h3 class="attributes-title"><?php esc_html_e('Key Specifications', 'mcmaster-wc-theme'); ?></h3>
    <?php endif; ?>
    
    <?php if (!empty($display_attributes)): ?>
        <div class="attributes-container">
            <?php foreach ($display_attributes as $index => $attribute): ?>
                <?php 
                $item_classes = ['attribute-item'];
                if ($attribute['key_spec']) {
                    $item_classes[] = 'key-specification';
                }
                ?>
                <div class="<?php echo esc_attr(implode(' ', $item_classes)); ?>">
                    <div class="attribute-header">
                        <?php if (!empty($attribute['icon'])): ?>
                            <span class="attribute-icon"><?php echo $attribute['icon']; ?></span>
                        <?php endif; ?>
                        <span class="attribute-label"><?php echo esc_html($attribute['label']); ?></span>
                    </div>
                    <div class="attribute-values">
                        <?php echo esc_html(implode(', ', $attribute['values'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($features) && is_array($features)): ?>
        <div class="product-features-container">
            <h4 class="features-subtitle"><?php esc_html_e('Product Features', 'mcmaster-wc-theme'); ?></h4>
            <ul class="features-list enhanced">
                <?php foreach ($features as $feature): ?>
                    <li class="feature-item">
                        <svg class="feature-checkmark" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z"/>
                        </svg>
                        <span class="feature-text"><?php echo esc_html($feature); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <?php 
    // Show link to full specifications if there are more attributes
    $total_attributes = count($attributes);
    if ($total_attributes > $args['max_attributes']): ?>
        <div class="view-all-specs">
            <a href="#tab-additional_information" class="view-specs-link">
                <?php echo sprintf(
                    /* translators: %d: number of additional specifications */
                    __('View all %d specifications', 'mcmaster-wc-theme'),
                    $total_attributes
                ); ?>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z"/>
                </svg>
            </a>
        </div>
    <?php endif; ?>
    
</div>