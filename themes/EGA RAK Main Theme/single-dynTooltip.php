<?php

$tooltip_content = get_field('tooltip_content'); // Retrieve the tooltip content
$trigger_type = get_field('trigger_type'); // Retrieve the trigger type
$trigger_text = get_field('trigger_text'); // Retrieve the trigger text, if any

if ($tooltip_content):
    if ($trigger_type == 'icon'):
        // Display an information icon as the trigger
        ?>
        <span class="tooltip-trigger">
            <i class="info-icon">i</i>
            <span class="tooltip-content"><?php echo esc_html($tooltip_content); ?></span>
        </span>
        <?php
    elseif ($trigger_type == 'text' && $trigger_text):
        // Display custom text as the trigger
        ?>
        <span class="tooltip-trigger">
            <?php echo esc_html($trigger_text); ?>
            <span class="tooltip-content"><?php echo esc_html($tooltip_content); ?></span>
        </span>
        <?php
    endif;
endif;
 ?>
