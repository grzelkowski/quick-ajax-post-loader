=== Quick Ajax Post Loader ===

Contributors: grzelkowski
Tags: ajax, load more, infinite scroll, filter, post grid
Requires at least: 6.2
Tested up to: 7.0
Stable tag: 1.9.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

AJAX Load More button, infinite scroll and taxonomy filters for posts, pages and custom post types. Fast, filterable post grids - no page reloads.

== Description ==

**Quick Ajax Post Loader** lets you build fast, filterable post grids in WordPress with an AJAX **Load More button** or **infinite scroll** - no page reloads, no coding required.

Display posts, pages, or any custom post type (CPT) in a responsive grid with **AJAX taxonomy filtering** (categories, tags, custom taxonomies), **AJAX search**, **AJAX sorting**, and dynamic pagination. Build your grid in the visual shortcode generator, paste the shortcode anywhere, and you're done.

Perfect for blogs, news sites, portfolios, directories, and content catalogs.

== Why choose Quick Ajax Post Loader? ==

Most plugins do just one thing - only infinite scroll, or only a filter. Quick Ajax Post Loader gives you the complete toolkit in one lightweight package:

* **AJAX Load More button** - load additional posts on click, with a customizable label
* **Infinite scroll** - posts load automatically as visitors scroll down
* **AJAX taxonomy filter** - filter posts by category, tag, or any custom taxonomy without reloading the page
* **AJAX sorting** - let visitors sort posts by date, title, comment count, or randomly
* **AJAX search** - a search field that finds posts by phrase, without reloading the page
* **Any post type** - posts, pages, and any registered custom post type
* **Responsive post grid** - control the number of columns, templates, and CSS classes
* **Shortcode generator & PHP function generator** - point-and-click configuration, no code needed
* **Overridable templates** - customize post layouts directly from your theme or child theme
* **Developer-friendly** - dozens of action and filter hooks for deep customization
* **Lightweight & fast** - optimized codebase, assets load only where needed, SEO-friendly output

= Perfect for: =

* Blogs and news / magazine websites
* Portfolios and creative showcases
* Product and offer grids based on custom post types
* Directories, listings, and content catalogs
* Knowledge bases and resource libraries

== Key features ==

* Load posts dynamically with AJAX - no page reloads
* Load More button or automatic infinite scroll (toggle per shortcode)
* AJAX filter buttons for categories, tags, and custom taxonomies
* Manually select which taxonomy terms appear in the filter
* Optional "Show All" button in the taxonomy filter
* Sort dropdown: date, title, comments, random - ascending or descending
* Search field so visitors can find posts by phrase, inline with the filters or on its own line
* Multiple independent AJAX grids on the same page
* Grid layout control: columns, item templates, custom CSS classes
* Exclude specific posts by ID, ignore sticky posts
* Load the initial set of posts via AJAX - fresh content even with caching plugins
* Customizable "No Posts Found" and "End of Posts" messages
* Custom loader icons with dark variants
* Built-in shortcode generator and PHP function generator for theme integration
* Translation-ready, follows WordPress coding standards

== How it works ==

1. Install and activate Quick Ajax Post Loader.
2. Go to **Quick AJAX → Shortcodes → Add New** in the WordPress admin.
3. Configure your grid: post type, taxonomy filter, sorting, layout, Load More or infinite scroll.
4. Copy the generated shortcode and paste it into any page, post, or page builder widget.
5. Done - your visitors get AJAX-powered filtering, sorting, and loading.

== For Developers ==

* `qapl_modify_posts_query_args` - modify the WP_Query arguments of any AJAX request
* `qapl_modify_taxonomy_filter_buttons` - customize filter button output
* `qapl_modify_sorting_options_variants` - add or change sorting options
* Template filters: `qapl_template_post_item_title`, `qapl_template_post_item_image`, `qapl_template_post_item_excerpt`, `qapl_template_post_item_date`, `qapl_template_post_item_read_more`, `qapl_template_load_more_button`
* Message filters: `qapl_template_no_post_message`, `qapl_template_end_post_message`
* Layout actions: `qapl_posts_container_before/after/start/end`, `qapl_filter_container_before/after/start/end`, `qapl_loader_before/after`
* PHP functions for theme integration: `qapl_render_post_container()`, `qapl_render_taxonomy_filter()`, `qapl_render_sort_controls()`
* Override any template by copying it to `/quick-ajax-post-loader/templates/` in your theme

