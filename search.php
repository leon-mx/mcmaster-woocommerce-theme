<?php
/**
 * The template for displaying search results pages
 *
 * @package McMaster_WC_Theme
 */

get_header(); ?>

<main id="primary" class="site-main search-results">
    <div class="container">
        <header class="page-header">
            <?php if (have_posts()) : ?>
                <h1 class="page-title">
                    <?php
                    printf(
                        esc_html__('Search Results for: %s', 'mcmaster-wc-theme'),
                        '<span class="search-query">' . get_search_query() . '</span>'
                    );
                    ?>
                </h1>
                
                <div class="search-meta">
                    <?php
                    global $wp_query;
                    $total_results = $wp_query->found_posts;
                    printf(
                        _n('%d result found', '%d results found', $total_results, 'mcmaster-wc-theme'),
                        $total_results
                    );
                    ?>
                </div>
            <?php else : ?>
                <h1 class="page-title">
                    <?php
                    printf(
                        esc_html__('No results for: %s', 'mcmaster-wc-theme'),
                        '<span class="search-query">' . get_search_query() . '</span>'
                    );
                    ?>
                </h1>
            <?php endif; ?>
            
            <!-- Search form for new search -->
            <div class="search-again">
                <h3><?php esc_html_e('Search again:', 'mcmaster-wc-theme'); ?></h3>
                <?php get_search_form(); ?>
            </div>
        </header>

        <?php if (have_posts()) : ?>
            
            <!-- Search Filters -->
            <div class="search-filters">
                <div class="filter-tabs">
                    <button class="filter-tab active" data-type="all">
                        <?php esc_html_e('All Results', 'mcmaster-wc-theme'); ?>
                        <span class="count"><?php echo esc_html($total_results); ?></span>
                    </button>
                    
                    <?php if (function_exists('WC')) : ?>
                        <?php
                        $product_count = 0;
                        $temp_query = new WP_Query(array(
                            'post_type' => 'product',
                            's' => get_search_query(),
                            'posts_per_page' => -1,
                            'fields' => 'ids',
                        ));
                        $product_count = $temp_query->found_posts;
                        wp_reset_postdata();
                        ?>
                        <button class="filter-tab" data-type="product">
                            <?php esc_html_e('Products', 'mcmaster-wc-theme'); ?>
                            <span class="count"><?php echo esc_html($product_count); ?></span>
                        </button>
                    <?php endif; ?>
                    
                    <?php
                    $post_count = 0;
                    $temp_query = new WP_Query(array(
                        'post_type' => 'post',
                        's' => get_search_query(),
                        'posts_per_page' => -1,
                        'fields' => 'ids',
                    ));
                    $post_count = $temp_query->found_posts;
                    wp_reset_postdata();
                    ?>
                    <button class="filter-tab" data-type="post">
                        <?php esc_html_e('Posts', 'mcmaster-wc-theme'); ?>
                        <span class="count"><?php echo esc_html($post_count); ?></span>
                    </button>
                    
                    <?php
                    $page_count = 0;
                    $temp_query = new WP_Query(array(
                        'post_type' => 'page',
                        's' => get_search_query(),
                        'posts_per_page' => -1,
                        'fields' => 'ids',
                    ));
                    $page_count = $temp_query->found_posts;
                    wp_reset_postdata();
                    ?>
                    <button class="filter-tab" data-type="page">
                        <?php esc_html_e('Pages', 'mcmaster-wc-theme'); ?>
                        <span class="count"><?php echo esc_html($page_count); ?></span>
                    </button>
                </div>
            </div>

            <!-- Search Results -->
            <div class="search-results-container">
                <?php while (have_posts()) : ?>
                    <?php the_post(); ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('search-result-item'); ?> data-type="<?php echo esc_attr(get_post_type()); ?>">
                        <div class="result-content">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="result-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="result-text">
                                <header class="result-header">
                                    <div class="result-meta">
                                        <span class="result-type">
                                            <?php
                                            $post_type = get_post_type();
                                            if ($post_type === 'product') {
                                                esc_html_e('Product', 'mcmaster-wc-theme');
                                            } elseif ($post_type === 'post') {
                                                esc_html_e('Blog Post', 'mcmaster-wc-theme');
                                            } elseif ($post_type === 'page') {
                                                esc_html_e('Page', 'mcmaster-wc-theme');
                                            } else {
                                                echo esc_html(ucfirst($post_type));
                                            }
                                            ?>
                                        </span>
                                        
                                        <?php if (get_post_type() === 'post') : ?>
                                            <span class="result-date">
                                                <?php echo esc_html(get_the_date()); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <h2 class="result-title">
                                        <a href="<?php the_permalink(); ?>" rel="bookmark">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>
                                    
                                    <?php if (function_exists('WC') && get_post_type() === 'product') : ?>
                                        <div class="result-price">
                                            <?php
                                            $product = wc_get_product(get_the_ID());
                                            if ($product) {
                                                echo $product->get_price_html();
                                            }
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                </header>
                                
                                <div class="result-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <footer class="result-footer">
                                    <a href="<?php the_permalink(); ?>" class="read-more-btn">
                                        <?php
                                        if (get_post_type() === 'product') {
                                            esc_html_e('View Product', 'mcmaster-wc-theme');
                                        } else {
                                            esc_html_e('Read More', 'mcmaster-wc-theme');
                                        }
                                        ?>
                                    </a>
                                    
                                    <?php if (get_post_type() === 'post' && has_category()) : ?>
                                        <div class="result-categories">
                                            <?php the_category(', '); ?>
                                        </div>
                                    <?php endif; ?>
                                </footer>
                            </div>
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

            <div class="no-search-results">
                <div class="no-results-content">
                    <div class="no-results-icon">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="#ccc">
                            <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                        </svg>
                    </div>
                    
                    <h2><?php esc_html_e('Nothing found', 'mcmaster-wc-theme'); ?></h2>
                    <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'mcmaster-wc-theme'); ?></p>

                    <!-- Search Suggestions -->
                    <div class="search-suggestions">
                        <h3><?php esc_html_e('Search Suggestions:', 'mcmaster-wc-theme'); ?></h3>
                        <ul>
                            <li><?php esc_html_e('Check your spelling', 'mcmaster-wc-theme'); ?></li>
                            <li><?php esc_html_e('Try more general keywords', 'mcmaster-wc-theme'); ?></li>
                            <li><?php esc_html_e('Try different keywords', 'mcmaster-wc-theme'); ?></li>
                            <li><?php esc_html_e('Try fewer keywords', 'mcmaster-wc-theme'); ?></li>
                        </ul>
                    </div>

                    <!-- Popular Content -->
                    <?php
                    $popular_posts = get_posts(array(
                        'numberposts' => 5,
                        'meta_key' => 'post_views_count',
                        'orderby' => 'meta_value_num',
                        'order' => 'DESC'
                    ));

                    if (!$popular_posts) {
                        $popular_posts = get_posts(array(
                            'numberposts' => 5,
                            'orderby' => 'date',
                            'order' => 'DESC'
                        ));
                    }
                    ?>

                    <?php if ($popular_posts) : ?>
                        <div class="popular-content">
                            <h3><?php esc_html_e('Popular Content', 'mcmaster-wc-theme'); ?></h3>
                            <ul>
                                <?php foreach ($popular_posts as $post) : ?>
                                    <li>
                                        <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">
                                            <?php echo esc_html($post->post_title); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        <?php endif; ?>
    </div>
