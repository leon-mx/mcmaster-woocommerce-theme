<?php
/**
 * The template for displaying the footer
 *
 * @package McMaster_WC_Theme
 */
?>

    <footer id="colophon" class="site-footer">
        <div class="container">
            <!-- Footer Content -->
            <div class="footer-content">
                <!-- Footer Widget Area 1 -->
                <div class="footer-section footer-section-1">
                    <?php if (is_active_sidebar('footer-1')) : ?>
                        <?php dynamic_sidebar('footer-1'); ?>
                    <?php else : ?>
                        <h3><?php esc_html_e('About Us', 'mcmaster-wc-theme'); ?></h3>
                        <p><?php esc_html_e('We provide high-quality products with fast shipping and excellent customer service, inspired by industry leaders.', 'mcmaster-wc-theme'); ?></p>
                        <div class="social-links">
                            <a href="#" title="<?php esc_attr_e('Facebook', 'mcmaster-wc-theme'); ?>">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="#" title="<?php esc_attr_e('Twitter', 'mcmaster-wc-theme'); ?>">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                            <a href="#" title="<?php esc_attr_e('LinkedIn', 'mcmaster-wc-theme'); ?>">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Footer Widget Area 2 -->
                <div class="footer-section footer-section-2">
                    <?php if (is_active_sidebar('footer-2')) : ?>
                        <?php dynamic_sidebar('footer-2'); ?>
                    <?php else : ?>
                        <h3><?php esc_html_e('Customer Service', 'mcmaster-wc-theme'); ?></h3>
                        <ul>
                            <li><a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"><?php esc_html_e('My Account', 'mcmaster-wc-theme'); ?></a></li>
                            <li><a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"><?php esc_html_e('Order Status', 'mcmaster-wc-theme'); ?></a></li>
                            <li><a href="#"><?php esc_html_e('Shipping Info', 'mcmaster-wc-theme'); ?></a></li>
                            <li><a href="#"><?php esc_html_e('Returns', 'mcmaster-wc-theme'); ?></a></li>
                            <li><a href="#"><?php esc_html_e('Size Guide', 'mcmaster-wc-theme'); ?></a></li>
                            <li><a href="#"><?php esc_html_e('Contact Us', 'mcmaster-wc-theme'); ?></a></li>
                        </ul>
                    <?php endif; ?>
                </div>

                <!-- Footer Widget Area 3 -->
                <div class="footer-section footer-section-3">
                    <?php if (is_active_sidebar('footer-3')) : ?>
                        <?php dynamic_sidebar('footer-3'); ?>
                    <?php else : ?>
                        <h3><?php esc_html_e('Quick Links', 'mcmaster-wc-theme'); ?></h3>
                        <ul>
                            <?php if (function_exists('wc_get_page_permalink')) : ?>
                                <li><a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"><?php esc_html_e('Shop All', 'mcmaster-wc-theme'); ?></a></li>
                            <?php endif; ?>
                            <li><a href="#"><?php esc_html_e('New Arrivals', 'mcmaster-wc-theme'); ?></a></li>
                            <li><a href="#"><?php esc_html_e('Best Sellers', 'mcmaster-wc-theme'); ?></a></li>
                            <li><a href="#"><?php esc_html_e('Sale Items', 'mcmaster-wc-theme'); ?></a></li>
                            <li><a href="#"><?php esc_html_e('Gift Cards', 'mcmaster-wc-theme'); ?></a></li>
                            <li><a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>"><?php esc_html_e('Blog', 'mcmaster-wc-theme'); ?></a></li>
                        </ul>
                    <?php endif; ?>
                </div>

                <!-- Newsletter Signup -->
                <div class="footer-section footer-section-4">
                    <h3><?php esc_html_e('Stay Connected', 'mcmaster-wc-theme'); ?></h3>
                    <p><?php esc_html_e('Subscribe to our newsletter for updates and exclusive offers.', 'mcmaster-wc-theme'); ?></p>
                    <form class="newsletter-form" action="#" method="post">
                        <div class="form-group">
                            <input type="email" name="newsletter_email" placeholder="<?php esc_attr_e('Enter your email', 'mcmaster-wc-theme'); ?>" required>
                            <button type="submit" class="newsletter-submit">
                                <?php esc_html_e('Subscribe', 'mcmaster-wc-theme'); ?>
                            </button>
                        </div>
                    </form>
                    
                    <!-- Contact Information -->
                    <div class="contact-info">
                        <h4><?php esc_html_e('Contact Info', 'mcmaster-wc-theme'); ?></h4>
                        <p>
                            <strong><?php esc_html_e('Phone:', 'mcmaster-wc-theme'); ?></strong> 
                            <a href="tel:+1234567890">+1 (234) 567-890</a>
                        </p>
                        <p>
                            <strong><?php esc_html_e('Email:', 'mcmaster-wc-theme'); ?></strong> 
                            <a href="mailto:info@example.com">info@example.com</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <div class="copyright">
                        <?php
                        $copyright_text = get_theme_mod('footer_copyright', '© 2024 McMaster-Carr Inspired Theme. All rights reserved.');
                        echo esc_html($copyright_text);
                        ?>
                    </div>
                    
                    <div class="footer-menu">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer',
                            'menu_class'     => 'footer-menu-list',
                            'container'      => false,
                            'depth'          => 1,
                            'fallback_cb'    => function() {
                                echo '<ul class="footer-menu-list">';
                                echo '<li><a href="#">' . esc_html__('Privacy Policy', 'mcmaster-wc-theme') . '</a></li>';
                                echo '<li><a href="#">' . esc_html__('Terms of Service', 'mcmaster-wc-theme') . '</a></li>';
                                echo '<li><a href="#">' . esc_html__('Accessibility', 'mcmaster-wc-theme') . '</a></li>';
                                echo '</ul>';
                            },
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>