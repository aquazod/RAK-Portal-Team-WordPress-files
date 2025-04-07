<?php
if (!defined('ABSPATH')) {
    exit;
}

class Infographic_Elementor_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'infographic_widget';
    }

    public function get_title()
    {
        return __('Infographic Section', 'text-domain');
    }

    public function get_icon()
    {
        return 'eicon-post';
    }

    public function get_categories()
    {
        return ['custom-category'];
    }

    public function get_keywords()
    {
        return ['infographic', 'banner', 'section'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Settings', 'text-domain'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'infographic_post',
            [
                'label' => __('Select Infographic', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_infographic_posts(),
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    private function get_infographic_posts()
    {
        $infographics = get_posts([
            'post_type' => 'infographic',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ]);

        $options = [];
        foreach ($infographics as $infographic) {
            $options[$infographic->ID] = $infographic->post_title;
        }

        return $options;
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $infographic_id = $settings['infographic_post'];

        if (!$infographic_id) {
            echo '<p style="color: red;">No infographic selected.</p>';
            return;
        }

        // Get ACF fields
        $file = get_field('infographic_file', $infographic_id);
        $link = get_field('infographic_link', $infographic_id);
        $alt_text = get_field('alt_text', $infographic_id);
        $description = get_field('description', $infographic_id);
        $infographic_source = $file ? $file : $link;

        echo '<main class="infographic-container" style="max-width: 700px;
            margin: 10px auto;
            text-align: center;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            padding: 10px 16px;">';

        if ($infographic_source) {
            $file_extension = pathinfo(parse_url($infographic_source, PHP_URL_PATH), PATHINFO_EXTENSION);
            $file_extension = strtolower($file_extension);

            if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
                echo '<img src="' . esc_url($infographic_source) . '" alt="' . esc_attr($alt_text) . '" style="max-width: 100%; height: auto;">';
            } elseif ($file_extension === 'pdf') {
                echo '<iframe src="' . esc_url($infographic_source) . '" width="100%" height="500px"></iframe>';
            } else {
                echo '<a href="' . esc_url($infographic_source) . '" target="_blank" class="button">View Infographic</a>';
            }
        } else {
            echo '<p style="color: red;">No infographic available.</p>';
        }

        if ($description) {
            echo '<p>' . esc_html($description) . '</p>';
        } else {
            echo '<p style="color: red;">No description found</p>';
        }

        echo '</main>';
    }
}
