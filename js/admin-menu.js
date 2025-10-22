/**
 * Admin Menu JavaScript for McMaster Navigation
 */

(function($) {
    'use strict';
    
    class McMasterAdminMenu {
        constructor() {
            this.init();
        }
        
        init() {
            this.addMenuItemFields();
            this.handleMenuItemSettings();
            this.setupEventListeners();
        }
        
        addMenuItemFields() {
            // Add fields to existing menu items
            $('.menu-item').each((index, item) => {
                this.addFieldsToMenuItem($(item));
            });
            
            // Add fields to new menu items as they're added
            $(document).on('menu-item-added', (e, $menuItem) => {
                this.addFieldsToMenuItem($menuItem);
            });
        }
        
        addFieldsToMenuItem($menuItem) {
            const itemId = $menuItem.find('.menu-item-data-db-id').val();
            
            if (!itemId || $menuItem.find('.mcmaster-menu-options').length) {
                return;
            }
            
            const fieldsHtml = this.getMenuItemFieldsHtml(itemId);
            $menuItem.find('.menu-item-settings').append(fieldsHtml);
        }
        
        getMenuItemFieldsHtml(itemId) {
            return `
                <div class="mcmaster-menu-options">
                    <h4>McMaster Menu Options</h4>
                    
                    <p class="description">
                        Configure special behaviors for this menu item in the McMaster navigation system.
                    </p>
                    
                    <p class="field-mega-menu">
                        <label>
                            <input type="checkbox" name="menu-item-mega-menu[${itemId}]" 
                                   id="edit-menu-item-mega-menu-${itemId}" 
                                   value="1" class="menu-item-mega-menu" />
                            Enable mega menu for this item
                        </label>
                    </p>
                    
                    <p class="field-menu-columns" style="display: none;">
                        <label for="edit-menu-item-columns-${itemId}">
                            Number of columns:
                            <select name="menu-item-columns[${itemId}]" 
                                    id="edit-menu-item-columns-${itemId}" 
                                    class="menu-item-columns">
                                <option value="2">2 Columns</option>
                                <option value="3" selected>3 Columns</option>
                                <option value="4">4 Columns</option>
                            </select>
                        </label>
                    </p>
                    
                    <p class="field-menu-icon">
                        <label for="edit-menu-item-icon-${itemId}">
                            Menu icon class:
                            <input type="text" name="menu-item-icon[${itemId}]" 
                                   id="edit-menu-item-icon-${itemId}" 
                                   class="menu-item-icon widefat"
                                   placeholder="e.g., fas fa-home" />
                        </label>
                    </p>
                    
                    <p class="field-menu-description">
                        <label for="edit-menu-item-description-${itemId}">
                            Menu description:
                            <textarea name="menu-item-description[${itemId}]" 
                                      id="edit-menu-item-description-${itemId}" 
                                      class="menu-item-description widefat" 
                                      rows="3"
                                      placeholder="Short description for mega menu display"></textarea>
                        </label>
                    </p>
                    
                    <p class="field-highlight-menu">
                        <label>
                            <input type="checkbox" name="menu-item-highlight[${itemId}]" 
                                   id="edit-menu-item-highlight-${itemId}" 
                                   value="1" class="menu-item-highlight" />
                            Highlight this menu item
                        </label>
                    </p>
                </div>
            `;
        }
        
        handleMenuItemSettings() {
            // Toggle mega menu options
            $(document).on('change', '.menu-item-mega-menu', (e) => {
                const $checkbox = $(e.target);
                const $columnsField = $checkbox.closest('.mcmaster-menu-options').find('.field-menu-columns');
                
                if ($checkbox.is(':checked')) {
                    $columnsField.slideDown();
                } else {
                    $columnsField.slideUp();
                }
            });
            
            // Load existing values
            this.loadExistingValues();
        }
        
        loadExistingValues() {
            $('.menu-item').each((index, item) => {
                const $item = $(item);
                const itemId = $item.find('.menu-item-data-db-id').val();
                
                if (!itemId) return;
                
                // Load mega menu setting
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'get_menu_item_meta',
                        item_id: itemId,
                        meta_key: '_menu_item_mega_menu'
                    },
                    success: (response) => {
                        if (response === '1') {
                            $item.find('.menu-item-mega-menu').prop('checked', true).trigger('change');
                        }
                    }
                });
                
                // Load other settings
                this.loadMenuItemMeta($item, itemId, '_menu_item_columns', '.menu-item-columns');
                this.loadMenuItemMeta($item, itemId, '_menu_item_icon', '.menu-item-icon');
                this.loadMenuItemMeta($item, itemId, '_menu_item_description', '.menu-item-description');
                this.loadMenuItemMeta($item, itemId, '_menu_item_highlight', '.menu-item-highlight');
            });
        }
        
        loadMenuItemMeta($item, itemId, metaKey, selector) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'get_menu_item_meta',
                    item_id: itemId,
                    meta_key: metaKey
                },
                success: (response) => {
                    const $field = $item.find(selector);
                    
                    if ($field.is(':checkbox')) {
                        $field.prop('checked', response === '1');
                    } else {
                        $field.val(response);
                    }
                }
            });
        }
        
        setupEventListeners() {
            // Save menu item meta when menu is saved
            $('#update-nav-menu').on('click', () => {
                $('.menu-item').each((index, item) => {
                    this.saveMenuItemMeta($(item));
                });
            });
            
            // Real-time preview updates
            $(document).on('input change', '.mcmaster-menu-options input, .mcmaster-menu-options select, .mcmaster-menu-options textarea', (e) => {
                this.updatePreview($(e.target));
            });
            
            // Add helpful tooltips
            this.addTooltips();
        }
        
        saveMenuItemMeta($menuItem) {
            const itemId = $menuItem.find('.menu-item-data-db-id').val();
            
            if (!itemId) return;
            
            const metaData = {
                '_menu_item_mega_menu': $menuItem.find('.menu-item-mega-menu').is(':checked') ? '1' : '0',
                '_menu_item_columns': $menuItem.find('.menu-item-columns').val(),
                '_menu_item_icon': $menuItem.find('.menu-item-icon').val(),
                '_menu_item_description': $menuItem.find('.menu-item-description').val(),
                '_menu_item_highlight': $menuItem.find('.menu-item-highlight').is(':checked') ? '1' : '0'
            };
            
            // Save each meta field
            Object.entries(metaData).forEach(([key, value]) => {
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'save_menu_item_meta',
                        item_id: itemId,
                        meta_key: key,
                        meta_value: value
                    }
                });
            });
        }
        
        updatePreview($field) {
            const $menuItem = $field.closest('.menu-item');
            const $title = $menuItem.find('.menu-item-title');
            
            // Add visual indicators
            if ($field.hasClass('menu-item-mega-menu') && $field.is(':checked')) {
                $title.addClass('has-mega-menu');
            } else if ($field.hasClass('menu-item-mega-menu')) {
                $title.removeClass('has-mega-menu');
            }
            
            if ($field.hasClass('menu-item-highlight') && $field.is(':checked')) {
                $title.addClass('is-highlighted');
            } else if ($field.hasClass('menu-item-highlight')) {
                $title.removeClass('is-highlighted');
            }
        }
        
        addTooltips() {
            // Add tooltips for better UX
            $('.mcmaster-menu-options input, .mcmaster-menu-options select').each((index, element) => {
                const $element = $(element);
                let tooltip = '';
                
                if ($element.hasClass('menu-item-mega-menu')) {
                    tooltip = 'Enable mega menu to show child items in a multi-column layout';
                } else if ($element.hasClass('menu-item-columns')) {
                    tooltip = 'Choose how many columns to display in the mega menu';
                } else if ($element.hasClass('menu-item-icon')) {
                    tooltip = 'Add an icon class (e.g., Font Awesome) to display next to the menu item';
                } else if ($element.hasClass('menu-item-highlight')) {
                    tooltip = 'Highlight this menu item with special styling';
                }
                
                if (tooltip) {
                    $element.attr('title', tooltip);
                }
            });
        }
    }
    
    // Initialize when document is ready
    $(document).ready(() => {
        // Only run on nav-menus.php page
        if (window.location.href.indexOf('nav-menus.php') > -1) {
            new McMasterAdminMenu();
        }
    });
    
    // Handle AJAX for menu item meta
    $(document).ajaxComplete((event, xhr, settings) => {
        if (settings.data && settings.data.indexOf('action=add-menu-item') > -1) {
            // New menu item added, reinitialize
            setTimeout(() => {
                $('.menu-item').each((index, item) => {
                    const $item = $(item);
                    if (!$item.find('.mcmaster-menu-options').length) {
                        const itemId = $item.find('.menu-item-data-db-id').val();
                        if (itemId) {
                            const fieldsHtml = window.mcmasterAdmin.getMenuItemFieldsHtml(itemId);
                            $item.find('.menu-item-settings').append(fieldsHtml);
                        }
                    }
                });
            }, 100);
        }
    });
    
})(jQuery);