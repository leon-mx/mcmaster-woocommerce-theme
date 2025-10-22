<?php
/**
 * The main template file
 */

get_header();
?>

<div class="container">
    <div class="content-area">
        <h1>McMaster Navigation System Demo</h1>
        
        <p>This is a demonstration of the McMaster-style navigation system. The navigation includes:</p>
        
        <ul>
            <li>Mega menu support for deep product categories</li>
            <li>Responsive design for desktop and mobile</li>
            <li>Custom walker for advanced menu functionality</li>
            <li>Theme customization options</li>
            <li>AJAX-powered dynamic content loading</li>
            <li>Accessibility features</li>
        </ul>
        
        <h2>Navigation Features</h2>
        
        <div class="feature-grid">
            <div class="feature-card">
                <h3>Mega Menu</h3>
                <p>Multi-column dropdown menus that can display deep category hierarchies with custom layouts.</p>
            </div>
            
            <div class="feature-card">
                <h3>Mobile Responsive</h3>
                <p>Touch-friendly mobile navigation with collapsible submenus and smooth animations.</p>
            </div>
            
            <div class="feature-card">
                <h3>Keyboard Navigation</h3>
                <p>Full keyboard accessibility with arrow key navigation and proper ARIA attributes.</p>
            </div>
            
            <div class="feature-card">
                <h3>Dynamic Loading</h3>
                <p>AJAX-powered content loading for large category structures to improve performance.</p>
            </div>
        </div>
        
        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <h2 class="entry-title">
                            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                        </h2>
                    </header>
                    
                    <div class="entry-content">
                        <?php the_excerpt(); ?>
                    </div>
                    
                    <footer class="entry-footer">
                        <span class="posted-on">
                            <?php echo get_the_date(); ?>
                        </span>
                    </footer>
                </article>
                <?php
            endwhile;
            
            the_posts_navigation();
        else :
            ?>
            <p><?php esc_html_e('No content found. Please add some posts or pages to see the navigation in action.', 'mcmaster'); ?></p>
        <?php endif; ?>
    </div>
</div>

<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
}

.content-area {
    background: #fff;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin: 30px 0;
}

.feature-card {
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background: #f9f9f9;
}

.feature-card h3 {
    color: #007cba;
    margin-bottom: 10px;
}

article {
    margin-bottom: 30px;
    padding-bottom: 30px;
    border-bottom: 1px solid #eee;
}

.entry-title a {
    text-decoration: none;
    color: #333;
}

.entry-title a:hover {
    color: #007cba;
}
</style>

<?php
get_footer();