    </main><!-- #primary -->

    <footer id="colophon" class="site-footer">
        <div class="footer-container">
            
            <?php if (has_nav_menu('footer')) : ?>
                <div class="footer-navigation-section">
                    <?php mcmaster_footer_navigation(); ?>
                </div>
            <?php endif; ?>
            
            <div class="footer-content">
                <div class="footer-info">
                    <div class="site-info">
                        <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'mcmaster'); ?></p>
                        <p>
                            <?php
                            printf(
                                esc_html__('Powered by %1$s with McMaster Navigation System', 'mcmaster'),
                                '<a href="https://wordpress.org/">WordPress</a>'
                            );
                            ?>
                        </p>
                    </div>
                    
                    <?php
                    // Footer widgets area
                    if (is_active_sidebar('footer-widgets')) {
                        dynamic_sidebar('footer-widgets');
                    }
                    ?>
                </div>
                
                <!-- Back to Top Button -->
                <button id="back-to-top" class="back-to-top" aria-label="<?php esc_attr_e('Back to top', 'mcmaster'); ?>">
                    <span class="back-to-top-icon">↑</span>
                </button>
            </div>
        </div><!-- .footer-container -->
    </footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

<style>
.site-footer {
    background: #333;
    color: #fff;
    margin-top: 60px;
    padding: 40px 0 20px;
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.footer-navigation-section {
    margin-bottom: 30px;
    padding-bottom: 30px;
    border-bottom: 1px solid #555;
}

.footer-navigation .nav-menu {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 30px;
    list-style: none;
    margin: 0;
    padding: 0;
}

.footer-navigation .nav-menu a {
    color: #ccc;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.3s ease;
}

.footer-navigation .nav-menu a:hover {
    color: #fff;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.site-info {
    font-size: 14px;
    color: #ccc;
}

.site-info p {
    margin: 5px 0;
}

.site-info a {
    color: #007cba;
    text-decoration: none;
}

.site-info a:hover {
    text-decoration: underline;
}

.back-to-top {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: #007cba;
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    cursor: pointer;
    display: none;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    transition: all 0.3s ease;
    z-index: 999;
}

.back-to-top:hover {
    background: #005a87;
    transform: translateY(-2px);
}

.back-to-top.show {
    display: flex;
}

@media (max-width: 768px) {
    .footer-navigation .nav-menu {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .footer-content {
        flex-direction: column;
        text-align: center;
    }
    
    .back-to-top {
        width: 45px;
        height: 45px;
        bottom: 20px;
        right: 20px;
        font-size: 16px;
    }
}
</style>

<script>
// Back to top functionality
(function($) {
    'use strict';
    
    $(document).ready(function() {
        const $backToTop = $('#back-to-top');
        
        // Show/hide back to top button
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                $backToTop.addClass('show');
            } else {
                $backToTop.removeClass('show');
            }
        });
        
        // Smooth scroll to top
        $backToTop.on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, 600);
        });
    });
    
})(jQuery);
</script>

</body>
</html>