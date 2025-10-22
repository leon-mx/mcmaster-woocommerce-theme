<?php
/**
 * The header for our theme
 *
 * @package McMaster_WC_Theme
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary">
        <?php esc_html_e('Skip to content', 'mcmaster-wc-theme'); ?>
    </a>

    <header id="masthead" class="site-header">
        <!-- Header Top Bar -->
        <div class="header-top">
            <div class="container">
                <div class="header-top-content">
                    <div class="header-top-left">
                        <?php
                        $header_text = get_theme_mod('header_top_text', 'Free shipping on orders over $100');
                        if ($header_text) {
                            echo '<span class="header-promo">' . esc_html($header_text) . '</span>';
                        }
                        ?>
                    </div>
                    <div class="header-top-right">
                        <nav class="top-menu">
                            <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">
                                <?php esc_html_e('My Account', 'mcmaster-wc-theme'); ?>
                            </a>
                            <span class="separator">|</span>
                            <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>">
                                <?php esc_html_e('Sign In', 'mcmaster-wc-theme'); ?>
                            </a>
                            <span class="separator">|</span>
                            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">
                                <?php esc_html_e('Shop', 'mcmaster-wc-theme'); ?>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <div class="header-main">
            <div class="container">
                <div class="header-content">
                    <!-- Logo -->
                    <div class="site-branding">
                        <?php
                        if (has_custom_logo()) {
                            the_custom_logo();
                        } else {
                            ?>
                            <h1 class="site-title">
                                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                    <?php bloginfo('name'); ?>
                                </a>
                            </h1>
                            <?php
                            $description = get_bloginfo('description', 'display');
                            if ($description || is_customize_preview()) {
                                ?>
                                <p class="site-description"><?php echo $description; ?></p>
                                <?php
                            }
                        }
                        ?>
                    </div>

                    <!-- Search Bar -->
                    <div class="header-search">
                        <?php if (function_exists('aws_get_search_form')) : ?>
                            <?php aws_get_search_form(); ?>
                        <?php else : ?>
                            <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                                <input type="search" 
                                       class="search-field" 
                                       placeholder="<?php esc_attr_e('Search products...', 'mcmaster-wc-theme'); ?>" 
                                       value="<?php echo get_search_query(); ?>" 
                                       name="s" />
                                <input type="hidden" name="post_type" value="product" />
                                <button type="submit" class="search-submit">
                                    <span class="screen-reader-text"><?php esc_html_e('Search', 'mcmaster-wc-theme'); ?></span>
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                                    </svg>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <!-- Header Actions -->
                    <div class="header-actions">
                        <!-- Account Icon -->
                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="account-icon" title="<?php esc_attr_e('My Account', 'mcmaster-wc-theme'); ?>">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </a>

                        <!-- Cart Icon -->
                        <?php if (function_exists('WC')) : ?>
                            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-icon" title="<?php esc_attr_e('View Cart', 'mcmaster-wc-theme'); ?>">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M7 18c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12L8.1 13h7.45c.75 0 1.41-.41 1.75-1.03L21.7 4H5.21l-.94-2H1zm16 16c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                </svg>
                                <?php mcmaster_cart_count(); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Main Navigation -->
                <nav id="site-navigation" class="main-navigation">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'menu_class'     => 'primary-menu-list',
                        'container'      => false,
                        'fallback_cb'    => function() {
                            echo '<ul class="primary-menu-list">';
                            echo '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'mcmaster-wc-theme') . '</a></li>';
                            if (function_exists('wc_get_page_permalink')) {
                                echo '<li><a href="' . esc_url(wc_get_page_permalink('shop')) . '">' . esc_html__('Shop', 'mcmaster-wc-theme') . '</a></li>';
                            }
                            echo '<li><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">' . esc_html__('Blog', 'mcmaster-wc-theme') . '</a></li>';
                            echo '<li><a href="' . esc_url(get_privacy_policy_url()) . '">' . esc_html__('Contact', 'mcmaster-wc-theme') . '</a></li>';
                            echo '</ul>';
                        },
                    ));
                    ?>
                    
                    <!-- Mobile Menu Toggle -->
                    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                        <span class="screen-reader-text"><?php esc_html_e('Primary Menu', 'mcmaster-wc-theme'); ?></span>
                        <span class="menu-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>
                </nav>
            </div>
        </div>
    </header>

    <?php if (function_exists('mcmaster_breadcrumb') && !is_front_page()) : ?>
        <?php mcmaster_breadcrumb(); ?>
    <?php endif; ?>