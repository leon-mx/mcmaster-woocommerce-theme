<?php
/**
 * Custom search form template
 *
 * @package McMaster_WC_Theme
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="screen-reader-text" for="search-field">
        <?php esc_html_e('Search for:', 'mcmaster-wc-theme'); ?>
    </label>
    
    <div class="search-form-wrapper">
        <input type="search" 
               id="search-field"
               class="search-field" 
               placeholder="<?php esc_attr_e('Search products, articles...', 'mcmaster-wc-theme'); ?>" 
               value="<?php echo get_search_query(); ?>" 
               name="s" 
               autocomplete="off" />
        
        <?php if (function_exists('WC') && !is_admin()) : ?>
            <input type="hidden" name="post_type" value="product" />
        <?php endif; ?>
        
        <button type="submit" class="search-submit" aria-label="<?php esc_attr_e('Search', 'mcmaster-wc-theme'); ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
            </svg>
            <span class="screen-reader-text"><?php esc_html_e('Search', 'mcmaster-wc-theme'); ?></span>
        </button>
    </div>
</form>