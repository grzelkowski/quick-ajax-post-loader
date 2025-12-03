## Changelog

### 1.8.6 - 2025-12-03
- Improved how multiple AJAX containers work together on the same page to ensure they stay independent and conflict-free.
- Enhanced consistency between sorting, filtering, and post loading for a smoother user experience.
- Optimized internal AJAX handling for better stability across different setups.
- Added small internal cleanups to improve overall reliability and maintainability.
- Tested and confirmed compatibility with WordPress 6.9.

### 1.8.5 - 2025-11-20
- Refactored internal AJAX rendering logic for improved structure and maintainability.
- Improved internal code organization for better stability and future development.
- Applied backend optimizations to reduce redundancy and improve overall performance.
- Performed minor cleanups across related core classes.

### 1.8.4 - 2025-10-23
- Fixed issue with multiple initial post loaders not triggering correctly on the same page.
- Improved label display for sorting options in certain configurations.
- Improved handling of active taxonomy filters on first page load for better user experience.
- Optimized AJAX handling and button rendering logic for better frontend performance.
- Updated translations for improved localization support.
- Minor code cleanups and stability improvements across core classes.

### 1.8.3 - 2025-09-29
- Added sorting support for the Shortcode column in the shortcode list.
- Improved shortcode editor form for more stable field initialization and saving.
- Cleaned up labels and descriptions for better clarity.
- Fixed minor admin warnings and improved overall stability.
- Fixed issue where the Load More button could disappear too early when using custom post limits.

### 1.8.2 - 2025-09-08
- Fixed an issue in the plugin activator that could prevent the plugin from starting on some setups.

### 1.8.1 - 2025-09-08
- Improved plugin startup process with better class loading and dependency checks.
- Improved the way plugin scripts and styles are loaded for better reliability.
- Enhanced plugin stability by adding internal class verification before startup.
- Introduced internal logger with fallback to WordPress debug log.
- Cleaned up internal utility functions to support better error handling and production readiness.

### 1.8.0 - 2025-08-29
- Major internal refactoring to improve plugin stability and long-term reliability.
- Fixed several issues in the Function Generator for more consistent output.
- Corrected minor CSS and JavaScript bugs to improve layout and functionality.
- Optimized performance across both frontend and admin interface.
- Improved compatibility with the latest WordPress versions.

**Note:**
- Most changes are behind the scenes. You may notice smoother performance and fewer issues, while the plugin remains fully compatible with your setup.

### 1.7.7 - 2025-08-01
- Improved CSS for better layout adaptability and responsiveness across various screen sizes.
- Enhanced the admin interface styling for more consistent alignment and spacing in the Function Generator.
- Optimized admin scripts to improve loading performance and reduce unnecessary processing.
- Refactored frontend JavaScript to simplify the codebase and improve maintainability.

**Note:**
- Minor visual differences may occur in some themes if you rely on the plugin's default styling. Please review your layout after updating.

### 1.7.6 - 2025-07-15
- Refactored the Function Generator module to produce cleaner and more consistent PHP output.
- Enhanced the shortcode generation process for better formatting and improved reliability.
- Updated admin area CSS to improve layout consistency, spacing, and alignment within the Function Generator.

### 1.7.5 - 2025-07-04
- Resolved post visibility issues that occurred in specific configurations when using taxonomy filter buttons.
- Improved layout stability by preserving the container height during AJAX transitions.
- Refined CSS styling for smoother reloads and better consistency with various themes.
- Enhanced support for using multiple AJAX containers on a single page.
- Fixed an issue where Polish translation files were not loading correctly in some environments.


### 1.7.4 - 2025-07-02
- Updated CSS styles for improved layout consistency and better theme compatibility.
- Improved default labels for title sorting options to make them clearer and more user-friendly.
- Updated several field labels in the plugin settings for better clarity and understanding.
- Applied internal code improvements focused on security, stability, and maintainability.

**Important:**
- Some styling or label changes may slightly affect the appearance of your filters or UI if you use default settings.

### 1.7.3 - 2025-05-31
- Updated CSS to improve the responsive grid layout and visual consistency.
- Improved internal logic for cleaner structure and better maintainability.
- Updated the image output to use `get_the_post_thumbnail()` for better responsiveness and maintainability, and ensured alt tags are properly set for accessibility and SEO.
- Post images now use the `large` image size by default - this can be changed in the WordPress Media settings to better suit your layout.

**Important:**
- These style updates may slightly change the visual presentation of posts if you use the plugin's default styling.

### 1.7.2 - 2025-05-18
- Fixed an issue where multiple custom CSS classes added to the post container were not handled correctly when separated by commas or spaces.
- Minor internal improvements for better code reliability.

### 1.7.1 - 2025-05-10
- Enhanced taxonomy filter in shortcode settings - saved terms are now automatically pre-selected when editing.
- The "Load More" button now passes only essential parameters, ensuring cleaner functionality.
- Improved compatibility with the latest PHP versions, ensuring smooth operation without deprecated warnings.
- Applied minor code optimizations for better performance.

