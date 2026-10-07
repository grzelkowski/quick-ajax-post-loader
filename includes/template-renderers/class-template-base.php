<?php 
if (!defined('ABSPATH')) {
    exit;
}

class QAPL_Template_Config {
    protected $options = [];
    public function __construct(array $options = []) {
        $defaults = [
            'show_date'        => true,                // Show/hide date
            'date_format'      => get_option('date_format'),      // Date format
            'show_read_more'   => true,                // Show/hide "read more"
            'read_more_label'  => null,                // Read more label;
            'load_more_label'  => null,                // Load more label;
            'no_post_message' => null,                 // No post message;
            'end_post_message' => null,                // End post message;
        ];
        $this->options = wp_parse_args($options, $defaults);
    }
    public function get(string $key, $default = null) {
        return isset($this->options[$key]) ? $this->options[$key] : $default;
    }
    public function set(string $key, $value) {
        $this->options[$key] = $value;
    }
    public function toArray(): array {
        return $this->options;
    }
}

abstract class QAPL_Template_Base {
    protected $quick_ajax_id;
    protected $template_name;
    protected $config;

    public function __construct($quick_ajax_id, $template_name, QAPL_Template_Config $config, array $global_options) {
        $this->quick_ajax_id = $quick_ajax_id;
        $this->template_name = $template_name;
        $this->config = $config;

        // Initialize config using global options
        $this->init_config($global_options);
    }

    protected function init_config(array $global_options) {
        if ($this->config->get('show_date') === true) {
            $this->config->set('date_format', $global_options['date_format'] ?? get_option('date_format'));
        }
        if ($this->config->get('show_read_more') === true) {
            $this->config->set('read_more_label', !empty($global_options['read_more_label']) ? $global_options['read_more_label'] : __('Read More', 'quick-ajax-post-loader'));
        }
        if ($this->config->get('load_more_label') === null) {
            $this->config->set('load_more_label', !empty($global_options['load_more_label']) ? $global_options['load_more_label'] : __('Load More', 'quick-ajax-post-loader'));
        }
        if ($this->config->get('no_post_message') === null) {
            $this->config->set('no_post_message', !empty($global_options['no_post_message']) ? $global_options['no_post_message'] : __('No posts found', 'quick-ajax-post-loader'));
        }
        if ($this->config->get('end_post_message') === null) {
            $this->config->set('end_post_message', !empty($global_options['end_post_message']) ? $global_options['end_post_message'] : __('No more posts to load', 'quick-ajax-post-loader'));
        }
              
    }
}