Full documentation with code examples: **[Developer Guide on GitHub](https://github.com/grzelkowski/quick-ajax-post-loader/blob/main/DEVELOPER_GUIDE.md)**

== Installation ==

= Installing via the WordPress admin panel =
1. Log in to your WordPress dashboard and go to **Plugins → Add New**.
2. Search for **Quick Ajax Post Loader** or upload the ZIP file downloaded from the repository.
3. Click **Install Now**, then **Activate**.

= Manual installation via FTP =
1. Extract the downloaded ZIP file.
2. Upload the `/quick-ajax-post-loader/` folder to the `wp-content/plugins/` directory on your server.
3. Go to **Plugins → Installed Plugins** and activate **Quick Ajax Post Loader**.

= First steps =
After activation, a new **Quick Ajax** menu appears in the WordPress admin. Go to **Quick AJAX → Shortcodes → Add New** to create your first AJAX post grid.

== Frequently Asked Questions ==

= How do I add a Load More button to my posts? =
Create a shortcode in **Quick AJAX → Shortcodes**, enable the Load More button, and paste the shortcode into any page or post. You can customize the button label and how many posts load per click.

= Can I use infinite scroll instead of a Load More button? =
Yes. Infinite scroll can be enabled per shortcode - posts then load automatically as visitors scroll down the page.

= Does it work with custom post types (CPT)? =
Yes. You can display posts, pages, and any registered custom post type, each with its own taxonomies.

= How do I filter posts by category or tag without reloading the page? =
Enable the taxonomy filter in your shortcode settings and choose a taxonomy (categories, tags, or a custom taxonomy). Filter buttons appear above the grid and update the posts via AJAX.

= Can visitors search posts? =
Yes. Enable the search field in your shortcode settings. Visitors can then find posts by phrase - results update via AJAX, work together with sorting, and the field can be placed next to the taxonomy filter or on its own line.

= Can I customize the post templates? =
Yes. Copy a template to the `/quick-ajax-post-loader/templates/` folder in your theme or child theme and edit it freely. You can also modify individual elements (title, image, excerpt, buttons) via filter hooks.

= Can I use multiple grids on one page? =
Yes. Each shortcode instance works independently, so you can combine several grids with different post types, filters, and layouts on a single page.

= Does it work with caching plugins? =
Yes. The plugin can load the initial set of posts via AJAX, which ensures fresh content even on aggressively cached pages.

= Does it work with Gutenberg, Elementor, and other page builders? =
Yes. Paste the shortcode into a Shortcode block in Gutenberg or a shortcode widget in Elementor, Divi, WPBakery, and other builders.

= Can I extend the plugin with hooks and filters? =
Yes. The plugin exposes dozens of actions and filters - including full control over the WP_Query arguments via `qapl_modify_posts_query_args`. See the [Developer Guide](https://github.com/grzelkowski/quick-ajax-post-loader/blob/main/DEVELOPER_GUIDE.md).

= Is the plugin translation-ready? =
Yes. The plugin follows WordPress internationalization standards and can be translated via translate.wordpress.org.

== Screenshots ==

1. All your AJAX shortcodes in one place, ready to copy and paste into any page or post.
2. Shortcode creator - choose the post type, taxonomy filter, sorting options and posts per page.
3. Sorting and additional settings - sorting options, excluded post IDs, sticky posts, initial AJAX load and infinite scroll.
4. Layout settings - columns, post item template, custom CSS classes, Load More quantity and loader icon.
5. AJAX post grid with taxonomy filter buttons and a sorting dropdown - filter and sort posts without reloading the page.
6. Full background image post template with a Load More button - load additional posts on click.
7. Four-column grid with the loader icon while new posts are being fetched via AJAX.
8. Global options - customize the loader icon and the Read More, Show All, Load More and sorting labels.
9. PHP Function Generator - generate ready-to-use code for direct integration in your theme.
10. Built-in Help section with guides on templates, hooks and customization.

== Changelog ==

= 1.9.0 - 2026-08-19 =
- Added a search option - the AJAX loader can now show a search field, so visitors can find posts by phrase alongside the existing filters and sorting.
- Improved the plugin's admin menu - the main "Quick AJAX" item now opens your shortcodes list directly.
- Improved reliability of AJAX post loading and filtering on sites using full-page caching or a CDN.
- Improved the Purge Old Data tool with clear status messages after each action.
- Minor code cleanup and coding standards improvements.
- Minimum required WordPress version raised from 5.6 to 6.2.

= 1.8.14 - 2026-07-20 =
- Security: hardened capability checks for admin menu access.
- Security: improved sanitization and validation of form inputs and AJAX parameters.
- Added an optional setting to remove all plugin data on uninstall (disabled by default).
- More reliable post loading, filtering, and taxonomy selection thanks to better AJAX error handling.
- Faster rendering of taxonomy filters with many terms (fewer database queries).
- Faster front-end page loads - admin-only files are no longer loaded on the site front end.
- Custom sorting option labels from global settings are now correctly applied on the front end.
- Improved the Purge Old Data tool with required confirmation and clearer status handling.
- Minor code cleanup and coding standards improvements.
- Plugin styles and scripts are now loaded only on pages that actually use Quick Ajax, improving performance across the rest of the site.

= 1.8.13 - 2026-06-22 =
- Improved number field handling for more accurate and consistent value processing.
- Enhanced required field support across select, number, and text input fields.
- Minor improvements and refinements to code consistency and standards compliance.
- Improved support for negative numeric values in query parameters for more flexible post display options.
- Improved AJAX response handling for more reliable and consistent frontend behavior.
- Improved internal term selection handling for more reliable and consistent behavior.

= 1.8.12 - 2026-05-20 =
- Updated admin URL handling in tooltip links to ensure proper compatibility across different WordPress setups.
- Added min/max constraint support for number fields, with both browser-side and server-side validation.
- Replaced inline display style with a dedicated CSS class for cleaner markup and better compatibility.
- Minor improvements and refinements to code consistency and standards compliance.
- Tested and confirmed compatibility with WordPress 7.0.

= 1.8.11 - 2026-04-28 =
- Fixed an issue where multi-select field values in global plugin settings could be lost when saving.
- Minor internal improvements and refinements to enhance stability and consistency.
- Minor improvements and refinements to translations.

For the full changelog of earlier versions, see the CHANGELOG.md file included with the plugin or the [GitHub repository](https://github.com/grzelkowski/quick-ajax-post-loader/blob/main/CHANGELOG.md).

== Upgrade Notice ==

= 1.9.0 =
Adds a search option that lets visitors find posts by phrase directly in the AJAX loader. Requires WordPress 6.2 or newer. Recommended for all users.

= 1.8.14 =
Security release: hardened capability checks and improved input sanitization. Also improves performance by loading plugin assets only where needed. Update recommended for all users.

= 1.8.12 =
Improves admin URL handling, adds min/max validation for number fields, and replaces inline styles with dedicated CSS classes.
Tested and confirmed compatible with WordPress 7.0.

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