### 1.7.0 - 2025-05-05
- Added support for manually selecting specific taxonomy terms in the shortcode or PHP function settings.
- Improved loader icon system with support for dark variants and better visual integration.
- Updated labels and structure of filter and loader buttons for clarity and consistency.
- Updated the `qapl_render_taxonomy_filter()` PHP function - the `$taxonomy` parameter is now optional (still supported for backward compatibility).
- Optimized internal plugin code for better performance, readability, and long-term maintainability.
- Enhanced shortcode parameter handling and security validation to prevent conflicts and unexpected behavior.
- Fixed several minor styling issues to improve compatibility with custom themes.

### 1.6.4 - 2025-04-16
- Added a new global option to define a custom "No Posts Found" message.
- Updated the "No Posts Found" template and added the `qapl_template_no_post_message` filter hook to allow developers to customize the message.
- Fixed an issue where the loader icon did not appear correctly in some configurations.
- Fixed problems with text labels not displaying as expected.
- Tested and confirmed compatibility with WordPress 6.8.

### 1.6.3 - 2025-04-09
- Added a new option to display an "End Message" when there are no more posts to load via AJAX.
  - The message can now be set via a global option in the plugin settings, customized in the template, or modified using the `qapl_template_end_post_message` filter hook for full flexibility.
- Improved CSS styling to ensure better compatibility with a wider range of WordPress themes.
- Updated Polish translations for improved clarity and consistency.

### 1.6.2 - 2025-03-30
- This version was released to resolve a problem with the WordPress.org system not generating a ZIP package for version 1.6.1.
- There are no changes in code or functionality - this version is identical to 1.6.1.

### 1.6.1 - 2025-03-30
- Improved CSS styles for better appearance and theme compatibility.
- Fixed display issues to ensure everything looks as expected.
- Applied minor code tweaks to improve stability and performance.

### 1.6.0 - 2025-03-19
- Introduced a new Infinite Scroll feature, allowing posts to load automatically as users scroll down, improving user experience and engagement.
- Added a fully updated and more user-friendly Help section within the plugin settings for easier navigation and guidance.
- Released a new, improved Development Guide to assist developers in customizing and extending the plugin more efficiently.
- Refined grid containers and elements, resulting in better structure and layout consistency across different themes.
- Applied multiple CSS improvements to enhance responsiveness and compatibility with a wider range of WordPress themes.
- General code enhancements and minor bug fixes to improve plugin stability and performance.

### 1.5.0 - 2025-02-25
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

### 1.4.1 - 2025-02-14
- Tested and confirmed full compatibility with WordPress 6.7.2.

### 1.4.0 - 2025-02-10
- Added new template filters for customizing post elements. Developers can now modify the post date, image, title, excerpt, read more button, and load more button via filters, allowing greater flexibility in template customization.
- Standardized action and filter names for better consistency and readability.
- Introduced a backward compatibility layer for deprecated hooks to ensure smooth transition.
  **Note:** Old hooks remain functional, but updating your customizations to use the new hook names is highly recommended for long-term stability.
- Standardized function naming conventions and introduced alias functions to maintain backward compatibility while improving code clarity.
- Improved CSS structure for better compatibility with different WordPress themes.
- Simplified class names in the template system, making customization and integration easier.
- General code refactoring to improve maintainability, readability, and performance.

### 1.3.10 - 2025-02-06
- Added a new template: "Full Background Image Post Template" for enhanced post display.
- Improved CSS compatibility by adding missing vendor prefixes (-webkit-box-pack, -ms-flex-pack, justify-content, etc.).
- Refined CSS handling in the AJAX container, ensuring styles apply correctly when the "Apply Quick AJAX CSS Style" option is enabled. This improves theme compatibility and layout consistency.
- Optimized and cleaned up CSS styles for better responsiveness across different themes.
- General code improvements and performance optimizations for smoother execution.

### 1.3.9 - 2025-01-19
- Added support for loading the initial set of posts via AJAX on page load, improving compatibility with caching systems and ensuring fresh content display.
- Enhanced the "Load More" button functionality, allowing it to load a different number of posts than specified for the initial page load.
- Updated CSS transitions for smoother and more visually appealing effects.
- Improved translation support and ensured compatibility with WordPress translation standards.
- General performance improvements and minor bug fixes.

### 1.3.8 - 2025-01-09
- Added new global options for customizing button labels:
  Read More: Customize the "Read More" button text for your templates.
  Show All: Customize the label for the "Show All" button, which displays all posts without taxonomy filtering.
  Load More: Customize the label for the "Load More" button, used to load additional posts.
- Updated the default template to enhance design and usability.
- Improved code maintainability by reducing redundancy and optimizing helper methods.

### 1.3.7 - 2025-01-02
- Improved JavaScript minification and optimized the code for better performance.
- Enhanced CSS compatibility across different browsers by adding automatic prefixes.
- Improved CSS minification for smaller file sizes and faster loading times.
- Added support for loading -dev versions of CSS and JS files when the URL includes ?dev=true or ?dev=1 (available only in the GitHub version with -dev files, as the public release on WordPress.org excludes these files to maintain a streamlined production package).
- Improved error handling and overall plugin reliability.
- Enhanced security measures for safer handling of content.

