# Quick Ajax Post Loader

AJAX Load More button, infinite scroll and taxonomy filters for posts, pages and custom post types. Fast, filterable post grids - no page reloads.

## Description

**Quick Ajax Post Loader** lets you build fast, filterable post grids in WordPress with an AJAX **Load More button** or **infinite scroll** - no page reloads, no coding required.

Display posts, pages, or any custom post type (CPT) in a responsive grid with **AJAX taxonomy filtering** (categories, tags, custom taxonomies), **AJAX sorting**, and dynamic pagination. Build your grid in the visual shortcode generator, paste the shortcode anywhere, and you're done.

Perfect for blogs, news sites, portfolios, directories, and content catalogs.

## Why choose Quick Ajax Post Loader?

Most plugins do just one thing - only infinite scroll, or only a filter. Quick Ajax Post Loader gives you the complete toolkit in one lightweight package:

* **AJAX Load More button** - load additional posts on click, with a customizable label
* **Infinite scroll** - posts load automatically as visitors scroll down
* **AJAX taxonomy filter** - filter posts by category, tag, or any custom taxonomy without reloading the page
* **AJAX sorting** - let visitors sort posts by date, title, comment count, or randomly
* **Any post type** - posts, pages, and any registered custom post type
* **Responsive post grid** - control the number of columns, templates, and CSS classes
* **Shortcode generator & PHP function generator** - point-and-click configuration, no code needed
* **Overridable templates** - customize post layouts directly from your theme or child theme
* **Developer-friendly** - dozens of action and filter hooks for deep customization
* **Lightweight & fast** - optimized codebase, assets load only where needed, SEO-friendly output

### Perfect for:

* Blogs and news / magazine websites
* Portfolios and creative showcases
* Product and offer grids based on custom post types
* Directories, listings, and content catalogs
* Knowledge bases and resource libraries

## Key features

* Load posts dynamically with AJAX - no page reloads
* Load More button or automatic infinite scroll (toggle per shortcode)
* AJAX filter buttons for categories, tags, and custom taxonomies
* Manually select which taxonomy terms appear in the filter
* Optional "Show All" button in the taxonomy filter
* Sort dropdown: date, title, comments, random - ascending or descending
* Multiple independent AJAX grids on the same page
* Grid layout control: columns, item templates, custom CSS classes
* Exclude specific posts by ID, ignore sticky posts
* Load the initial set of posts via AJAX - fresh content even with caching plugins
* Customizable "No Posts Found" and "End of Posts" messages
* Custom loader icons with dark variants
* Built-in shortcode generator and PHP function generator for theme integration
* Translation-ready, follows WordPress coding standards

## How it works

1. Install and activate Quick Ajax Post Loader.
2. Go to **Quick AJAX → Shortcodes → Add New** in the WordPress admin.
3. Configure your grid: post type, taxonomy filter, sorting, layout, Load More or infinite scroll.
4. Copy the generated shortcode and paste it into any page, post, or page builder widget.
5. Done - your visitors get AJAX-powered filtering, sorting, and loading.

## For Developers

* `qapl_modify_posts_query_args` - modify the WP_Query arguments of any AJAX request
* `qapl_modify_taxonomy_filter_buttons` - customize filter button output
* `qapl_modify_sorting_options_variants` - add or change sorting options
* Template filters: `qapl_template_post_item_title`, `qapl_template_post_item_image`, `qapl_template_post_item_excerpt`, `qapl_template_post_item_date`, `qapl_template_post_item_read_more`, `qapl_template_load_more_button`
* Message filters: `qapl_template_no_post_message`, `qapl_template_end_post_message`
* Layout actions: `qapl_posts_container_before/after/start/end`, `qapl_filter_container_before/after/start/end`, `qapl_loader_before/after`
* PHP functions for theme integration: `qapl_render_post_container()`, `qapl_render_taxonomy_filter()`, `qapl_render_sort_controls()`
* Override any template by copying it to `/quick-ajax-post-loader/templates/` in your theme

Full documentation with code examples: **[Developer Guide on GitHub](https://github.com/grzelkowski/quick-ajax-post-loader/blob/main/DEVELOPER_GUIDE.md)**

## Minimum Requirements

- **PHP:** Version 7.4 or higher
- **WordPress:** Version 5.6 or higher

## Installation

### Installing via the WordPress admin panel
1. Log in to your WordPress dashboard and go to **Plugins → Add New**.
2. Search for **Quick Ajax Post Loader** or upload the ZIP file downloaded from the repository.
3. Click **Install Now**, then **Activate**.

### Manual installation via FTP
1. Extract the downloaded ZIP file.
2. Upload the `/quick-ajax-post-loader/` folder to the `wp-content/plugins/` directory on your server.
3. Go to **Plugins → Installed Plugins** and activate **Quick Ajax Post Loader**.

### First steps
After activation, a new **Quick Ajax** menu appears in the WordPress admin. Go to **Quick AJAX → Shortcodes → Add New** to create your first AJAX post grid.

## Frequently Asked Questions

### How do I add a Load More button to my posts?
Create a shortcode in **Quick AJAX → Shortcodes**, enable the Load More button, and paste the shortcode into any page or post. You can customize the button label and how many posts load per click.

### Can I use infinite scroll instead of a Load More button?
Yes. Infinite scroll can be enabled per shortcode - posts then load automatically as visitors scroll down the page.

### Does it work with custom post types (CPT)?
Yes. You can display posts, pages, and any registered custom post type, each with its own taxonomies.

### How do I filter posts by category or tag without reloading the page?
Enable the taxonomy filter in your shortcode settings and choose a taxonomy (categories, tags, or a custom taxonomy). Filter buttons appear above the grid and update the posts via AJAX.

### Can I customize the post templates?
Yes. Copy a template to the `/quick-ajax-post-loader/templates/` folder in your theme or child theme and edit it freely. You can also modify individual elements (title, image, excerpt, buttons) via filter hooks.

### Can I use multiple grids on one page?
Yes. Each shortcode instance works independently, so you can combine several grids with different post types, filters, and layouts on a single page.

### Does it work with caching plugins?
Yes. The plugin can load the initial set of posts via AJAX, which ensures fresh content even on aggressively cached pages.

### Does it work with Gutenberg, Elementor, and other page builders?
Yes. Paste the shortcode into a Shortcode block in Gutenberg or a shortcode widget in Elementor, Divi, WPBakery, and other builders.

### Can I extend the plugin with hooks and filters?
Yes. The plugin exposes dozens of actions and filters - including full control over the WP_Query arguments via `qapl_modify_posts_query_args`. See the [Developer Guide](https://github.com/grzelkowski/quick-ajax-post-loader/blob/main/DEVELOPER_GUIDE.md).

### Is the plugin translation-ready?
Yes. The plugin follows WordPress internationalization standards and can be translated via translate.wordpress.org.

## Additional Links

- [Developer Guide](https://github.com/grzelkowski/quick-ajax-post-loader/blob/main/DEVELOPER_GUIDE.md)  
  A comprehensive guide for developers to leverage hooks, filters, and custom templates.

- [GitHub Repository](https://github.com/grzelkowski/quick-ajax-post-loader/)  
  Access the plugin's source code, contribute to its development, or report issues.

- [Support Forum](https://wordpress.org/support/plugin/quick-ajax-post-loader)  
  Get help, ask questions, or report bugs related to the plugin.

## Privacy Policy

Quick Ajax Post Loader does not collect or store any user data.

## Credits

Developed by Pawel Grzelkowski.