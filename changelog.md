== Changelog ==
= 1.3.0 = 2024-11-09 =
- Added support for automated plugin updates directly from the WordPress repository.
- Improved code structure and organization for better maintainability and performance.
- Enhanced security with stricter input validation and nonce verification.
- Optimized asset loading for improved performance.
- Fixed various minor bugs and compatibility issues.

= 1.2.1 = 2024-10-21 =
- Fixed issue with text domain.
- Removed closing PHP tags from all PHP files to comply with PSR-2 standards.

= 1.2 = 2024-10-20 =
- Eliminated use of HEREDOC and NOWDOC syntax to enhance compatibility and security.
- Sanitized and validated all user inputs including GET, POST, REQUEST, and FILE calls.
- Improved nonce verification to ensure safer handling by using wp_unslash() and sanitize_text_field().
- Updated function, class, and define names to use unique prefixes (qapl_) to prevent conflicts.
- Added checks to prevent direct file access by adding if ( ! defined( 'ABSPATH' ) ) exit; in all PHP files.
- Optimized several functions for better performance, reducing redundant database queries and improving load times.
- Updated hooks and actions to use properly namespaced prefixes to avoid conflicts.
- Removed redundant code blocks and enhanced logic for improved maintainability.
- Fixed minor bugs related to form submission and data handling.

= 1.1.1 = 2024-08-02 =
- Optimized script loading to only load `quick-ajax-script` on the frontend, improving performance and avoiding unnecessary loading in the admin area.

= 1.1 = 2024-08-01 =
- Improved Template File Overriding Hierarchy: Ensured child theme templates take precedence over parent themes and plugins.
- Enhanced template search with `glob` for better efficiency.
- Replaced `json_encode` with `wp_json_encode` for improved compatibility.
- Replaced `fopen` with `WP_Filesystem` for safer file operations.
- Secured all output with functions like `esc_html` and `wp_kses`.
- Added new shortcode attributes for flexibility (`post_type`, `posts_per_page`, `order`, `orderby`, `post_status`).
- Introduced 'Ignore Sticky Posts' option.
- Various security improvements and bug fixes.

= 1.0.1 = 2024-03-20 =
- Fixed the loader icon bug.
- Improved Polish translations.

= 1.0 = 2024-03-13 =
- Initial release.

== Upgrade Notice ==
= 1.3.0 =
This update introduces automated updates, significant code improvements, and enhanced security measures. Updating is recommended to benefit from these enhancements.

= 1.2 =
Significant security and performance improvements. Updating is highly recommended.

= 1.1 =
Major update with template handling and security enhancements. Strongly recommended to update.

= 1.0.1 =
Fixes loader icon bug and improves translations. Recommended for update.

= 1.0 =
Initial release. Enjoy new features and improvements.
