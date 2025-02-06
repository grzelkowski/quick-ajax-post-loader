# Quick Ajax Post Loader Developer Guide

Welcome to the **Quick Ajax Post Loader** developer guide. This guide is designed for developers who want to take full advantage of the plugin's advanced features, hooks, filters, and customization options.

## Why Use Quick Ajax Post Loader?

The **Quick Ajax Post Loader** plugin provides an efficient way to dynamically load content on WordPress websites using AJAX. This approach eliminates the need for page reloads, improving user experience and site performance. Below are some of the key benefits of using this plugin:

- **Improved User Experience**: Content is loaded seamlessly without page reloads, providing a smooth browsing experience.  
- **Customizability**: Easily customize post grids, taxonomy filters, loading icons, and more using hooks, filters, and templates.  
- **Flexibility**: Supports multiple post types, taxonomies, and custom templates, making it suitable for various use cases.  
- **Performance Optimization**: AJAX-based loading reduces server load by only fetching the required data.  
- **Developer-Friendly**: Includes tools like the AJAX Function Generator and a variety of hooks and filters for deep integration.

---

## Table of Contents

1. [Introduction to Shortcodes](#1-introduction-to-shortcodes)  
2. [Custom Post Templates](#2-custom-post-templates)   
3. [Customizing Taxonomy Filter Buttons](#3-customizing-taxonomy-filter-buttons)  
4. [Custom Loading Icons](#4-custom-loading-icons) 
5. [Using AJAX Function Generator](#5-using-ajax-function-generator)  
6. [Key Functions](#6-key-functions)  
   - [qapl_quick_ajax_post_grid Function](#qapl_quick_ajax_post_grid-function)  
   - [qapl_quick_ajax_term_filter Function](#qapl_quick_ajax_term_filter-function)  
7. [Detailed Parameter Descriptions](#7-detailed-parameter-descriptions)  
   - [quick_ajax_args Parameter](#quick_ajax_args-parameter)  
   - [quick_ajax_attributes Parameter](#quick_ajax_attributes-parameter)  
8. [Available Actions](#8-available-actions)  
9. [Available Filters](#9-available-filters)

---

## 1. Introduction to Shortcodes

### What Are Shortcodes?

The **Quick Ajax Post Loader** plugin enables the creation of shortcodes that allow for the dynamic display of posts in WordPress using AJAX. This eliminates the need to refresh the page, providing a seamless content browsing experience.

### Why Use Shortcodes?

Shortcodes are a powerful way to dynamically load posts on your WordPress site. They allow you to integrate AJAX-based functionality into pages or posts with minimal effort.

### Steps to Create a Shortcode  

1. Navigate to **Quick Ajax > Shortcodes** or click **Add New** in the WordPress admin panel.  
2. Configure the settings:
    - Choose the **post type** to display (e.g., posts, pages, or custom post types).
    - Add **taxonomy filters** to allow users to filter content dynamically.
    - Customize **display options**, such as layout, grid structure, and loading icons.
3. Save the configuration.  
4. Copy the generated shortcode (e.g., `[qapl-quick-ajax id="1" title="My Ajax"]`).
5. Paste the shortcode into any page or post to enable AJAX-based content loading.  

### Important Notes  

- The actual shortcode will depend on the settings you configure in the plugin interface.  
- Always verify the functionality of the shortcode on your site to ensure it matches the desired behavior.  

---

## 2. Custom Post Templates

The **Quick Ajax Post Loader** plugin allows you to override the default post template or create your own custom templates to personalize the appearance and behavior of dynamically loaded posts.

### Steps to Create a Custom Template  

1. Navigate to the directory:  
   `wp-content/themes/your-active-theme/quick-ajax-post-loader/templates/post-items/`.  
2. To override the default template:  
   - Create a file named `post-item.php` in the directory above. This file will replace the default template provided by the plugin.  
3. To create additional custom templates:  
   - Use the naming convention `post-item-*.php` (e.g., `post-item-custom-name.php`). 
   - Add a comment at the top of the file to specify the template name, e.g.:  
     
        /* Post Item Name: My Custom Template */
     
   - If the comment is missing, the file name (without the `.php` extension) will be displayed as the template name in the admin panel.  
4. Save the file in the specified directory. The plugin will automatically detect it and make it available for selection in the shortcode configuration.

### Example Template Code  

    <?php  
    /* Post Item Name: My Custom Template */  
    ?>  
    <div class="quick-ajax-post-item">  
        <a href="<?php echo get_permalink(); ?>">  
            <h2><?php the_title(); ?></h2>  
            <!-- Add custom post elements, e.g., thumbnail or excerpt -->
        </a>  
    </div>

### Template Selection  

- All templates created in the specified directory will be detected automatically by the plugin.  
- Templates will appear as selectable options in the shortcode configuration.  

### Template File Naming Rules  

- **Default Template**:  
    - File name: `post-item.php`.  
    - Location: `wp-content/themes/your-active-theme/quick-ajax-post-loader/templates/post-items/`.  

- **Custom Templates**:  
    - File names must start with `post-item`, e.g., `post-item-custom-name.php`.  
    - The naming convention ensures that the plugin recognizes the files as additional templates.

### Customizing the "No Posts" Message  

To customize the message displayed when there are no posts to show:  

1. Create a file named `no-posts.php`.  
2. Place it in the directory:  
   `wp-content/themes/your-active-theme/quick-ajax-post-loader/templates/post-items/`.  
3. Add your custom HTML or message to this file.  

### Template Loading Order  

1. **Child Theme**  
2. **Parent Theme**  
3. **Plugin Defaults**  

This hierarchy ensures that customizations in the child theme are prioritized, safeguarding changes from being overwritten during updates.

---

## 3. Customizing Taxonomy Filter Buttons

The **Quick Ajax Post Loader** plugin allows you to customize the appearance and functionality of taxonomy filter buttons by overriding the `term-filter-button.php` file. These buttons are used to filter posts by categories, tags, or other taxonomies.

### Steps to Customise  

1. Navigate to the directory:  
   `wp-content/themes/your-active-theme/quick-ajax-post-loader/templates/term-filter/`.  
2. Create a file named `term-filter-button.php`.  
3. Edit the file to modify the button's HTML structure, CSS classes, or attributes.

### Example Filter Button Code  

    <button type="button" class="filter-button custom-class" data-button="quick-ajax-filter-button">QUICK_AJAX_LABEL</button>

In this example:
- **`QUICK_AJAX_LABEL`**: A dynamic label that changes based on the taxonomy being filtered.  
- **`data-button="quick-ajax-filter-button"`**: This attribute is essential for integration with the plugin's AJAX filtering logic, ensuring dynamic content updates without page reloads.  

---

## 4. Custom Loading Icons

The **Quick Ajax Post Loader** plugin allows you to customize loading icons by creating your own templates. These icons can include HTML, CSS animations, or GIFs, and they will be available for selection in the plugin configuration.

### Steps to Create a Custom Loading Icon  

1. Navigate to the directory:  
   `wp-content/themes/your-active-theme/quick-ajax-post-loader/templates/loader-icon/`.  
2. Create a file with a descriptive name, e.g., `loader-icon-custom-loader.php`.  
3. Add your custom HTML, CSS, or JavaScript code for the loading icon.  

### Example Loading Icon Code  

    <?php  
    /* Loader Icon Name: Custom Loader */  
    ?>  
    <div class="quick-ajax-loader-custom">  
        <!-- Add your custom HTML, CSS, or animations here -->
        <img src="images/loader_image.gif" alt="Loading..." />  
        <!-- Example CSS animation -->
        <div class="loader-dot"></div>  
        <div class="loader-dot"></div>  
        <div class="loader-dot"></div>  
    </div>

### Rules for Overriding and Loading Icons  

1. **Directory Placement**: Place the custom loading icon file in the child theme or theme directory:  
   `wp-content/themes/your-active-theme/quick-ajax-post-loader/templates/loader-icon/`.  
2. **Template Detection**: The plugin will automatically detect all files in this directory as available loading icons.  
3. **Loading Order**: The plugin follows this hierarchy to load templates:  
    - **Child Theme**  
    - **Parent Theme**  
    - **Plugin Defaults**  

This ensures that custom loading icons in the child theme take priority and are not overwritten during theme or plugin updates.

---

## 5. Using AJAX Function Generator

The **Quick Ajax Post Loader** plugin includes an **AJAX Function Generator** tool available in the **Quick Ajax > Settings & Features** menu under the "Function Generator" tab. This tool generates PHP code that can be directly implemented in theme files to dynamically load posts and taxonomies via AJAX.

### Steps to Use  

1. Navigate to **Quick Ajax > Settings & Features** and open the "Function Generator" tab.  
2. Configure the required parameters, such as:
   - **Query arguments**: Define the post type, number of posts, taxonomy terms, etc.  
   - **Attributes**: Set grid layout options, loader icon, custom CSS classes, etc.  
3. Copy the generated PHP code.  
4. Paste the code into your theme file (e.g., `page.php`, `single.php`, or a custom template) where you want the dynamic content to appear.  

### Example Implementation  

Below is an example of the code generated by the AJAX Function Generator, demonstrating the integration of a post grid and taxonomy filter:

    <?php  
    // Define AJAX query parameters for 'post' type posts.
    $quick_ajax_args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 6,
        'orderby' => 'date',
        'order' => 'DESC',
        'post__not_in' => array(3, 66),
        'ignore_sticky_posts' => 1,
    );

    // Define attributes for AJAX.
    $quick_ajax_attributes = array(
        'quick_ajax_id' => 8250,
        'quick_ajax_css_style' => 1,
        'grid_num_columns' => 3,
        'post_item_template' => 'post-item',
        'taxonomy_filter_class' => 'class-taxonomy filter-class',
        'container_class' => 'container-class',
        'loader_icon' => 'loader-icon'
    );

    // Set the taxonomy for filtering posts.
    $quick_ajax_taxonomy = 'category';

    // Render the navigation for 'category' terms.
    if(function_exists('qapl_quick_ajax_term_filter')):
        qapl_quick_ajax_term_filter(
            $quick_ajax_args,
            $quick_ajax_attributes,
            $quick_ajax_taxonomy
        );
    endif;

    // Render the grid for 'post' type posts.
    if(function_exists('qapl_quick_ajax_post_grid')):
       qapl_quick_ajax_post_grid(
           $quick_ajax_args,
           $quick_ajax_attributes
       );
    endif;
    ?>

---

## 6. Key Functions

### `qapl_quick_ajax_post_grid` Function

#### Description  
The `qapl_quick_ajax_post_grid` function is designed to render a dynamic post grid in WordPress using AJAX technology. It allows for the display of posts of a specific type without needing to reload the page, significantly improving user experience and performance.

#### Parameters  

- **`$quick_ajax_args` (array)**: Defines the query parameters for selecting posts.  
- **`$quick_ajax_attributes` (array)**: Configures the appearance and behavior of the post grid.

#### Example Usage  

    <?php  
    // Define AJAX query parameters for posts
    $quick_ajax_args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 6,
        'orderby' => 'date',
        'order' => 'DESC',
        'post__not_in' => array(3, 66, 100),
    );

    // Define attributes for the AJAX post grid
    $quick_ajax_attributes = array(
        'quick_ajax_id' => 12056,
        'quick_ajax_css_style' => 1,
        'grid_num_columns' => 3,
        'post_item_template' => 'post-item-custom-name',
        'taxonomy_filter_class' => 'class-one class-two',
        'container_class' => 'class-one class-two',
        'load_more_posts' => 4,
        'loader_icon' => 'loader-icon-quick-ajax-dot'
    );

    // Render the AJAX post grid
    if(function_exists('qapl_quick_ajax_post_grid')):
        qapl_quick_ajax_post_grid(
            $quick_ajax_args,
            $quick_ajax_attributes
        );
    endif;
    ```

#### Notes  
- Ensure that the `$quick_ajax_args` array is configured according to the posts you want to display.  
- Customize `$quick_ajax_attributes` to match the appearance and functionality requirements of your site.  
- This function relies on the **Quick Ajax Post Loader** plugin being active and properly configured.  

---

### `qapl_quick_ajax_term_filter` Function

#### Description  
The `qapl_quick_ajax_term_filter` function enables dynamic loading and updating of posts based on selected taxonomy terms, such as categories or tags, without reloading the entire page. It is an essential tool for creating interactive, filterable post lists in WordPress using AJAX.

#### Parameters  

- **`$quick_ajax_args` (array)**: Defines the query parameters for selecting posts.  
- **`$quick_ajax_attributes` (array)**: Configures the appearance and behavior of the taxonomy filter and post grid.  
- **`$quick_ajax_taxonomy` (string)**: Specifies the taxonomy used for filtering posts, e.g., `'category'` or `'tag'`.

#### Example Usage  

    <?php  
    // Define AJAX query parameters for posts
    $quick_ajax_args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 6,
        'orderby' => 'date',
        'order' => 'DESC',
        'post__not_in' => array(3, 66, 100),
    );

    // Define attributes for the AJAX taxonomy filter
    $quick_ajax_attributes = array(
        'quick_ajax_id' => 12056,
        'quick_ajax_css_style' => 1,
        'grid_num_columns' => 3,
        'post_item_template' => 'post-item-custom-name',
        'taxonomy_filter_class' => 'class-one class-two',
        'container_class' => 'class-one class-two',
        'load_more_posts' => 4,
        'loader_icon' => 'loader-icon-quick-ajax-dot',
    );

    // Specify the taxonomy to filter by
    $quick_ajax_taxonomy = 'category';

    // Render the AJAX taxonomy filter
    if (function_exists('qapl_quick_ajax_term_filter')):
        qapl_quick_ajax_term_filter(
            $quick_ajax_args,
            $quick_ajax_attributes,
            $quick_ajax_taxonomy
        );
    endif;
    ```

#### Notes  
- Ensure that the `$quick_ajax_args` array is configured to select the desired posts for filtering.  
- Use `$quick_ajax_attributes` to customize the appearance and behavior of the taxonomy filter and post grid.  
- The `$quick_ajax_taxonomy` parameter should match the taxonomy you wish to use for filtering, such as `'category'` or `'tag'`.

---

## 7. Detailed Parameter Descriptions  

### `$quick_ajax_args` Parameter  

#### Description  
The `$quick_ajax_args` parameter is crucial for configuring AJAX queries in the **Quick Ajax Post Loader** plugin. It allows for detailed specification of which posts to load and display in a post grid or using taxonomic filters, providing a dynamic and interactive user experience.

#### Application  
The `$quick_ajax_args` parameter is utilized in functions such as `qapl_quick_ajax_post_grid` and `qapl_quick_ajax_term_filter`. It enables flexible and advanced content management without the need for page reloads.

#### Parameters  
- **`post_type` (string)**: Type of posts to load, e.g., `'post'`, `'page'`, or custom post types.  
- **`post_status` (string)**: Status of posts to display, e.g., `'publish'`.  
- **`posts_per_page` (int)**: Number of posts to display per page.  
- **`orderby` (string)**: Criterion for sorting posts, e.g., `'date'`, `'title'`.  
- **`order` (string)**: Order of post sorting, e.g., `'ASC'`, `'DESC'`.  
- **`post__not_in` (array)**: An array of post IDs to exclude from the query.

---

### `$quick_ajax_attributes` Parameter  

#### Description  
The `$quick_ajax_attributes` parameter is used to configure the appearance and behavior options of post grids and taxonomy filters in the **Quick Ajax Post Loader** plugin for WordPress. It enables the customization of styles, number of columns, container classes, and other attributes that affect how dynamically loaded content is displayed and functions.

#### Application  
The `$quick_ajax_attributes` parameter is crucial when using functions such as `qapl_quick_ajax_post_grid` and `qapl_quick_ajax_term_filter`, enabling detailed personalization of AJAX-loaded content.

#### Parameters  
- **`quick_ajax_id` (int)**: A unique identifier for the AJAX instance, allowing multiple independent grids on the same page.  
- **`quick_ajax_css_style` (int)**: Enables or disables built-in Quick Ajax CSS styles.  
    - `0`: Disable default styles.
    - `1`: Enable default styles.
- **`grid_num_columns` (int)**: Specifies the number of columns in the post grid.  
- **`post_item_template` (string)**: Allows for the selection of a post template, e.g., `'post-item-custom-name'` for a custom template (file name without the `.php` extension).  
- **`taxonomy_filter_class` (string)**: Adds custom CSS classes to the taxonomy filter.  
- **`container_class` (string)**: Adds custom CSS classes to the post grid container.  
- **`load_more_posts` (int)**: Specifies the number of posts to load upon clicking the "Load More" button.  
- **`loader_icon` (int)**: Allows for the selection of a loading icon.
- **`ajax_initial_load` (int)**: Enables loading the initial set of posts via AJAX when the page loads. This helps to ensure that post data is always up-to-date, especially in cases where caching might display outdated content.

---

## 8. Available Actions

The **Quick Ajax Post Loader** plugin provides several action hooks that allow developers to customize the behavior and rendering of various elements, such as filters, post grids, and loading icons. These hooks enable greater flexibility and extend the plugin's functionality.

### Available Hooks  

#### Filter Wrapper Hooks  
- **`qapl_filter_wrapper_pre`**: Executes before rendering the AJAX filter wrapper. Ideal for adding custom HTML before the wrapper.  
- **`qapl_filter_wrapper_open`**: Executes at the start of the AJAX filter wrapper rendering. Allows for inserting content at the beginning of the wrapper.  
- **`qapl_filter_wrapper_close`**: Executes at the end of the AJAX filter wrapper rendering. Enables adding content just before closing the wrapper.  
- **`qapl_filter_wrapper_complete`**: Executes after rendering the AJAX filter wrapper.  

#### Posts Wrapper Hooks  
- **`qapl_posts_wrapper_pre`**: Executes before rendering the AJAX posts wrapper.  
- **`qapl_posts_wrapper_open`**: Executes right after opening the posts wrapper. Ideal for inserting content at the beginning of the posts section.  
- **`qapl_posts_wrapper_close`**: Executes just before closing the posts wrapper. Allows for adding content at the end of the posts section.  
- **`qapl_posts_wrapper_complete`**: Executes after rendering the AJAX posts wrapper.  

#### Load More Button Hooks  
- **`qapl_load_more_button_pre`**: Executes before rendering the "Load More" button.  
- **`qapl_load_more_button_complete`**: Executes after rendering the "Load More" button.  

#### Loader Icon Hooks  
- **`qapl_loader_icon_pre`**: Executes before rendering the loading icon.  
- **`qapl_loader_icon_complete`**: Executes after rendering the loading icon.  

### How to Use  

You can add your own actions using the `add_action()` function. Below is an example of how to add custom content before the AJAX filter wrapper:

    add_action('qapl_filter_wrapper_pre', function() {
        echo '<div class="custom-filter-wrapper">';
    });

### Example Usage  

Add the following code to your theme or plugin to customize a specific part of the plugin's operation:

    <?php
    add_action('qapl_filter_wrapper_pre', function() {
        echo 'Custom text before the filter navigation';
    });

    add_action('qapl_posts_wrapper_close', function() {
        echo '<p>Additional content at the end of the posts section</p>';
    });
    ```

### Notes  
- These hooks are designed to provide maximum flexibility for developers.  
- Test your custom actions to ensure they do not conflict with other plugins or themes.  
- Use these hooks responsibly to maintain the performance and usability of your site.


---

## 9. Available Filters

The **Quick Ajax Post Loader** plugin provides several filters to customize the behavior and appearance of AJAX-driven features. Below are the key filters, their descriptions, and examples of how to use them.

---

### `qapl_modify_query` Filter  

#### Description  
The `qapl_modify_query` filter allows for the customization of `WP_Query` arguments used in the Quick Ajax Post Loader plugin. This enables precise control over AJAX query results, tailoring them to meet the specific needs of your site.

#### Example Usage  

    add_filter('qapl_modify_query', function($args, $quick_ajax_id) {
        // Use the AJAX identifier to modify query arguments
        if ($quick_ajax_id === 'some_specific_id') {
            $args['posts_per_page'] = 5; // Limit to 5 posts per page
        }
        return $args;
    }, 10, 2);

#### Finding the `quick_ajax_id`  
- The `quick_ajax_id` is derived from the `id` attribute of the outer `<div>` containing the AJAX buttons.  
- Example:

      <div id="quick-ajax-p9" class="quick-ajax-posts-wrapper">

  In this case, the `quick_ajax_id` is `"p9"`.  
- For debugging, you can use `print_r($quick_ajax_id)` inside your modifying function to inspect the identifier during development. Avoid exposing this information in a production environment.

---

### `qapl_modify_term_buttons` Filter  

#### Description  
The `qapl_modify_term_buttons` filter allows customization of taxonomy filter buttons used to filter content dynamically. Developers can modify properties such as button labels and styles, providing tailored user experiences.

#### Example Usage  

    add_filter('qapl_modify_term_buttons', function($buttons, $quick_ajax_id) {
        foreach ($buttons as &$button) {
            if ($quick_ajax_id === 'some_specific_id') {
                // Customize "Show All" button label
                if ($button['term_id'] === 'none') {
                    $button['button_label'] = 'View All'; // Change to "View All"
                } else {
                    // Convert labels of other buttons to uppercase
                    $button['button_label'] = strtoupper($button['button_label']);
                }
            }
        }
        return $buttons;
    }, 10, 2);

#### Finding the `quick_ajax_id`  
- The `quick_ajax_id` can be found in the `id` attribute of the outer `<div>` containing the taxonomy filter buttons.  
- Example:

      <div id="quick-ajax-term-filter-p9" class="quick-ajax-filter-wrapper">

  In this case, the `quick_ajax_id` is `"p9"`.  
- For debugging purposes, you can use `print_r($quick_ajax_id)` to inspect the identifier while working on modifications. Avoid exposing this information to end users in a production environment.