</main>

<style>
/* Search Results Styles */
.search-results {
    padding: 40px 0;
}

.page-header {
    margin-bottom: 40px;
    text-align: center;
}

.page-title {
    font-size: 32px;
    margin-bottom: 15px;
    color: #333;
}

.search-query {
    color: #0066cc;
    font-style: italic;
}

.search-meta {
    color: #666;
    font-size: 16px;
    margin-bottom: 30px;
}

.search-again {
    max-width: 500px;
    margin: 0 auto;
    padding: 30px;
    background: #f8f9fa;
    border-radius: 8px;
}

.search-again h3 {
    margin-bottom: 15px;
    color: #333;
    font-size: 18px;
}

.search-filters {
    margin-bottom: 40px;
}

.filter-tabs {
    display: flex;
    gap: 10px;
    justify-content: center;
    flex-wrap: wrap;
}

.filter-tab {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: white;
    border: 2px solid #ddd;
    border-radius: 25px;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
    font-weight: 500;
}

.filter-tab:hover,
.filter-tab.active {
    border-color: #0066cc;
    color: #0066cc;
}

.filter-tab .count {
    background: #0066cc;
    color: white;
    border-radius: 12px;
    padding: 2px 8px;
    font-size: 12px;
    font-weight: 600;
}

.search-results-container {
    display: grid;
    gap: 30px;
}

