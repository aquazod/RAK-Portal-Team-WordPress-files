<?php get_header(); ?>

<main class="infographic-container" style="max-width: 700px;
    margin: 10px auto;
    text-align: center;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    transition: 0.3s;
    padding: 10px 16px;">
    <?php while (have_posts()):
        the_post(); ?>


        <?php
        $file = get_field('infographic_file');
        $link = get_field('infographic_link');
        $alt_text = get_field('alt_text');
        $description = get_field('description');
        $infographic_source = $file ? $file : $link;

        // Check file first, if not available, use the link
        $infographic_source = $file ? $file : $link;

        if ($infographic_source) {
            $file_extension = pathinfo(parse_url($infographic_source, PHP_URL_PATH), PATHINFO_EXTENSION);
            $file_extension = strtolower($file_extension);

            if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {
                // Display image
                echo '<img src="' . esc_url($infographic_source) . '" alt="' . esc_attr($alt_text) . '" style="max-width: 100%; height: auto;">';
            } elseif ($file_extension === 'pdf') {
                // Display PDF
                echo '<iframe src="' . esc_url($infographic_source) . '" width="100%" height="500px"></iframe>';
            } else {
                // Display as a download link for other file types
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
        ?>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>