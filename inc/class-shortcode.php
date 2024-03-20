<?php 
if (!defined('ABSPATH')) {
    exit;
}

if (WPG_Quick_Ajax_Helper::quick_ajax_element_exists('class','WPG_Quick_Ajax_Shortcode')) {
    class WPG_Quick_Ajax_Shortcode {
        private $shortcode_args = array();
        private $shortcode_settings = array();
        
        private function get_shortcode_attributes($attributes) {
            $defaults = array(
                'id' => '',
                'excluded_post_ids' =>''
            );
            
            $merged_atts = shortcode_atts($defaults, $attributes, 'quick-ajax');
            $this->shortcode_args = $merged_atts;
        }
        
        private function unserialize_shortcode_data($id){
            $serialized_data = get_post_meta($id, WPG_Quick_Ajax_Helper::quick_ajax_settings_wrapper_id(), true);
            if ($serialized_data) {
                $form_data = unserialize($serialized_data);
                foreach ($form_data as $field_name => $field_value) {
                    $this->shortcode_settings[$field_name] = $field_value;
                }
            }
        }

        private function create_shortcode_args(){
            if(!empty($this->shortcode_args['id'])){
                $selected_post_type = $this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_post_type()];
                $post_per_page = $this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_posts_per_page()];
                $post_order = $this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_order()];
                $post_orderby = $this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_orderby()];
                $post_post_status = $this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_post_status()];
                $post_not_in = $this->shortcode_args['excluded_post_ids'];
                
                if(!empty($selected_post_type)){
                    $args = array(
                        'post_type' => $selected_post_type,
                        'post_status' => $post_post_status,
                        'orderby' => $post_orderby, 
                        'order' => $post_order,                     
                        'posts_per_page' => $post_per_page,
                        'post__not_in' => $post_not_in,
                    );
                }
            }
            if(!empty($args)){
                return $args;
            }
            return false;
        }

        private function create_shortcode_attributes(){
            if(!empty($this->shortcode_args['id'])){
                $attributes['shortcode'] = true;
                $attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_quick_ajax_id()] = $this->shortcode_args['id'];
                if(isset($this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_quick_ajax_css_style()]) && ($this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_quick_ajax_css_style()] != 0)){
                    $attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_quick_ajax_css_style()] = $this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_quick_ajax_css_style()];         
                }
                if(isset($this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_quick_ajax_css_style()]) && ($this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_quick_ajax_css_style()] != 0) && isset($this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_select_columns_qty()])){
                    $attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_grid_num_columns()] = $this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_select_columns_qty()];         
                }
                if(isset($this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_post_item_template()])){
                    $attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_post_item_template()] = $this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_post_item_template()]; 
                }
                if(isset($this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_taxonomy_filter_class()])){
                    $attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_taxonomy_filter_class()] = $this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_taxonomy_filter_class()]; 
                }
                if(isset($this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_container_class()])){
                    $attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_container_class()] = $this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_layout_container_class()]; 
                }
                if(isset($this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_show_custom_load_more_post_quantity()]) && ($this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_show_custom_load_more_post_quantity()] != 0)){
                    $attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_load_more_posts()] = $this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_custom_load_more_post_quantity()];         
                }
                if(isset($this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_override_global_loader_icon()]) && ($this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_override_global_loader_icon()] != 0) && isset($this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_loader_icon()])){
                    $attributes[WPG_Quick_Ajax_Helper::quick_ajax_layout_select_loader_icon()] = $this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_loader_icon()]; 
                }
            }
            if(!empty($attributes)){
                return $attributes;
            }
            return false;
        }

        private function create_shortcode_taxonomy(){
            if(!empty($this->shortcode_args['id'])){
                //$postID = $this->shortcode_args['id'];
                //$selectedTaxonomy = get_post_meta($postID, 'quick_ajax_meta_box_select_taxonomy', true);
                $show_taxonomies_filter = isset($this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_show_taxonomy_filter()]) ? $this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_show_taxonomy_filter()] : null;
                if($show_taxonomies_filter==1){
                    $selectedTaxonomy = isset($this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_taxonomy()]) ? $this->shortcode_settings[WPG_Quick_Ajax_Helper::quick_ajax_shortcode_page_select_taxonomy()] : null;
                    $selectedTaxonomy = esc_attr($selectedTaxonomy);
                }
            }
            if(!empty($selectedTaxonomy)){
                return $selectedTaxonomy;
            }
            return null;
        }

        public function render_quick_ajax_shortcode($atts) {
            $this->get_shortcode_attributes($atts);
            $this->unserialize_shortcode_data($this->shortcode_args['id']);
            $args = $this->create_shortcode_args();
            $attributes = $this->create_shortcode_attributes();
            $taxonomy = $this->create_shortcode_taxonomy();
            ob_start();
            if (!empty($args) && function_exists('wpg_quick_ajax_post_grid')) {
                wpg_quick_ajax_post_grid($args, $attributes, $taxonomy);
            }
            $output = ob_get_clean();
            return $output;
        }
    }
    $quick_ajax_shortcode = new WPG_Quick_Ajax_Shortcode();
    add_shortcode('quick-ajax', array($quick_ajax_shortcode, 'render_quick_ajax_shortcode'));
}
?>