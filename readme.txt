=== Quick Ajax Post Loader ===

Contributors: grzelkowski
Tags: ajax-load-more, infinite-scroll, category-filter, load-more, ajax
Requires at least: 5.6
Tested up to: 6.7.2
Stable tag: 1.6.3
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Load and display WordPress posts dynamically using AJAX for faster, interactive browsing - no page reloads.

== Description ==

**Quick Ajax Post Loader** is a powerful yet lightweight plugin for WordPress that helps you create dynamic, interactive post grids using AJAX - no page reloads required!

Display posts, pages, or custom post types (CPT) with full AJAX filtering, sorting, and pagination. Whether you're building a blog, a product catalog, a portfolio, or a directory - this plugin will give your users a seamless browsing experience.

Includes both a "Load More" button and infinite scroll option for maximum flexibility.

Boost your site's UX and performance with AJAX!

**[View the full developer guide on GitHub](https://github.com/grzelkowski/quick-ajax-post-loader/blob/main/DEVELOPER_GUIDE.md)**.

== Why choose Quick Ajax Post Loader? ==

Unlike other plugins that focus on one feature (e.g., just infinite scroll), Quick Ajax Post Loader offers a complete solution:

* AJAX-based loading for posts, pages, or CPT (no page reloads)
* Powerful taxonomy filtering (categories, tags, or custom taxonomies)
* Customizable "Load More" button OR smooth infinite scroll
* Sort posts by date, title, comment count, or random
* Fully responsive post grids with column control
* Built-in shortcode generator and PHP function generator
* Compatible with any theme (customizable templates included)
* Developer-friendly with actions, filters, and overridable templates
* Optimized for performance and SEO

= Designed for: =
- Blogs & News websites
- Portfolios & creative showcases
- WooCommerce product grids (custom post types)
- Directories, listings, and content catalogs

== Features in detail ==

* Load posts dynamically with AJAX (without reloading the page)
* Supports multiple post types: posts, pages, and CPT
* AJAX taxonomy filter (categories, tags, or custom taxonomies)
* AJAX sort dropdown (by date, title, comments, random)
* Load More button with customizable label
* Infinite Scroll option for automatic loading
* Customize post grids: number of columns, item templates, CSS classes
* Override templates directly from your theme or child theme
* Control which posts to exclude from results (by post ID)
* Compatible with sticky posts (optionally ignore them)
* Built-in Function Generator (for theme integration)
* Built-in Shortcode Generator (for easy use in pages or posts)
* Lightweight codebase optimized for speed
* Follows WordPress coding standards and best practices

== How it works ==

1. Install and activate Quick Ajax Post Loader.
2. Go to **Quick AJAX > Shortcodes > Add New** in the WordPress admin.
3. Configure your post grid (select post type, filters, sort options, layout).
4. Copy the generated shortcode and paste it into any page or post.
5. Enjoy AJAX-powered filtering, sorting, and infinite scroll!

== Installation ==

= Downloading the Plugin =
To install **Quick Ajax Post Loader**, download the latest version of the plugin from the WordPress repository and install it using one of the following methods:

= Installing via the WordPress Admin Panel =
1. Log in to your WordPress dashboard and go to **Plugins > Add New**.
2. Search for **Quick Ajax Post Loader** or upload the ZIP file downloaded from the repository.
3. Click **Install Now**, then **Activate**.

#### Manual Installation via FTP
1. Extract the downloaded ZIP file.
2. Upload the entire **quick-ajax-post-loader** folder to the `wp-content/plugins/` directory on your server.
3. In the WordPress admin panel, go to **Plugins > Installed Plugins** and click **Activate** next to **Quick Ajax Post Loader**.

= Activation and First Steps =
After activating the plugin, a new **Quick Ajax** menu item will appear in the WordPress admin panel, where you can configure settings and shortcodes.

== Frequently Asked Questions ==

= Does it work with custom post types? =
Yes, you can display posts, pages, and custom post types (CPT).

= Can I enable infinite scroll instead of "Load More"? =
Yes, infinite scroll is available and can be toggled per shortcode.

= Can I customize post templates? =
Yes, you can override templates directly from your theme's `/quick-ajax-post-loader/templates/` folder.

= Does it support multiple shortcodes on one page? =
Yes, you can create multiple AJAX grids with different configurations.

= Can I extend the plugin via hooks and filters? =
Yes, developers can use available WordPress hooks and filters to fully customize functionality.

== Changelog ==

= 1.6.3 - 2025-04-09 =
- Added a new option to display an "End Message" when there are no more posts to load via AJAX.
  - The message can now be set via a global option in the plugin settings, customized in the template, or controlled using a filter hook for full flexibility.
- Improved CSS styling to ensure better compatibility with a wider range of WordPress themes.
- Updated Polish translations for improved clarity and consistency.

= 1.6.2 - 2025-03-30 =
- This version was released to resolve a problem with the WordPress.org system not generating a ZIP package for version 1.6.1.
- There are no changes in code or functionality - this version is identical to 1.6.1.

= 1.6.1 - 2025-03-30 =
- Improved CSS styles for better appearance and theme compatibility.
- Fixed display issues to ensure everything looks as expected.
- Applied minor code tweaks to improve stability and performance.

= 1.6.0 - 2025-03-19 =
- Introduced a new Infinite Scroll feature, allowing posts to load automatically as users scroll down, improving user experience and engagement.
- Added a fully updated and more user-friendly Help section within the plugin settings for easier navigation and guidance.
- Released a new, improved Development Guide to assist developers in customizing and extending the plugin more efficiently.
- Refined grid containers and elements, resulting in better structure and layout consistency across different themes.
- Applied multiple CSS improvements to enhance responsiveness and compatibility with a wider range of WordPress themes.
- General code enhancements and minor bug fixes to improve plugin stability and performance.

= 1.5.0 - 2025-02-25 =
- Added a new sorting button option, allowing users to change the post order based on predefined sorting options.
- Added global options to define labels for each sorting method, providing better customization.
- Removed the ability to change `post_status` in shortcode settings.
  - WordPress by default displays only published (`publish`) posts to users, making this option unnecessary.
  - The plugin now supports only `publish` posts, removing redundant settings.
  - Developers can still modify `post_status` using the `qapl_modify_posts_query_args` hook.
- Optimized the `orderby` options list, removing rarely used sorting values: `none`, `ID`, `author`, `name`, `modified`, `parent`, `menu_order`.
  - These can still be implemented using the `qapl_modify_posts_query_args` hook.
- Applied CSS improvements to enhance compatibility with different WordPress themes.
- Fixed minor bugs to improve overall stability and performance.
- Additional optimizations and security enhancements to ensure better reliability and safety.

= 1.4.1 - 2025-02-14 =
- Tested and confirmed full compatibility with WordPress 6.7.2.

= 1.4.0 - 2025-02-10 =
- Added new template filters for customizing post elements. Developers can now modify the post date, image, title, excerpt, read more button, and load more button via filters, allowing greater flexibility in template customization.
- Standardized action and filter names for better consistency and readability.
- Introduced a backward compatibility layer for deprecated hooks to ensure smooth transition.
  **Note:** Old hooks remain functional, but updating your customizations to use the new hook names is highly recommended for long-term stability.
- Standardized function naming conventions and introduced alias functions to maintain backward compatibility while improving code clarity.
- Improved CSS structure for better compatibility with different WordPress themes.
- Simplified class names in the template system, making customization and integration easier.
- General code refactoring to improve maintainability, readability, and performance.

= 1.3.10 - 2025-02-06 =
- Added a new template: "Full Background Image Post Template" for enhanced post display.
- Improved CSS compatibility by adding missing vendor prefixes (-webkit-box-pack, -ms-flex-pack, justify-content, etc.).
- Refined CSS handling in the AJAX container, ensuring styles apply correctly when the "Apply Quick AJAX CSS Style" option is enabled. This improves theme compatibility and layout consistency.
- Optimized and cleaned up CSS styles for better responsiveness across different themes.
- General code improvements and performance optimizations for smoother execution.

= 1.3.9 - 2025-01-19 =
- Added support for loading the initial set of posts via AJAX on page load, improving compatibility with caching systems and ensuring fresh content display.
- Enhanced the "Load More" button functionality, allowing it to load a different number of posts than specified for the initial page load.
- Updated CSS transitions for smoother and more visually appealing effects.
- Improved translation support and ensured compatibility with WordPress translation standards.
- General performance improvements and minor bug fixes.

= 1.3.8 - 2025-01-09 =
- Added new global options for customizing button labels:
  Read More: Customize the "Read More" button text for your templates.
  Show All: Customize the label for the "Show All" button, which displays all posts without taxonomy filtering.
  Load More: Customize the label for the "Load More" button, used to load additional posts.
- Updated the default template to enhance design and usability.
- Improved code maintainability by reducing redundancy and optimizing helper methods.

= 1.3.7 - 2025-01-02 =
- Improved JavaScript minification and optimized the code for better performance.
- Enhanced CSS compatibility across different browsers by adding automatic prefixes.
- Improved CSS minification for smaller file sizes and faster loading times.
- Added support for loading -dev versions of CSS and JS files when the URL includes ?dev=true or ?dev=1 (available only in the GitHub version with -dev files, as the public release on WordPress.org excludes these files to maintain a streamlined production package).
- Improved error handling and overall plugin reliability.
- Enhanced security measures for safer handling of content.

= 1.3.6 - 2024-12-05 =
- Improved the appearance of the shortcode box in the post editor to make it more user-friendly.
- Enhanced the plugin's performance and stability for a smoother experience.

= 1.3.5 - 2024-12-01 =
- Fixed an issue with taxonomy handling for post types.
- Added support for cases where no taxonomy is assigned, ensuring the plugin works smoothly.
- Performed additional testing and applied minor fixes to improve stability and user experience.

= 1.3.4 - 2024-11-29 =
- Improved the activation process to ensure default options are correctly set.
- Fixed an issue where some default settings were not applied during activation.
- Introduced enhancements to improve the plugin's stability and reliability.

= 1.3.3 - 2024-11-27 =
- Improved ARIA compliance and accessibility for tab navigation in the settings page.
- Improved the update process for smoother upgrades to newer versions of the plugin.
- Added a new option to remove old data used by previous versions, keeping your site clean and optimized.
- Fixed an issue where the loader icon was not displaying correctly in some configurations.

= 1.3.2 - 2024-11-13 =
- Improved `post_meta` handling for better shortcode management.
- Fixed an issue where shortcodes were not visible on the shortcode management page.
- Enhanced data migration for better backward compatibility.

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

= 1.6.0 =
This update introduces an Infinite Scroll feature and an improved Help section within the plugin settings.

**Important:**
- A new Development Guide has been added to assist developers with customization and integration.
- The grid structure has been refined, and additional CSS improvements have been made for better responsiveness and theme compatibility.

= 1.5.0 =
Introduces a new sorting button feature and global sorting label options.

**Important:**
- The plugin now only supports posts with `publish` status, removing unnecessary settings.
- Unused `orderby` options have been removed but can still be re-added using the `qapl_modify_posts_query_args` hook.

Recommended update for improved sorting control and better plugin stability.

= 1.4.0 =
This update introduces new template filters, allowing developers to modify post elements (date, title, image, excerpt, and buttons) using filters.

Additionally, action and filter names have been standardized, and a backward compatibility layer has been implemented for deprecated hooks.
**Important:** If you rely on existing hooks, review your customizations and update them to use the new standardized names for long-term stability.

= 1.3.9 =
Adds support for loading the initial set of posts via AJAX, improving caching compatibility and ensuring fresh content. Enhances "Load More" button functionality for greater flexibility.

= 1.3.5 =
Fixes taxonomy handling issues and adds support for cases where no taxonomy is assigned. Includes minor fixes and stability improvements. Recommended for better functionality.

= 1.3.3 =
Enhances accessibility, adds options for managing old data, and fixes loader icon display issues. Recommended for improved user experience and site management.

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

- [Developer Guide](https://github.com/grzelkowski/quick-ajax-post-loader/blob/main/DEVELOPER_GUIDE.md)  
  A comprehensive guide for developers to leverage hooks, filters, and custom templates.

- [GitHub Repository](https://github.com/grzelkowski/quick-ajax-post-loader/)  
  Access the plugin's source code, contribute to its development, or report issues.

- [Support Forum](https://wordpress.org/support/plugin/quick-ajax-post-loader)  
  Get help, ask questions, or report bugs related to the plugin.

== Privacy Policy ==

Quick Ajax Post Loader does not collect or store any user data.

== Credits ==

Developed by Pawel Grzelkowski.