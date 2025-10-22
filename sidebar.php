<?php
/**
 * The sidebar containing the main widget area
 *
 * @package McMaster_WC_Theme
 */

if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside id="secondary" class="widget-area sidebar">
    <div class="sidebar-inner">
        <?php dynamic_sidebar('sidebar-1'); ?>
        
        <?php if (!is_active_sidebar('sidebar-1')) : ?>
            <!-- Default sidebar content if no widgets are active -->
            <section class="widget widget_search">
                <h3 class="widget-title"><?php esc_html_e('Search', 'mcmaster-wc-theme'); ?></h3>
                <?php get_search_form(); ?>
            </section>
            
            <?php if (function_exists('wc_get_page_permalink')) : ?>
                <section class="widget widget_product_categories">
                    <h3 class="widget-title"><?php esc_html_e('Product Categories', 'mcmaster-wc-theme'); ?></h3>
                    <?php
                    wp_list_categories(array(
                        'taxonomy'     => 'product_cat',
                        'show_count'   => true,
                        'hierarchical' => true,
                        'title_li'     => '',
                    ));
                    ?>
                </section>
            <?php endif; ?>
            
            <section class="widget widget_recent_posts">
                <h3 class="widget-title"><?php esc_html_e('Recent Posts', 'mcmaster-wc-theme'); ?></h3>
                <?php
                $recent_posts = wp_get_recent_posts(array(
                    'numberposts' => 5,
                    'post_status' => 'publish'
                ));
                
                if ($recent_posts) :
                    echo '<ul>';
                    foreach ($recent_posts as $post) {
                        echo '<li><a href="' . get_permalink($post['ID']) . '">' . $post['post_title'] . '</a></li>';
                    }
                    echo '</ul>';
                endif;
                ?>
            </section>
        <?php endif; ?>
    </div>
</aside>