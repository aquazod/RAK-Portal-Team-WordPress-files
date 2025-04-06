<?php
$hero_title = get_the_title();
$hero_subtitle = get_field('sub_title');
$image_or_video = get_field('video_or_image'); // Radio button value
$background_image = get_field('background_image'); // Image field
$background_video = get_field('video'); // Video field
$cta_text = get_field('cta_text');
$cta_link = get_field('cta_link');
$publish_date = get_field('publish_date');
$expiry_date = get_field('expiry_date');

// Check expiry date
$today = date('Ymd'); 
if ($expiry_date && $expiry_date < $today) {
    return; // Don't display expired heroes
}


?>

<section class="hero">
    <div class="hero-bg">
        <?php if ($image_or_video === 'Video' && $background_video): ?>
            <video autoplay muted loop>
                <source src="<?php echo esc_url($background_video); ?>" type="video/mp4">
            </video>
        <?php elseif ($image_or_video === 'Image' && $background_image): ?>
            <img src="<?php echo esc_url($background_image); ?>" alt="Hero Background">
        <?php endif; ?>
    </div>

    <div class="hero-content">
        <h1><?php echo esc_html($hero_title); ?></h1>
        <?php if ($hero_subtitle): ?>
            <h2><?php echo esc_html($hero_subtitle); ?></h2>
        <?php endif; ?>
        <?php if ($cta_text && $cta_link): ?>
            <a href="<?php echo esc_url($cta_link); ?>" class="hero-cta"><?php echo esc_html($cta_text); ?></a>
        <?php endif; ?>
    </div>
</section>
