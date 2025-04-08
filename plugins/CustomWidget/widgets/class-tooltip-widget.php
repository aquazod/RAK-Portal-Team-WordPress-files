<?php if (!defined('ABSPATH')) exit;

class Tooltip_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'tooltip_widget';
    }

    public function get_title() {
        return __('Dynamic Tooltip', 'text-domain');
    }

    public function get_icon() {
        return 'eicon-info-circle';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            ['label' => __('Select Tooltip', 'text-domain')]
        );

        $this->add_control(
            'tooltip_post',
            [
                'label' => __('Tooltip', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_tooltips(),
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    private function get_tooltips() {
        $tooltips = get_posts([
            'post_type' => 'dynTooltip',
            'posts_per_page' => -1
        ]);

        $options = [];
        foreach ($tooltips as $tip) {
            $options[$tip->ID] = $tip->post_title;
        }
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $tooltip_id = $settings['tooltip_post'];

        if ($tooltip_id) {
            global $post;
            $backup_post = $post;

            $post = get_post($tooltip_id);
            setup_postdata($post);

            $template = locate_template('single-dynTooltip.php');
            if ($template) {
                include($template);
            } else {
                echo '<p style="color: red;">Tooltip template not found.</p>';
            }

            wp_reset_postdata();
            $post = $backup_post;
        }
    }
}
