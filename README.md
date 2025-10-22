# McMaster Navigation System

A comprehensive WordPress theme featuring a custom McMaster-style navigation system with mega menus, deep category support, and responsive design.

## Features

### Navigation System
- **Mega Menu Support**: Multi-column dropdown menus for complex category hierarchies
- **Custom Walker**: Advanced menu walker with support for deep product categories
- **Responsive Design**: Mobile-first approach with touch-friendly interactions
- **Keyboard Navigation**: Full accessibility support with arrow key navigation
- **Dynamic Loading**: AJAX-powered content loading for better performance

### Menu Configuration
- **Theme Customizer Integration**: Configure navigation settings through WordPress Customizer
- **Custom Menu Fields**: Add mega menu options, icons, descriptions, and highlighting
- **Multiple Data Sources**: Support for WordPress categories, custom categories, or external APIs
- **Flexible Column Layouts**: Choose 2, 3, or 4-column mega menu layouts

### Mobile Experience
- **Collapsible Mobile Menu**: Touch-friendly mobile navigation
- **Smooth Animations**: CSS transitions and JavaScript animations
- **Progressive Enhancement**: Works without JavaScript as fallback

## File Structure

```
├── style.css                          # Main theme stylesheet with navigation styles
├── functions.php                      # Theme functions and menu registration
├── header.php                        # Header template with navigation
├── footer.php                        # Footer template
├── index.php                         # Main template file
├── inc/
│   ├── class-mcmaster-walker.php     # Custom navigation walker
│   └── navigation-functions.php      # Navigation helper functions
├── template-parts/
│   └── navigation/
│       ├── navigation-primary.php    # Primary navigation template
│       ├── mega-menu.php            # Mega menu template
│       └── mobile-menu.php          # Mobile navigation template
├── js/
│   ├── navigation.js                 # Frontend navigation JavaScript
│   └── admin-menu.js                # Admin menu customization
└── css/
    └── admin-menu.css               # Admin styling for menu options
```

## Installation

1. Upload the theme files to your WordPress themes directory
2. Activate the theme in WordPress Admin > Appearance > Themes
3. Configure navigation settings in Customizer > Navigation Settings
4. Create menus in WordPress Admin > Appearance > Menus
5. Assign menus to the Primary and Secondary menu locations

## Menu Configuration

### Creating Mega Menus

1. Go to **Appearance > Menus** in WordPress admin
2. Select or create a menu item that will have the mega menu
3. In the menu item settings, check "Enable mega menu for this item"
4. Choose the number of columns (2, 3, or 4)
5. Add child menu items to populate the mega menu

### Menu Options

Each menu item supports these custom options:

- **Enable Mega Menu**: Converts dropdown to multi-column mega menu
- **Number of Columns**: Choose layout for mega menu content
- **Menu Icon**: Add icon class (e.g., Font Awesome icons)
- **Description**: Short description displayed in mega menu
- **Highlight**: Special styling to make menu item stand out

### Category Data Sources

Configure in **Customizer > Navigation Settings**:

- **WordPress Categories**: Use built-in WordPress categories
- **Custom Categories**: Hook custom category data via filters
- **External API**: Connect to external category data source

## Customization

### Theme Hooks

The theme provides several hooks for customization:

```php
// Filter custom category data
add_filter('mcmaster_custom_categories', function($categories) {
    // Add your custom categories
    return $categories;
});

// Filter external category data
add_filter('mcmaster_external_categories', function($categories) {
    // Add external API categories
    return $categories;
});

// Add featured content to mega menu
add_filter('mcmaster_mega_menu_featured_content', function($content, $menu_item, $category_id) {
    // Add custom featured content
    return $content;
}, 10, 3);

// Add mobile contact info
add_filter('mcmaster_mobile_contact_info', function($contact_info) {
    return array(
        array(
            'icon' => 'fas fa-phone',
            'text' => '(555) 123-4567',
            'url' => 'tel:5551234567'
        ),
        array(
            'icon' => 'fas fa-envelope',
            'text' => 'info@example.com',
            'url' => 'mailto:info@example.com'
        )
    );
});
```

### CSS Customization

The theme uses CSS custom properties for easy customization:

```css
:root {
    --nav-bg-color: #fff;
    --nav-text-color: #333;
    --nav-hover-bg: #f5f5f5;
    --nav-hover-color: #007cba;
    --mega-menu-shadow: 0 4px 8px rgba(0,0,0,0.15);
    --mega-menu-border: 3px solid #007cba;
}
```

### JavaScript Events

The navigation system dispatches custom events:

```javascript
// Menu opened
document.addEventListener('mcmaster:menu:opened', function(e) {
    console.log('Menu opened:', e.detail.menuItem);
});

// Menu closed
document.addEventListener('mcmaster:menu:closed', function(e) {
    console.log('Menu closed:', e.detail.menuItem);
});

// Mobile menu toggled
document.addEventListener('mcmaster:mobile:toggled', function(e) {
    console.log('Mobile menu toggled:', e.detail.isOpen);
});
```

## Browser Support

- **Modern Browsers**: Chrome, Firefox, Safari, Edge (latest versions)
- **Mobile**: iOS Safari, Chrome Mobile, Samsung Internet
- **Accessibility**: Screen readers, keyboard navigation
- **Fallbacks**: Graceful degradation for older browsers

## Performance

- **CSS**: Optimized for critical rendering path
- **JavaScript**: Lazy loading and event delegation
- **AJAX**: Dynamic content loading for large menus
- **Caching**: Proper cache headers and ETags support

## Accessibility

- **ARIA Attributes**: Proper labeling and states
- **Keyboard Navigation**: Arrow keys, Enter, Escape support
- **Screen Reader**: Optimized for assistive technologies
- **Focus Management**: Clear focus indicators and logical tab order

## License

This theme is released under the GPL v2 or later license.

## Support

For questions, issues, or feature requests, please refer to the theme documentation or contact the development team.