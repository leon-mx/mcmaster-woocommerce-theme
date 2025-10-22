<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package McMaster_WC_Theme
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container">
        <div class="error-404 not-found">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e('Page Not Found', 'mcmaster-wc-theme'); ?></h1>
            </header>

            <div class="page-content">
                <div class="error-content">
                    <div class="error-icon">
                        <svg width="120" height="120" viewBox="0 0 24 24" fill="#0066cc">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    
                    <h2><?php esc_html_e('Oops! That page can\'t be found.', 'mcmaster-wc-theme'); ?></h2>
                    
                    <p><?php esc_html_e('It looks like nothing was found at this location. The page you\'re looking for might have been moved, deleted, or you may have mistyped the URL.', 'mcmaster-wc-theme'); ?></p>

                    <!-- Search Form -->
                    <div class="error-search">
                        <h3><?php esc_html_e('Try searching for what you need:', 'mcmaster-wc-theme'); ?></h3>
                        <?php get_search_form(); ?>
                    </div>

                    <!-- Popular Links -->
                    <div class="popular-links">
                        <h3><?php esc_html_e('Popular Pages', 'mcmaster-wc-theme'); ?></h3>
                        <div class="link-grid">
                            <div class="link-item">
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="link-button">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                                    </svg>
                                    <?php esc_html_e('Home', 'mcmaster-wc-theme'); ?>
                                </a>
                            </div>

                            <?php if (function_exists('wc_get_page_permalink')) : ?>
                                <div class="link-item">
                                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="link-button">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12L8.1 13h7.45c.75 0 1.41-.41 1.75-1.03L21.7 4H5.21l-.94-2H1zm16 16c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                        </svg>
                                        <?php esc_html_e('Shop', 'mcmaster-wc-theme'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <?php if (get_option('page_for_posts')) : ?>
                                <div class="link-item">
                                    <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="link-button">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                                        </svg>
                                        <?php esc_html_e('Blog', 'mcmaster-wc-theme'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <div class="link-item">
                                <a href="#" class="link-button" onclick="history.back(); return false;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                                    </svg>
                                    <?php esc_html_e('Go Back', 'mcmaster-wc-theme'); ?>
                                </a>
                            </div>
                        </div>
                    </div>

                    <?php if (function_exists('WC')) : ?>
                        <!-- Featured Products -->
                        <div class="featured-products">
                            <h3><?php esc_html_e('Featured Products', 'mcmaster-wc-theme'); ?></h3>
                            <?php
                            $featured_query = new WP_Query(array(
                                'post_type' => 'product',
                                'posts_per_page' => 4,
                                'meta_query' => array(
                                    array(
                                        'key' => '_featured',
                                        'value' => 'yes'
                                    )
                                )
                            ));

                            if ($featured_query->have_posts()) :
                                echo '<div class="products-grid">';
                                while ($featured_query->have_posts()) : $featured_query->the_post();
                                    wc_get_template_part('content', 'product');
                                endwhile;
                                echo '</div>';
                                wp_reset_postdata();
                            endif;
                            ?>
                        </div>
                    <?php endif; ?>

                    <!-- Contact Information -->
                    <div class="contact-info">
                        <h3><?php esc_html_e('Need Help?', 'mcmaster-wc-theme'); ?></h3>
                        <p><?php esc_html_e('If you believe this is an error, please contact our support team.', 'mcmaster-wc-theme'); ?></p>
                        <a href="mailto:support@example.com" class="contact-button">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                            </svg>
                            <?php esc_html_e('Contact Support', 'mcmaster-wc-theme'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
/* 404 Page Styles */
.error-404 {
    text-align: center;
    padding: 60px 20px;
    max-width: 800px;
    margin: 0 auto;
}

.error-content {
    background: white;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.error-icon {
    margin-bottom: 30px;
}

.error-content h2 {
    font-size: 28px;
    margin-bottom: 20px;
    color: #333;
}

.error-content p {
    font-size: 16px;
    color: #666;
    margin-bottom: 40px;
    line-height: 1.6;
}

.error-search {
    margin-bottom: 40px;
    padding: 30px;
    background: #f8f9fa;
    border-radius: 8px;
}

.error-search h3 {
    margin-bottom: 20px;
    color: #333;
    font-size: 20px;
}

.popular-links {
    margin-bottom: 40px;
}

.popular-links h3 {
    margin-bottom: 20px;
    color: #333;
    font-size: 20px;
}

.link-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-bottom: 30px;
}

.link-button {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px 20px;
    background: #0066cc;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.link-button:hover {
    background: #0052a3;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,102,204,0.3);
}

.featured-products {
    margin-bottom: 40px;
}

.featured-products h3 {
    margin-bottom: 20px;
    color: #333;
    font-size: 20px;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.contact-info {
    padding: 30px;
    background: #f8f9fa;
    border-radius: 8px;
}

.contact-info h3 {
    margin-bottom: 15px;
    color: #333;
    font-size: 20px;
}

.contact-info p {
    margin-bottom: 20px;
    color: #666;
}

.contact-button {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 24px;
    background: #28a745;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    transition: background 0.3s ease;
    font-weight: 500;
}

.contact-button:hover {
    background: #218838;
}

@media (max-width: 768px) {
    .error-content {
        padding: 30px 20px;
    }
    
    .link-grid {
        grid-template-columns: 1fr;
    }
    
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<?php
get_footer();