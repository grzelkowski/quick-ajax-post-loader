<?php 
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Ajax_Layout_Builder {
    private $file_manager;
    private $helper;

    public function __construct(QAPL_Quick_Ajax_File_Manager $file_manager, QAPL_Ajax_Helper $helper) {
        $this->file_manager = $file_manager;
        $this->helper = $helper;
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
            $layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_TAXONOMY_FILTER_CLASS] = $this->helper->extract_classes_from_string($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_TAXONOMY_FILTER_CLASS]);
        }
        //Add class to post container
        if(isset($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_CONTAINER_CLASS])){
            $layout[QAPL_Quick_Ajax_Constants::ATTRIBUTE_CONTAINER_CLASS] = $this->helper->extract_classes_from_string($attributes[QAPL_Quick_Ajax_Constants::ATTRIBUTE_CONTAINER_CLASS]);
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
}