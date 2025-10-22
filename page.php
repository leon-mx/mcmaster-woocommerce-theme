<?php
/**
 * The template for displaying all pages
 *
 * @package McMaster_WC_Theme
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container">
        <?php while (have_posts()) : ?>
            <?php the_post(); ?>
            
            <article id="page-<?php the_ID(); ?>" <?php post_class('single-page'); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="page-thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="entry-content">
                    <?php
                    the_content();
                    
                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'mcmaster-wc-theme'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div>
                
                <?php if (get_edit_post_link()) : ?>
                    <footer class="entry-footer">
                        <?php edit_post_link(
                            sprintf(
                                wp_kses(
                                    __('Edit <span class="screen-reader-text">%s</span>', 'mcmaster-wc-theme'),
                                    array('span' => array('class' => array()))
                                ),
                                get_the_title()
                            ),
                            '<span class="edit-link">',
                            '</span>'
                        ); ?>
                    </footer>
                <?php endif; ?>
            </article>
            
            <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) {
                comments_template();
            }
            ?>
            
        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer();