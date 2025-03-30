# Quick Ajax Post Loader

Load and display WordPress posts dynamically using AJAX for faster, interactive browsing - no page reloads.

## Description

**Quick Ajax Post Loader** is a powerful yet lightweight plugin for WordPress that helps you create dynamic, interactive post grids using AJAX - no page reloads required!

Display posts, pages, or custom post types (CPT) with full AJAX filtering, sorting, and pagination. Whether you're building a blog, a product catalog, a portfolio, or a directory - this plugin will give your users a seamless browsing experience.

Includes both a "Load More" button and infinite scroll option for maximum flexibility.

Boost your site's UX and performance with AJAX!

**[View the full developer guide on GitHub](https://github.com/grzelkowski/quick-ajax-post-loader/blob/main/DEVELOPER_GUIDE.md)**.

## Why choose Quick Ajax Post Loader?

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

### Designed for:
- Blogs & News websites
- Portfolios & creative showcases
- WooCommerce product grids (custom post types)
- Directories, listings, and content catalogs

## Features in detail

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

## How it works

1. Install and activate Quick Ajax Post Loader.
2. Go to **Quick AJAX > Shortcodes > Add New** in the WordPress admin.
3. Configure your post grid (select post type, filters, sort options, layout).
4. Copy the generated shortcode and paste it into any page or post.
5. Enjoy AJAX-powered filtering, sorting, and infinite scroll!

## Minimum Requirements

- **PHP:** Version 7.4 or higher
- **WordPress:** Version 5.6 or higher

## Installation

### Downloading the Plugin
To install **Quick Ajax Post Loader**, download the latest version of the plugin from the WordPress repository and install it using one of the following methods:

### Installing via the WordPress Admin Panel
1. Log in to your WordPress dashboard and go to **Plugins > Add New**.
2. Search for **Quick Ajax Post Loader** or upload the ZIP file downloaded from the repository.
3. Click **Install Now**, then **Activate**.

#### Manual Installation via FTP
1. Extract the downloaded ZIP file.
2. Upload the entire **quick-ajax-post-loader** folder to the `wp-content/plugins/` directory on your server.
3. In the WordPress admin panel, go to **Plugins > Installed Plugins** and click **Activate** next to **Quick Ajax Post Loader**.

### Activation and First Steps
After activating the plugin, a new **Quick Ajax** menu item will appear in the WordPress admin panel, where you can configure settings and shortcodes.

## Frequently Asked Questions

### Does it work with custom post types?
Yes, you can display posts, pages, and custom post types (CPT).

### Can I enable infinite scroll instead of "Load More"?
Yes, infinite scroll is available and can be toggled per shortcode.

### Can I customize post templates?
Yes, you can override templates directly from your theme's `/quick-ajax-post-loader/templates/` folder.

### Does it support multiple shortcodes on one page?
Yes, you can create multiple AJAX grids with different configurations.

### Can I extend the plugin via hooks and filters?
Yes, developers can use available WordPress hooks and filters to fully customize functionality.

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