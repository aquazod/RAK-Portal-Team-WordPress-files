<?php if (!defined('ABSPATH')) exit;
class Banner_Elementor_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'banner_widget';
    }

    public function get_title()
    {
        return __('Banner Section', 'text-domain');
    }

    public function get_icon()
    {
        return 'eicon-post'; // Choose an Elementor icon    
    }

    public function get_categories()
    {
        return ['custom-category'];
    }

    public function get_keywords()
    {
        return ['banner', 'custom', 'section'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Settings', 'text-domain'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'banner_post',
            [
                'label' => __('Select Banner', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_banner_posts(),
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    private function get_banner_posts()
    {
        $banners = get_posts([
            'post_type' => 'banner',
            'posts_per_page' => -1
        ]);

        $options = [];
        foreach ($banners as $banner) {
            $options[$banner->ID] = $banner->post_title;
        }
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $banner_id = $settings['banner_post'];

        if ($banner_id) {
            // Temporarily set up the global post object so single-flag.php works properly
            global $post;
            $backup_post = $post;
            $post = get_post($banner_id);
            setup_postdata($post);

            // Load the existing template for the Flag post
            include(locate_template('single-banner.php'));

            // Reset post data to avoid conflicts
            wp_reset_postdata();
            $post = $backup_post;
        }
    }
}