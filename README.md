# Quick Ajax Post Loader

Load and display WordPress posts dynamically using AJAX for faster, interactive browsing – no page reloads.

## Description

**Quick Ajax Post Loader** is designed for users who need an easy way to display posts using AJAX on WordPress without coding. Customize layouts, filter posts by taxonomy, and create personalized post templates with ease. This plugin allows you to dynamically load posts without refreshing the page, ensuring a smooth and engaging user experience. Whether you're showcasing blog posts, portfolios, or custom post types, Quick Ajax Post Loader simplifies the process of implementing AJAX functionality.

Additionally, for developers, the plugin offers advanced features such as:
- Overriding and creating custom templates
- Customizing taxonomy filter buttons
- Using advanced hooks and filters for greater flexibility
- Generating AJAX PHP functions for direct integration into theme templates

**[View the full developer guide on GitHub](https://github.com/grzelkowski/quick-ajax-post-loader/blob/main/DEVELOPER_GUIDE.md)**.

## Why use Quick Ajax Post Loader?

Quick Ajax Post Loader is the ultimate solution for anyone looking to enhance their WordPress site's functionality and user experience. Here’s why it stands out:

- **Streamlined User Experience**: Instantly load posts without page refreshes, offering visitors a smooth and uninterrupted browsing experience. Ideal for blogs, portfolios, and product showcases.
- **Time-Saving Simplicity**: Even if you're not a developer, you can easily use the plugin to dynamically display content with just a few clicks in the admin panel.
- **Customizable for Any Design**: Tailor the appearance of your posts, filters, and loading animations to fit perfectly with your site's unique branding.
- **Versatile Content Filtering**: Enable users to interactively filter posts by categories, tags, or custom taxonomies, ensuring they find what they're looking for quickly and effortlessly.
- **Engage Visitors Longer**: By delivering dynamic content without delays, keep your visitors engaged and reduce bounce rates.
- **Built for Speed**: The plugin optimizes data loading to ensure fast performance, even for high-traffic websites.
- **Empowering Developers**: Advanced users can leverage hooks, filters, and reusable AJAX functions for deep integration and endless possibilities.

Whether you’re a blogger, a web designer, or a developer, Quick Ajax Post Loader adapts to your needs, making it a must-have tool for dynamic content loading.

## Features

- **Dynamic Shortcodes**: Easily create and configure AJAX-powered shortcodes in the admin panel. Insert them into any page or post to display content dynamically.
- **Interactive Taxonomy Filtering**: Enable users to filter posts by categories, tags, or custom taxonomies using clickable, visually engaging buttons.
- **Customizable Templates**: Gain full control over the appearance of post items, buttons, and loading animations by creating custom templates in your theme directory.
- **Flexible Grid Layouts**: Set up responsive grids with up to 12 columns, and style them with custom CSS classes to fit your design.
- **Personalized Loading Icons**: Replace default loading spinners with your own animated GIFs, CSS-based designs, or custom HTML elements.
- **Advanced Sorting Options**: Sort posts dynamically by title, date, or custom fields for enhanced user navigation.
- **AJAX Function Generator**: Simplify advanced integrations by generating reusable PHP snippets for AJAX content loading directly in your theme files.
- **Performance Optimizations**: Speed up your site by loading only the necessary data and assets, reducing server load and improving user experience.
- **Hierarchical Template Loading**: Preserve customizations by prioritizing child theme templates over plugin defaults.
- **Multi-Taxonomy Support**: Offer users the ability to filter posts using multiple taxonomies, perfect for complex websites or eCommerce platforms.
- **Developer Tools**: Extend functionality and integrate seamlessly with your theme or plugin using hooks, filters, and other developer-friendly features.

## Advanced Features and Usage

### Custom Post Templates

The **Quick Ajax Post Loader** plugin provides the flexibility to create and use custom post templates. These templates allow you to completely control the appearance and behavior of dynamically loaded posts, ensuring they match your website's design and functionality.

**Why Use Custom Templates?**
- Fully customize the layout of your posts to match your site’s branding.
- Create templates for specific use cases, such as portfolios, product listings, or blogs.
- Preserve your changes during theme updates by storing templates in a child theme.

**Highlights:**
- Override the default template by placing a file named `post-item.php` in your theme's `/quick-ajax-post-loader/templates/post-items/` directory.
- Add additional templates with custom names (e.g., `post-item-custom-name.php`) for advanced use cases.
- Templates can include custom HTML, CSS, or PHP elements to enhance your design.

For detailed instructions and code examples, visit the [Developer Guide](https://github.com/grzelkowski/quick-ajax-post-loader/blob/main/DEVELOPER_GUIDE.md).

### Customizing Taxonomy Filter Buttons

With this plugin, you can create interactive and visually appealing taxonomy filter buttons for your users. These buttons allow dynamic filtering of posts by categories, tags, or custom taxonomies.

**Benefits of Custom Filter Buttons:**
- Match the buttons’ style to your website’s design.
- Enhance user experience with intuitive and interactive navigation.
- Enable seamless content filtering without page reloads.

To customize, place a `taxonomy-filter-button.php` file in your theme's `/quick-ajax-post-loader/templates/taxonomy-filter/` directory. This file lets you modify button labels, classes, or behavior. More details can be found in the [Developer Guide](https://github.com/grzelkowski/quick-ajax-post-loader/blob/main/DEVELOPER_GUIDE.md).

### Custom Loading Icons

Personalize your site’s loading experience with custom loading icons. These icons can include animated GIFs, CSS effects, or custom HTML designs to match your branding.

**Key Features:**
- Create unique animations or designs to signal content loading.
- Add a professional touch to your website by replacing generic loading spinners with custom designs.
- Seamlessly integrate custom icons by placing files in the `/quick-ajax-post-loader/templates/loader-icon/` directory of your theme.

Visit the [Developer Guide](https://github.com/grzelkowski/quick-ajax-post-loader/blob/main/DEVELOPER_GUIDE.md) for examples and setup instructions.

### AJAX Function Generator

For developers looking for advanced integration, the **AJAX Function Generator** simplifies the creation of PHP-based AJAX functions. This tool allows you to integrate dynamic post grids and taxonomy filters directly into your theme files.

**Why Use the AJAX Function Generator?**
- Easily generate reusable PHP snippets for AJAX functionality.
- Integrate dynamic content loading seamlessly into custom themes or plugins.
- Save development time with a guided setup process.

Navigate to **Quick Ajax > Settings & Features** and use the "Function Generator" tab to configure and generate code snippets tailored to your requirements.

**Pro Tip:** Use the generated code in theme files like `page.php` or `single.php` to enhance content interactivity.

### Hook and Filter Support

For developers, **Quick Ajax Post Loader** offers extensive support for hooks and filters, enabling full customization of the plugin's functionality.

**Why Use Hooks and Filters?**
- Modify default behaviors without editing core plugin files.
- Extend or override plugin functionality to meet specific requirements.
- Seamlessly integrate AJAX-based features into your custom themes or plugins.

**Examples of Available Hooks:**
- `qapl_filter_container_before`: Add custom content before rendering taxonomy filters.
- `qapl_posts_container_end`: Insert additional HTML at the end of the posts section.
- `qapl_loader_after`: Customize the rendering of loading icons.

**Examples of Filters:**
- `qapl_modify_posts_query_args`: Adjust the WP_Query arguments for AJAX content loading.
- `qapl_modify_taxonomy_filter_buttons`: Change the labels or behavior of taxonomy filter buttons.

For a complete list of hooks and filters, along with examples, refer to the [Developer Guide](https://github.com/grzelkowski/quick-ajax-post-loader/blob/main/DEVELOPER_GUIDE.md).

### Why Use These Features?

- Customize the plugin’s functionality to meet the specific needs of your project.
- Improve user engagement with personalized designs and seamless interactivity.
- Simplify integration with developer-friendly tools and clear documentation.
- Ensure consistent updates without losing customizations by leveraging child themes.

For further details, examples, and setup guides, refer to the [Developer Guide](https://github.com/grzelkowski/quick-ajax-post-loader/blob/main/DEVELOPER_GUIDE.md).

## Minimum Requirements

- **PHP:** Version 7.4 or higher
- **WordPress:** Version 5.6 or higher

## Installation

1. **Upload the Plugin**  
   - Upload the `quick-ajax-post-loader` folder to the `/wp-content/plugins/` directory.  
   - Alternatively, install the plugin directly from the WordPress Plugins screen by searching for "Quick Ajax Post Loader."

2. **Activate the Plugin**  
   - Go to the **Plugins** screen in your WordPress admin panel and activate the **Quick Ajax Post Loader** plugin.

3. **Basic Configuration**  
   - Navigate to **Quick Ajax** in the WordPress admin menu to configure the plugin’s basic settings.  
   - Set options like post types, taxonomy filters, grid layouts, and default loading icons.

4. **Generate Shortcodes**  
   - Use the **Shortcodes** tab to create customized shortcodes for dynamic content.  
   - Copy and paste the shortcode into your page or post editor to enable AJAX functionality.

5. **Optional Advanced Customization**  
   - Developers can leverage custom templates, hooks, and filters for advanced integrations. Visit the [Developer Guide](https://github.com/grzelkowski/quick-ajax-post-loader/blob/main/DEVELOPER_GUIDE.md) for step-by-step instructions.

6. **Preview and Test**  
   - Test your configuration by previewing the page or post.  
   - Adjust settings if needed to ensure smooth content loading.

## Frequently Asked Questions

### How do I generate shortcodes?
Go to the WordPress admin panel, navigate to **Quick Ajax > Shortcodes**, or click **Add New**. Configure your settings, such as post type, taxonomy filters, and grid layout. Once done, copy the generated shortcode (e.g., `[qapl-quick-ajax id="1" title="My Ajax"]`) and paste it into any page or post to enable dynamic post loading.

### Can I filter posts by categories or tags?
Yes, the plugin supports taxonomy filtering. You can enable clickable buttons to allow users to filter posts dynamically by categories, tags, or custom taxonomies.

### How can I display WordPress posts using AJAX?
Simply generate a shortcode using the plugin's interface and insert it into a page or post. The plugin handles the AJAX functionality, dynamically loading posts without refreshing the page.

### Can I create my own post templates?
Yes, you can create custom templates for post items, loading icons, and taxonomy filter buttons. This allows full control over the design and functionality of dynamically loaded content.

### How can I create custom post templates?
To create custom post templates:
1. Add a file named `post-item.php` to the directory:  
   `wp-content/themes/your-active-theme/quick-ajax-post-loader/templates/post-items/`.
2. Use the naming convention `post-item-custom-name.php` to create multiple templates.
3. Select your custom templates in the shortcode configuration panel.  
For more details, refer to the [Developer Guide](https://github.com/grzelkowski/quick-ajax-post-loader/blob/main/DEVELOPER_GUIDE.md).

### Is coding required to use this plugin?
No, the plugin is beginner-friendly and includes a shortcode generator for easy implementation without coding. Advanced users can take advantage of hooks, filters, and the AJAX Function Generator for deeper customization.

### Does the plugin include default styles?
Yes, it includes a default grid layout with up to 12 columns. These styles can be disabled if you prefer to apply custom CSS.

### Does the plugin support custom filters or hooks?
Yes, Quick Ajax Post Loader is developer-friendly and includes a variety of actions and filters. These allow advanced users to modify functionality, customize templates, and integrate the plugin seamlessly into their themes or plugins.

### How can I customize the loading icon?
You can create a custom loading icon by adding a file (e.g., `loader-icon-custom.php`) to the directory:  
`wp-content/themes/your-active-theme/quick-ajax-post-loader/templates/loader-icon/`. Use your own HTML, CSS, or animations to design a unique loading indicator.

## Additional Links

- [GitHub Repository](https://github.com/grzelkowski/quick-ajax-post-loader/)  
  Access the plugin's source code, contribute to its development, or report issues.

- [Developer Guide](https://github.com/grzelkowski/quick-ajax-post-loader/blob/main/DEVELOPER_GUIDE.md)  
  A comprehensive guide for developers to leverage hooks, filters, and custom templates.

## Privacy Policy

Quick Ajax Post Loader does not collect or store any user data.

## Credits

Developed by Pawel Grzelkowski.