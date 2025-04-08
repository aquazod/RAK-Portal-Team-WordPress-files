<?php
/**
 * Plugin Name: Custom Elementor Widgets
 * Description: Adds a custom Elementor widgets.
 * Version: 1.0
 * Author: Portal Team
 */

if (!defined('ABSPATH')) exit;

// Register the custom Elementor widget 
function register_elementor_widgets($widgets_manager) {
    require_once(__DIR__ . '/widgets/class-hero-widget.php');
    require_once(__DIR__ . '/widgets/class-banner-widget.php');
    require_once(__DIR__ . '/widgets/class-flag-widget.php');
    require_once(__DIR__ . '/class-infographic-widget.php');
    // Fix: Ensure class name matches the actual class definition
    if (class_exists('Hero_Elementor_Widget')) {
        $widgets_manager->register(new \Hero_Elementor_Widget());
    } else {
        error_log('Hero_Elementor_Widget class not found.');
    }
    if (class_exists('Banner_Elementor_Widget')) {
        $widgets_manager->register(new \Banner_Elementor_Widget());
    } else {
        error_log('Banner_Elementor_Widget class not found.');
    }
    if (class_exists('Flag_Elementor_Widget')) {
        $widgets_manager->register(new \Flag_Elementor_Widget());
    } else {
        error_log('Flag_Elementor_Widget class not found.');
    }
    if (class_exists('Infographic_Elementor_Widget')) {
        $widgets_manager->register(new \Infographic_Elementor_Widget());
    } else {
        error_log('infographic_Elementor_Widget class not found.');
    }
}

// enqueue the styles for the widgets
function enqueue_widget_styles_global() {
    wp_enqueue_style(
        'hero-css',
        plugins_url('css/hero-style.css', __FILE__),
        [],
        filemtime(plugin_dir_path(__FILE__) . 'css/hero-style.css'),
        'all'
    );

    wp_enqueue_style(
        'flag-css',
        plugins_url('css/Flag-Styles.css', __FILE__),
        [],
        filemtime(plugin_dir_path(__FILE__) . 'css/Flag-Styles.css'),
        'all'
    );
}
add_action('wp_enqueue_scripts', 'enqueue_widget_styles_global');


add_action('elementor/widgets/register', 'register_elementor_widgets');

