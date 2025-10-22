<!DOCTYPE html>
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
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'mcmaster'); ?></a>

    <header id="masthead" class="site-header">
        <div class="header-container">
            <div class="site-branding">
                <?php
                if (is_front_page() && is_home()) :
                    ?>
                    <h1 class="site-title">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" rel="home">
                            <?php bloginfo('name'); ?>
                        </a>
                    </h1>
                    <?php
                else :
                    ?>
                    <p class="site-title">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" rel="home">
                            <?php bloginfo('name'); ?>
                        </a>
                    </p>
                    <?php
                endif;
                
                $mcmaster_description = get_bloginfo('description', 'display');
                if ($mcmaster_description || is_customize_preview()) :
                    ?>
                    <p class="site-description"><?php echo $mcmaster_description; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?></p>
                <?php endif; ?>
            </div><!-- .site-branding -->
            
            <!-- Mobile Menu Toggle -->
            <?php mcmaster_mobile_menu_toggle(); ?>
            
            <!-- Primary Navigation -->
            <?php mcmaster_primary_navigation(); ?>
            
        </div><!-- .header-container -->
        
        <!-- Secondary Navigation -->
        <?php if (has_nav_menu('secondary')) : ?>
            <div class="secondary-header">
                <div class="header-container">
                    <?php mcmaster_secondary_navigation(); ?>
                </div>
            </div>
        <?php endif; ?>
        
    </header><!-- #masthead -->
    
    <!-- Breadcrumb Navigation -->
    <?php mcmaster_breadcrumb_navigation(); ?>

    <main id="primary" class="site-main">