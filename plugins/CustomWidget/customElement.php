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
    require_once(__DIR__ . '/widgets/class-infographic-widget.php');
    require_once(__DIR__ . '/widgets/class-tooltip-widget.php');
    require_once(__DIR__ . '/widgets/class-dynamic-image-toggle.php');
    require_once(__DIR__ . '/widgets/test-banner-widget.php');
    require_once(__DIR__ . '/widgets/all-controllers-widget.php');

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

    if (class_exists('Tooltip_Elementor_Widget')) {
        $widgets_manager->register(new \Tooltip_Elementor_Widget());
    } else {
        error_log('Tooltip_Elementor_Widget class not found.');
    }

    $widgets_manager->register(new \My_Dynamic_Image_Toggle());
    $widgets_manager->register(new \Banner_Gallery_Slider());
    $widgets_manager->register(new \Elementor_All_Controls_Demo_Widget());
}
add_action('elementor/widgets/register', 'register_elementor_widgets');

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

    wp_enqueue_style(
        'tooltip-css',
        plugins_url('css/tooltip.css', __FILE__),
        [],
        filemtime(plugin_dir_path(__FILE__) . 'css/tooltip.css'),
        'all'
    );
}
add_action('wp_enqueue_scripts', 'enqueue_widget_styles_global');