jQuery(document).ready(function ($) {
    console.log("🔧 ACF field disabling script loaded");
    $('.disabled-acf-field').each(function (index) {
        let $field = $(this);
        console.log(`➡️ Disabling field #${index + 1}:`, $field);

        // Disable input elements
        $field.find('input, textarea, select, button').each(function () {
            console.log("  🚫 Disabling input:", this);
            $(this).prop('disabled', true);
        });

        // Remove image/file/gallery edit buttons
        $field.find('.acf-button, .acf-actions a').each(function () {
            console.log("  ❌ Removing ACF button or action:", this);
            $(this).remove();
        });

        // Disable image/file/gallery uploader interactions
        $field.find('.acf-image-uploader, .acf-file-uploader, .acf-gallery').each(function () {
            console.log("  🧊 Disabling pointer events for uploader:", this);
            $(this).css('pointer-events', 'none');
        });
    });
});
