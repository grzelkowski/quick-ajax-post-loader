# Quick Ajax Post Loader

Boost WordPress performance with Quick Ajax Post Loader. Use AJAX to dynamically display posts, enhancing site speed and user experience.

## Description

Quick Ajax Post Loader for WordPress leverages AJAX technology to load content dynamically, offering users a seamless browsing experience without interruptions from page reloads. This powerful plugin is packed with features such as generating shortcodes for displaying posts, creating custom post templates, and customizing loading icons. By enabling dynamic post display, it significantly enhances site performance and user engagement, making your WordPress site more responsive and engaging to user interactions.

## Features

- **Generating Shortcodes for Displaying Posts:** Easily create shortcodes to dynamically display posts using AJAX, improving content accessibility and interaction.
- **Creating and Using Custom Post Templates:** Personalize how dynamically loaded content appears by using your own post templates.
- **Customizing Loading Icons:** Customize the loading icons with your own designs for a unique and engaging user interface.
- **Customizing the Taxonomy Filter Button:** Tailor the taxonomy filter button to seamlessly integrate with your site’s design.
- **AJAX Function Generator Tool:** Directly generate PHP code for dynamic content display, allowing for integration into your WordPress theme files.
- **Flexible Configuration:** A wide range of parameters and attributes are available for customizing AJAX queries and the appearance of the post grid, providing flexibility in how content is displayed.

## Minimum Requirements

- **PHP:** Version 7.4 or higher
- **WordPress:** Version 5.6 or higher

Ensure that your hosting environment meets these requirements before installing the plugin.

## Installation

1. Upload the plugin files to the `/wp-content/plugins/quick-ajax-post-loader` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the plugin's settings page to configure and customize according to your needs.

## Usage

### Shortcodes

From the WordPress admin panel, navigate to `Quick Ajax -> Shortcodes` or `Add New` to generate shortcodes. Place these shortcodes anywhere on your site to start displaying dynamic post content.

### AJAX Function Generator

Access the AJAX Function Generator under the `Quick Ajax > Settings & Features` menu. This tool creates PHP code for dynamic content display, which can be included directly in your WordPress theme's template files.

## Frequently Asked Questions

**Q: Can I use this plugin with any WordPress theme?**  
A: Yes, Quick Ajax Post Loader is designed to be compatible with most WordPress themes.

**Q: Is it possible to create multiple shortcodes for various content types?**  
A: Yes, you can generate as many shortcodes as needed for different post types or configurations, enhancing your site's flexibility and content presentation.

## Changelog

### 1.3.0 - 2024-11-08
- Added support for automated plugin updates directly from the WordPress repository.
- Improved code structure and organization for better maintainability and performance.
- Enhanced security with stricter input validation and nonce verification.
- Optimized asset loading for improved performance.
- Fixed various minor bugs and compatibility issues.

### 1.2.1 - 2024-10-21
- Fixed issue with text domain.
- Removed closing PHP tags from all PHP files to comply with PSR-2 standards.

### 1.2 - 2024-10-20
- Eliminated use of HEREDOC and NOWDOC syntax to enhance compatibility and security.
- Sanitized and validated all user inputs including GET, POST, REQUEST, and FILE calls.
- Improved nonce verification to ensure safer handling by using wp_unslash() and sanitize_text_field().
- Updated function, class, and define names to use unique prefixes (qapl_) to prevent conflicts.
- Added checks to prevent direct file access by adding if ( ! defined( 'ABSPATH' ) ) exit; in all PHP files.
- Optimized several functions for better performance, reducing redundant database queries and improving load times.
- Updated hooks and actions to use properly namespaced prefixes to avoid conflicts.
- Removed redundant code blocks and enhanced logic for improved maintainability.
- Fixed minor bugs related to form submission and data handling.

### 1.1.1 - 2024-08-02
- Optimized script loading to only load `quick-ajax-script` on the frontend, improving performance and avoiding unnecessary loading in the admin area.

### 1.1 - 2024-08-01
- Improved Template File Overriding Hierarchy: Ensured child theme templates take precedence over parent themes and plugins.
- Enhanced template search with `glob` for better efficiency.
- Replaced `json_encode` with `wp_json_encode` for improved compatibility.
- Replaced `fopen` with `WP_Filesystem` for safer file operations.
- Secured all output with functions like `esc_html` and `wp_kses`.
- Added new shortcode attributes for flexibility (`post_type`, `posts_per_page`, `order`, `orderby`, `post_status`).
- Introduced 'Ignore Sticky Posts' option.
- Various security improvements and bug fixes.

### 1.0.1 - 2024-03-20
- Fixed the loader icon bug.
- Improved Polish translations.

### 1.0 - 2024-03-13
- Initial release.

### Upgrade Notice

### 1.3.0
This update introduces automated updates, significant code improvements, and enhanced security measures. Updating is recommended to benefit from these enhancements.

### 1.2
Significant security and performance improvements. Updating is highly recommended.

### 1.1
Major update with template handling and security enhancements. Strongly recommended to update.

### 1.0.1
Fixes loader icon bug and improves translations. Recommended for update.

### 1.0
Initial release. Enjoy new features and improvements.

## Available Languages

Quick Ajax Post Loader currently supports the following languages:
- English (original)
- Polish (additional translation added)

## License

This plugin is licensed under the GPLv2 or later.  
**License URI:** [GPLv2 License](https://www.gnu.org/licenses/gpl-2.0.html)

## Credits

Developed by Pawel Grzelkowski.

## Additional Links

- [GitHub Repository](https://github.com/grzelkowski/quick-ajax-post-loader/)

## Privacy Policy

Quick Ajax Post Loader does not collect or store any user data.
