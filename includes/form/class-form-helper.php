<?php 
if (!defined('ABSPATH')) {
    exit;
}

final class QAPL_Form_Helper {
    
    public static function wp_kses_allowed_tags(){
        return array(
            'div' => array(
                'class' => array(),
                'id' => array(),
                'style' => array(),
                'tabindex' => array(),
                'data-item' => array(),
                'data-conditional' => array(),
                'role' => array(),
                'hidden' => array(),
            ),
            'button' => array(
                'type' => array(),
                'class' => array(),
                'style' => array(),
                'id' => array(),                
                'data-tab' => array(),
                'data-output' => array(),
                'data-copy' => array(),
                'role' => array(),
                'aria-selected' => array(),
                'aria-controls' => array(),
            ),
            'input' => array(
                'type' => array(),
                'name' => array(),
                'id' => array(),
                'value' => array(),
                'checked' => array(),
                'style' => array(),
                'placeholder' => array(),
                'class' => array(),
                'disabled' => array(),
                'readonly' => array(),
                'size' => array(),
                'maxlength' => array(),
                'min' => array(),
                'max' => array(),
                'step' => array(),
                'required' => array(),
            ),
            'select' => array(
                'name' => array(),
                'id' => array(),
                'style' => array(),
            ),
            'option' => array(
                'value' => array(),
                'selected' => array(),
                'style' => array(),
            ),
            'label' => array(
                'for' => array(),
                'style' => array(),
            ),
            'span' => array(
                'class' => array(),
                'style' => array(),
            ),
            'p' => array(
                'class' => array(),
                'style' => array(),
            ),
            'h1' => array(
                'class' => array(),
                'style' => array(),
            ),
            'h2' => array(
                'class' => array(),
                'style' => array(),
            ),
            'h3' => array(
                'class' => array(),
                'style' => array(),
            ),
            'h4' => array(
                'class' => array(),
                'style' => array(),
            ),
            'h5' => array(
                'class' => array(),
                'style' => array(),
            ),
            'h6' => array(
                'class' => array(),
                'style' => array(),
            ),
            'strong' => array(
                'class' => array(),
            ),
            'ul' => array(
                'class' => array(),
                'style' => array(),
                'id' => array(),
            ),
            'li' => array(
                'class' => array(),
                'style' => array(),
                'id' => array(),
            ),
            'code' => array(
                'class' => array(),
            ),
            'pre' => array(
                'class' => array(),
                'id' => array(),
            ),            
            'form' => array(
                'class' => array(),
                'id' => array(),
                'action' => array(),
                'method' => array(),
            ),
        );
    } 
}
