<?php

if (!defined('ABSPATH')) {
    exit; // prevent direct access
}

class QAPL_Quick_Ajax_Post_Item_Actions {
    private $helper;
    private $global_options;
    public function __construct() {
        $this->helper = QAPL_Quick_Ajax_Helper::get_instance();
        $this->global_options = get_option($this->helper->admin_page_global_options_name(), []);
        // Register actions
        add_action('qapl_template_post_item_date', [$this, 'render_date']);
        add_action('qapl_template_post_item_image', [$this, 'render_image']);
        add_action('qapl_template_post_item_title', [$this, 'render_title']);
        add_action('qapl_template_post_item_excerpt', [$this, 'render_excerpt']);
        add_action('qapl_template_post_item_read_more', [$this, 'render_read_more']);
        add_action('qapl_template_render_load_more_button', [$this, 'render_load_more_button']);
    }

    public function render_date() {
        if (apply_filters('qapl_show_date', true)) {
            echo '<div class="post-date"><span>' . esc_html(get_the_date()) . '</span></div>';
        }
    }

    public function render_image() {
        if (has_post_thumbnail()) {
            echo '<div class="post-image">' . get_the_post_thumbnail(get_the_ID(), 'medium', ['loading' => 'lazy']) . '</div>';
        }
    }

    public function render_title() {
        $no_image = has_post_thumbnail() ? '' : 'no-image';
        echo '<div class="post-title '.$no_image.'"><h3>' . esc_html(get_the_title()) . '</h3></div>';
    }

    public function render_excerpt() {
        echo '<div class="post-desc"><p>' . esc_html(wp_trim_words(get_the_excerpt(), 20)) . '</p></div>';
    }

    public function render_read_more() {
        $label = $this->global_options['read_more_label'] ?? __('Read More', 'quick-ajax-post-loader');
        $label = apply_filters('qapl_template_read_more_label', $label);
        echo '<p class="read-more">' . esc_html($label) . '</p>';
    }
    public function render_load_more_button() {
        $label = $this->global_options['load_more_label'] ?? __('Load More', 'quick-ajax-post-loader');    
        $label = apply_filters('qapl_load_more_label', $label);
        echo '<button type="button" class="quick-ajax-load-more-button quick-ajax-button" data-button="quick-ajax-load-more">' . esc_html($label) . '</button>';
    }
}

// Initialize class
new QAPL_Quick_Ajax_Post_Item_Actions();
