<?php if (!defined('ABSPATH')) exit;

class Flag_Elementor_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'flag_widget';
    }

    public function get_title()
    {
        return __('Flag Section', 'text-domain');
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
        return ['flag', 'custom', 'section'];
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
            'flag_post',
            [
                'label' => __('Select Flag', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_flag_posts(),
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    private function get_flag_posts()
    {
        $flags = get_posts([
            'post_type' => 'flag',
            'posts_per_page' => -1
        ]);

        $options = [];
        foreach ($flags as $flag) {
            $options[$flag->ID] = $flag->post_title;
        }
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $flag_id = $settings['flag_post'];

        if ($flag_id) {
            // Temporarily set up the global post object so single-flag.php works properly
            global $post;
            $backup_post = $post;
            $post = get_post($flag_id);
            setup_postdata($post);

            // Load the existing template for the Flag post
            include(locate_template('single-flag.php'));

            // Reset post data to avoid conflicts
            wp_reset_postdata();
            $post = $backup_post;
        }
    }
}