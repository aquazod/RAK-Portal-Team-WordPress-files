<?php
/**
 * Plugin Name: Custom Elementor Widgets
 * Description: Adds a custom Elementor widget for the "hero" post type.
 * Version: 1.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) exit;

// Register the custom Elementor widget 
function register_hero_elementor_widget($widgets_manager) {
    require_once(__DIR__ . '/widgets/class-hero-widget.php');

    // Fix: Ensure class name matches the actual class definition
    if (class_exists('Hero_Elementor_Widget')) {
        $widgets_manager->register(new \Hero_Elementor_Widget());
    } else {
        error_log('Hero_Elementor_Widget class not found.');
    }
}

add_action('elementor/widgets/register', 'register_hero_elementor_widget');