### 1.3.6 - 2024-12-05
- Improved the appearance of the shortcode box in the post editor to make it more user-friendly.
- Enhanced the plugin's performance and stability for a smoother experience.

### 1.3.5 - 2024-12-01
- Fixed an issue with taxonomy handling for post types.
- Added support for cases where no taxonomy is assigned, ensuring the plugin works smoothly.
- Performed additional testing and applied minor fixes to improve stability and user experience.

### 1.3.4 - 2024-11-29
- Improved the activation process to ensure default options are correctly set.
- Fixed an issue where some default settings were not applied during activation.
- Introduced enhancements to improve the plugin's stability and reliability.

### 1.3.3 - 2024-11-27
- Improved ARIA compliance and accessibility for tab navigation in the settings page.
- Improved the update process for smoother upgrades to newer versions of the plugin.
- Added a new option to remove old data used by previous versions, keeping your site clean and optimized.
- Fixed an issue where the loader icon was not displaying correctly in some configurations.

### 1.3.2 - 2024-11-13
- Improved `post_meta` handling for better shortcode management.
- Fixed an issue where shortcodes were not visible on the shortcode management page.
- Enhanced data migration for better backward compatibility.

### 1.3.1 - 2024-11-11
- Resolved an issue where custom post types (CPT) were sometimes not selectable in the settings.
- Fixed minor bugs and enhanced overall stability.

### 1.3.0 - 2024-11-09
- Added support for automatic plugin updates from the WordPress repository.
- Refactored code structure for better maintainability and performance.
- Strengthened security with stricter input validation and nonce verification.
- Optimized asset loading for faster page performance.
- Fixed several minor bugs and improved compatibility with different WordPress themes.

### 1.2.1 - 2024-10-21
- Corrected an issue with the text domain for better localization support.
- Removed closing PHP tags in all files to comply with PSR-2 standards and prevent unexpected output.

### 1.2 - 2024-10-20
- Implemented major security enhancements, including input sanitization and validation.
- Optimized query handling for improved database performance.

### 1.1.1 - 2024-08-02
- Optimized script loading to ensure `quick-ajax-script` loads only on the frontend, improving performance and reducing backend load.

### 1.1 - 2024-08-01
- Enhanced template file handling to support safer file operations and better template hierarchy.
- Added new shortcode attributes for increased flexibility.
- Introduced an 'Ignore Sticky Posts' option in the settings.

### 1.0.1 - 2024-03-20
- Fixed a loader icon display issue.
- Improved Polish language translations.

### 1.0 - 2024-03-13
- Initial release.

## Upgrade Notice

### 1.8.0
Includes major internal improvements, bug fixes, and performance optimizations.  
Recommended update for better stability and compatibility.

### 1.7.3
**Important:**
- The updated CSS may slightly change the visual presentation of posts if you use the plugin's default styling.
- Post images now use the `large` image size by default - you can adjust this size in your WordPress Media settings (`Settings → Media`) to better suit your layout.

### 1.7.0
Improves filter buttons, loader styling, and shortcode parameters. Adds taxonomy term selection in settings. `qapl_render_taxonomy_filter()` now auto-detects taxonomy. Recommended for better stability.


### 1.6.4
This update adds a new global option and filter hook to customize the "No Posts Found" message.

**Important:**
- Developers can use the `qapl_template_no_post_message` filter to modify the output.
- Includes fixes for loader display and label issues.

Fully tested with WordPress 6.8.

### 1.6.3
Adds support for an "End Message" shown when no more posts load. It can be set globally, edited in the template, or modified via the `qapl_template_end_post_message` filter.

### 1.6.0
This update introduces an Infinite Scroll feature and an improved Help section within the plugin settings.

**Important:**
- A new Developer Guide helps with customization and integration.
- The grid and CSS have been improved for better responsiveness and theme compatibility.

### 1.5.0
Adds new sorting button and global sorting labels. Now supports only `publish` posts. Unused `orderby` values removed (can be restored via `qapl_modify_posts_query_args`). Improves sorting control and plugin stability.

### 1.4.0
This update introduces new template filters, allowing developers to modify post elements (date, title, image, excerpt, and buttons) using filters.

**Important:**
- Action and filter names were standardized.
- Review and update your custom code to use new names for future compatibility.

### 1.3.9
Adds support for loading the initial set of posts via AJAX, improving caching compatibility and ensuring fresh content. Enhances "Load More" button functionality for greater flexibility.

### 1.3.5
Fixes taxonomy handling issues and adds support for cases where no taxonomy is assigned. Includes minor fixes and stability improvements. Recommended for better functionality.

### 1.3.3
Enhances accessibility, adds options for managing old data, and fixes loader icon display issues. Recommended for improved user experience and site management.

### 1.3.0
This update includes new features like automatic plugin updates and important security enhancements. Update recommended.

### 1.2
Includes critical security updates and performance improvements. Strongly recommended to update.

### 1.1
Major update introducing new template handling and security features. Update is highly encouraged.

### 1.0.1
Fixes a loader icon issue and updates Polish translations. Recommended for users affected by these issues.

### 1.0
Initial release. Enjoy the full set of features and improvements.