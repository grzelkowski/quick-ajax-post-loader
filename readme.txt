=== Quick Ajax Post Loader ===
Contributors: grzelkowski
Tags: ajax, dynamic, custom post type, content display, post
Requires at least: 5.6
Tested up to: 6.6.1
Stable tag: 1.2
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Boost WordPress performance with Quick Ajax Post Loader. Use AJAX to dynamically display posts, enhancing site speed and user experience.

== Description ==

Quick Ajax Post Loader for WordPress leverages AJAX technology to load content dynamically, offering users a seamless browsing experience without interruptions from page reloads. This powerful plugin is packed with features such as generating shortcodes for displaying posts, creating custom post templates, and customizing loading icons. By enabling dynamic post display, it significantly enhances site performance and user engagement, making your WordPress site more responsive and engaging to user interactions.
== Features ==

- Generating Shortcodes for Displaying Posts: Easily create shortcodes to dynamically display posts using AJAX.
- Creating and Using Custom Post Templates: Personalize how dynamically loaded content appears.
- Customizing Loading Icons: Customize the loading icons with your own designs.
- Customizing the Taxonomy Filter Button: Tailor the taxonomy filter button to seamlessly integrate with your site’s design.
- AJAX Function Generator Tool: Directly generate PHP code for dynamic content display.
- Flexible Configuration: Customize AJAX queries and the appearance of the post grid.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/quick-ajax-post-loader` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the plugin's settings page to configure and customize according to your needs.

== Frequently Asked Questions ==

= Can I use this plugin with any WordPress theme? =
Yes, Quick Ajax Post Loader is designed to be compatible with most WordPress themes.

= Is it possible to create multiple shortcodes for various content types? =
Yes, you can generate as many shortcodes as needed for different post types or configurations.

== Changelog ==

= 1.2.1 - 2024-10-21
- Fixing issue with text domain name
- Removed closing PHP tags from all PHP files to comply with PSR-2 standards.

= 1.2 - 2024-10-20
- Update recommended: This version introduces significant security enhancements, code optimizations, and prefix changes to prevent compatibility issues.

- Eliminated use of HEREDOC and NOWDOC syntax to enhance compatibility and security.
- Sanitized and validated all user inputs including GET, POST, REQUEST, and FILE calls.
- Improved nonce verification to ensure safer handling by using wp_unslash() and sanitize_text_field().
- Updated function, class, and define names to use unique prefixes (qapl_) to prevent conflicts.
- Added checks to prevent direct file access by adding if ( ! defined( 'ABSPATH' ) ) exit; in all PHP files.
- Optimized several functions for better performance, reducing redundant database queries and improving load times.
- Updated hooks and actions to use properly namespaced prefixes to avoid conflicts.
- Removed redundant code blocks and enhanced logic for improved maintainability.
- Fixed minor bugs related to form submission and data handling.

= 1.1.1 - 2024-08-02 =
- Optimized script loading to only load `quick-ajax-script` on the frontend, improving performance and avoiding unnecessary loading in the admin area.

= 1.1 - 2024-08-01 =
- Improved Template File Overriding Hierarchy: Updated the mechanism for searching and merging template files to ensure that files from the child theme take precedence over those from the parent theme and the plugin. Introduced a system for mapping file names to their paths to retain only the most recent versions.
- Optimized Template File Search: Enhanced the find_template_files function to use glob for more efficient searching of template files matching specific patterns.
- Adopted Recommended Functions for JSON Data Handling: Replaced direct calls to json_encode with wp_json_encode in accordance with WordPress standards, improving the compatibility and security of JSON data.
- Safer File Operations: Replaced the use of fopen with the WordPress Filesystem API (WP_Filesystem) for file operations, ensuring greater compatibility with different hosting configurations and enhancing security.
- Improved Output Security: Secured all outputs to prevent potential XSS attacks.
- Optimized Code Structure: Enhanced code performance and maintainability through various optimizations.
- Improved Security Measures: Strengthened security protocols and addressed vulnerabilities to enhance overall code safety.
- Support for New Shortcode Attributes: Added support for additional shortcode parameters to increase flexibility and customization.
- Added 'Ignore Sticky Posts' Option: Introduced a new option to the plugin settings that allows users to ignore sticky posts in the WP_Query.

= 1.0.1 - 2024-03-20 =
- Fixed the loader icon bug.
- Improved translations for the Polish language.

= 1.0 - 2024-03-13 =
- Initial release.

== Upgrade Notice ==

= 1.1 =
Update recommended: Implements improved template file overriding hierarchy, optimized template file search, adopted recommended functions for JSON data handling, safer file operations, enhanced output security, and new shortcode attributes support. Released on 2024-06-06.

= 1.0.1 =
Update recommended: Fixes the loader icon bug and improves Polish translations. Released on 2024-03-20.

= 1.0 =
Welcome to the first release of the plugin! Enjoy the new features and improvements. Released on 2024-03-13.

== Additional Links ==

- GitHub Repository: https://github.com/grzelkowski/quick-ajax-post-loader/

== Privacy Policy ==

Quick Ajax Post Loader does not collect or store any user data.

== Credits ==

Developed by Pawel Grzelkowski.
