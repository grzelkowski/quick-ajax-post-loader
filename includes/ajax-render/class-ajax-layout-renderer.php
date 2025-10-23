<?php 
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Ajax_Layout_Renderer{
    private $file_manager;
    private $ui_renderer;

    public function __construct(QAPL_Quick_Ajax_File_Manager $file_manager, QAPL_Ajax_UI_Renderer $ui_renderer) {
        $this->file_manager = $file_manager;
        $this->ui_renderer  = $ui_renderer;
    }

    public function layout_customization($attributes, $global_options){
        $layout = [];
        $attrs = [];
        //Apply quick AJAX CSS Style
        $layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_CSS_STYLE] = (isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_CSS_STYLE])) ? esc_attr($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_CSS_STYLE]) : 0;
        //Number of columns
        $layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_GRID_NUM_COLUMNS] = (isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_GRID_NUM_COLUMNS])) ? esc_attr($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_GRID_NUM_COLUMNS]) : 0;
        //add custom class for taxonomy filter
        if(isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_TAXONOMY_FILTER_CLASS])){
            $layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_TAXONOMY_FILTER_CLASS] = $this->extract_classes_from_string($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_TAXONOMY_FILTER_CLASS]);
        }
        //Add class to post container
        if(isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_CONTAINER_CLASS])){
            $layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_CONTAINER_CLASS] = $this->extract_classes_from_string($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_CONTAINER_CLASS]);
        }
        //Post Item Template
        $post_item_template = isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE]) ? $attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE] : false;
        $layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE] = $this->file_manager->get_post_item_template($post_item_template);
        $attrs[QAPL_Quick_Ajax_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE] = $post_item_template;
        //Custom Load More Post Quantity            
        if(isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOAD_MORE_POSTS])){
            $attrs[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOAD_MORE_POSTS] = intval($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOAD_MORE_POSTS]);
        }            
        //Select Loader Icon
        if (isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOADER_ICON]) && !empty($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOADER_ICON])) {
            $loader_icon = $attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOADER_ICON];
        } elseif (isset($global_options['loader_icon']) && !empty($global_options['loader_icon'])) {
            // fallback to global option if attributes value is invalid
            $loader_icon = $global_options['loader_icon'];
        } else {
            // final fallback to default value
            $loader_icon = QAPL_Quick_Ajax_Constants::LAYOUT_SETTING_SELECT_LOADER_ICON_DEFAULT;
        }
        $layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOADER_ICON] = $this->file_manager->get_loader_icon_template($loader_icon);
        $attrs[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOADER_ICON] = $loader_icon;
        // infinite_scroll
        if(isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_AJAX_INFINITE_SCROLL])){
            $attrs[QAPL_Quick_Ajax_Constants::ATTRIBUTE_AJAX_INFINITE_SCROLL] = intval($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_AJAX_INFINITE_SCROLL]);
        }
        // show_end_message
        if(isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_SHOW_END_MESSAGE])){
            $attrs[QAPL_Quick_Ajax_Constants::ATTRIBUTE_SHOW_END_MESSAGE] = intval($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_SHOW_END_MESSAGE]);
        }
        $ajax_initial_load = isset($attributes[QAPL_Quick_Ajax_Constants::AJAX_SETTING_AJAX_INITIAL_LOAD]) ? intval($attributes[QAPL_Quick_Ajax_Constants::AJAX_SETTING_AJAX_INITIAL_LOAD]) : QAPL_Quick_Ajax_Constants::QUERY_SETTING_AJAX_ON_INITIAL_LOAD_DEFAULT;
        return [
            'layout' => $layout,
            'attributes' => $attrs,
            'ajax_initial_load' => $ajax_initial_load,
        ];
    }

    public function wp_query($args, $input_args, $layout, $attributes, $ajax_initial_load, $action_args, $quick_ajax_id) {
        if (empty($args)) {
            return false;
        }
        $args['selected_taxonomy'] = sanitize_text_field($input_args['selected_taxonomy'] ?? '');
        $args['selected_terms'] = array_map('absint', $input_args['selected_terms'] ?? []);

        $query = new WP_Query($args);
        //$this->attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_ID] = $this->quick_ajax_id;
        //$layout_quick_ajax_id = esc_attr($this->attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_ID]);
        $class_container = '';
        $class_inner_container = '';
        if (isset($layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_CSS_STYLE]) && $layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_CSS_STYLE] != 0) {
            $class_container .= 'quick-ajax-theme';
        }
        if(isset($layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_GRID_NUM_COLUMNS]) && (!empty($layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_GRID_NUM_COLUMNS]))){
            $class_inner_container .= 'col-qty-'.$layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_GRID_NUM_COLUMNS];   
        }
        if(isset($layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_CONTAINER_CLASS]) && !empty(trim($layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_CONTAINER_CLASS]))){
            $class_inner_container .= ' '.$layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_CONTAINER_CLASS];
        }
        $container_class = $this->extract_classes_from_string($class_container);
        $container_inner_class = $this->extract_classes_from_string($class_inner_container);
        ob_start();
        // Start output buffering
        do_action(QAPL_Quick_Ajax_Constants::HOOK_POSTS_CONTAINER_BEFORE, $quick_ajax_id);
        echo '<div id="quick-ajax-'.esc_attr($quick_ajax_id).'" class="quick-ajax-posts-container '.esc_attr($container_class).'">';
        do_action(QAPL_Quick_Ajax_Constants::HOOK_POSTS_CONTAINER_START, $quick_ajax_id);
        echo '<div class="quick-ajax-posts-wrapper '.esc_attr($container_inner_class).'">';
        

        if ($query->have_posts()) {
            $container_settings = [
                'quick_ajax_id' => $quick_ajax_id,
                'template_name' => $attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE],
            ];
            $qapl_post_template = QAPL_Post_Template_Factory::get_template($container_settings);
            QAPL_Post_Template_Context::set_template($qapl_post_template);
            if ($ajax_initial_load) {
                if (empty($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_ID])) {
                    $attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_ID] = $quick_ajax_id;
                }
                echo '<div class="qapl-initial-loader" data-button="quick-ajax-filter-button" style="display:none;" data-action="' . esc_attr(wp_json_encode($input_args)) . '" data-attributes="' . esc_attr(wp_json_encode($attributes)) . '"></div>';
            } else {
                while ($query->have_posts()) {
                    $query->the_post();                        
                    include($layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_POST_ITEM_TEMPLATE]);
                }
            }
            QAPL_Post_Template_Context::clear_template(); 
        } else {
            // No posts found
            $container_settings = [
                'quick_ajax_id' => $quick_ajax_id,
                'template_name' => 'no-post-message',
            ];
            $qapl_no_post_template = QAPL_Post_Template_Factory::get_template($container_settings);
            QAPL_Post_Template_Context::set_template($qapl_no_post_template);
            include($this->file_manager->get_no_posts_template());
            QAPL_Post_Template_Context::clear_template(); 
        }
                    
        echo '</div>';
        do_action(QAPL_Quick_Ajax_Constants::HOOK_LOADER_BEFORE, $quick_ajax_id);
        echo '<div class="quick-ajax-loader-container">'; 
        include($layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOADER_ICON]);
        echo '</div>';
        do_action(QAPL_Quick_Ajax_Constants::HOOK_LOADER_AFTER, $quick_ajax_id);
        if (!$ajax_initial_load) {
            $infinite_scroll = isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_AJAX_INFINITE_SCROLL]) ? intval($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_AJAX_INFINITE_SCROLL]) : QAPL_Quick_Ajax_Constants::QUERY_SETTING_AJAX_ON_INITIAL_LOAD_DEFAULT;
            $query_data = [
                'paged'           => intval($query->get('paged')),
                'max_num_pages'   => intval($query->max_num_pages),
                'found_posts'     => intval($query->found_posts),
                'post_count'      => intval($query->post_count),
                'infinite_scroll' => $infinite_scroll,
            ];
         
            //echo wp_kses_post($this->load_more_button(intval($query->get('paged')), intval($query->max_num_pages), intval($query->found_posts), intval($query->post_count), $infinite_scroll));
            echo wp_kses_post($this->load_more_button($args, $attributes, $action_args, $query_data, $quick_ajax_id));
        }
        do_action(QAPL_Quick_Ajax_Constants::HOOK_POSTS_CONTAINER_END, $quick_ajax_id);
        echo '</div>';
        do_action(QAPL_Quick_Ajax_Constants::HOOK_POSTS_CONTAINER_AFTER, $quick_ajax_id);
        
        wp_reset_postdata();
        // Get the buffered content into a variable
        $output = ob_get_clean(); 
        
        //$output = $this->replace_placeholders($output); // not in use after removing placeholders
        return $output; // Return the content
    }
    public function load_more_button($args, $attributes, $action_args, $query_data, $quick_ajax_id) {
        if (empty($query_data) || !is_array($query_data)) {
            return false;
        }
        $paged           = intval($query_data['paged'] ?? 1);
        $max_num_pages   = intval($query_data['max_num_pages'] ?? 1);
        $found_posts     = intval($query_data['found_posts'] ?? 0);
        $post_count      = intval($query_data['post_count'] ?? 0);
        $infinite_scroll = !empty($query_data['infinite_scroll']);
        //echo 'paged:'.$paged.'<br />$max_num_pages:'.$max_num_pages.'<br />$found_posts:'.$found_posts.'<br />';
        //print_r($this->args);
        $load_more_args = $action_args;
        //$load_more_args['paged'] = isset($this->args['paged']) ? intval($this->args['paged']) : 1;
        $load_more_args['paged'] = $paged;
        if (isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOAD_MORE_POSTS]) && !empty($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOAD_MORE_POSTS])) {
        // Check if load_more_posts attribute is set
        // if we want to add a different number of posts than displayed at the start
        // use 'offset' not 'paged'
            $load_more_posts = intval($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_LOAD_MORE_POSTS]);
            //get initial offset and number of posts per page
            $initial_offset = isset($load_more_args['offset']) ? intval($load_more_args['offset']) : 0;
            //get number of posts per page
            $posts_per_page = intval($load_more_args['posts_per_page']);
                        
            //old logic
            //if post_found smaller than initial offset and post per page
            //if ($found_posts <= $initial_offset + $posts_per_page) {
            //   return false;
            //}
            //new logic
            $shown_posts = $initial_offset + $post_count;
            if ($found_posts <= $shown_posts) {
                return false;
            }
                // Update offset
            $load_more_args['offset'] = isset($load_more_args['offset']) ? intval($load_more_args['offset']) + $load_more_posts : intval($load_more_args['posts_per_page']);
            $load_more_args['posts_per_page'] = $load_more_posts;
        } else {
            // Check if there are no more pages to load
            if ($max_num_pages <= $paged) {
                return false;
            }                
            $load_more_args['paged'] += 1;
        }
        $class = '';
        if ($infinite_scroll) {
            $class = ' infinite-scroll';
        }
        //do_action(QAPL_Quick_Ajax_Constants::HOOK_LOAD_MORE_BEFORE);
        if (empty($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_ID])) {
            $attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_QUICK_AJAX_ID] = $quick_ajax_id;
        }
        $button_data['template'] = $this->file_manager->get_load_more_button_template();
        $button_data['button_label'] = __('Load More', 'quick-ajax-post-loader');
        $button_data['data-button'] = QAPL_Quick_Ajax_Constants::LOAD_MORE_BUTTON_DATA_BUTTON;
        $button_data['data-action'] = $load_more_args;
        $button_data['data-attributes'] = $attributes;
        $load_more_button = '<div class="quick-ajax-load-more-container'.$class.'">';
        $load_more_button .= $this->ui_renderer->update_button_template($button_data);
        //$load_more_button .=  $this->update_button_template($button_data);
        $load_more_button .= '</div>';
        return $load_more_button;
        //do_action(QAPL_Quick_Ajax_Constants::HOOK_LOAD_MORE_AFTER);
    }
    public function render_end_of_posts_message($load_more, $max_num_pages, $quick_ajax_id, $show_end_post_message = false) {
        if(!$show_end_post_message){
            return '';
        }
        if ($max_num_pages <= 1) {
            return ''; // only one page, don't show anything
        }
        if ($load_more) {
            return ''; // load more still available, don't show anything
        }
        $end_post_message_settings = [ 
            'quick_ajax_id' => $quick_ajax_id, //$this->quick_ajax_id returns'c'
            'template_name' => 'end-post-message',
        ];
        $qapl_end_post_message_template = QAPL_Post_Template_Factory::get_template($end_post_message_settings);
        QAPL_Post_Template_Context::set_template($qapl_end_post_message_template);
        
        ob_start();
        echo '<div class="quick-ajax-end-message-container">';
        include($this->file_manager->get_end_posts_template());
        echo '</div>';
        $content = ob_get_clean();
        QAPL_Post_Template_Context::clear_template();
        return $content;
    }

    private function extract_classes_from_string($string){
        // Split the input string into an array using whitespace or comma as separators
        $class_container_array = preg_split('/[\s,]+/', $string);
        $class_container_array = array_map('sanitize_html_class', $class_container_array);

        // Iterate over the array and remove elements that start with a digit
        foreach ($class_container_array as $key => $item) {
            if (preg_match('/^\d/', $item)) {
                // Use unset to remove the item from the array if it starts with a digit
                unset($class_container_array[$key]);
            }
        }
        $container_class = implode(' ', $class_container_array);
        return $container_class;
    }
}