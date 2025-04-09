# Quick Ajax Post Loader Developer Guide

Welcome to the **Quick Ajax Post Loader** developer guide.
This guide is designed for developers who want to take full advantage of the plugin's advanced features, hooks, filters, and customization options.

---

## Why Use Quick Ajax Post Loader?

The **Quick Ajax Post Loader** plugin provides an efficient way to dynamically load content on WordPress websites using AJAX. This approach eliminates the need for page reloads, improving user experience and site performance.
Below are some of the key benefits of using this plugin:

- **Improved User Experience**: Content is loaded seamlessly without page reloads, providing a smooth browsing experience.
- **Customizability**: Easily customize post grids, taxonomy filters, loading icons, and more using hooks, filters, and templates.
- **Flexibility**: Supports multiple post types, taxonomies, and custom templates, making it suitable for various use cases.
- **Performance Optimization**: AJAX-based loading reduces server load by only fetching the required data.
- **Developer-Friendly**: Includes tools like the AJAX Function Generator and a variety of hooks and filters for deep integration.

---

## Table of Contents

1. [Installation & Activation](#1-installation--activation)
2. [Configuration & Using Shortcodes](#2-configuration--using-shortcodes)
3. [Templates: Overview & Customization](#3-templates-overview--customization)
    - 3.1. [Templates: How to Create Custom Post Layouts](#31-templates-how-to-create-custom-post-layouts)
    - 3.2. [Templates: How to Override Default Post Layouts](#32-templates-how-to-override-default-post-layouts)
    - 3.3. [Templates: Customize "No Posts Found" Message](#33-templates-customize-no-posts-found-message)
    - 3.4. [Templates: Customize "End of Posts" Message](#34-templates-customize-end-of-posts-message)
    - 3.5. [Templates: Modify Taxonomy Filter Buttons](#35-templates-modify-taxonomy-filter-buttons)
    - 3.6. [Templates: Customize "Load More" Button Design](#36-templates-customize-load-more-button-design)
    - 3.7. [Templates: How to Create Custom Loading Icons](#37-templates-how-to-create-custom-loading-icons)
    - 3.8. [Templates: Best Practices for Working with Post Layouts](#38-templates-best-practices-for-working-with-post-layouts)
4. [Customization with Hooks & Filters](#4-customization-with-hooks--filters)
    - 4.1. [Hooks: Filter Container - Modify the Filtering Section](#41-hooks-filter-container---modify-the-filtering-section)
    - 4.2. [Hooks: Post Container - Modify Post List Display](#42-hooks-post-container---modify-post-list-display)
    - 4.3. [Hooks: Load More Button - Customize Load More Behavior](#43-hooks-load-more-button---customize-load-more-behavior)
    - 4.4. [Hooks: Modify WP_Query Parameters for AJAX Requests](#44-hooks-modify-wp_query-parameters-for-ajax-requests)
    - 4.5. [Hooks: Modify Sorting Options for AJAX Queries](#45-hooks-modify-sorting-options-for-ajax-queries)
    - 4.6. [Hooks: Modify Taxonomy Filter Buttons](#46-hooks-modify-taxonomy-filter-buttons)
    - 4.7. [Hooks: Modify Template Elements](#47-hooks-modify-template-elements)
    - 4.8. [Hooks: Modifying Post Content Elements](#48-hooks-modifying-post-content-elements)
    - 4.9. [Hooks: Customize Load More Button HTML & Styling](#49-hooks-customize-load-more-button-html--styling)
    - 4.10. [Hooks: Customize End of Posts Message](#410-hooks-customize-end-of-posts-message)
    - 4.11. [Debugging: Find & Log quick_ajax_id for AJAX Hooks](#411-debugging-find--log-quick_ajax_id-for-ajax-hooks)
    - 4.12. [Best Practices for Hooks and Filters](#412-best-practices-for-hooks-and-filters)
5. [Advanced Features](#5-advanced-features)
    - 5.1. [AJAX Function Generator](#51-ajax-function-generator)
    - 5.2. [Key Functions & Parameters](#52-key-functions--parameters)
6. [Advanced Configuration of Quick Ajax Parameters](#6-advanced-configuration-of-quick-ajax-parameters)
    - 6.1. [$quick_ajax_args - Configuring AJAX Queries](#61-quick_ajax_args---configuring-ajax-queries)
    - 6.2. [$quick_ajax_attributes - Configuring AJAX Appearance & Behavior](#62-quick_ajax_attributes---configuring-ajax-appearance--behavior)

---

## 1. Installation & Activation

### Installation

#### Downloading the Plugin
To install **Quick Ajax Post Loader**, download the latest version of the plugin from the WordPress repository and install it using one of the following methods:

#### Installing via the WordPress Admin Panel
1. Log in to your WordPress dashboard and go to **Plugins > Add New**.
2. Search for **Quick Ajax Post Loader** or upload the ZIP file downloaded from the repository.
3. Click **Install Now**, then **Activate**.

#### Manual Installation via FTP
1. Extract the downloaded ZIP file.
2. Upload the entire **quick-ajax-post-loader** folder to the `wp-content/plugins/` directory on your server.
3. In the WordPress admin panel, go to **Plugins > Installed Plugins** and click **Activate** next to **Quick Ajax Post Loader**.

### Activation and First Steps
After activating the plugin, a new **Quick Ajax** menu item will appear in the WordPress admin panel, where you can configure settings and shortcodes. Before you start using the plugin, make sure that:
- Your WordPress version is up to date (recommended version: 5.6+).
- Your server supports PHP version 7.4 or higher.
- The plugin is compatible with your theme - if you encounter issues, check the **Error Log** or contact support.

---

## 2. Configuration & Using Shortcodes

The **Quick Ajax Post Loader** plugin allows dynamic post loading using shortcodes. Shortcodes provide a flexible way to display posts with AJAX-based filtering, sorting, and pagination.

In this section, you will learn how to:
- **Create and configure a shortcode** using the WordPress admin panel.
- **Customize sorting, filtering, and layout settings** to match your needs.
- **Use the shortcode** inside pages and posts to dynamically load content.
- **Test and troubleshoot** shortcode behavior.

Each subsection provides detailed information on how to fine-tune these settings.

### Creating a Shortcode

To add a new shortcode:

1. Go to **Quick Ajax > Shortcodes** in the WordPress admin panel.
2. Click **Add New Shortcode** and fill in the configuration form.
3. Enter the following details:
   - **Shortcode Name** - Provide a name for the configuration, e.g., "My Post List."
   - **Select Post Type** - Choose the type of content to load (e.g., posts, pages, or custom post types).
   - **Show Taxonomy Filter** - Enable or disable filtering by category or tag.
   - **Select Taxonomy** - If filtering is enabled, select which taxonomy (e.g., categories, tags) will be used.
   - **Posts Per Page** - Define how many posts will be loaded in a single AJAX request.

After saving the settings, copy the generated shortcode, e.g.:

    [qapl-quick-ajax id="125" title="My Post List"]

Paste it anywhere on a page or post to display dynamically loaded posts.

### Sorting Settings

This section allows you to configure how posts are sorted when loaded via AJAX.

- **Default Sort Order** - Choose whether posts should be displayed in ascending or descending order.
- **Default Sort By** - Select the sorting criteria (e.g., by title, date, or comment count).
- **Show Sorting Button** - Allows users to switch between ascending and descending sorting.
- **Available Sorting Options** - Choose which sorting methods will be available (e.g., newest, oldest, most popular).
- **Inline Filter & Sorting** - Display sorting and filtering controls in a single row.

### Additional Settings

This section allows you to configure extra AJAX query parameters:

- **Excluded Post IDs** - Specify post IDs that should be excluded from the results.
- **Ignore Sticky Posts** - Treat sticky posts as regular entries.
- **Load Initial Posts via AJAX** - If disabled, the first batch of posts will be pre-rendered in HTML instead of loaded dynamically.
- **Enable Infinite Scroll** - Enable this option to automatically load more posts via AJAX as the user scrolls down the page.


### Layout Settings

Customize how posts are displayed:

- **Apply Quick AJAX CSS Style** - Use built-in Quick AJAX styles for consistent layout and spacing.
- **Number of Columns** - Define the number of columns in the post grid.
- **Select Post Item Template** - Choose between the default or a custom post template.
- **Add class to taxonomy filter** - Add custom CSS classes to the taxonomy filter section.
- **Add class to post container** - Add custom CSS classes to the post container for styling flexibility.
- **Custom Load More Post Quantity** - Set how many posts load each time the **"Load More"** button is clicked.
- **Override Global Loader Icon** - Customize the loading animation icon displayed during AJAX requests.


### Using the Shortcode on a Page

To insert the shortcode into a post or page:

1. Open a page or post in the WordPress editor.
2. Paste the generated shortcode into the content (use the **Shortcode** block in Gutenberg or the Classic Editor).
3. Save changes and preview the page.

### Testing and Verification

After adding the shortcode, verify its behavior:

- **Dynamic Loading** - Posts should appear without requiring a full-page reload.
- **Filtering** - If filters are enabled, test their functionality.
- **Sorting** - Confirm that sorting options modify the post order as expected.
- **"Load More" Button** - Check that additional posts load correctly.
- **Post Grid Layout** - Ensure that the layout adapts properly to different screen sizes.

If you encounter issues, return to **Quick Ajax > Shortcodes** settings and adjust the configuration.

### Shortcode Best Practices

- **Test shortcodes on a staging site** before deploying them to a live website.
- **Ensure theme compatibility** - Some themes may override styling, affecting the layout.
- **Refer to the documentation** - Learn more about **custom templates, hooks, and the AJAX Function Generator** for advanced customization.

---

## 3. Templates: Overview & Customization

The **Quick Ajax Post Loader** plugin allows full customization of how posts are displayed. You can create custom post templates, override default templates, and modify interface elements such as **filter buttons, the "Load More" button, and the "No Posts Found" message**.

Additionally, the plugin provides **hooks and filters**, allowing further customization of elements such as **post date, thumbnail, title, excerpt, and the "Read More" button**. A complete list of available filters can be found in the **"Customization with Hooks & Filters"** section.

---

### 3.1. Templates: How to Create Custom Post Layouts

You can create custom post templates to adjust the layout and structure of dynamically loaded posts.

#### Template File Location

To add a custom template, place a PHP file in your theme directory (preferably in a child theme) at the following path:

    wp-content/themes/your-theme/quick-ajax-post-loader/templates/post-items/

#### Creating a New Template File

1. Create a new PHP file with a name starting with **"post-item"**, e.g.:

    post-item-custom-name.php

2. Add a header with the template name:

    <?php
    /* Post Item Name: My Custom Template */
    ?>

3. Define the HTML structure and PHP logic for rendering a single post.

Example template code:

    <?php
    /* Post Item Name: My Custom Template */
    ?>
    <div class="qapl-post-item">
       <a href="<?php echo get_permalink(); ?>">
          <h2><?php the_title(); ?></h2>
          <p><?php the_excerpt(); ?></p>
       </a>
    </div>

#### Testing the Template

1. Save the file and go to the shortcode settings.
2. Select the new template from the available options.
3. Test whether the posts are rendered correctly using the new template.

---

### 3.2. Templates: How to Override Default Post Layouts

The plugin allows you to **override default post templates** without modifying the plugin files.

#### Default Template Location

The default post templates are located in the plugin folder. To override them, copy the relevant file to your theme's template directory and make modifications:

    wp-content/themes/your-theme/quick-ajax-post-loader/templates/post-items/

#### Example of Overriding a Default Template

1. Copy the **post-item.php** file from the plugin folder to your theme's template directory.
2. Modify its HTML structure, add custom CSS classes, or change the way content is displayed.

WordPress follows this template loading priority:
1. It first loads templates from the **child theme**.
2. Then, it loads templates from the **parent theme**.
3. If no matching file is found, the plugin uses the **default templates**.

---

### 3.3. Templates: Customize "No Posts Found" Message

By default, if no posts match the AJAX query, the plugin displays a **"No Posts Found"** message. You can customize this message by creating a custom template file.

#### Template File Location

Place a file named **no-posts.php** in the following directory:

    wp-content/themes/your-theme/quick-ajax-post-loader/templates/post-items/

#### Example File Structure

    <div class="qapl-no-posts">
       <p>Sorry, no posts found to display.</p>
    </div>

---

### 3.4. Templates: Customize "End of Posts" Message

When all posts have been loaded via AJAX and there are no more available to show, the plugin can display a customizable end message.

#### Template File Location

Place a file named **end-posts.php** in the following directory:

    wp-content/themes/your-theme/quick-ajax-post-loader/templates/post-items/

#### Example File Structure

    <div class="qapl-end-message">
        <p>No more posts to load.</p>
    </div>

---

### 3.5. Templates: Modify Taxonomy Filter Buttons

Taxonomy filter buttons allow users to select categories, tags, or other taxonomies.

#### Template File Location

Place the filter button template file in the following directory:

    wp-content/themes/your-theme/quick-ajax-post-loader/templates/taxonomy-filter/

#### Creating or Overriding the File

Create or edit the **taxonomy-filter-button.php** file.

#### Example File Structure

    <button type="button" class="qapl-filter-button custom-class" data-button="quick-ajax-filter-button">
       QUICK_AJAX_LABEL
    </button>  

#### Note:
- The **label (`QUICK_AJAX_LABEL`) and `data-button` attribute are required** for filtering to work correctly.
- You can add **custom CSS styles** to make the buttons match your site's design.

---

### 3.6. Templates: Customize "Load More" Button Design

The "Load More" button allows users to dynamically load additional posts via AJAX without refreshing the page.  
The plugin provides a default button template, but you can override it with your custom design.

#### Template File Location

The default "Load More" button template is located in the plugin directory:

    quick-ajax-post-loader/templates/loader-more-button.php

To override it, copy it to your theme folder:

    wp-content/themes/your-theme/quick-ajax-post-loader/templates/


#### Example File Structure

     <button type="button" class="qapl-load-more-button custom-class" data-button="quick-ajax-load-more-button">
        Load More
     </button>
    
#### Modifying the Template

- Adjust the **HTML structure**, CSS classes, or button text to match your website's design.


---

### 3.7. Templates: How to Create Custom Loading Icons

The **Quick Ajax Post Loader** plugin allows you to customize loading icons by creating your own templates. You can use HTML, CSS animations, or GIFs, and then select the icon in the plugin configuration.

#### Steps to Create a Custom Loading Icon

1. Navigate to the directory:

    wp-content/themes/your-active-theme/quick-ajax-post-loader/templates/loader-icon/

2. Create a file with a descriptive name, e.g., **loader-icon-custom-loader.php**.
3. Add your custom HTML, CSS, or JavaScript code for the loading icon.

#### Example Loading Icon Cod

    <?php
    /* Loader Icon Name: Custom Loader */
    ?>
    <div class="qapl-loader-custom">
        <!-- Add your custom HTML, CSS, or animations -->
        <img src="images/loader_image.gif" alt="Loading..." />
        <!-- Example CSS animation -->
        <div class="loader-dot"></div>
        <div class="loader-dot"></div>
        <div class="loader-dot"></div>
    </div>

#### Rules for Overriding and Loading Icons

1. **Directory Placement**: Place the custom loading icon file in the child theme or theme directory:

    wp-content/themes/your-active-theme/quick-ajax-post-loader/templates/loader-icon/

2. **Template Detection**: The plugin automatically detects all files in this directory as available loading icons.
3. **Loading Order**:
    - **First**, the plugin checks the child theme directory.
    - **Next**, it checks the parent theme directory.
    - **If no file is found**, it uses the default icons from the plugin.

This ensures that custom loading icons in the child theme take priority and are not overwritten during theme or plugin updates.

---

### 3.8. Templates: Best Practices for Working with Post Layouts

- **Work with a child theme** - This ensures that your changes won't be lost when updating your theme or the plugin.  
- **Test all changes on a staging site** before deploying them to your live site.  
- **Use clear comments in your modifications** to make future maintenance easier.  
- **Utilize dedicated hooks and filters** to introduce changes **without modifying core plugin template files**.  

---

## 4. Customization with Hooks & Filters

The **Quick Ajax Post Loader** plugin offers extensive customization options through **hooks and filters**. These allow you to modify various aspects of the plugin, including:

- **Container Hooks** - modify the structure of the plugin's frontend by adding custom elements in predefined locations.
- **Query Filters** - adjust how posts are retrieved, sorted, and displayed.
- **Content Filters** - customize the appearance and structure of individual post items.

By using these hooks and filters, you can seamlessly integrate the plugin into your theme and tailor its functionality to match your specific needs.

---

### 4.1. Hooks: Filter Container - Modify the Filtering Section

Filter container hooks allow you to insert custom elements within the filter section of the plugin interface. These hooks provide flexibility to enhance filtering options or add additional UI components.

Below is a list of available hooks and their usage.

#### **qapl_filter_container_before**
Triggered just before rendering the filter container.

**Parameters:**
- `$quick_ajax_id` *(string)* - the unique identifier of the plugin instance.

**Example:**

    function my_filter_container_before( $quick_ajax_id ) {
        if ( $quick_ajax_id === 'example_id' ) {
            echo '<div class="custom-filter-header">My Custom Filter Section</div>';
        }
    }
    add_action( 'qapl_filter_container_before', 'my_filter_container_before', 10, 1 );

#### **qapl_filter_container_start**
Triggered at the beginning of the filter container rendering.

**Example:**

    function my_filter_container_start( $quick_ajax_id ) {
        echo '<div class="custom-filter-start">Start of the filter container</div>';
    }
    add_action( 'qapl_filter_container_start', 'my_filter_container_start', 10, 1 );

#### **qapl_filter_container_end**
Triggered just before finishing the rendering of the filter container.

**Example:**

    function my_filter_container_end( $quick_ajax_id ) {
        echo '<div class="custom-filter-end">End of the filter container</div>';
    }
    add_action( 'qapl_filter_container_end', 'my_filter_container_end', 10, 1 );

#### **qapl_filter_container_after**
Triggered immediately after rendering the filter container.

**Example:**

    function my_filter_container_after( $quick_ajax_id ) {
        echo '<div class="custom-filter-after">After the filter container</div>';
    }
    add_action( 'qapl_filter_container_after', 'my_filter_container_after', 10, 1 );

---

### 4.2. Hooks: Post Container - Modify Post List Display

Post container hooks allow you to modify the area where dynamically loaded posts are displayed. These hooks can be used to add custom elements before or after the post list, style the container, or display additional information.

Here are the available post container hooks.

#### **qapl_posts_container_before**
Triggered before rendering the post container.

**Example:**

    function my_posts_container_before( $quick_ajax_id ) {
        echo '<div class="custom-posts-before">Before the post container</div>';
    }
    add_action( 'qapl_posts_container_before', 'my_posts_container_before', 10, 1 );

#### **qapl_posts_container_start**
Triggered at the beginning of the post container rendering.

**Example:**

    function my_posts_container_start( $quick_ajax_id ) {
        echo '<div class="custom-posts-start">Start of the post container</div>';
    }
    add_action( 'qapl_posts_container_start', 'my_posts_container_start', 10, 1 );

#### **qapl_posts_container_end**
Triggered just before finishing the rendering of the post container.

**Example:**

    function my_posts_container_end( $quick_ajax_id ) {
        echo '<div class="custom-posts-end">End of the post container</div>';
    }
    add_action( 'qapl_posts_container_end', 'my_posts_container_end', 10, 1 );

#### **qapl_posts_container_after**
Triggered immediately after rendering the post container.

**Example:**

    function my_posts_container_after( $quick_ajax_id ) {
        echo '<div class="custom-posts-after">After the post container</div>';
    }
    add_action( 'qapl_posts_container_after', 'my_posts_container_after', 10, 1 );

---

### 4.3. Hooks: Load More Button - Customize Load More Behavior

Load More button hooks allow you to modify the behavior and appearance of the **"Load More"** button and its surrounding elements. These hooks give you control over what happens before and after the loading process, enabling you to add custom animations, messages, or additional elements.

Here are the available hooks for customizing the loading experience:

#### **qapl_loader_before**
Triggered before rendering the loading element (e.g., "Load More" button or loading animation).

**Example:**

    function my_loader_before( $quick_ajax_id ) {
        echo '<div class="custom-loader-before">Loading starts...</div>';
    }
    add_action( 'qapl_loader_before', 'my_loader_before', 10, 1 );

#### **qapl_loader_after**
Triggered after rendering the loading element.

**Example:**

    function my_loader_after( $quick_ajax_id ) {
        echo '<div class="custom-loader-after">Loading finished</div>';
    }
    add_action( 'qapl_loader_after', 'my_loader_after', 10, 1 );

---

### 4.4. Hooks: Modify WP_Query Parameters for AJAX Requests

Query modification hooks allow you to **customize the post retrieval process** by altering WP_Query arguments before the AJAX request is executed. This enables fine-grained control over which posts are displayed.

#### **qapl_modify_posts_query_args**
This filter allows you to modify **WP_Query** arguments to fully control the data retrieved by AJAX requests.

**Parameters:**
- `$args` *(array)* - original query arguments.
- `$quick_ajax_id` *(string)* - unique instance identifier.

**Example:**

    function modify_query_args( $args, $quick_ajax_id ) {
        if ($quick_ajax_id === 'some_specific_id') {
            $args['posts_per_page'] = 5; // change posts per page to 5
        }
        return $args;
    }
    add_filter( 'qapl_modify_posts_query_args', 'modify_query_args', 10, 2 );

This example shows how to change the number of posts per page to **5**, using a specific AJAX identifier.

---

### 4.5. Hooks: Modify Sorting Options for AJAX Queries

Sorting filters allow you to **customize or extend the available sorting methods** used by the AJAX query. This can be useful for adding new sorting options based on custom fields, modified dates, or taxonomies.

#### **qapl_modify_sorting_options_variants**
This filter allows modifying or extending the available sorting methods.

**Parameters:**
- `$sorting_options` *(array)* - array containing sorting options.
- `$quick_ajax_id` *(string)* - unique instance identifier.

**Example:**

    function modify_sorting_options_variants( $sorting_options, $quick_ajax_id ) {
    if ($quick_ajax_id === 'p369') {
        $sorting_options[] = [
            'orderby' => 'modified',
            'order'   => 'DESC',
            'label'   => 'Modify date',
        ];
    }
    return $sorting_options;
}
add_filter( 'qapl_modify_sorting_options_variants', 'modify_sorting_options_variants', 10, 2 );

This example adds a **sorting option based on the last modified date** for the AJAX instance with **quick_ajax_id = 'p369'**.

---

### 4.6. Hooks: Modify Taxonomy Filter Buttons

Taxonomy filter hooks let you **modify the taxonomy filter buttons**, changing their appearance, labels, or even adding custom logic.

#### **qapl_modify_taxonomy_filter_buttons**
This filter allows modifying the properties of taxonomy filter buttons.

**Parameters:**
- `$buttons` *(array)* - array containing button data.
- `$quick_ajax_id` *(string)* - unique instance identifier.

**Example:**

    function modify_filter_buttons( $buttons, $quick_ajax_id ) {
        foreach ($buttons as &$button) {
            if ($quick_ajax_id === 'some_specific_id') {
                if ($button['term_id'] === 'none') {
                    $button['button_label'] = 'View All';
                } else {
                    $button['button_label'] = strtoupper($button['button_label']);
                }
            }
        }
        return $buttons;
    }
    add_filter( 'qapl_modify_taxonomy_filter_buttons', 'modify_filter_buttons', 10, 2 );

This example changes the **"Show All"** button label to **"View All"** and converts the labels of other filter buttons to uppercase.

---

### 4.7. Hooks: Modify Template Elements

The **Quick Ajax Post Loader** plugin provides template hooks that allow modifying the structure and appearance of dynamically loaded posts. 

These hooks let you customize key elements such as:
- **Post title, date, image, and excerpt**
- **"Read More" label inside post content**
- **"Load More" button styles and behavior**

Below are the main hooks you can use to modify your post templates.

---

### 4.8. Hooks: Modifying Post Content Elements

These filters allow customizing different parts of the post content.

### Modifying the Post Title

#### **qapl_template_post_item_title**

Modify the **title HTML output**.

**Parameters:**
- `$output` *(string)* - the original HTML of the post title.
- `$template` *(string)* - the template file name.
- `$quick_ajax_id` *(string)* - unique instance identifier.

**Example:**

    function customize_post_title( $output, $template, $quick_ajax_id ) {
        if ( $template === 'post-item' ) {
            $output = '<div class="qapl-post-title"><h2 class="custom-title">' . esc_html(get_the_title()) . '</h2></div>';
        }
        return $output;
    }
    add_filter( 'qapl_template_post_item_title', 'customize_post_title', 10, 3 );

This example wraps the title in an **H2 tag** and adds a permalink.

### Modifying the Post Excerpt

#### **qapl_template_post_item_excerpt**

Modify the **excerpt HTML output**.

**Parameters:**
- `$output` *(string)* - the original HTML of the post excerpt.
- `$template` *(string)* - the template file name.
- `$quick_ajax_id` *(string)* - unique instance identifier.

**Example:**

    function customize_post_excerpt( $output, $template, $quick_ajax_id ) {
        if ( $template === 'post-item' ) {
            $output = '<div class="qapl-post-description"><p class="custom-excerpt">' . wp_trim_words(get_the_excerpt(), 15) . '</p></div>';
        }
        return $output;
    }
    add_filter( 'qapl_template_post_item_excerpt', 'customize_post_excerpt', 10, 3 );

This example limits the excerpt to **15 words** and wraps it in a `<p>` tag.

### Modifying the Post Image

#### **qapl_template_post_item_image**

Modify the **featured image (thumbnail)**.

**Parameters:**
- `$output` *(string)* - the original HTML of the post thumbnail.
- `$template` *(string)* - the template file name (e.g., `'post-item.php'`).
- `$quick_ajax_id` *(string)* - unique instance identifier.

**Example:**

    function customize_post_image( $output, $template, $quick_ajax_id ) {
        if ( $template === 'post-item' ) {
            $output = '<div class="qapl-post-image"><img src="' . esc_url(get_the_post_thumbnail_url(null, "large")) . '" alt="' . esc_attr(get_the_title()) . '"></div>';
        }
        return $output;
    }
    add_filter( 'qapl_template_post_item_image', 'customize_post_image', 10, 3 );

This example forces **large images** instead of using the default thumbnail size.

### Modifying the Post Date

#### **qapl_template_post_item_date**

Modify how the **post date** is displayed.

**Parameters:**
- `$output` *(string)* - the original HTML of the post date.
- `$template` *(string)* - the template file name (e.g., `'post-item.php'`).
- `$quick_ajax_id` *(string)* - unique instance identifier.

**Example:**

    function customize_post_date( $output, $template, $quick_ajax_id ) {
        if ( $template === 'post-item' ) {
            $new_date = get_the_date( 'd-m-Y' );
            $output = '<div class="qapl-post-date"><div class="custom-date">Date: ' . esc_html( $new_date ) . '</div></div>';
        }
        return $output;
    }
    add_filter( 'qapl_template_post_item_date', 'customize_post_date', 10, 3 );

This example changes the **date format** and wraps it in a `<div class="custom-date"></div>` container.

### Modifying the Read More Label

#### **qapl_template_post_item_read_more**

Modify the **"Read More" label inside post content**.

**Parameters:**
- `$output` *(string)* - the original HTML of the "Read More" Label.
- `$template` *(string)* - the template file name.
- `$quick_ajax_id` *(string)* - unique instance identifier.

**Example:**

    function customize_read_more( $output, $template, $quick_ajax_id ) {
        if ( $template === 'post-item' ) {
            $output = '<div class="qapl-read-more custom-read_more"><p>Read Full Article</p></div>';
        }
        return $output;
    }
    add_filter( 'qapl_template_post_item_read_more', 'customize_read_more', 10, 3 );

This example changes the **text of the "Read More" button** to "Read Full Article".

---

### 4.9. Hooks: Customize Load More Button HTML & Styling

Customize the **"Load More" button** styling and behavior.

#### **qapl_template_load_more_button**

Modify the **"Load More" button** HTML output.

**Parameters:**
- `$output` *(string)* - the original HTML of the button.
- `$quick_ajax_id` *(string)* - unique instance identifier.

**Example:**

    function customize_load_more_button( $output, $quick_ajax_id ) {
        $output = '<button type="button" class="custom-load-more qapl-load-more-button qapl-button" data-button="quick-ajax-load-more-button">Show More Posts</button>';
        return $output;
    }
    add_filter( 'qapl_template_load_more_button', 'customize_load_more_button', 10, 2 );

This example replaces the **default "Load More" button** with a custom-styled version.

---

### 4.10. Hooks: Customize End of Posts Message

Customize the **"End of Posts" message** displayed when all posts have been loaded and there are no more items to show.

#### **qapl_template_end_post_message**

Modify the HTML output for the final message shown when no more posts are available to load.

**Parameters:**
- `$output` *(string)* - the default message HTML output.
- `$template_name` *(string)* - the template type used.
- `$quick_ajax_id` *(string)* - unique instance identifier.

**Example:**

    function customize_end_post_message( $output, $quick_ajax_id ) {
        $output = '<div class="custom-end-message"><p>That\'s all we have for now!</p></div>';
        return $output;
    }
    add_filter( 'qapl_template_end_post_message', 'customize_end_post_message', 10, 2 );

This example replaces the **default "End of Posts" message** with a custom-styled HTML block.

---

### 4.11. Debugging: Find & Log quick_ajax_id for AJAX Hooks

Each **quick_ajax_id** is unique to an instance of the **Quick Ajax Post Loader** shortcode. It is needed when using hooks and filters to apply changes to a specific shortcode instance.

### Finding quick_ajax_id in the Page Source
To manually find the **quick_ajax_id**, follow these steps:

1. Open the page where the shortcode is used.
2. Right-click on the dynamically loaded posts and select **Inspect** (Chrome) or **Inspect Element** (Firefox).
3. Find a `div` element with an **id** that starts with `"quick-ajax-"`.

**Example HTML Structure:**

    <div id="quick-ajax-p963" class="quick-ajax-posts-container"></div>

In this case, the **quick_ajax_id** is **"p963"**.

Once you have the correct **quick_ajax_id**, you can use it in hooks and filters to modify only the selected instance of the plugin.

### Debugging quick_ajax_id

If you need to **programmatically find and verify the quick_ajax_id**, use one of the methods below.

#### Display quick_ajax_id in the Browser Console

Use this method if you want to debug **quick_ajax_id** directly in your browser.

**Example:**

    function debug_quick_ajax_id( $args, $quick_ajax_id ) {
        if (is_user_logged_in()) { // Ensure this is visible only to logged-in users
            echo '<script>console.log("Quick Ajax ID: ' . esc_js($quick_ajax_id) . '")</script>';
        }
        return $args;
    }
    add_filter( 'qapl_modify_posts_query_args', 'debug_quick_ajax_id', 10, 2 );

**How to use it?**
- Open **Developer Console** (`F12 > Console`).
- Look for **"Quick Ajax ID: ..."** message.

#### Log quick_ajax_id in the PHP Error Log

If you prefer debugging on the **server side**, log the **quick_ajax_id** into the PHP error log.

**Example: Log quick_ajax_id to wp-content/debug.log**

    function log_quick_ajax_id( $args, $quick_ajax_id ) {
        error_log('Quick Ajax ID: ' . $quick_ajax_id);
        return $args;
    }
    add_filter( 'qapl_modify_posts_query_args', 'log_quick_ajax_id', 10, 2 );

**Where to check logs?**
- Enable `WP_DEBUG_LOG` in `wp-config.php`
- Find logs in **wp-content/debug.log**
- Alternatively, check your **server error logs**.

**Warning:** Do not log sensitive data in production.

---

### 4.12. Best Practices for Hooks and Filters

To ensure safe and effective modifications to the **Quick Ajax Post Loader**, follow these best practices:

- **Use filters and hooks instead of modifying core plugin files.**  
- **Target only specific instances** using `$quick_ajax_id`, instead of applying global changes.  
- **Test all modifications on a staging site** before applying them to a live website.  
- **Use debugging tools** such as `error_log(print_r($args, true));` to inspect filter output.  
- **Check for conflicts with other plugins and themes** when applying modifications.  
- **Follow WordPress coding standards** to ensure compatibility and maintainability.  
- **Document your changes** if working in a team or managing multiple sites.  

By following these practices, you can ensure that your custom modifications are **reliable, efficient, and easy to maintain**.

---

## 5. Advanced Features

The **Quick Ajax Post Loader** plugin provides additional features that allow for even more advanced configuration and customization of the plugin's behavior.  
In this section, you will find:

- **AJAX Function Generator**
- **Key Functions & Parameters**

---

### 5.1. AJAX Function Generator

### Description

The **AJAX Function Generator** is a tool available in the WordPress admin panel under **Quick Ajax > Settings & Features**, in the **"Function Generator"** tab.  
It allows you to generate PHP code that can be placed directly in theme files such as **page.php, single.php**, or other page templates.

The generated code works similarly to shortcodes but provides greater flexibility since it can be embedded in PHP files.

### Example Code Generated by Function Generator"

The following code enables dynamically displaying posts via AJAX without the need to refresh the page.

    <?php
    // Define AJAX query parameters for 'post' type posts.
    $quick_ajax_args = [
        'post_type' => 'post',
        'posts_per_page' => 6,
        'orderby' => 'date',
        'order' => 'DESC',
        'post__not_in' => [3, 66, 999],
        'ignore_sticky_posts' => 1,
    ];

    // Define attributes for AJAX.
    $quick_ajax_attributes = [
        'quick_ajax_id' => 15298,
        'quick_ajax_css_style' => 1,
        'grid_num_columns' => 3,
        'post_item_template' => 'post-item',
        'taxonomy_filter_class' => 'class-taxonomy filter-class',
        'container_class' => 'container-class',
        'load_more_posts' => 4,
        'loader_icon' => 'loader-icon',
        'ajax_initial_load' => 1,
        'infinite_scroll' => 1
    ];

    // Set the sort options for the button.
    $quick_ajax_sort_options = ['date-desc', 'date-asc', 'comment_count-desc', 'title-asc', 'title-desc', 'rand'];

    // Render the sorting control button.
    if(function_exists('qapl_render_sort_controls')):
        qapl_render_sort_controls(
            $quick_ajax_args,
            $quick_ajax_attributes,
            $quick_ajax_sort_options
        );
    endif;

    // Set the taxonomy for filtering posts.
    $quick_ajax_taxonomy = 'category';

    // Render the navigation for 'category' taxonomy.
    if(function_exists('qapl_render_taxonomy_filter')):
        qapl_render_taxonomy_filter(
            $quick_ajax_args,
            $quick_ajax_attributes,
            $quick_ajax_taxonomy
        );
    endif;

    // Render the grid for 'post' type posts.
    if(function_exists('qapl_render_post_container')):
    qapl_render_post_container(
        $quick_ajax_args,
        $quick_ajax_attributes
    );
    endif;
    ?>

---

### 5.2. Key Functions & Parameters

### qapl_render_post_container

Function responsible for rendering the **grid of dynamically loaded posts**.

**Parameters:**

- **$quick_ajax_args** - WP_Query arguments array.
- **$quick_ajax_attributes** - display attributes array.

### qapl_render_taxonomy_filter

Function that generates **filter buttons** for a selected taxonomy.

**Parameters:**

- **$quick_ajax_args** - WP_Query arguments array.
- **$quick_ajax_attributes** - display attributes array.
- **$quick_ajax_taxonomy** - taxonomy name (e.g., `'category'`, `'tag'`).

### qapl_render_sort_controls

This function generates **sorting buttons**, allowing users to dynamically change the order of displayed posts without refreshing the page. Users can select different sorting criteria, such as by date, comment count, or title.

**Parameters:**

- **$quick_ajax_args** - WP_Query arguments array.
- **$quick_ajax_attributes** - display attributes array.
- **$quick_ajax_sort_options** - available sorting options.

### Advanced Features Tips

- **Test all changes** in a staging environment before deploying them.  
- **Use the generated code** to avoid errors.  
- **Customize attributes in the PHP code** instead of relying solely on shortcodes.  

---

## 6. Advanced Configuration of Quick Ajax Parameters

The **Quick Ajax Post Loader** plugin allows detailed configuration of how posts are retrieved and displayed using two main sets of parameters:

- **$quick_ajax_args** controls **which posts** are retrieved.  
- **$quick_ajax_attributes** controls **how posts** are displayed.  

If you want to customize post loading behavior, use these parameters in the **qapl_render_post_container** and **qapl_render_taxonomy_filter** functions.


---

### 6.1. $quick_ajax_args - Configuring AJAX Queries

**$quick_ajax_args** is an array of arguments passed to **WP_Query**, which determines which posts are retrieved via AJAX.

### Available Options:

- **post_type** *(string)* - the post type to retrieve, e.g., `'post'`, `'page'`, or custom post types.
- **posts_per_page** *(int)* - the number of posts displayed per page.
- **orderby** *(string)* - the sorting criteria for posts, e.g., `'date'`, `'title'`.
- **order** *(string)* - the order in which posts are sorted, e.g., `'ASC'`, `'DESC'`.
- **post__not_in** *(array)* - an array of post IDs to exclude.
- **ignore_sticky_posts** *(bool)*  
  - `true` - ignores sticky posts.
  - `false` - follows WordPress default behavior.

### Example Configuration of $quick_ajax_args

    $quick_ajax_args = [
        'post_type'           => 'post',
        'posts_per_page'      => 6,
        'orderby'             => 'date',
        'order'               => 'DESC',
        'post__not_in'        => [3, 66, 100],
        'ignore_sticky_posts' => true,
    ];

This setup fetches **the 6 most recent posts**, ignores **sticky posts**, and excludes posts with **IDs 3, 66, and 100**.

---

### 6.2. $quick_ajax_attributes - Configuring AJAX Appearance & Behavior

**$quick_ajax_attributes** defines how dynamically loaded posts are displayed and how users interact with them.

### Available Options:

- **quick_ajax_id** *(int)* - a unique identifier for the AJAX instance, allowing multiple independent post grids on a single page.
- **quick_ajax_css_style** *(int)* - enables or disables the built-in Quick Ajax CSS styles.
- **grid_num_columns** *(int)* - defines the number of columns in the post grid.
- **post_item_template** *(string)* - allows selecting a custom post template, e.g., `'post-item-custom-name'` (use the file name without the `.php` extension).
- **taxonomy_filter_class** *(string)* - adds custom CSS classes to the taxonomy filter.
- **container_class** *(string)* - adds custom CSS classes to the post grid container.
- **load_more_posts** *(int)* - defines the number of posts to load when the **"Load More"** button is clicked.
- **loader_icon** *(int)* - allows choosing a loading icon.
- **infinite_scroll** *(int)* - enables or disables infinite scroll. When enabled, more posts will automatically load via AJAX as the user scrolls down the page.
- **ajax_initial_load** *(int)* - enables loading the initial set of posts via AJAX on page load. This feature ensures posts are up-to-date, especially in cases of caching issues.

### Example Configuration of $quick_ajax_attributes

    $quick_ajax_attributes = [
        'quick_ajax_id'         => 12056,
        'quick_ajax_css_style'  => 1,
        'grid_num_columns'      => 3,
        'post_item_template'    => 'post-item-custom-name',
        'taxonomy_filter_class' => 'filter-class',
        'container_class'       => 'custom-post-container',
        'load_more_posts'       => 3,
        'loader_icon'           => 'loader-icon',
        'infinite_scroll'       => 1,
        'ajax_initial_load'     => 1
    );

This setup creates **a 3-column post grid**, uses a custom template **`post-item-custom-name`**, and loads **4 posts** when clicking **"Load More"**.