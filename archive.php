<?php
/**
 * The template for displaying archive pages
 *
 * @package McMaster_WC_Theme
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container">
        <?php if (have_posts()) : ?>

            <header class="page-header">
                <?php
                the_archive_title('<h1 class="page-title">', '</h1>');
                the_archive_description('<div class="archive-description">', '</div>');
                ?>
                
                <!-- Archive Meta Information -->
                <div class="archive-meta">
                    <?php if (is_category()) : ?>
                        <span class="archive-type"><?php esc_html_e('Category Archive', 'mcmaster-wc-theme'); ?></span>
                    <?php elseif (is_tag()) : ?>
                        <span class="archive-type"><?php esc_html_e('Tag Archive', 'mcmaster-wc-theme'); ?></span>
                    <?php elseif (is_author()) : ?>
                        <span class="archive-type"><?php esc_html_e('Author Archive', 'mcmaster-wc-theme'); ?></span>
                        <div class="author-info">
                            <?php if (get_the_author_meta('description')) : ?>
                                <div class="author-bio">
                                    <?php echo wp_kses_post(get_the_author_meta('description')); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif (is_date()) : ?>
                        <span class="archive-type"><?php esc_html_e('Date Archive', 'mcmaster-wc-theme'); ?></span>
                    <?php endif; ?>
                    
                    <span class="post-count">
                        <?php
                        global $wp_query;
                        $total_posts = $wp_query->found_posts;
                        printf(
                            _n('%d post found', '%d posts found', $total_posts, 'mcmaster-wc-theme'),
                            $total_posts
                        );
                        ?>
                    </span>
                </div>
            </header>

            <!-- Archive Filters/Sorting (if applicable) -->
            <div class="archive-controls">
                <div class="archive-view-toggle">
                    <button class="view-grid active" data-view="grid" title="<?php esc_attr_e('Grid View', 'mcmaster-wc-theme'); ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M4 11h5V5H4v6zm0 7h5v-6H4v6zm6 0h5v-6h-5v6zm6 0h5v-6h-5v6zm-6-7h5V5h-5v6zm6-6v6h5V5h-5z"/>
                        </svg>
                    </button>
                    <button class="view-list" data-view="list" title="<?php esc_attr_e('List View', 'mcmaster-wc-theme'); ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M4 14h4v-4H4v4zm0 5h4v-4H4v4zM4 9h4V5H4v4zm5 5h12v-4H9v4zm0 5h12v-4H9v4zM9 5v4h12V5H9z"/>
                        </svg>
                    </button>
                </div>
                
                <div class="archive-sort">
                    <select id="archive-sort-select">
                        <option value="date-desc"><?php esc_html_e('Newest First', 'mcmaster-wc-theme'); ?></option>
                        <option value="date-asc"><?php esc_html_e('Oldest First', 'mcmaster-wc-theme'); ?></option>
                        <option value="title-asc"><?php esc_html_e('A-Z', 'mcmaster-wc-theme'); ?></option>
                        <option value="title-desc"><?php esc_html_e('Z-A', 'mcmaster-wc-theme'); ?></option>
                    </select>
                </div>
            </div>

            <!-- Posts Container -->
            <div class="posts-container archive-posts" data-view="grid">
                <?php while (have_posts()) : ?>
                    <?php the_post(); ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('archive-post-item'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                                
                                <?php if (is_sticky()) : ?>
                                    <span class="featured-badge">
                                        <?php esc_html_e('Featured', 'mcmaster-wc-theme'); ?>
                                    </span>
                                <?php endif; ?>
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
                                            <a href="<?php comments_link(); ?>">
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
                            
                            <div class="entry-summary">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <footer class="entry-footer">
                                <a href="<?php the_permalink(); ?>" class="read-more-btn">
                                    <?php esc_html_e('Read More', 'mcmaster-wc-theme'); ?>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
                                    </svg>
                                </a>
                                
                                <?php if (has_tag()) : ?>
                                    <div class="post-tags">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M17.63 5.84C17.27 5.33 16.67 5 16 5L5 5.01C3.9 5.01 3 5.9 3 7v10c0 1.1.9 1.99 2 1.99L16 19c.67 0 1.27-.33 1.63-.84L22 12l-4.37-6.16z"/>
                                        </svg>
                                        <?php the_tags('', ', ', ''); ?>
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

            <div class="no-content archive-no-content">
                <h1><?php esc_html_e('Nothing Found', 'mcmaster-wc-theme'); ?></h1>
                <p><?php esc_html_e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'mcmaster-wc-theme'); ?></p>
                
                <div class="search-form-container">
                    <?php get_search_form(); ?>
                </div>
                
                <div class="helpful-links">
                    <h3><?php esc_html_e('Helpful Links', 'mcmaster-wc-theme'); ?></h3>
                    <ul>
                        <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Back to Homepage', 'mcmaster-wc-theme'); ?></a></li>
                        <?php if (function_exists('wc_get_page_permalink')) : ?>
                            <li><a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"><?php esc_html_e('Shop All Products', 'mcmaster-wc-theme'); ?></a></li>
                        <?php endif; ?>
                        <li><a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>"><?php esc_html_e('Latest Posts', 'mcmaster-wc-theme'); ?></a></li>
                    </ul>
                </div>
            </div>

        <?php endif; ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();