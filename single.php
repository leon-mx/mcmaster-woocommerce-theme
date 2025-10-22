<?php
/**
 * The template for displaying all single posts
 *
 * @package McMaster_WC_Theme
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container">
        <?php while (have_posts()) : ?>
            <?php the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    
                    <div class="entry-meta">
                        <span class="post-date">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                            </svg>
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                <?php echo esc_html(get_the_date()); ?>
                            </time>
                        </span>
                        
                        <span class="post-author">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                            <?php esc_html_e('by', 'mcmaster-wc-theme'); ?> 
                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                <?php the_author(); ?>
                            </a>
                        </span>
                        
                        <?php if (has_category()) : ?>
                            <span class="post-categories">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                <?php the_category(', '); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if (function_exists('get_comments_number') && comments_open()) : ?>
                            <span class="post-comments">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M21.99 4c0-1.1-.89-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.89 2 2 2h14l4 4-.01-18z"/>
                                </svg>
                                <a href="#comments">
                                    <?php 
                                    $comment_count = get_comments_number();
                                    printf(
                                        _n('%d Comment', '%d Comments', $comment_count, 'mcmaster-wc-theme'),
                                        $comment_count
                                    );
                                    ?>
                                </a>
                            </span>
                        <?php endif; ?>
                    </div>
                </header>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail">
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
                
                <footer class="entry-footer">
                    <?php if (has_tag()) : ?>
                        <div class="post-tags">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.63 5.84C17.27 5.33 16.67 5 16 5L5 5.01C3.9 5.01 3 5.9 3 7v10c0 1.1.9 1.99 2 1.99L16 19c.67 0 1.27-.33 1.63-.84L22 12l-4.37-6.16z"/>
                            </svg>
                            <?php the_tags('', ', ', ''); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Social Share Buttons -->
                    <div class="social-share">
                        <h4><?php esc_html_e('Share this article:', 'mcmaster-wc-theme'); ?></h4>
                        <div class="share-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="share-button facebook"
                               title="<?php esc_attr_e('Share on Facebook', 'mcmaster-wc-theme'); ?>">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="share-button twitter"
                               title="<?php esc_attr_e('Share on Twitter', 'mcmaster-wc-theme'); ?>">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                            
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="share-button linkedin"
                               title="<?php esc_attr_e('Share on LinkedIn', 'mcmaster-wc-theme'); ?>">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </footer>
            </article>
            
            <!-- Author Bio -->
            <?php if (get_the_author_meta('description')) : ?>
                <div class="author-info">
                    <div class="author-avatar">
                        <?php echo get_avatar(get_the_author_meta('ID'), 80); ?>
                    </div>
                    <div class="author-details">
                        <h3><?php esc_html_e('About', 'mcmaster-wc-theme'); ?> <?php the_author(); ?></h3>
                        <p><?php echo wp_kses_post(get_the_author_meta('description')); ?></p>
                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="author-link">
                            <?php esc_html_e('View all posts by', 'mcmaster-wc-theme'); ?> <?php the_author(); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Post Navigation -->
            <nav class="post-navigation">
                <?php
                $prev_post = get_previous_post();
                $next_post = get_next_post();
                ?>
                
                <?php if ($prev_post) : ?>
                    <div class="nav-previous">
                        <a href="<?php echo esc_url(get_permalink($prev_post)); ?>" rel="prev">
                            <span class="nav-label"><?php esc_html_e('Previous Post', 'mcmaster-wc-theme'); ?></span>
                            <span class="nav-title"><?php echo esc_html(get_the_title($prev_post)); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php if ($next_post) : ?>
                    <div class="nav-next">
                        <a href="<?php echo esc_url(get_permalink($next_post)); ?>" rel="next">
                            <span class="nav-label"><?php esc_html_e('Next Post', 'mcmaster-wc-theme'); ?></span>
                            <span class="nav-title"><?php echo esc_html(get_the_title($next_post)); ?></span>
                        </a>
                    </div>
                <?php endif; ?>
            </nav>
            
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
get_sidebar();
get_footer();