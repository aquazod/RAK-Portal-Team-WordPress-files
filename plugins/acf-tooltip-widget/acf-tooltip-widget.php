<?php
/*
Plugin Name: Custom Elementor Tooltips
Description: Adds a dynamic ACF-powered Tooltip widget to Elementor.
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) exit;

function register_tooltip_widget($widgets_manager) {
    require_once(__DIR__ . '/widgets/class-tooltip-widget.php');

    if (class_exists('Tooltip_Elementor_Widget')) {
        $widgets_manager->register(new \Tooltip_Elementor_Widget());
    }
}

add_action('elementor/widgets/register', 'register_tooltip_widget');
