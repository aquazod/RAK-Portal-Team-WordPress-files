<?php
if (is_singular('flag')) {
    get_header();
}

// Correct function names
$flag_name = get_the_title();
$flag_construction_method = get_field("construction_method");
$flag_ready_flag = get_field("ready_flag");
$flag_text = get_field("text");
$flag_size = get_field("size");
$flag_length = $flag_size["length"] ?? "";
$flag_width = $flag_size["width"] ?? "";
$flag_type = get_field("type");
$flag_media = get_field("flag_media");
$publish_date = get_the_date();
$flag_expiry_date = get_post_meta(get_the_ID(), 'expiration_date', true);

// Check if the flag is expired
$current_date = date('Y-m-d');
$is_expired = ($flag_expiry_date && $flag_expiry_date < $current_date);

if (!$is_expired) {
    // Construct the flag HTML in a variable
    $flag_content = '<div class="flag-container">';
    $flag_content .= '<div class="flag flag-' . esc_attr($flag_type) . '" style="width:' . esc_attr($flag_length) . 'px; height:' . esc_attr($flag_width) . 'px">';

    if ($flag_construction_method === "Ready_Made") {
        $flag_content .= '<img src="' . esc_url($flag_ready_flag) . '" alt="Ready Made Flag">';
    } elseif ($flag_construction_method === "Custom_Made") {
        if ($flag_media) {
            $flag_content .= '<img src="' . esc_url($flag_media) . '" alt="Custom Flag Media">';
        }
        $flag_content .= '<div class="flag-text">' . esc_html($flag_text) . '</div>';
    }

    $flag_content .= '</div>'; // Close .flag
    $flag_content .= '</div>'; // Close .flag-container

    // Output the content
    echo apply_filters('the_content', $flag_content);
}

if (is_singular('flag')) {
    get_footer();
}
?>