.search-result-item {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.search-result-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.result-content {
    display: flex;
    gap: 25px;
    align-items: flex-start;
}

.result-thumbnail {
    flex: 0 0 150px;
}

.result-thumbnail img {
    width: 100%;
    height: auto;
    border-radius: 8px;
}

.result-text {
    flex: 1;
}

.result-meta {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 10px;
    font-size: 14px;
}

.result-type {
    background: #0066cc;
    color: white;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
}

.result-date {
    color: #666;
}

.result-title {
    font-size: 24px;
    margin-bottom: 10px;
    line-height: 1.3;
}

.result-title a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
}

.result-title a:hover {
    color: #0066cc;
}

.result-price {
    font-size: 18px;
    font-weight: bold;
    color: #0066cc;
    margin-bottom: 15px;
}

.result-excerpt {
    color: #666;
    line-height: 1.6;
    margin-bottom: 20px;
}

.result-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.read-more-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #0066cc;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.3s ease;
}

.read-more-btn:hover {
    background: #0052a3;
}

.result-categories {
    font-size: 14px;
    color: #666;
}

.result-categories a {
    color: #0066cc;
    text-decoration: none;
}

.result-categories a:hover {
    text-decoration: underline;
}

.no-search-results {
    text-align: center;
    padding: 60px 20px;
}

.no-results-content {
    max-width: 600px;
    margin: 0 auto;
    background: white;
    padding: 50px;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
}

.no-results-icon {
    margin-bottom: 30px;
}

.no-results-content h2 {
    font-size: 28px;
    margin-bottom: 15px;
    color: #333;
}

.no-results-content p {
    font-size: 16px;
    color: #666;
    margin-bottom: 40px;
    line-height: 1.6;
}

.search-suggestions,
.popular-content {
    margin-bottom: 30px;
    text-align: left;
}

.search-suggestions h3,
.popular-content h3 {
    font-size: 18px;
    margin-bottom: 15px;
    color: #333;
}

.search-suggestions ul,
.popular-content ul {
    list-style: none;
    padding: 0;
}

.search-suggestions ul li,
.popular-content ul li {
    padding: 8px 0;
    color: #666;
    border-bottom: 1px solid #f0f0f0;
}

.popular-content ul li a {
    color: #0066cc;
    text-decoration: none;
    font-weight: 500;
}

.popular-content ul li a:hover {
    text-decoration: underline;
}

/* Filter functionality */
.search-result-item[data-type] {
    display: block;
}

.search-result-item[data-type].hidden {
    display: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    .result-content {
        flex-direction: column;
    }
    
    .result-thumbnail {
        flex: none;
        width: 100%;
    }
    
    .result-footer {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-tabs {
        justify-content: stretch;
    }
    
    .filter-tab {
        flex: 1;
        justify-content: center;
    }
    
    .no-results-content {
        padding: 30px 20px;
    }
}
</style>

<script>
// Search filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    const searchResults = document.querySelectorAll('.search-result-item');
    
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const filterType = this.dataset.type;
            
            // Update active tab
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Filter results
            searchResults.forEach(result => {
                if (filterType === 'all' || result.dataset.type === filterType) {
                    result.style.display = 'block';
                } else {
                    result.style.display = 'none';
                }
            });
        });
    });
});
</script>

<?php
get_sidebar();
get_footer();