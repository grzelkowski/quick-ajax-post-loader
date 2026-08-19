<?php 
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Shortcode {
    private $shortcode_params = array();
    private $shortcode_postmeta = array();
    private $query_args = array();
    
    private function get_shortcode_params($params) {
        $this->shortcode_params = QAPL_Shortcode_Params_Handler::get_params($params);
    }    
    private function unserialize_shortcode_data($id) {
        $this->shortcode_postmeta = QAPL_Shortcode_Post_Meta_Handler::load_and_sanitize($id);
    }
    private function create_shortcode_args(){
        $data_args = new QAPL_Shortcode_Query_Args_Provider($this->shortcode_params, $this->shortcode_postmeta);
        $args = array();
        // get main query params from shortcode or postmeta 
        $selected_post_type = $data_args->get_arg_value('post_type', QAPL_Constants::QUERY_SETTING_SELECT_POST_TYPE);
        $post_per_page = $data_args->get_arg_value('posts_per_page', QAPL_Constants::QUERY_SETTING_SELECT_POSTS_PER_PAGE);
        $post_order = $data_args->get_arg_value('order', QAPL_Constants::QUERY_SETTING_SELECT_ORDER);
        $post_orderby = $data_args->get_arg_value('orderby', QAPL_Constants::QUERY_SETTING_SELECT_ORDERBY);
        $post_not_in = $data_args->get_arg_value('excluded_post_ids', QAPL_Constants::QUERY_SETTING_SET_POST_NOT_IN);
        $ignore_sticky_posts = $data_args->get_arg_value('ignore_sticky_posts', QAPL_Constants::QUERY_SETTING_IGNORE_STICKY_POSTS);
        $show_taxonomy = $data_args->get_arg_value('show_taxonomy', QAPL_Constants::QUERY_SETTING_SHOW_TAXONOMY_FILTER);
        $select_taxonomy = $data_args->get_arg_value('select_taxonomy', QAPL_Constants::QUERY_SETTING_SELECT_TAXONOMY);
        $manual_term_selection = $data_args->get_arg_value('manual_term_selection', QAPL_Constants::QUERY_SETTING_MANUAL_TERM_SELECTION);
        $manual_selected_terms = $data_args->get_arg_value('manual_selected_terms', QAPL_Constants::QUERY_SETTING_SELECTED_TERMS);
        // return query args if post type is defined
        // phpcs:disable WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in -- intentional exclusion of rendered posts
        if(!empty($selected_post_type)){
            $args = array(
                'post_type' => $selected_post_type,
                'orderby' => $post_orderby, 
                'order' => $post_order,
                'posts_per_page' => $post_per_page,
            );
            if (!empty($post_not_in)) {
                $args['post__not_in'] = $post_not_in;
            }                
            if (!empty($ignore_sticky_posts)) {
                $args['ignore_sticky_posts'] = $ignore_sticky_posts;
            }
            if($show_taxonomy && $select_taxonomy){
                $args['selected_taxonomy'] = $select_taxonomy;
            }
            if($select_taxonomy && $manual_term_selection && $manual_selected_terms){
                $args['selected_terms'] = $manual_selected_terms;
            }
        }
        // phpcs:enable WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_post__not_in
        if(!empty($args)){
            $this->query_args = $args;
        }
        return false;
    }
    /*
    // replaced by args['selected_taxonomy'] - may be needed in the future if there is an option to hide the filter
    private function create_shortcode_taxonomy(){
        if (empty($this->shortcode_params['id'])) {
            return null;
        }
        $show_taxonomies_filter = isset($this->shortcode_postmeta[QAPL_Constants::QUERY_SETTING_SHOW_TAXONOMY_FILTER]) ? $this->shortcode_postmeta[QAPL_Constants::QUERY_SETTING_SHOW_TAXONOMY_FILTER] : null;
        if($show_taxonomies_filter==1){
            $selectedTaxonomy = isset($this->shortcode_postmeta[QAPL_Constants::QUERY_SETTING_SELECT_TAXONOMY]) ? $this->shortcode_postmeta[QAPL_Constants::QUERY_SETTING_SELECT_TAXONOMY] : null;
            $selectedTaxonomy = esc_attr($selectedTaxonomy);
        }           
        if(!empty($selectedTaxonomy)){
            return $selectedTaxonomy;
        }
        return null;
    }*/
    private function create_shortcode_controls_container(){
        if(empty($this->shortcode_params['id'])){
            return null;
        }
        // the wrapper is needed when sorting or the search field is set to share a row with the filter
        if($this->create_shortcode_sort_inline()){
            return 1;
        }
        if($this->create_shortcode_search_field() && $this->create_shortcode_search_inline()){
            return 1;
        }
        return null;
    }
    private function create_shortcode_sort_inline(){
        $show_sort_orderby_button = isset($this->shortcode_postmeta[QAPL_Constants::QUERY_SETTING_SHOW_SORT_BUTTON]) ? $this->shortcode_postmeta[QAPL_Constants::QUERY_SETTING_SHOW_SORT_BUTTON] : null;
        if($show_sort_orderby_button !== 1){
            return false;
        }
        $inline_sorting = isset($this->shortcode_postmeta[QAPL_Constants::QUERY_SETTING_SHOW_INLINE_FILTER_SORTING]) ? $this->shortcode_postmeta[QAPL_Constants::QUERY_SETTING_SHOW_INLINE_FILTER_SORTING] : QAPL_Constants::QUERY_SETTING_SHOW_INLINE_FILTER_SORTING_DEFAULT;
        return !empty($inline_sorting);
    }
    private function create_shortcode_search_inline(){
        $inline_search = isset($this->shortcode_postmeta[QAPL_Constants::QUERY_SETTING_SHOW_INLINE_FILTER_SEARCH]) ? $this->shortcode_postmeta[QAPL_Constants::QUERY_SETTING_SHOW_INLINE_FILTER_SEARCH] : QAPL_Constants::QUERY_SETTING_SHOW_INLINE_FILTER_SEARCH_DEFAULT;
        return !empty($inline_search);
    }
    private function create_shortcode_sort_button(){
        if(!empty($this->shortcode_params['id'])){
            $show_sort_orderby_button = isset($this->shortcode_postmeta[QAPL_Constants::QUERY_SETTING_SHOW_SORT_BUTTON]) ? $this->shortcode_postmeta[QAPL_Constants::QUERY_SETTING_SHOW_SORT_BUTTON] : null;
            if($show_sort_orderby_button === 1){
                $sort_orderby = isset($this->shortcode_postmeta[QAPL_Constants::QUERY_SETTING_SELECT_SORT_BUTTON_OPTIONS]) ? $this->shortcode_postmeta[QAPL_Constants::QUERY_SETTING_SELECT_SORT_BUTTON_OPTIONS] : null;
                if (is_array($sort_orderby)) {
                    $sort_orderby = array_map('esc_attr', $sort_orderby);
                }else{
                    $sort_orderby = esc_attr($sort_orderby);
                }
                return $sort_orderby;
            }
        }
        return null;
    }

    private function create_shortcode_search_field(){
        if(!empty($this->shortcode_params['id'])){
            $show_search_field = isset($this->shortcode_postmeta[QAPL_Constants::QUERY_SETTING_SHOW_SEARCH_FIELD]) ? $this->shortcode_postmeta[QAPL_Constants::QUERY_SETTING_SHOW_SEARCH_FIELD] : null;
            if($show_search_field === 1){
                return true;
            }
        }
        return false;
    }
    private function create_shortcode_search_position(){
        $search_position = isset($this->shortcode_postmeta[QAPL_Constants::QUERY_SETTING_SEARCH_FIELD_POSITION]) ? $this->shortcode_postmeta[QAPL_Constants::QUERY_SETTING_SEARCH_FIELD_POSITION] : null;
        // fall back to the default position for any unknown value
        if($search_position === QAPL_Constants::QUERY_SETTING_SEARCH_FIELD_POSITION_BEFORE_FILTERS){
            return QAPL_Constants::QUERY_SETTING_SEARCH_FIELD_POSITION_BEFORE_FILTERS;
        }
        return QAPL_Constants::QUERY_SETTING_SEARCH_FIELD_POSITION_DEFAULT;
    }

    public function render_quick_ajax_shortcode($params) {
        $this->get_shortcode_params($params);
        if (!empty($this->shortcode_params['id'])) {
            $this->unserialize_shortcode_data($this->shortcode_params['id']);
        }
        $this->create_shortcode_args();
        $attribute_provider = new QAPL_Shortcode_Attributes_Provider($this->shortcode_params, $this->shortcode_postmeta);
        $attributes = $attribute_provider->get_attributes();
        //$render_context['show_taxonomy_filter'] = $this->create_shortcode_taxonomy();
        $render_context['show_taxonomy_filter'] = !empty($this->query_args['selected_taxonomy']);
        $render_context['sort_options'] = $this->create_shortcode_sort_button();
        $render_context['controls_container'] = $this->create_shortcode_controls_container();
        $render_context['sort_inline'] = $this->create_shortcode_sort_inline();
        $render_context['show_search'] = $this->create_shortcode_search_field();
        $render_context['search_position'] = $this->create_shortcode_search_position();
        $render_context['search_inline'] = $this->create_shortcode_search_inline();
        ob_start();
        if (!empty($this->query_args) && function_exists('qapl_render_post_container')) {
            qapl_render_post_container($this->query_args, $attributes, $render_context);
        }
        $output = ob_get_clean();
        return $output;
    }
}
add_shortcode('qapl-quick-ajax', array(new QAPL_Shortcode(), 'render_quick_ajax_shortcode'));

