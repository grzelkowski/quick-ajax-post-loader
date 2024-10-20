<?php 
if (!defined('ABSPATH')) {
    exit;
}

if (QAPL_Quick_Ajax_Helper::element_exists('class','QAPL_Quick_Ajax_Shortcode')) {
    class QAPL_Quick_Ajax_Shortcode {
        private $shortcode_args = array();
        private $shortcode_settings = array();
        
        private function get_shortcode_args($args) {
            $defaults = array(
                'id' => '',
                'excluded_post_ids' =>'',
                'post_type' =>'',
                'posts_per_page' => '',
                'order' => '',
                'orderby' => '',
                'post_status' => '',
                'quick_ajax_css_style' => '',
                'grid_num_columns' => '',
                'post_item_template' => '',
                'taxonomy_filter_class' => '',
                'container_class' => '',
                'load_more_posts' => '',
                'loader_icon' => '',
                'quick_ajax_id' => '',
                'quick_ajax_taxonomy' => '',
                'ignore_sticky_posts' => '',
            );
            
            $merged_atts = array_map('sanitize_text_field', shortcode_atts($defaults, $args, 'quick-ajax'));

            if (!is_numeric($merged_atts['id'])) {
                $merged_atts['id'] = '';
            }
            $merged_atts['ignore_sticky_posts'] = isset($merged_atts['ignore_sticky_posts']) ? filter_var($merged_atts['ignore_sticky_posts'], FILTER_VALIDATE_BOOLEAN) : false;
            $merged_atts['excluded_post_ids'] = is_string($merged_atts['excluded_post_ids']) ? array_filter(array_map('intval', explode(',', $merged_atts['excluded_post_ids']))) : '';
            $merged_atts['post_type'] = is_string($merged_atts['post_type']) ? $merged_atts['post_type'] : '';
            $merged_atts['posts_per_page'] = is_string($merged_atts['posts_per_page'])  ? intval($merged_atts['posts_per_page'])  : '';
            $merged_atts['order'] = is_string($merged_atts['order']) ? $merged_atts['order'] : '';
            $merged_atts['orderby'] = is_string($merged_atts['orderby']) ? $merged_atts['orderby'] : '';
            $merged_atts['post_status'] = is_string($merged_atts['post_status']) ? $merged_atts['post_status'] : '';
            $merged_atts['quick_ajax_css_style'] = is_numeric($merged_atts['quick_ajax_css_style']) ? intval($merged_atts['quick_ajax_css_style']) : '';
            $merged_atts['grid_num_columns'] = is_numeric($merged_atts['grid_num_columns']) ? intval($merged_atts['grid_num_columns']) : '';
            $merged_atts['post_item_template'] = is_string($merged_atts['post_item_template']) ? $merged_atts['post_item_template'] : '';
            $merged_atts['taxonomy_filter_class'] = is_string($merged_atts['taxonomy_filter_class']) ? $merged_atts['taxonomy_filter_class'] : '';
            $merged_atts['container_class'] = is_string($merged_atts['container_class']) ? $merged_atts['container_class'] : '';
            $merged_atts['load_more_posts'] = is_numeric($merged_atts['load_more_posts']) ? intval($merged_atts['load_more_posts']) : '';
            $merged_atts['loader_icon'] = is_string($merged_atts['loader_icon']) ? $merged_atts['loader_icon'] : '';
            $merged_atts['quick_ajax_id'] = is_numeric($merged_atts['quick_ajax_id']) ? intval($merged_atts['quick_ajax_id']) : '';
            $merged_atts['quick_ajax_taxonomy'] = is_string($merged_atts['quick_ajax_taxonomy']) ? $merged_atts['quick_ajax_taxonomy'] : '';
            $this->shortcode_args = $merged_atts;
            
        }
        
        private function unserialize_shortcode_data($id){
            $serialized_data = get_post_meta($id, QAPL_Quick_Ajax_Helper::settings_wrapper_id(), true);
            if ($serialized_data) {
                $form_data = maybe_unserialize($serialized_data);
                foreach ($form_data as $field_name => $field_value) {
                    $this->shortcode_settings[$field_name] = $field_value;
                }
            }
        }

        private function create_shortcode_args(){
            $args = array();
            if(!empty($this->shortcode_args['id'])){
                $selected_post_type = !empty($this->shortcode_args['post_type']) ? $this->shortcode_args['post_type'] : $this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_select_post_type()];
                $post_per_page = !empty($this->shortcode_args['posts_per_page']) ? $this->shortcode_args['posts_per_page'] : $this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_select_posts_per_page()];
                $post_order = !empty($this->shortcode_args['order']) ? $this->shortcode_args['order'] : $this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_select_order()];
                $post_orderby = !empty($this->shortcode_args['orderby']) ? $this->shortcode_args['orderby'] : $this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_select_orderby()];
                $post_post_status = !empty($this->shortcode_args['post_status']) ? $this->shortcode_args['post_status'] : $this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_select_post_status()];
                $post_not_in = ($this->shortcode_args['excluded_post_ids']) ? $this->shortcode_args['excluded_post_ids'] : $this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_set_post_not_in()];     
                $ignore_sticky_posts = ($this->shortcode_args['ignore_sticky_posts']) ? $this->shortcode_args['ignore_sticky_posts'] : $this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_ignore_sticky_posts()];     
            }else{
                $selected_post_type = !empty($this->shortcode_args['post_type']) ? $this->shortcode_args['post_type'] : '';
                $post_per_page = !empty($this->shortcode_args['posts_per_page']) ? $this->shortcode_args['posts_per_page'] : '';
                $post_order = !empty($this->shortcode_args['order']) ? $this->shortcode_args['order'] : '';
                $post_orderby = !empty($this->shortcode_args['orderby']) ? $this->shortcode_args['orderby'] : '';
                $post_post_status = !empty($this->shortcode_args['post_status']) ? $this->shortcode_args['post_status'] : '';
                $post_not_in = !empty($this->shortcode_args['excluded_post_ids']) ? $this->shortcode_args['excluded_post_ids'] : '';
                $ignore_sticky_posts = !empty($this->shortcode_args['ignore_sticky_posts']) ? $this->shortcode_args['ignore_sticky_posts'] : '';
            }
            if(!empty($selected_post_type)){
                $args = array(
                    'post_type' => $selected_post_type,
                    'post_status' => $post_post_status,
                    'orderby' => $post_orderby, 
                    'order' => $post_order,                     
                    'posts_per_page' => $post_per_page,
                    'post__not_in' => $post_not_in,
                    'ignore_sticky_posts' => $ignore_sticky_posts,
                );
            }
            if(!empty($args)){
                return $args;
            }
            return false;
        }
        /*
        private function create_shortcode_attributes_old(){
            if(!empty($this->shortcode_args['id'])){
                $attributes['shortcode'] = true;
                $attributes[QAPL_Quick_Ajax_Helper::layout_quick_ajax_id()] = $this->shortcode_args['id'];
                if(isset($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_quick_ajax_css_style()]) && ($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_quick_ajax_css_style()] != 0)){
                    $attributes[QAPL_Quick_Ajax_Helper::layout_quick_ajax_css_style()] = $this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_quick_ajax_css_style()];         
                }
                if(isset($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_quick_ajax_css_style()]) && ($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_quick_ajax_css_style()] != 0) && isset($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_select_columns_qty()])){
                    $attributes[QAPL_Quick_Ajax_Helper::layout_grid_num_columns()] = $this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_select_columns_qty()];         
                }
                if(isset($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_post_item_template()])){
                    $attributes[QAPL_Quick_Ajax_Helper::layout_post_item_template()] = $this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_post_item_template()]; 
                }
                if(isset($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_taxonomy_filter_class()])){
                    $attributes[QAPL_Quick_Ajax_Helper::layout_taxonomy_filter_class()] = $this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_taxonomy_filter_class()]; 
                }
                if(isset($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_container_class()])){
                    $attributes[QAPL_Quick_Ajax_Helper::layout_container_class()] = $this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_container_class()]; 
                }
                if(isset($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_show_custom_load_more_post_quantity()]) && ($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_show_custom_load_more_post_quantity()] != 0)){
                    $attributes[QAPL_Quick_Ajax_Helper::layout_load_more_posts()] = $this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_select_custom_load_more_post_quantity()];         
                }
                if(isset($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_override_global_loader_icon()]) && ($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_override_global_loader_icon()] != 0) && isset($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_select_loader_icon()])){
                    $attributes[QAPL_Quick_Ajax_Helper::layout_select_loader_icon()] = $this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_select_loader_icon()]; 
                }
            }
            if(!empty($attributes)){
                return $attributes;
            }
            return false;
        }*/
        // updated version if attributes are added to the shortcode
        private function create_shortcode_attributes() {
            $attributes = array();
        
            if (!empty($this->shortcode_args['id'])) {
                $attributes['shortcode'] = true;
                $attributes[QAPL_Quick_Ajax_Helper::layout_quick_ajax_id()] = absint($this->shortcode_args['id']);
        
                $attributes[QAPL_Quick_Ajax_Helper::layout_quick_ajax_css_style()] = 
                    !empty($this->shortcode_args['quick_ajax_css_style']) ? 
                    sanitize_text_field($this->shortcode_args['quick_ajax_css_style']) : 
                    (isset($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_quick_ajax_css_style()]) ? 
                    sanitize_text_field($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_quick_ajax_css_style()]) : '');
        
                $attributes[QAPL_Quick_Ajax_Helper::layout_grid_num_columns()] = 
                    !empty($this->shortcode_args['grid_num_columns']) ? 
                    intval($this->shortcode_args['grid_num_columns']) : 
                    (isset($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_select_columns_qty()]) ? 
                    intval($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_select_columns_qty()]) : '');
        
                $attributes[QAPL_Quick_Ajax_Helper::layout_post_item_template()] = 
                    !empty($this->shortcode_args['post_item_template']) ? 
                    sanitize_text_field($this->shortcode_args['post_item_template']) : 
                    (isset($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_post_item_template()]) ? 
                    sanitize_text_field($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_post_item_template()]) : '');
        
                $attributes[QAPL_Quick_Ajax_Helper::layout_taxonomy_filter_class()] = 
                    !empty($this->shortcode_args['taxonomy_filter_class']) ? 
                    sanitize_html_class($this->shortcode_args['taxonomy_filter_class']) : 
                    (isset($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_taxonomy_filter_class()]) ? 
                    sanitize_html_class($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_taxonomy_filter_class()]) : '');
        
                $attributes[QAPL_Quick_Ajax_Helper::layout_container_class()] = 
                    !empty($this->shortcode_args['container_class']) ? 
                    sanitize_html_class($this->shortcode_args['container_class']) : 
                    (isset($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_container_class()]) ? 
                    sanitize_html_class($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_layout_container_class()]) : '');
        
                $attributes[QAPL_Quick_Ajax_Helper::layout_load_more_posts()] = 
                    !empty($this->shortcode_args['load_more_posts']) ? 
                    intval($this->shortcode_args['load_more_posts']) : 
                    (isset($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_show_custom_load_more_post_quantity()]) && 
                    intval($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_show_custom_load_more_post_quantity()]) !== 0 ? 
                    intval($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_select_custom_load_more_post_quantity()]) : 0);
        
                $attributes[QAPL_Quick_Ajax_Helper::layout_select_loader_icon()] = 
                    !empty($this->shortcode_args['loader_icon']) ? 
                    sanitize_text_field($this->shortcode_args['loader_icon']) : 
                    (isset($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_override_global_loader_icon()]) && 
                    intval($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_override_global_loader_icon()]) !== 0 && 
                    isset($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_select_loader_icon()]) ? 
                    sanitize_text_field($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_select_loader_icon()]) : '');
            } else {
                $attributes[QAPL_Quick_Ajax_Helper::layout_quick_ajax_id()] = 
                    !empty($this->shortcode_args['quick_ajax_id']) ? 
                    absint($this->shortcode_args['quick_ajax_id']) : 0;
        
                $attributes[QAPL_Quick_Ajax_Helper::layout_quick_ajax_css_style()] = 
                    !empty($this->shortcode_args['quick_ajax_css_style']) ? 
                    sanitize_text_field($this->shortcode_args['quick_ajax_css_style']) : '';
        
                $attributes[QAPL_Quick_Ajax_Helper::layout_grid_num_columns()] = 
                    !empty($this->shortcode_args['grid_num_columns']) ? 
                    intval($this->shortcode_args['grid_num_columns']) : 0;
        
                $attributes[QAPL_Quick_Ajax_Helper::layout_post_item_template()] = 
                    !empty($this->shortcode_args['post_item_template']) ? 
                    sanitize_text_field($this->shortcode_args['post_item_template']) : '';
        
                $attributes[QAPL_Quick_Ajax_Helper::layout_taxonomy_filter_class()] = 
                    !empty($this->shortcode_args['taxonomy_filter_class']) ? 
                    sanitize_html_class($this->shortcode_args['taxonomy_filter_class']) : '';
        
                $attributes[QAPL_Quick_Ajax_Helper::layout_container_class()] = 
                    !empty($this->shortcode_args['container_class']) ? 
                    sanitize_html_class($this->shortcode_args['container_class']) : '';
        
                $attributes[QAPL_Quick_Ajax_Helper::layout_load_more_posts()] = 
                    !empty($this->shortcode_args['load_more_posts']) ? 
                    intval($this->shortcode_args['load_more_posts']) : 0;
        
                $attributes[QAPL_Quick_Ajax_Helper::layout_select_loader_icon()] = 
                    !empty($this->shortcode_args['loader_icon']) ? 
                    sanitize_text_field($this->shortcode_args['loader_icon']) : '';
            }
        
            if (!empty($attributes)) {
                return $attributes;
            }
        
            return false;
        }
               

        private function create_shortcode_taxonomy(){
            if(!empty($this->shortcode_args['id'])){
                //$postID = $this->shortcode_args['id'];
                //$selectedTaxonomy = get_post_meta($postID, 'quick_ajax_meta_box_select_taxonomy', true);
                $show_taxonomies_filter = isset($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_show_taxonomy_filter()]) ? $this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_show_taxonomy_filter()] : null;
                if($show_taxonomies_filter==1){
                    $selectedTaxonomy = isset($this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_select_taxonomy()]) ? $this->shortcode_settings[QAPL_Quick_Ajax_Helper::shortcode_page_select_taxonomy()] : null;
                    $selectedTaxonomy = esc_attr($selectedTaxonomy);
                }
            }
            if(!empty($selectedTaxonomy)){
                return $selectedTaxonomy;
            }
            return null;
        }

        public function render_quick_ajax_shortcode($atts) {
            $this->get_shortcode_args($atts);
            if (!empty($this->shortcode_args['id'])) {
                $this->unserialize_shortcode_data($this->shortcode_args['id']);
            }
            $args = $this->create_shortcode_args();
            $attributes = $this->create_shortcode_attributes();
            $taxonomy = $this->create_shortcode_taxonomy();
            ob_start();
            if (!empty($args) && function_exists('qapl_quick_ajax_post_grid')) {
                qapl_quick_ajax_post_grid($args, $attributes, $taxonomy);
            }
            $output = ob_get_clean();
            return $output;
        }
    }
    $quick_ajax_shortcode = new QAPL_Quick_Ajax_Shortcode();
    add_shortcode('qapl-quick-ajax', array($quick_ajax_shortcode, 'render_quick_ajax_shortcode'));
}
?>