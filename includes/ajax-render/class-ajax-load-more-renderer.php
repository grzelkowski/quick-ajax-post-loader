<?php 
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Ajax_Load_More_Renderer {

    private $file_manager;
    private $ui_renderer;
    private $helper;

    public function __construct( QAPL_File_Manager $file_manager,QAPL_Ajax_Filter_Menu_Renderer $ui_renderer, QAPL_Ajax_Helper $helper) {
        $this->file_manager = $file_manager;
        $this->ui_renderer  = $ui_renderer;
        $this->helper       = $helper;
    }

    public function build_load_more_button($attributes, $source_args, $query_data, $quick_ajax_id) {

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
        $load_more_args = $source_args;
        //$load_more_args['paged'] = isset($this->args['paged']) ? intval($this->args['paged']) : 1;
        $load_more_args['paged'] = $paged;
        if (isset($attributes[QAPL_Constants::ATTRIBUTE_LOAD_MORE_POSTS]) && !empty($attributes[QAPL_Constants::ATTRIBUTE_LOAD_MORE_POSTS])) {
        // Check if load_more_posts attribute is set
        // if we want to add a different number of posts than displayed at the start
        // use 'offset' not 'paged'
            $load_more_posts = intval($attributes[QAPL_Constants::ATTRIBUTE_LOAD_MORE_POSTS]);
            //get initial offset and number of posts per page
            $initial_offset = isset($load_more_args['offset']) ? intval($load_more_args['offset']) : 0;
            //get number of posts per page
            //$posts_per_page = intval($load_more_args['posts_per_page']);
                        
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
        $css_class = $infinite_scroll ? 'infinite-scroll' : '';

        //do_action(QAPL_Constants::HOOK_LOAD_MORE_BEFORE);
        if (empty($attributes[QAPL_Constants::ATTRIBUTE_QUICK_AJAX_ID])) {
            $attributes[QAPL_Constants::ATTRIBUTE_QUICK_AJAX_ID] = $quick_ajax_id;
        }
        $button_data = [
            'template'        => $this->file_manager->get_load_more_button_template(),
            'button_label'    => __('Load More', 'quick-ajax-post-loader'),
            'css_class'       => $css_class,
            'data-button'     => QAPL_Constants::LOAD_MORE_BUTTON_DATA_BUTTON,
            'data-action'     => $load_more_args,
            'data-attributes' => $attributes,
        ];        
        return $button_data;
        //do_action(QAPL_Constants::HOOK_LOAD_MORE_AFTER);
    }
    public function render_load_more_button(array $button_data): string {
        if (empty($button_data)) {
            return '';
        }
        $class_suffix = !empty($button_data['css_class']) ? ' ' . $button_data['css_class'] : '';

        $button_html = $this->ui_renderer->update_button_template($button_data);

        return '<div class="quick-ajax-load-more-container' . esc_attr($class_suffix) . '">' 
                . $button_html .
            '</div>';
    }

}