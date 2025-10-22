/**
 * WooCommerce Templates JavaScript
 * McMaster-Carr inspired interactions with performance optimizations
 */

(function($) {
    'use strict';
    
    /**
     * DOM Ready
     */
    $(document).ready(function() {
        McMasterWC.init();
    });
    
    /**
     * Main McMaster WC object
     */
    window.McMasterWC = {
        
        /**
         * Initialize all functionality
         */
        init: function() {
            this.setupViewToggle();
            this.setupQuickView();
            this.setupAjaxAddToCart();
            this.setupFilterToggle();
            this.setupLazyLoading();
            this.setupProductCardInteractions();
            this.setupPaginationPreload();
            this.setupSearchDebounce();
        },
        
        /**
         * Grid/List view toggle
         */
        setupViewToggle: function() {
            $('.view-toggle').on('click', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const view = $button.data('view');
                const $grid = $('.mcmaster-products-grid');
                
                // Update button states
                $('.view-toggle').removeClass('active');
                $button.addClass('active');
                
                // Update grid layout
                $grid.removeClass('view-grid view-list').addClass('view-' + view);
                
                // Store preference
                localStorage.setItem('mcmaster_view_preference', view);
                
                // Animate transition
                $grid.addClass('view-transitioning');
                setTimeout(() => {
                    $grid.removeClass('view-transitioning');
                }, 300);
            });
            
            // Restore saved preference
            const savedView = localStorage.getItem('mcmaster_view_preference');
            if (savedView && $('.view-toggle[data-view="' + savedView + '"]').length) {
                $('.view-toggle[data-view="' + savedView + '"]').trigger('click');
            }
        },
        
        /**
         * Quick view functionality
         */
        setupQuickView: function() {
            $(document).on('click', '.quick-view-btn', function(e) {
                e.preventDefault();
                
                const $button = $(this);
                const productId = $button.data('product-id');
                
                if (!productId) return;
                
                // Show loading state
                $button.addClass('loading').prop('disabled', true);
                
                // Load quick view content
                this.loadQuickView(productId, $button);
            }.bind(this));
        },
        
        /**
         * Load quick view modal content
         */
        loadQuickView: function(productId, $button) {
            $.ajax({
                url: mcmaster_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mcmaster_quick_view',
                    product_id: productId,
                    nonce: mcmaster_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        this.showQuickViewModal(response.data);
                    } else {
                        console.error('Quick view failed:', response.data);
                    }
                }.bind(this),
                error: function(xhr, status, error) {
                    console.error('Quick view AJAX error:', error);
                },
                complete: function() {
                    $button.removeClass('loading').prop('disabled', false);
                }
            });
        },
        
        /**
         * Show quick view modal
         */
        showQuickViewModal: function(content) {
            // Create modal if it doesn't exist
            if (!$('#mcmaster-quick-view-modal').length) {
                const modalHtml = `
                    <div id="mcmaster-quick-view-modal" class="mcmaster-modal">
                        <div class="modal-backdrop"></div>
                        <div class="modal-container">
                            <div class="modal-header">
                                <button class="modal-close" type="button">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="modal-content"></div>
                        </div>
                    </div>
                `;
                $('body').append(modalHtml);
            }
            
            const $modal = $('#mcmaster-quick-view-modal');
            $modal.find('.modal-content').html(content);
            
            // Show modal
            $modal.addClass('active');
            $('body').addClass('modal-open');
            
            // Setup modal close events
            $modal.find('.modal-close, .modal-backdrop').on('click', function(e) {
                e.preventDefault();
                this.closeQuickViewModal();
            }.bind(this));
            
            // ESC key to close
            $(document).on('keydown.quickview', function(e) {
                if (e.keyCode === 27) {
                    this.closeQuickViewModal();
                }
            }.bind(this));
            
            // Initialize WC features in modal
            $modal.find('form.cart').on('submit', this.handleQuickViewAddToCart.bind(this));
        },
        
        /**
         * Close quick view modal
         */
        closeQuickViewModal: function() {
            const $modal = $('#mcmaster-quick-view-modal');
            $modal.removeClass('active');
            $('body').removeClass('modal-open');
            $(document).off('keydown.quickview');
        },
        
        /**
         * Handle add to cart from quick view
         */
        handleQuickViewAddToCart: function(e) {
            e.preventDefault();
            
            const $form = $(e.target);
            const $button = $form.find('.single_add_to_cart_button');
            
            // Show loading
            $button.addClass('loading').prop('disabled', true);
            
            // Get form data
            const formData = $form.serialize();
            
            $.ajax({
                url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.error) {
                        this.showNotice(response.error_message, 'error');
                    } else {
                        this.showNotice('Product added to cart!', 'success');
                        this.updateCartCount();
                        this.closeQuickViewModal();
                    }
                }.bind(this),
                error: function() {
                    this.showNotice('Error adding product to cart', 'error');
                }.bind(this),
                complete: function() {
                    $button.removeClass('loading').prop('disabled', false);
                }
            });
        },
        
        /**
         * AJAX Add to Cart for product cards
         */
        setupAjaxAddToCart: function() {
            $(document).on('click', '.add-to-cart-btn', function(e) {
                const $button = $(this);
                
                // Skip if it's a variable product (needs product page)
                if ($button.hasClass('product_type_variable')) {
                    return true; // Allow default behavior
                }
                
                e.preventDefault();
                
                const productId = $button.data('product-id');
                const quantity = $button.data('quantity') || 1;
                
                if (!productId) return;
                
                // Show loading state
                $button.addClass('loading').prop('disabled', true);
                
                // Add to cart
                $.ajax({
                    url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
                    type: 'POST',
                    data: {
                        product_id: productId,
                        quantity: quantity
                    },
                    success: function(response) {
                        if (response.error) {
                            this.showNotice(response.error_message, 'error');
                        } else {
                            // Update button text
                            $button.text('Added!').addClass('added');
                            
                            // Show success message
                            this.showNotice('Product added to cart!', 'success');
                            
                            // Update cart count
                            this.updateCartCount();
                            
                            // Reset button after delay
                            setTimeout(() => {
                                $button.text($button.data('original-text') || 'Add to Cart')
                                      .removeClass('added');
                            }, 2000);
                        }
                    }.bind(this),
                    error: function() {
                        this.showNotice('Error adding product to cart', 'error');
                    }.bind(this),
                    complete: function() {
                        $button.removeClass('loading').prop('disabled', false);
                    }
                });
            }.bind(this));
        },
        
        /**
         * Show notification message
         */
        showNotice: function(message, type = 'info') {
            const $notice = $(`
                <div class="mcmaster-notice mcmaster-notice-${type}">
                    <span class="notice-message">${message}</span>
                    <button class="notice-close">×</button>
                </div>
            `);
            
            // Add to page
            if (!$('.mcmaster-notices').length) {
                $('body').append('<div class="mcmaster-notices"></div>');
            }
            
            $('.mcmaster-notices').append($notice);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                $notice.fadeOut(() => $notice.remove());
            }, 5000);
            
            // Close button
            $notice.find('.notice-close').on('click', () => {
                $notice.fadeOut(() => $notice.remove());
            });
        },
        
        /**
         * Update cart count in header
         */
        updateCartCount: function() {
            $.ajax({
                url: mcmaster_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'update_cart_count',
                    nonce: mcmaster_ajax.nonce
                },
                success: function(response) {
                    $('.cart-count').text(response);
                }
            });
        },
        
        /**
         * Filter toggle functionality
         */
        setupFilterToggle: function() {
            // Mobile filter toggle
            $('.filter-toggle-btn').on('click', function(e) {
                e.preventDefault();
                $('.archive-sidebar, .category-sidebar').toggleClass('filters-open');
                $('body').toggleClass('filters-overlay-active');
            });
            
            // Filter section collapse/expand
            $('.filter-title').on('click', function(e) {
                const $section = $(this).closest('.sidebar-inner > div');
                $section.toggleClass('collapsed');
            });
            
            // Apply filters button
            $('.apply-filters-btn').on('click', function(e) {
                e.preventDefault();
                
                const filters = {};
                
                // Collect filter values
                $('.filter-checkbox input:checked').each(function() {
                    const name = $(this).attr('name');
                    if (!filters[name]) filters[name] = [];
                    filters[name].push($(this).val());
                });
                
                // Update URL and reload
                const url = new URL(window.location);
                Object.keys(filters).forEach(key => {
                    url.searchParams.set(key, filters[key].join(','));
                });
                
                window.location.href = url.toString();
            });
        },
        
        /**
         * Lazy loading for images
         */
        setupLazyLoading: function() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            imageObserver.unobserve(img);
                        }
                    });
                });
                
                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            }
        },
        
        /**
         * Product card interactions
         */
        setupProductCardInteractions: function() {
            // Hover effects with throttling
            let hoverTimeout;
            $('.mcmaster-product-card').on('mouseenter', function() {
                clearTimeout(hoverTimeout);
                const $card = $(this);
                
                hoverTimeout = setTimeout(() => {
                    $card.addClass('hovered');
                    
                    // Preload product image if not loaded
                    const $img = $card.find('.product-card-img[data-src]');
                    if ($img.length) {
                        $img.attr('src', $img.data('src')).removeAttr('data-src');
                    }
                }, 100);
            }).on('mouseleave', function() {
                clearTimeout(hoverTimeout);
                $(this).removeClass('hovered');
            });
            
            // Save original button text for add to cart
            $('.add-to-cart-btn').each(function() {
                $(this).data('original-text', $(this).text());
            });
        },
        
        /**
         * Pagination preloading
         */
        setupPaginationPreload: function() {
            // Preload next page on scroll near bottom
            let preloadTriggered = false;
            
            $(window).on('scroll', this.throttle(() => {
                if (preloadTriggered) return;
                
                const scrollTop = $(window).scrollTop();
                const windowHeight = $(window).height();
                const documentHeight = $(document).height();
                
                // If within 500px of bottom
                if (scrollTop + windowHeight > documentHeight - 500) {
                    const $nextLink = $('.woocommerce-pagination .next');
                    if ($nextLink.length) {
                        this.preloadPage($nextLink.attr('href'));
                        preloadTriggered = true;
                    }
                }
            }, 250));
        },
        
        /**
         * Preload page content
         */
        preloadPage: function(url) {
            const link = document.createElement('link');
            link.rel = 'prefetch';
            link.href = url;
            document.head.appendChild(link);
        },
        
        /**
         * Debounced search functionality
         */
        setupSearchDebounce: function() {
            const $searchInput = $('.search-form input[type="search"]');
            
            if ($searchInput.length) {
                let searchTimeout;
                
                $searchInput.on('input', function() {
                    clearTimeout(searchTimeout);
                    const query = $(this).val();
                    
                    if (query.length >= 3) {
                        searchTimeout = setTimeout(() => {
                            this.performSearch(query);
                        }, 500);
                    }
                }.bind(this));
            }
        },
        
        /**
         * Perform AJAX search
         */
        performSearch: function(query) {
            $.ajax({
                url: mcmaster_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mcmaster_live_search',
                    query: query,
                    nonce: mcmaster_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        this.showSearchSuggestions(response.data);
                    }
                }.bind(this)
            });
        },
        
        /**
         * Show search suggestions
         */
        showSearchSuggestions: function(suggestions) {
            let $suggestions = $('.search-suggestions');
            
            if (!$suggestions.length) {
                $suggestions = $('<div class="search-suggestions"></div>');
                $('.search-form').after($suggestions);
            }
            
            if (suggestions.length) {
                let html = '<ul>';
                suggestions.forEach(item => {
                    html += `<li><a href="${item.url}">${item.title}</a></li>`;
                });
                html += '</ul>';
                
                $suggestions.html(html).show();
            } else {
                $suggestions.hide();
            }
        },
        
        /**
         * Utility: Throttle function
         */
        throttle: function(func, delay) {
            let timeoutId;
            let lastExecTime = 0;
            return function() {
                const currentTime = Date.now();
                
                if (currentTime - lastExecTime > delay) {
                    func.apply(this, arguments);
                    lastExecTime = currentTime;
                } else {
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => {
                        func.apply(this, arguments);
                        lastExecTime = Date.now();
                    }, delay - (currentTime - lastExecTime));
                }
            };
        }
    };
    
    /**
     * Handle page visibility change to pause/resume animations
     */
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            $('body').addClass('page-hidden');
        } else {
            $('body').removeClass('page-hidden');
        }
    });
    
})(jQuery);