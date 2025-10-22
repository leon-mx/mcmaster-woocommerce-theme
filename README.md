# McMaster-Carr Inspired WooCommerce Theme

A modern, professional WordPress theme inspired by McMaster-Carr's design philosophy, optimized for WooCommerce stores and business websites.

## Features

- **WooCommerce Ready**: Full compatibility with WooCommerce including product galleries, cart functionality, and responsive design
- **McMaster-Carr Inspired Design**: Clean, professional layout inspired by industrial e-commerce leaders
- **Responsive Design**: Mobile-first approach ensuring perfect display on all devices
- **Performance Optimized**: Lightweight code and optimized asset loading
- **SEO Friendly**: Semantic HTML structure and proper heading hierarchy
- **Accessibility Ready**: WCAG compliant with screen reader support
- **Customizer Integration**: Easy customization through WordPress Customizer
- **Widget Areas**: Multiple widget areas including sidebar and footer sections

## Requirements

- WordPress 6.0 or higher
- PHP 8.0 or higher
- WooCommerce 7.0 or higher (for e-commerce features)

## Installation

1. Upload the theme files to your `/wp-content/themes/` directory
2. Activate the theme through the WordPress admin
3. Install and activate WooCommerce plugin
4. Configure theme settings in Appearance > Customize

## Theme Structure

```
mcmaster-wc-theme/
├── assets/
│   ├── css/
│   │   └── custom.css
│   ├── js/
│   │   └── main.js
│   └── images/
├── style.css
├── functions.php
├── index.php
├── header.php
├── footer.php
├── sidebar.php
├── archive.php
├── single.php
├── page.php
├── searchform.php
└── README.md
```

## Key Template Files

- **style.css**: Main stylesheet with theme header and base styles
- **functions.php**: Theme setup, WooCommerce support, and custom functionality
- **header.php**: Header template with navigation and search
- **footer.php**: Footer template with widgets and newsletter signup
- **index.php**: Main template file for blog posts
- **archive.php**: Archive page template with grid/list view options
- **single.php**: Single post template with social sharing
- **page.php**: Static page template
- **sidebar.php**: Sidebar widget area
- **searchform.php**: Custom search form template

## Customization Options

### Theme Customizer Settings

1. **Header Settings**
   - Header top bar promotional text
   - Custom logo upload

2. **Footer Settings**
   - Copyright text customization

### Widget Areas

- **Sidebar**: Main sidebar widget area
- **Footer Area 1**: First footer column
- **Footer Area 2**: Second footer column
- **Footer Area 3**: Third footer column

### Navigation Menus

- **Primary Menu**: Main navigation in header
- **Footer Menu**: Footer navigation links

## WooCommerce Integration

The theme includes comprehensive WooCommerce support:

- Product grid layouts (2, 3, 4 columns)
- Product gallery with zoom and lightbox
- Cart icon with live count updates
- Responsive product layouts
- Custom WooCommerce styling

## JavaScript Features

- Mobile menu toggle
- Archive view switching (grid/list)
- Smooth scrolling for anchor links
- AJAX cart count updates
- Search form enhancements
- Newsletter form handling
- Notification system

## Browser Support

- Chrome (latest 2 versions)
- Firefox (latest 2 versions)
- Safari (latest 2 versions)
- Edge (latest 2 versions)

## Performance Features

- Optimized CSS and JavaScript loading
- Responsive images
- Minimal HTTP requests
- Clean, semantic HTML
- Efficient asset organization

## Development

### Build Process

No build process required - theme uses standard WordPress enqueuing for CSS and JavaScript.

### Coding Standards

- WordPress Coding Standards
- PSR-4 autoloading for PHP classes
- ESLint for JavaScript
- Semantic HTML5 markup

## Changelog

### Version 1.0.0
- Initial release
- WooCommerce integration
- Responsive design
- McMaster-Carr inspired layout
- Widget areas and customizer options
- Performance optimizations

## Support

For theme support and documentation, please refer to the WordPress documentation or contact the theme developer.

## License

This theme is licensed under the GPL v2 or later license.

## Credits

- Inspired by McMaster-Carr's design philosophy
- Icons from Material Design Icons
- Built with WordPress best practices