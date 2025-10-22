<?php
/**
 * The main template file
 *
 * @package McMaster_WC_Theme
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container">
        <?php if (have_posts()) : ?>
            
            <?php if (is_home() && !is_front_page()) : ?>
                <header class="page-header">
                    <h1 class="page-title"><?php single_post_title(); ?></h1>
                </header>
            <?php endif; ?>
            
            <div class="posts-container">
                <?php while (have_posts()) : ?>
                    <?php the_post(); ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-item'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-content">
                            <header class="entry-header">
                                <h2 class="entry-title">
                                    <a href="<?php the_permalink(); ?>" rel="bookmark">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                                
                                <div class="entry-meta">
                                    <span class="post-date">
                                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                            <?php echo esc_html(get_the_date()); ?>
                                        </time>
                                    </span>
                                    
                                    <span class="post-author">
                                        <?php esc_html_e('by', 'mcmaster-wc-theme'); ?> 
                                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                            <?php the_author(); ?>
                                        </a>
                                    </span>
                                    
                                    <?php if (has_category()) : ?>
                                        <span class="post-categories">
                                            <?php esc_html_e('in', 'mcmaster-wc-theme'); ?> 
                                            <?php the_category(', '); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </header>
                            
                            <div class="entry-summary">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <footer class="entry-footer">
                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    <?php esc_html_e('Read More', 'mcmaster-wc-theme'); ?>
                                </a>
                                
                                <?php if (has_tag()) : ?>
                                    <div class="post-tags">
                                        <?php the_tags('Tags: ', ', ', ''); ?>
                                    </div>
                                <?php endif; ?>
                            </footer>
                        </div>
                    </article>
                    
                <?php endwhile; ?>
            </div>
            
            <?php
            // Pagination
            the_posts_pagination(array(
                'mid_size'  => 2,
                'prev_text' => __('← Previous', 'mcmaster-wc-theme'),
                'next_text' => __('Next →', 'mcmaster-wc-theme'),
                'class'     => 'pagination-nav',
            ));
            ?>
            
        <?php else : ?>
            
            <div class="no-content">
                <h1><?php esc_html_e('Nothing here', 'mcmaster-wc-theme'); ?></h1>
                <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'mcmaster-wc-theme'); ?></p>
                
                <div class="search-form-container">
                    <?php get_search_form(); ?>
                </div>
            </div>
            
        <?php endif; ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();