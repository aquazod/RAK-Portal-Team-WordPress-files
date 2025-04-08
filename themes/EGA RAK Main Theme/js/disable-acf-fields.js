jQuery(document).ready(function ($) {
    $('.disabled-acf-field').each(function (index) {
        let $field = $(this);
        // Disable input elements
        $field.find('input, textarea, select, button').each(function () {
            $(this).prop('disabled', true);
        });

        // Remove image/file/gallery edit buttons
        $field.find('.acf-button, .acf-actions a').each(function () {
            $(this).remove();
        });

        // Disable image/file/gallery uploader interactions
        $field.find('.acf-image-uploader, .acf-file-uploader, .acf-gallery').each(function () {
            $(this).css('pointer-events', 'none');
        });
    });
});
