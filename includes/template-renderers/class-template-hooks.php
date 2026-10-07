<?php

if (!defined('ABSPATH')) {
    exit; // prevent direct access
}

interface QAPL_Post_Item_Date_Interface {
    public function render_date();
}

interface QAPL_Post_Item_Image_Interface {
    public function render_image();
}

interface QAPL_Post_Item_Title_Interface {
    public function render_title();
}

interface QAPL_Post_Item_Excerpt_Interface {
    public function render_excerpt();
}

interface QAPL_Post_Item_ReadMore_Interface {
    public function render_read_more();
}

interface QAPL_Load_More_Interface {
    public function render_load_more_button();
}

interface QAPL_End_Post_Message_Interface {
    public function render_end_post_message();
}

interface QAPL_No_Post_Message_Interface {
    public function render_no_post_message();
}

function qapl_output_template_post_date() {
    $template = QAPL_Post_Template_Context::get_template();
    if ($template && method_exists($template, 'render_date')) {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- template method returns prepared html
        echo $template->render_date();
    }
}
function qapl_output_template_post_image() {
    $template = QAPL_Post_Template_Context::get_template();
    if ($template && method_exists($template, 'render_image')) {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- template method returns prepared html
        echo $template->render_image();
    }
}
function qapl_output_template_post_title() {
    $template = QAPL_Post_Template_Context::get_template();
    if ($template && method_exists($template, 'render_title')) {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- template method returns prepared html
        echo $template->render_title();
    }
}
function qapl_output_template_post_excerpt() {
    $template = QAPL_Post_Template_Context::get_template();
    if ($template && method_exists($template, 'render_excerpt')) {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- template method returns prepared html
        echo $template->render_excerpt();
    }
}
function qapl_output_template_post_read_more() {
    $template = QAPL_Post_Template_Context::get_template();
    if ($template && method_exists($template, 'render_read_more')) {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- template method returns prepared html
        echo $template->render_read_more();
    }
}
function qapl_output_template_button_load_more() {
    $template = QAPL_Post_Template_Context::get_template();
    if ($template && method_exists($template, 'render_load_more_button')) {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- template method returns prepared html
        echo $template->render_load_more_button();
    }
}
function qapl_output_template_no_post_message() {
    $template = QAPL_Post_Template_Context::get_template();
    if ($template && method_exists($template, 'render_no_post_message')) {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- template method returns prepared html
        echo $template->render_no_post_message();
    }
}
function qapl_output_template_end_post_message() {
    $template = QAPL_Post_Template_Context::get_template();
    if ($template && method_exists($template, 'render_end_post_message')) {
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- template method returns prepared html
        echo $template->render_end_post_message();
    }
}

class QAPL_Template_Post_Item extends QAPL_Template_Base
    implements  QAPL_Post_Item_Date_Interface, 
                QAPL_Post_Item_Image_Interface, 
                QAPL_Post_Item_Title_Interface, 
                QAPL_Post_Item_Excerpt_Interface, 
                QAPL_Post_Item_ReadMore_Interface {

    public function render_date() {
        if (!$this->config->get('show_date')) {
            return '';
        }
        $date_format = $this->config->get('date_format');
        $output = '<div class="qapl-post-date"><span>' . esc_html(get_the_date($date_format)) . '</span></div>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_POST_ITEM_DATE, $output, $this->template_name, $this->quick_ajax_id);
    }

    public function render_image() {
        $image_id = get_post_thumbnail_id();
        if (!$image_id) {
            return '<div class="qapl-post-image qapl-no-image"></div>';
        }
        $output = '<div class="qapl-post-image">';
        $output .= wp_get_attachment_image($image_id, 'large', false, ['loading' => 'lazy', 'alt' => esc_attr(get_the_title())]);
        $output .= '</div>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_POST_ITEM_IMAGE, $output, $this->template_name, $this->quick_ajax_id);
    }

    public function render_title() {
        $output = '<div class="qapl-post-title"><h3>' . esc_html(get_the_title()) . '</h3></div>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_POST_ITEM_TITLE, $output, $this->template_name, $this->quick_ajax_id);
    }

    public function render_excerpt() {
        $output = '<div class="qapl-post-description"><p>' . esc_html(wp_trim_words(get_the_excerpt(), 20)) . '</p></div>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_POST_ITEM_EXCERPT, $output, $this->template_name, $this->quick_ajax_id);
    }

    public function render_read_more() {
        if (!$this->config->get('show_read_more')) {
            return '';
        }
        $label = $this->config->get('read_more_label');
        $output = '<div class="qapl-read-more"><p>' . esc_html($label) . '</p></div>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_POST_ITEM_READ_MORE, $output, $this->template_name, $this->quick_ajax_id);
    }
}


