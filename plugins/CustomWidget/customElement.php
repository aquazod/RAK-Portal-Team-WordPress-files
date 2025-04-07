<?php
/**
 * Plugin Name: Custom Widgets
 * Description: Adds a custom Elementor widget for the "infographic" post type.
 * Version: 1.0
 * Author: Your Name
 */

if (!defined('ABSPATH'))
    exit;

// Register the custom Elementor widget 
function register_infographic_elementor_widget($widgets_manager)
{
    require_once(__DIR__ . '/class-infographic-widget.php');

    // Fix: Ensure class name matches the actual class definition
    if (class_exists('Infographic_Elementor_Widget')) {
        $widgets_manager->register(new \Infographic_Elementor_Widget());
    } else {
        error_log('infographic_Elementor_Widget class not found.');
    }
}

add_action('elementor/widgets/register', 'register_infographic_elementor_widget');

