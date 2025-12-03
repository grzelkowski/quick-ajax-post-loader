<?php 
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Ajax_End_Message_Renderer {
        private $file_manager;

    public function __construct(QAPL_File_Manager $file_manager) {
        $this->file_manager = $file_manager;
    }
    public function build_end_of_posts_message($load_more, $max_num_pages, $quick_ajax_id, $show_end_post_message = false) {
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
}