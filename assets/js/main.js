/**
 * Main JavaScript for McMaster-Carr inspired theme
 */

(function($) {
    'use strict';

    // DOM Ready
    $(document).ready(function() {
        initMobileMenu();
        initArchiveControls();
        initSearchEnhancements();
        initCartUpdates();
        initSmoothScroll();
        initNewsletterForm();
    });

    /**
     * Mobile Menu Toggle
     */
    function initMobileMenu() {
        $('.menu-toggle').on('click', function() {
            const $this = $(this);
            const $menu = $('.primary-menu-list');
            
            $menu.toggleClass('active');
            $this.attr('aria-expanded', $menu.hasClass('active'));
            
            // Animate hamburger icon
            $this.toggleClass('active');
        });

        // Close menu on resize if window becomes larger
        $(window).on('resize', function() {
            if ($(window).width() > 768) {
                $('.primary-menu-list').removeClass('active');
                $('.menu-toggle').removeClass('active').attr('aria-expanded', 'false');
            }
        });
    }

    /**
     * Archive View Controls
     */
    function initArchiveControls() {
        // View toggle (grid/list)
        $('.view-grid, .view-list').on('click', function() {
            const $this = $(this);
            const view = $this.data('view');
            
            // Update button states
            $('.archive-view-toggle button').removeClass('active');
            $this.addClass('active');
            
            // Update posts container
            $('.posts-container.archive-posts').attr('data-view', view);
        });

        // Sort functionality
        $('#archive-sort-select').on('change', function() {
            const sortValue = $(this).val();
            // This would typically trigger an AJAX request to re-sort posts
            console.log('Sorting by:', sortValue);
            // Implementation would depend on specific requirements
        });
    }

    /**
     * Enhanced Search Functionality
     */
    function initSearchEnhancements() {
        // Search form enhancements
        $('.search-form input[type="search"]').on('focus', function() {
            $(this).parent('.search-form').addClass('focused');
        }).on('blur', function() {
            $(this).parent('.search-form').removeClass('focused');
        });

        // Live search suggestions (if needed)
        $('.search-form input[type="search"]').on('input', debounce(function() {
            const query = $(this).val();
            if (query.length > 2) {
                // Implement live search suggestions
                // This would typically make an AJAX request
                console.log('Live search query:', query);
            }
        }, 300));
    }

    /**
     * Cart Updates (WooCommerce)
     */
    function initCartUpdates() {
        // Update cart count on AJAX events
        $(document.body).on('added_to_cart removed_from_cart', function() {
            updateCartCount();
        });

        // Update cart count function
        function updateCartCount() {
            if (typeof mcmaster_ajax !== 'undefined') {
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
            }
        }
    }

    /**
     * Smooth Scroll for Anchor Links
     */
    function initSmoothScroll() {
        $('a[href*="#"]:not([href="#"])').on('click', function() {
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && 
                location.hostname === this.hostname) {
                
                const target = $(this.hash);
                const $target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                
                if ($target.length) {
                    $('html, body').animate({
                        scrollTop: $target.offset().top - 80
                    }, 800);
                    return false;
                }
            }
        });
    }

    /**
     * Newsletter Form
     */
    function initNewsletterForm() {
        $('.newsletter-form').on('submit', function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const $email = $form.find('input[type="email"]');
            const $button = $form.find('.newsletter-submit');
            const email = $email.val();
            
            if (!isValidEmail(email)) {
                showNotification('Please enter a valid email address.', 'error');
                return;
            }

            // Disable form during submission
            $button.prop('disabled', true).text('Subscribing...');

            // AJAX submission (customize based on your newsletter service)
            $.ajax({
                url: mcmaster_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'newsletter_signup',
                    email: email,
                    nonce: mcmaster_ajax.nonce
                },
                success: function(response) {
                    showNotification('Thank you for subscribing!', 'success');
                    $email.val('');
                },
                error: function() {
                    showNotification('Something went wrong. Please try again.', 'error');
                },
                complete: function() {
                    $button.prop('disabled', false).text('Subscribe');
                }
            });
        });
    }

    /**
     * Utility Functions
     */

    // Debounce function for performance
    function debounce(func, wait, immediate) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            const later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    // Email validation
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Show notification
    function showNotification(message, type) {
        // Create notification element
        const $notification = $('<div class="notification notification-' + type + '">' + message + '</div>');
        
        // Add to page
        $('body').append($notification);
        
        // Show with animation
        setTimeout(function() {
            $notification.addClass('show');
        }, 100);
        
        // Remove after delay
        setTimeout(function() {
            $notification.removeClass('show');
            setTimeout(function() {
                $notification.remove();
            }, 300);
        }, 4000);
    }

    /**
     * Product Gallery Enhancements (WooCommerce)
     */
    function initProductGallery() {
        // Enhanced product image hover effects
        $('.woocommerce .products .product').on('mouseenter', function() {
            $(this).find('img').addClass('hover-effect');
        }).on('mouseleave', function() {
            $(this).find('img').removeClass('hover-effect');
        });
    }

    /**
     * Header Scroll Effects
     */
    function initHeaderScrollEffects() {
        let lastScrollTop = 0;
        const $header = $('.site-header');
        
        $(window).scroll(function() {
            const scrollTop = $(this).scrollTop();
            
            if (scrollTop > 100) {
                $header.addClass('scrolled');
            } else {
                $header.removeClass('scrolled');
            }
            
            // Hide/show header on scroll
            if (scrollTop > lastScrollTop && scrollTop > 200) {
                $header.addClass('header-hidden');
            } else {
                $header.removeClass('header-hidden');
            }
            
            lastScrollTop = scrollTop;
        });
    }

    /**
     * Initialize all scroll-related functionality
     */
    $(window).on('load', function() {
        initHeaderScrollEffects();
        initProductGallery();
    });

    /**
     * AJAX Navigation Enhancement
     */
    function initAjaxNavigation() {
        // This would be for loading content via AJAX
        // Implementation depends on specific requirements
        $('.pagination-nav a').on('click', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            
            // Show loading state
            $('.posts-container').addClass('loading');
            
            // Load content via AJAX
            // Implementation would go here
        });
    }

})(jQuery);

// CSS for notifications
const notificationCSS = `
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 4px;
        color: white;
        font-weight: 500;
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        max-width: 300px;
    }
    
    .notification.show {
        transform: translateX(0);
    }
    
    .notification-success {
        background-color: #4CAF50;
    }
    
    .notification-error {
        background-color: #f44336;
    }
    
    .notification-info {
        background-color: #2196F3;
    }
    
    .hover-effect {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }
    
    .site-header.scrolled {
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: box-shadow 0.3s ease;
    }
    
    .site-header.header-hidden {
        transform: translateY(-100%);
        transition: transform 0.3s ease;
    }
    
    .posts-container.loading {
        opacity: 0.6;
        pointer-events: none;
    }
    
    .posts-container.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 40px;
        height: 40px;
        margin: -20px 0 0 -20px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #0066cc;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
`;

// Inject CSS
const style = document.createElement('style');
style.textContent = notificationCSS;
document.head.appendChild(style);