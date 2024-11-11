=== Quick Ajax Post Loader ===
Contributors: grzelkowski
Tags: ajax, load-more, ajax-posts, dynamic, category-filter
Requires at least: 5.6
Tested up to: 6.6.2
Stable tag: 1.3.1
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Load and display WordPress posts dynamically using AJAX for faster, interactive browsing – no page reloads.

== Description ==

**Quick Ajax Post Loader** is designed for users who need an easy way to display posts using AJAX on WordPress without coding. Customize layouts, filter posts by taxonomy, and create personalized post templates with ease. This plugin allows you to dynamically load posts without refreshing the page, ensuring a smooth and engaging user experience. Whether you're showcasing blog posts, portfolios, or custom post types, Quick Ajax Post Loader simplifies the process of implementing AJAX functionality.


**Why use Quick Ajax Post Loader?**
- **Show Posts Without Page Reloads**: Perfect for displaying posts with AJAX to improve user navigation and site interactivity.
- **Easy to Use**: Generate and use shortcodes to display posts dynamically, even if you don't have coding skills.
- **Flexible Customization**: Adapt the display with options like grid columns, sorting, and custom templates.
- **Enhanced User Engagement**: Keep visitors on your site longer with seamless content loading.
- **Optimized for Speed**: Load only what’s needed, making your site faster and more efficient.

**Main features**:
- Generate shortcodes for AJAX post loading with custom configurations.
- Filter posts by taxonomy, such as categories or tags, with clickable buttons.
- Customize templates for post display, category buttons, load more buttons, and loading icons.
- Configure the display grid with up to 12 columns and add custom CSS classes for advanced styling.
- Built-in AJAX Function Generator for easy PHP integration.

== Features ==

- **Dynamic Shortcodes**: Easily create shortcodes to display posts using AJAX.
- **Custom Templates**: Personalize post items, buttons, and loading icons.
- **Flexible Taxonomy Filtering**: Display taxonomies as buttons to filter posts, such as categories for custom post types (e.g., articles, projects).
- **Customizable Grid Layouts**: Set the number of columns from 1 to 12 and apply custom CSS classes for containers.
- **Advanced Sorting Options**: Adjust query parameters such as sorting by name, date, and more.

== Installation ==

1. **Upload** the plugin to the `/wp-content/plugins/quick-ajax-post-loader` directory or install it via the WordPress Plugins screen.
2. **Activate** the plugin through the 'Plugins' screen.
3. **Customize** settings using the plugin’s options page and create shortcodes as needed.

== Frequently Asked Questions ==

= Can I filter posts by categories or tags? =
Yes, the plugin supports taxonomy filtering through clickable buttons that allow users to filter posts by categories or tags.

= How can I display WordPress posts using AJAX? =
Quick Ajax Post Loader makes it easy to display posts using AJAX. Simply generate a shortcode and insert it into your page or post to load content dynamically without reloading the page.

= Can I create my own post templates? =
Yes, you can create custom templates for post items, loading icons, and category buttons.

= Is coding required to use this plugin? =
No, you can use the built-in shortcode generator for easy implementation without coding. For advanced users, the plugin also includes an AJAX Function Generator.

= Does the plugin include default styles? =
Yes, it includes a default grid style (up to 12 columns) that can be disabled if full customization is desired.

== Changelog ==

= 1.3.1 - 2024-11-11 =
- Resolved an issue where custom post types (CPT) were sometimes not selectable in the settings.
- Fixed minor bugs and enhanced overall stability.

= 1.3.0 - 2024-11-09 =
- Added support for automatic plugin updates from the WordPress repository.
- Refactored code structure for better maintainability and performance.
- Strengthened security with stricter input validation and nonce verification.
- Optimized asset loading for faster page performance.
- Fixed several minor bugs and improved compatibility with different WordPress themes.

= 1.2.1 - 2024-10-21 =
- Corrected an issue with the text domain for better localization support.
- Removed closing PHP tags in all files to comply with PSR-2 standards and prevent unexpected output.

= 1.2 - 2024-10-20 =
- Implemented major security enhancements, including input sanitization and validation.
- Optimized query handling for improved database performance.

= 1.1.1 - 2024-08-02 =
- Optimized script loading to ensure `quick-ajax-script` loads only on the frontend, improving performance and reducing backend load.

= 1.1 - 2024-08-01 =
- Enhanced template file handling to support safer file operations and better template hierarchy.
- Added new shortcode attributes for increased flexibility.
- Introduced an 'Ignore Sticky Posts' option in the settings.

= 1.0.1 - 2024-03-20 =
- Fixed a loader icon display issue.
- Improved Polish language translations.

= 1.0 - 2024-03-13 =
- Initial release.

== Upgrade Notice ==

= 1.3.0 =
This update includes new features like automatic plugin updates and important security enhancements. Update recommended.

= 1.2 =
Includes critical security updates and performance improvements. Strongly recommended to update.

= 1.1 =
Major update introducing new template handling and security features. Update is highly encouraged.

= 1.0.1 =
Fixes a loader icon issue and updates Polish translations. Recommended for users affected by these issues.

= 1.0 =
Initial release. Enjoy the full set of features and improvements.

== Additional Links ==

- [GitHub Repository](https://github.com/grzelkowski/quick-ajax-post-loader/)

== Privacy Policy ==

Quick Ajax Post Loader does not collect or store any user data.

== Credits ==

Developed by Pawel Grzelkowski.
