<?php
   if (get_post_type() == 'banner'){
        get_header();
    }
    // Correct function names
    $banner_name = get_the_title();
    $banner_construction_method = get_field("construction_method");
    $banner_ready_banner = get_field("ready_banner");
    $banner_text = get_field("text");
    $banner_size = get_field("size");
    $banner_length = $banner_size["length"] ?? "";
    $banner_width = $banner_size["width"] ?? "";
    $banner_media = get_field("banner_media");
    $publish_date = get_the_date();
    $banner_expiry_date = get_field("expiry_date");

    // Construct the banner HTML in a variable
    $banner_content = '<div class="banner-container">';
        
    if ($banner_construction_method === "Ready_Made") {

        $banner_content .= '<img src="' . esc_url($banner_ready_banner) . '" alt="Ready Made Banner">';
        $banner_content .= '</div>';
    } 
	elseif ($banner_construction_method === "Custom_Made") {
        
        if ($banner_media) {
            $banner_content .= '<img src="' . esc_url($banner_media) . '" alt="Custom Banner Media">';
        }
		$banner_content .= '<div class="banner-text">' . esc_html($banner_text) . '</div>';
        $banner_content .= '</div>';
    }

    // Output the content as the post content
    echo apply_filters('the_content', $banner_content);

    
    if (get_post_type() == 'banner'){
    get_footer();} ?>
