<?php if (!defined('ABSPATH')) exit;

class Hero_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'hero_widget';
    }

    public function get_title() {
        return __('Hero Section', 'text-domain');
    }

    public function get_icon() {
        return 'eicon-post';
    }

    public function get_categories() {
        return ['custom-category'];
    }

    public function get_keywords() {
        return ['hero', 'banner', 'section'];
    }

    protected function register_controls() { // FIXED: Removed underscore
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Settings', 'text-domain'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'hero_post',
            [
                'label' => __('Select Hero', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_hero_posts(),
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    private function get_hero_posts() {
        $heroes = get_posts([
            'post_type' => 'hero',
            'posts_per_page' => -1
        ]);

        $options = [];
        foreach ($heroes as $hero) {
            $options[$hero->ID] = $hero->post_title;
        }
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $hero_id = $settings['hero_post'];

        if ($hero_id) {
            // Backup the global $post variable
            global $post;
            $backup_post = $post;

            // Set up the global post object so single-hero.php works properly
            $post = get_post($hero_id);
            setup_postdata($post);

            // Load the existing template for the Hero post (only if it exists)
            $template = locate_template('single-hero.php');
            if ($template) {
                include($template);
            } else {
                echo '<p style="color: red;">Error: single-hero.php not found.</p>';
            }

            // Reset post data to avoid conflicts
            wp_reset_postdata();
            $post = $backup_post;
        }
    }
}

// Register the widget
function register_hero_widget($widgets_manager) {
    require_once plugin_dir_path(__FILE__) . 'hero-elementor-widget.php'; // Ensure file exists
    $widgets_manager->register(new Hero_Elementor_Widget());
}
add_action('elementor/widgets/register', 'register_hero_widget');