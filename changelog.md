## Changelog

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
- Added a new option to remove old data used by previous versions, keeping your site clean and optimised.
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