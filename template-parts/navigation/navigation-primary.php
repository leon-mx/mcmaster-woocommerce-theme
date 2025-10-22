<?php
/**
 * Template part for displaying the primary navigation
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check if mega menu is enabled
$enable_mega_menu = get_theme_mod('enable_mega_menu', true);
$max_depth = get_theme_mod('max_menu_depth', 3);

// Get navigation structure for JavaScript
$nav_structure = mcmaster_get_navigation_structure('primary');

if (has_nav_menu('primary')) :
    ?>
    <nav class="main-navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'mcmaster'); ?>">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'menu_class'     => 'nav-menu primary-menu',
            'container'      => false,
            'walker'         => new McMaster_Walker(),
            'depth'          => $max_depth,
            'fallback_cb'    => 'mcmaster_fallback_menu',
        ));
        ?>
    </nav>
    
    <?php if (!empty($nav_structure)) : ?>
        <script type="text/javascript">
            window.mcmasterNavData = <?php echo wp_json_encode($nav_structure); ?>;
        </script>
    <?php endif; ?>
    
<?php else : ?>
    
    <!-- Fallback Navigation -->
    <nav class="main-navigation fallback-navigation" aria-label="<?php esc_attr_e('Fallback Navigation', 'mcmaster'); ?>">
        <ul class="nav-menu">
            <li class="menu-item">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'mcmaster'); ?></a>
            </li>
            <?php
            // Display recent posts as menu items
            $recent_posts = get_posts(array(
                'numberposts' => 5,
                'post_status' => 'publish',
            ));
            
            foreach ($recent_posts as $post) :
                ?>
                <li class="menu-item">
                    <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">
                        <?php echo esc_html($post->post_title); ?>
                    </a>
                </li>
                <?php
            endforeach;
            ?>
        </ul>
    </nav>
    
<?php endif; ?>