class QAPL_Template_Post_Item_Qapl_Full_Background_Image extends QAPL_Template_Base
    implements  QAPL_Post_Item_Date_Interface, 
                QAPL_Post_Item_Image_Interface, 
                QAPL_Post_Item_Title_Interface, 
                QAPL_Post_Item_Excerpt_Interface, 
                QAPL_Post_Item_ReadMore_Interface {

    public function render_date() {
        if (!$this->config->get('show_date')) {
            return '';
        }
        $date_format = $this->config->get('date_format');
        $output = '<div class="qapl-post-date"><span>' . esc_html(get_the_date($date_format)) . '</span></div>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_POST_ITEM_DATE, $output, $this->template_name, $this->quick_ajax_id);
    }

    public function render_image() {
        $image_id = get_post_thumbnail_id();
        if (!$image_id) {
            return '<span class="qapl-no-image"></span>';
        }
        $output = wp_get_attachment_image($image_id, 'large', false, ['loading' => 'lazy', 'class' => 'qapl-post-image', 'alt' => esc_attr(get_the_title())]);
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_POST_ITEM_IMAGE, $output, $this->template_name, $this->quick_ajax_id);
    }

    public function render_title() {
        $output = '<div class="qapl-post-title"><h3>' . esc_html(get_the_title()) . '</h3></div>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_POST_ITEM_TITLE, $output, $this->template_name, $this->quick_ajax_id);
    }

    public function render_excerpt() {
        $output = '<div class="qapl-post-description"><p>' . esc_html(wp_trim_words(get_the_excerpt(), 20)) . '</p></div>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_POST_ITEM_EXCERPT, $output, $this->template_name, $this->quick_ajax_id);
    }
    
    public function render_read_more() {
        if (!$this->config->get('show_read_more')) {
            return '';
        }
        $label = $this->config->get('read_more_label');
        $output = '<div class="qapl-read-more"><p>' . esc_html($label) . '</p></div>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_POST_ITEM_READ_MORE, $output, $this->template_name, $this->quick_ajax_id);
    }
}

class QAPL_Template_Load_More_Button extends QAPL_Template_Base implements QAPL_Load_More_Interface {
    public function render_load_more_button() {   
        $label = $this->config->get('load_more_label');
        $output = '<button type="button" class="qapl-load-more-button qapl-button" data-button="'.QAPL_Constants::LOAD_MORE_BUTTON_DATA_BUTTON.'">' . esc_html($label) . '</button>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_LOAD_MORE_BUTTON, $output, $this->quick_ajax_id);
    }
}

class QAPL_Template_End_Post_Message extends QAPL_Template_Base implements QAPL_End_Post_Message_Interface {
    public function render_end_post_message() {   
        $end_post_message = $this->config->get('end_post_message');
        $output = '<p>' . esc_html($end_post_message) . '</p>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_END_POST_MESSAGE, $output, $this->quick_ajax_id);
    }
}

class QAPL_Template_No_Post_Message extends QAPL_Template_Base implements QAPL_No_Post_Message_Interface {
    public function render_no_post_message() {   
        $no_post_message = $this->config->get('no_post_message');
        $output = '<p>' . esc_html($no_post_message) . '</p>';
        return apply_filters(QAPL_Constants::HOOK_TEMPLATE_NO_POST_MESSAGE, $output, $this->quick_ajax_id);
    }
}


abstract class QAPL_Template_Empty_Filters {
    public function render_date() { return ''; }
    public function render_image() { return ''; }
    public function render_title() { return ''; }
    public function render_excerpt() { return ''; }
    public function render_read_more() { return ''; }
    public function render_load_more_button() { return ''; }
    public function render_no_post_message() { return ''; }
    public function render_end_post_message() { return ''; }
}


class QAPL_Post_Template_Context {
    private static $current_template;

    public static function set_template($template) {
        self::$current_template = $template;
    }

    public static function get_template() {
        return self::$current_template;
    }

    public static function clear_template() {
        self::$current_template = null;
    }
}