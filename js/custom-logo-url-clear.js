jQuery(document).ready(function($) {
    $(document).on('click', '.stfq-custom-logo-url-clear-button', function() {
        var defaultVal = $(this).data('default');
        $('#stfq_custom_logo_url').val(defaultVal);
    });
});
