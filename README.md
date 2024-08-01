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

- **PHP:** Version 5.6 or higher
- **WordPress:** Version 5.6 or higher

Ensure that your hosting environment meets these requirements before installing the plugin.

## Installation

1. Upload the plugin files to the `/wp-content/plugins/wpg-quick-ajax-post-loader` directory, or install the plugin through the WordPress plugins screen directly.
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

### 1.1 - 2024-06-06
- Improved Template File Overriding Hierarchy: Updated the mechanism for searching and merging template files to ensure that files from the child theme take precedence over those from the parent theme and the plugin. Introduced a system for mapping file names to their paths to retain only the most recent versions.
- Optimized Template File Search: Enhanced the `find_template_files` function to use `glob` for more efficient searching of template files matching specific patterns.
- Adopted Recommended Functions for JSON Data Handling: Replaced direct calls to `json_encode` with `wp_json_encode` in accordance with WordPress standards, improving the compatibility and security of JSON data.
- Safer File Operations: Replaced the use of `fopen` with the WordPress Filesystem API (`WP_Filesystem`) for file operations, ensuring greater compatibility with different hosting configurations and enhancing security.
- Improved Output Security: Secured all outputs to prevent potential XSS attacks.
- Optimized Code Structure: Enhanced code performance and maintainability through various optimizations.
- Improved Security Measures: Strengthened security protocols and addressed vulnerabilities to enhance overall code safety.
- Support for New Shortcode Attributes: Added support for additional shortcode parameters to increase flexibility and customization.
- Added 'Ignore Sticky Posts' Option: Introduced a new option to the plugin settings that allows users to ignore sticky posts in the WP_Query.

### 1.0.1 - 2024-03-20
- Fixed the loader icon bug.
- Improved translations for the Polish language.

### 1.0 - 2024-03-13
- Initial release.

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

- [GitHub Repository](https://github.com/grzelkowski/wpg-quick-ajax-post-loader/)

## Privacy Policy

Quick Ajax Post Loader does not collect or store any user data.
