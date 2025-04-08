<?php
$tooltip_content = get_field('tooltip_content');
$trigger_type = get_field('trigger_type');
$trigger_text = get_field('trigger_text');
$trigger_image = get_field('trigger_image'); // New image field

if ($tooltip_content):
    if ($trigger_type == 'icon'):
        ?>
        <span class="tooltip-trigger">
            <i class="info-icon">i</i>
            <span class="tooltip-content"><?php echo esc_html($tooltip_content); ?></span>
        </span>
        <?php
    elseif ($trigger_type == 'text' && $trigger_text):
        ?>
        <span class="tooltip-trigger">
            <?php echo esc_html($trigger_text); ?>
            <span class="tooltip-content"><?php echo esc_html($tooltip_content); ?></span>
        </span>
        <?php
    elseif ($trigger_type == 'image' && $trigger_image):
        ?>
        <span class="tooltip-trigger">
            <img src="<?php echo esc_url($trigger_image); ?>" alt="tooltip image" style="max-width: 24px; cursor: pointer;">
            <span class="tooltip-content"><?php echo esc_html($tooltip_content); ?></span>
        </span>
        <?php
    endif;
endif;
?>
