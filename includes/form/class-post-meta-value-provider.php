<?php
if (!defined('ABSPATH')) {
    exit;
}
class QAPL_Post_Meta_Value_Provider implements QAPL_Value_Provider_Interface {
    private array $data = [];
    public function __construct(int $post_id, string $meta_key) {
        $meta = get_post_meta($post_id, $meta_key, true);
        if (is_string($meta)) {
            $meta = maybe_unserialize($meta);
        }
        if (is_array($meta)) {
            $this->data = $meta;
        }
    }
    public function get(string $field_name) {
        return $this->data[$field_name] ?? null;
    }
}