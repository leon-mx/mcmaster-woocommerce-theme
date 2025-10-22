/**
 * McMaster Navigation JavaScript
 */

(function($) {
    'use strict';
    
    class McMasterNavigation {
        constructor() {
            this.init();
        }
        
        init() {
            this.setupMobileToggle();
            this.setupMegaMenu();
            this.setupDropdowns();
            this.setupKeyboardNavigation();
            this.setupAccessibility();
        }
        
        setupMobileToggle() {
            const toggle = $('.mobile-menu-toggle');
            const nav = $('.main-navigation');
            
            toggle.on('click', (e) => {
                e.preventDefault();
                
                const isOpen = nav.hasClass('is-open');
                
                nav.toggleClass('is-open');
                toggle.toggleClass('is-open');
                
                // Update ARIA attributes
                toggle.attr('aria-expanded', !isOpen);
                
                // Handle body scroll
                if (!isOpen) {
                    $('body').addClass('nav-open');
                } else {
                    $('body').removeClass('nav-open');
                }
            });
            
            // Handle mobile submenu toggles
            $('.nav-menu .menu-item-has-children > a').on('click', (e) => {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    
                    const parent = $(e.target).closest('.menu-item-has-children');
                    const submenu = parent.find('.mega-menu, .dropdown-menu').first();
                    
                    parent.toggleClass('is-open');
                    submenu.slideToggle(300);
                }
            });
        }
        
        setupMegaMenu() {
            const megaMenuItems = $('.has-mega-menu, .mega-menu-enabled');
            
            megaMenuItems.each((index, item) => {
                const $item = $(item);
                const $megaMenu = $item.find('.mega-menu');
                let loadTimer;
                
                // Mouse enter
                $item.on('mouseenter', () => {
                    if (window.innerWidth > 768) {
                        clearTimeout(loadTimer);
                        
                        // Check if content needs to be loaded dynamically
                        const categoryId = $item.find('a[data-category-id]').data('category-id');
                        
                        if (categoryId && !$megaMenu.hasClass('loaded')) {
                            this.loadMegaMenuContent($item, categoryId);
                        }
                        
                        $megaMenu.stop(true, true).fadeIn(200);
                        $item.addClass('active');
                    }
                });
                
                // Mouse leave
                $item.on('mouseleave', () => {
                    if (window.innerWidth > 768) {
                        loadTimer = setTimeout(() => {
                            $megaMenu.stop(true, true).fadeOut(150);
                            $item.removeClass('active');
                        }, 100);
                    }
                });
                
                // Keep menu open when hovering over it
                $megaMenu.on('mouseenter', () => {
                    clearTimeout(loadTimer);
                });
                
                $megaMenu.on('mouseleave', () => {
                    $megaMenu.fadeOut(150);
                    $item.removeClass('active');
                });
            });
        }
        
        setupDropdowns() {
            const dropdownItems = $('.menu-item-has-children').not('.has-mega-menu, .mega-menu-enabled');
            
            dropdownItems.each((index, item) => {
                const $item = $(item);
                const $dropdown = $item.find('.dropdown-menu');
                
                $item.on('mouseenter', () => {
                    if (window.innerWidth > 768) {
                        $dropdown.stop(true, true).slideDown(200);
                        $item.addClass('active');
                    }
                });
                
                $item.on('mouseleave', () => {
                    if (window.innerWidth > 768) {
                        $dropdown.stop(true, true).slideUp(150);
                        $item.removeClass('active');
                    }
                });
            });
        }
        
        setupKeyboardNavigation() {
            const menuItems = $('.nav-menu a');
            
            menuItems.on('keydown', (e) => {
                const $current = $(e.target);
                const $parent = $current.closest('.menu-item');
                
                switch (e.key) {
                    case 'ArrowDown':
                        e.preventDefault();
                        this.navigateDown($current);
                        break;
                        
                    case 'ArrowUp':
                        e.preventDefault();
                        this.navigateUp($current);
                        break;
                        
                    case 'ArrowRight':
                        e.preventDefault();
                        this.navigateRight($current);
                        break;
                        
                    case 'ArrowLeft':
                        e.preventDefault();
                        this.navigateLeft($current);
                        break;
                        
                    case 'Escape':
                        this.closeMenus();
                        $current.blur();
                        break;
                        
                    case 'Enter':
                    case ' ':
                        if ($parent.hasClass('menu-item-has-children')) {
                            e.preventDefault();
                            this.toggleSubmenu($parent);
                        }
                        break;
                }
            });
        }
        
        navigateDown($current) {
            const $parent = $current.closest('.menu-item');
            const $submenu = $parent.find('.mega-menu, .dropdown-menu').first();
            
            if ($submenu.length && $submenu.is(':visible')) {
                const $firstLink = $submenu.find('a').first();
                if ($firstLink.length) {
                    $firstLink.focus();
                }
            } else {
                const $next = $parent.next('.menu-item').find('a').first();
                if ($next.length) {
                    $next.focus();
                }
            }
        }
        
        navigateUp($current) {
            const $parent = $current.closest('.menu-item');
            const $prev = $parent.prev('.menu-item').find('a').first();
            
            if ($prev.length) {
                $prev.focus();
            }
        }
        
        navigateRight($current) {
            const $parent = $current.closest('.menu-item');
            const $submenu = $parent.find('.mega-menu, .dropdown-menu').first();
            
            if ($submenu.length) {
                $submenu.show();
                const $firstLink = $submenu.find('a').first();
                if ($firstLink.length) {
                    $firstLink.focus();
                }
            }
        }
        
        navigateLeft($current) {
            const $submenu = $current.closest('.mega-menu, .dropdown-menu');
            
            if ($submenu.length) {
                $submenu.hide();
                const $parentLink = $submenu.siblings('a');
                if ($parentLink.length) {
                    $parentLink.focus();
                }
            }
        }
        
        toggleSubmenu($parent) {
            const $submenu = $parent.find('.mega-menu, .dropdown-menu').first();
            
            if ($submenu.is(':visible')) {
                $submenu.hide();
            } else {
                this.closeMenus();
                $submenu.show();
            }
        }
        
        closeMenus() {
            $('.mega-menu, .dropdown-menu').hide();
            $('.menu-item').removeClass('active');
        }
        
        setupAccessibility() {
            // Add ARIA attributes
            $('.menu-item-has-children > a').each((index, item) => {
                const $item = $(item);
                const $parent = $item.closest('.menu-item');
                const submenuId = 'submenu-' + index;
                
                $item.attr({
                    'aria-haspopup': 'true',
                    'aria-expanded': 'false',
                    'aria-controls': submenuId
                });
                
                $parent.find('.mega-menu, .dropdown-menu').first().attr('id', submenuId);
            });
            
            // Update ARIA expanded states
            $('.menu-item-has-children').on('mouseenter focusin', function() {
                $(this).find('> a').attr('aria-expanded', 'true');
            });
            
            $('.menu-item-has-children').on('mouseleave focusout', function() {
                setTimeout(() => {
                    if (!$(this).find(':focus').length) {
                        $(this).find('> a').attr('aria-expanded', 'false');
                    }
                }, 100);
            });
        }
        
        loadMegaMenuContent($item, categoryId) {
            const $megaMenu = $item.find('.mega-menu');
            
            // Show loading state
            $megaMenu.addClass('loading').html('<div class="loading-spinner">Loading...</div>');
            
            // Make AJAX request
            $.ajax({
                url: mcmaster_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'mcmaster_load_menu',
                    menu_id: $item.attr('id'),
                    category_id: categoryId,
                    nonce: mcmaster_ajax.nonce
                },
                success: (response) => {
                    if (response.success) {
                        $megaMenu.removeClass('loading').html(response.data);
                        $megaMenu.addClass('loaded');
                    } else {
                        $megaMenu.removeClass('loading').html('<div class="error">Error loading menu content</div>');
                    }
                },
                error: () => {
                    $megaMenu.removeClass('loading').html('<div class="error">Error loading menu content</div>');
                }
            });
        }
    }
    
    // Initialize when document is ready
    $(document).ready(() => {
        new McMasterNavigation();
        
        // Handle window resize
        $(window).on('resize', debounce(() => {
            if (window.innerWidth > 768) {
                $('.main-navigation').removeClass('is-open');
                $('.mobile-menu-toggle').removeClass('is-open').attr('aria-expanded', 'false');
                $('body').removeClass('nav-open');
                $('.menu-item-has-children').removeClass('is-open');
                $('.mega-menu, .dropdown-menu').removeAttr('style');
            }
        }, 250));
        
        // Close mobile menu when clicking outside
        $(document).on('click', (e) => {
            const $target = $(e.target);
            const $nav = $('.main-navigation');
            
            if ($nav.hasClass('is-open') && !$target.closest('.main-navigation, .mobile-menu-toggle').length) {
                $nav.removeClass('is-open');
                $('.mobile-menu-toggle').removeClass('is-open').attr('aria-expanded', 'false');
                $('body').removeClass('nav-open');
            }
        });
    });
    
    // Utility function for debouncing
    function debounce(func, wait, immediate) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
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
    
})(jQuery);