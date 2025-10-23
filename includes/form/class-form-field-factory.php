<?php 
if (!defined('ABSPATH')) {
    exit;
}
class QAPL_Quick_Ajax_Form_Field_Factory {
    //select post type  
    public static function build_select_post_type_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $post_types = get_post_types(['public' => true], 'objects');
        $options = [];
        foreach ($post_types as $post_type) {
            if (isset($post_type->labels->name) && $post_type->labels->name !== 'Media') {
                $options[] = [
                    'label' => $post_type->labels->name,
                    'value' => $post_type->name
                ];
            }
        }
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_POST_TYPE);
        $builder->set_label(__('Select Post Type', 'quick-ajax-post-loader'));
        $builder->set_type('select');
        $builder->set_options($options);
        $builder->set_default(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_POST_TYPE_DEFAULT);
        $builder->set_description(__('Choose the post type you want to display using AJAX.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //show taxonomy checkbox
    public static function build_show_taxonomy_filter_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_TAXONOMY_FILTER);
        $builder->set_label(__('Show Taxonomy Filter', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_TAXONOMY_FILTER_DEFAULT);
        $builder->set_description(__('Enable filtering by taxonomy/category.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //select taxonomy   
    public static function build_select_taxonomy_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $options = [];
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_TAXONOMY);
        $builder->set_label(__('Select Taxonomy', 'quick-ajax-post-loader'));
        $builder->set_type('select');
        $builder->set_options($options);
        $builder->set_default('');
        $builder->set_description(__('Select the taxonomy to be used for filtering posts.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //manual term selection checkbox
    public static function build_manual_term_selection_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::QUERY_SETTING_MANUAL_TERM_SELECTION);
        $builder->set_label(__('Select Specific Terms', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPL_Quick_Ajax_Constants::QUERY_SETTING_MANUAL_TERM_SELECTION_DEFAULT);
        $builder->set_description(__('Enable manual selection of taxonomy terms to be used for filtering.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //manual selected terms multiselect
    public static function build_manual_selected_terms_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECTED_TERMS);
        $builder->set_label(__('Choose Terms', 'quick-ajax-post-loader'));
        $builder->set_type('multiselect');
        $builder->set_options([]);
        $builder->set_default([]);
        $builder->set_description(__('Select the specific terms to be used for filtering posts. If left empty, no results will be shown.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //post per page number
    public static function build_posts_per_page_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_POSTS_PER_PAGE);
        $builder->set_label(__('Posts Per Page', 'quick-ajax-post-loader'));
        $builder->set_type('number');
        $builder->set_default(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_POSTS_PER_PAGE_DEFAULT);
        $builder->set_description(__('Determine the number of posts to be loaded per AJAX request.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //select post order
    public static function build_select_order_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $order_options = [
            [
                'label' => __('Descending - order from highest to lowest', 'quick-ajax-post-loader'),
                'value' => 'DESC'
            ],
            [
                'label' => __('Ascending - order from lowest to highest', 'quick-ajax-post-loader'),
                'value' => 'ASC'
            ],
        ];
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_ORDER);
        $builder->set_label(__('Default Sort Order', 'quick-ajax-post-loader'));
        $builder->set_type('select');
        $builder->set_options($order_options);
        $builder->set_default(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_ORDER_DEFAULT);
        $builder->set_description(__('Specify the order of posts.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //select post orderby
    public static function build_select_orderby_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $orderby_options = [
            [
                'label' => __('Date: Sort by publication date', 'quick-ajax-post-loader'),
                'value' => 'date'
            ],
            [
                'label' => __('Title: Sort by post title', 'quick-ajax-post-loader'),
                'value' => 'title'
            ],
            [
                'label' => __('Comments: Sort by comment count', 'quick-ajax-post-loader'),
                'value' => 'comment_count'
            ],
            [
                'label' => __('Random: Random order', 'quick-ajax-post-loader'),
                'value' => 'rand'
            ],
        ];
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_ORDERBY);
        $builder->set_label(__('Default Sort By', 'quick-ajax-post-loader'));
        $builder->set_type('select');
        $builder->set_options($orderby_options);
        $builder->set_default(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_ORDERBY_DEFAULT);
        $builder->set_description(__('Choose the sorting criteria for posts.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //show sort button
    public static function build_show_sort_button_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_SORT_BUTTON);
        $builder->set_label(__('Show Sorting Button', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_SORT_BUTTON_DEFAULT);
        $builder->set_description(__('Enable a button that allows users to switch between ascending and descending order.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //select sort button
    public static function build_select_sort_button_options_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $global_sort_labels = get_option(QAPL_Quick_Ajax_Constants::GLOBAL_OPTIONS_NAME, []);
        $sort_options = [
            [
                'value' => 'date-desc',
                'label' => isset($global_sort_labels['sort_option_date_desc_label'])
                    ? $global_sort_labels['sort_option_date_desc_label']
                    : __('Newest', 'quick-ajax-post-loader')
            ],
            [
                'value' => 'date-asc',
                'label' => isset($global_sort_labels['sort_option_date_asc_label'])
                    ? $global_sort_labels['sort_option_date_asc_label']
                    : __('Oldest', 'quick-ajax-post-loader')
            ],
            [
                'value' => 'comment_count-desc',
                'label' => isset($global_sort_labels['sort_option_comment_count_desc_label'])
                    ? $global_sort_labels['sort_option_comment_count_desc_label']
                    : __('Popular', 'quick-ajax-post-loader')
            ],
            [
                'value' => 'title-asc',
                'label' => isset($global_sort_labels['sort_option_title_desc_label'])
                    ? $global_sort_labels['sort_option_title_asc_label']
                    : __('A → Z', 'quick-ajax-post-loader')
            ],
            [
                'value' => 'title-desc',
                'label' => isset($global_sort_labels['sort_option_title_asc_label'])
                    ? $global_sort_labels['sort_option_title_desc_label']
                    : __('Z → A', 'quick-ajax-post-loader')
            ],
            [
                'value' => 'rand',
                'label' => isset($global_sort_labels['sort_option_rand_label'])
                    ? $global_sort_labels['sort_option_rand_label']
                    : __('Random', 'quick-ajax-post-loader')
            ],
        ];
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_SORT_BUTTON_OPTIONS);
        $builder->set_label(__('Available Sorting Options', 'quick-ajax-post-loader'));
        $builder->set_type('multiselect');
        $builder->set_options($sort_options);
        $builder->set_default(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_SORT_BUTTON_OPTIONS_DEFAULT);
        $builder->set_description(__('Select which sorting options will be available to users.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //Inline Filter & Sorting
    public static function build_show_inline_filter_sorting_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_INLINE_FILTER_SORTING);
        $builder->set_label(__('Inline Filter & Sorting', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_INLINE_FILTER_SORTING_DEFAULT);
        $builder->set_description(__('Display taxonomy filter and sorting options in a single row to save space and improve layout.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    /*
    public static function create_post_status_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $post_status_options = [
            [
                'label' => __('Publish: Published posts', 'quick-ajax-post-loader'),
                'value' => 'publish'
            ],
            [
                'label' => __('Draft: Draft posts', 'quick-ajax-post-loader'),
                'value' => 'draft'
            ],
            [
                'label' => __('Pending: Pending review posts', 'quick-ajax-post-loader'),
                'value' => 'pending'
            ],
            [
                'label' => __('Private: Private posts', 'quick-ajax-post-loader'),
                'value' => 'private'
            ],
            [
                'label' => __('Trash: Trashed posts', 'quick-ajax-post-loader'),
                'value' => 'trash'
            ],
            [
                'label' => __('Auto-Draft: Auto-draft posts', 'quick-ajax-post-loader'),
                'value' => 'auto-draft'
            ],
            [
                'label' => __('Inherit: Inherited posts', 'quick-ajax-post-loader'),
                'value' => 'inherit'
            ],
        ];
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_POST_STATUS);
        $builder->set_label(__('Post Status', 'quick-ajax-post-loader'));
        $builder->set_type('select');
        $builder->set_options($post_status_options);
        $builder->set_default(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_POST_STATUS_DEFAULT);
        $builder->set_description(__('Select the post status to be used by AJAX.', 'quick-ajax-post-loader'));
        return $builder->build();
    }*/
    //add Excluded Post IDs
    public static function build_excluded_post_ids_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SET_POST_NOT_IN);
        $builder->set_label(__('Excluded Post IDs', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default('');
        $builder->set_placeholder('3, 66, 999');
        $builder->set_description(__('Enter a list of post IDs to exclude from the query.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //set Ignore Sticky Posts
    public static function build_ignore_sticky_posts_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::QUERY_SETTING_IGNORE_STICKY_POSTS);
        $builder->set_label(__('Ignore Sticky Posts', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPL_Quick_Ajax_Constants::QUERY_SETTING_IGNORE_STICKY_POSTS_DEFAULT);
        $builder->set_description(__('Specify to ignore sticky posts, treating them as regular posts in the query.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //add Load Posts via AJAX on Initial Load
    public static function build_ajax_on_initial_load_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::QUERY_SETTING_AJAX_ON_INITIAL_LOAD);
        $builder->set_label(__('Load Initial Posts via AJAX', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPL_Quick_Ajax_Constants::QUERY_SETTING_AJAX_ON_INITIAL_LOAD_DEFAULT);
        $builder->set_description(__('Enable this option to load the initial set of posts via AJAX on page load. This can help in cases where caching might cause outdated content to be displayed.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    // add Infinite Scroll via AJAX
    public static function build_ajax_infinite_scroll_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::QUERY_SETTING_AJAX_INFINITE_SCROLL);
        $builder->set_label(__('Enable Infinite Scroll', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPL_Quick_Ajax_Constants::QUERY_SETTING_AJAX_INFINITE_SCROLL_DEFAULT);
        $builder->set_description(__('Enable this option to automatically load more posts via AJAX as the user scrolls down the page.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    // show end message
    public static function build_show_end_message_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_END_MESSAGE);
        $builder->set_label(__('Show End Message', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_END_MESSAGE_DEFAULT);
        $builder->set_description(__('Display a message when there are no more posts to load via AJAX.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //apply quick ajax css style
    public static function build_quick_ajax_css_style_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_QUICK_AJAX_CSS_STYLE);
        $builder->set_label(__('Apply Quick AJAX CSS Style', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_QUICK_AJAX_CSS_STYLE_DEFAULT);
        $builder->set_description(__('Apply Quick AJAX CSS styles and column layout.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //select number of columns
    public static function build_select_columns_qty_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $columns_options = [];
                for ($i = 1; $i <= 12; $i++) {
            $columns_options[] = array(
                'label' =>  strval($i),
                'value' => $i
            );
        }
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_SELECT_COLUMNS_QTY);
        $builder->set_label(__('Number of Columns', 'quick-ajax-post-loader'));
        $builder->set_type('select');
        $builder->set_options($columns_options);
        $builder->set_default(QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_SELECT_COLUMNS_QTY_DEFAULT);
        $builder->set_description(__('Specify the quantity of columns.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //select post item template
    public static function build_post_item_template_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $file_manager = new QAPL_Quick_Ajax_File_Manager();
        $templates = $file_manager->get_templates_items_array('post-items/post-item*.php', 'Post Item Name:', QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_POST_ITEM_TEMPLATE_DEFAULT);
        $options = [];
        foreach ($templates as $template) {
            $options[] = [
                'label' => $template['template_name'],
                'value' => $template['file_name']
            ];
        }
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_POST_ITEM_TEMPLATE);
        $builder->set_label(__('Select Post Item Template', 'quick-ajax-post-loader'));
        $builder->set_type('select');
        $builder->set_options($options);
        $builder->set_default(QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_POST_ITEM_TEMPLATE_DEFAULT);
        $builder->set_description(__('Choose a template for displaying post items.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //add custom class for taxonomy filter
    public static function build_taxonomy_filter_class_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_TAXONOMY_FILTER_CLASS);
        $builder->set_label(__('Add Class to Taxonomy Filter', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_TAXONOMY_FILTER_CLASS_DEFAULT);
        $builder->set_placeholder(__('class-name, another-class-name', 'quick-ajax-post-loader'));
        $builder->set_description(__('Add classes to the filter: class-one, class-two, class-three', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //add custom class for container
    public static function build_container_class_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_CONTAINER_CLASS);
        $builder->set_label(__('Add Class to Post Container', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_CONTAINER_CLASS_DEFAULT);
        $builder->set_placeholder(__('class-name, another-class-name', 'quick-ajax-post-loader'));
        $builder->set_description(__('Add classes to the post container: class-one, class-two, class-three', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //show custom load more post quantity
    public static function build_show_custom_load_more_post_quantity_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_CUSTOM_LOAD_MORE_POST_QUANTITY);
        $builder->set_label(__('Load More Post Quantity', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SHOW_CUSTOM_LOAD_MORE_POST_QUANTITY_DEFAULT);
        $builder->set_description(__('Load a different number of posts after the initial display.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //select custom load more post quantity
    public static function build_select_custom_load_more_post_quantity_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_CUSTOM_LOAD_MORE_POST_QUANTITY);
        $builder->set_label(__('Posts Per Load (After Initial)', 'quick-ajax-post-loader'));
        $builder->set_type('number');
        $builder->set_default(QAPL_Quick_Ajax_Constants::QUERY_SETTING_SELECT_CUSTOM_LOAD_MORE_POST_QUANTITY_DEFAULT);
        $builder->set_description(__('Set how many posts to load each time the "Load More" button is clicked.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //override global loader icon
    public static function build_override_global_loader_icon_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_OVERRIDE_GLOBAL_LOADER_ICON);
        $builder->set_label(__('Override Global Loader Icon', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_OVERRIDE_GLOBAL_LOADER_ICON_DEFAULT);
        $builder->set_description(__('Set a different loader icon than the one specified in global options.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //select loader icon
    public static function build_select_loader_icon(): QAPL_Quick_Ajax_Form_Field_Interface {
        return self::loader_icon_get_field(
            QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_SELECT_LOADER_ICON,
            QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_SELECT_LOADER_ICON_DEFAULT
        );
    }
    //select loader icon global
    public static function build_global_select_loader_icon(): QAPL_Quick_Ajax_Form_Field_Interface {
        return self::loader_icon_get_field(
            QAPL_Quick_Ajax_Constants::GLOBAL_LOADER_ICON_FIELD,
            QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_SELECT_LOADER_ICON_DEFAULT
        );
    }
    //build loader icon select field
    private static function loader_icon_get_field(string $name, string $default): QAPL_Quick_Ajax_Form_Field_Interface {
        $file_manager = new QAPL_Quick_Ajax_File_Manager();
        $templates = $file_manager->get_templates_items_array('loader-icon/*.php','Loader Icon Name:',$default);
        $options = [];
        foreach ($templates as $item) {
            $options[] = [
                'label' => $item['template_name'],
                'value' => $item['file_name']
            ];
        }
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name($name);
        $builder->set_label(__('Select Loader Icon', 'quick-ajax-post-loader'));
        $builder->set_type('select');
        $builder->set_options($options);
        $builder->set_default($default);
        $builder->set_description(__('Choose an icon to display as the loading indicator when the "Load More" button is clicked.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set read more
    public static function build_global_read_more_text_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::GLOBAL_READ_MORE_LABEL_FIELD);
        $builder->set_label(__('Set "Read More" Label', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('Read More', 'quick-ajax-post-loader'));
        $builder->set_placeholder(__('Enter custom label for Read More', 'quick-ajax-post-loader'));
        $builder->set_description(__('Customize the "Read More" text for your templates. This label will appear as a link or button for each post item. Examples: "Read More", "Continue Reading", or "Learn More".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set show all label
    public static function build_global_show_all_label_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::GLOBAL_SHOW_ALL_LABEL_FIELD);
        $builder->set_label(__('Set "Show All" Label', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('Show All', 'quick-ajax-post-loader'));  
        $builder->set_placeholder(__('Enter custom label for Show All', 'quick-ajax-post-loader'));
        $builder->set_description(__('Customize the "Show All" text label for the filter. This label will appear as an option to display all posts without filtering. Examples: "Show All", "View All", or "Display All".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set load more label
    public static function build_global_load_more_label_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::GLOBAL_LOAD_MORE_LABEL_FIELD);
        $builder->set_label(__('Set "Load More" Label', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('Load More', 'quick-ajax-post-loader'));
        $builder->set_placeholder(__('Enter custom label for Load More', 'quick-ajax-post-loader'));
        $builder->set_description(__('Customize the "Load More" button text. This label will appear on the button used to load additional posts dynamically. Examples: "Load More", "Show More", or "View More".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set no post message
    public static function build_global_no_post_message_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::GLOBAL_NO_POST_MESSAGE_FIELD);
        $builder->set_label(__('Set "No Posts Found" Message', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('No posts found', 'quick-ajax-post-loader'));
        $builder->set_placeholder(__('Enter message for no posts found', 'quick-ajax-post-loader'));
        $builder->set_description(__('Customize the message shown when no posts match the selected filters. Examples: "No posts found", "Nothing to display", or "Try adjusting your filters".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set end post message
    public static function build_global_end_post_message_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::GLOBAL_END_POST_MESSAGE_FIELD);
        $builder->set_label(__('Set "End of Posts" Message', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('No more posts to load', 'quick-ajax-post-loader'));
        $builder->set_placeholder(__('Enter message for end of posts', 'quick-ajax-post-loader'));
        $builder->set_description(__('Customize the message that appears when there are no more posts to load. Examples: "No more posts", "You have reached the end", or "That\'s all for now".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set post date format
    /*
    public static function build_global_post_date_format_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::GLOBAL_POST_DATE_FORMAT_FIELD);
        $builder->set_label(__('Set Date Format', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default('F j, Y');
        $builder->set_placeholder(__('Enter date format (e.g., F j, Y)', 'quick-ajax-post-loader'));
        $builder->set_description(__('Customize the format for displaying post dates. This text will replace the default date format. For example: "F j, Y" (January 1, 2023) or "Y-m-d" (2023-01-01). Refer to the PHP date format documentation for more options.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    */
    //build global options set sort option date desc label
    public static function build_global_sort_option_date_desc_label_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::GLOBAL_SORT_OPTION_DATE_DESC_LABEL_FIELD);
        $builder->set_label(__('Set "Newest" Label', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('Newest', 'quick-ajax-post-loader'));
        $builder->set_placeholder(__('Newest', 'quick-ajax-post-loader'));
        $builder->set_description(__('Set the label for sorting posts from newest to oldest (based on publication date). Examples: "Newest", "Latest", "Recent".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set sort option date asc label
    public static function build_global_sort_option_date_asc_label_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::GLOBAL_SORT_OPTION_DATE_ASC_LABEL_FIELD);
        $builder->set_label(__('Set "Oldest" Label', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('Oldest', 'quick-ajax-post-loader'));
        $builder->set_placeholder(__('Oldest', 'quick-ajax-post-loader'));
        $builder->set_description(__('Set the label for sorting posts from oldest to newest (based on publication date). Examples: "Oldest", "First", "Earliest".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set sort option comment count desc label
    public static function build_global_sort_option_comment_count_desc_label_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::GLOBAL_SORT_OPTION_COMMENT_COUNT_DESC_LABEL_FIELD);
        $builder->set_label(__('Set "Popular" Label', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('Popular', 'quick-ajax-post-loader'));
        $builder->set_placeholder(__('Popular', 'quick-ajax-post-loader'));
        $builder->set_description(__('Set the label for sorting posts by the highest number of comments. Examples: "Popular", "Trending", "Most Discussed".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set sort option title asc label
    public static function build_global_sort_option_title_asc_label_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::GLOBAL_SORT_OPTION_TITLE_ASC_LABEL_FIELD);
        $builder->set_label(__('Set "A → Z" Label', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('A → Z', 'quick-ajax-post-loader'));
        $builder->set_placeholder(__('A → Z', 'quick-ajax-post-loader'));
        $builder->set_description(__('Set the label for sorting posts alphabetically (A to Z) based on the post title. Examples: "Alphabetical", "A → Z", "Sort by Name".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set sort option title desc label
    public static function build_global_sort_option_title_desc_label_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::GLOBAL_SORT_OPTION_TITLE_DESC_LABEL_FIELD);
        $builder->set_label(__('Set "Z → A" Label', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('Z → A', 'quick-ajax-post-loader'));
        $builder->set_placeholder(__('Z → A', 'quick-ajax-post-loader'));
        $builder->set_description(__('Set the label for sorting posts alphabetically (Z to A) based on the post title. Examples: "Reverse Alphabetical", "Z → A", "Sort by Name Descending".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global options set sort option rand label
    public static function build_global_sort_option_rand_label_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::GLOBAL_SORT_OPTION_RAND_LABEL_FIELD);
        $builder->set_label(__('Set "Random" Label', 'quick-ajax-post-loader'));
        $builder->set_type('text');
        $builder->set_default(__('Random', 'quick-ajax-post-loader'));
        $builder->set_placeholder(__('Random', 'quick-ajax-post-loader'));
        $builder->set_description(__('Set the label for sorting posts in a random order. Examples: "Shuffle", "Random", "Surprise Me".', 'quick-ajax-post-loader'));
        return $builder->build();
    }
    //build global field remove old data
    public static function build_global_remove_old_data_field(): QAPL_Quick_Ajax_Form_Field_Interface {
        $builder = new QAPL_Quick_Ajax_Form_Field_Builder();
        $builder->set_name(QAPL_Quick_Ajax_Constants::REMOVE_OLD_DATA_FIELD);
        $builder->set_label(__('Confirm Purge of Old Data', 'quick-ajax-post-loader'));
        $builder->set_type('checkbox');
        $builder->set_default(QAPL_Quick_Ajax_Constants::REMOVE_OLD_DATA_FIELD_DEFAULT);
        $builder->set_description(__('Choose this option to remove old, unused data from the database. This will help keep your site clean and efficient. Be aware that if you switch back to an older version of the plugin, it might not work as expected.', 'quick-ajax-post-loader'));
        return $builder->build();
    }
}
