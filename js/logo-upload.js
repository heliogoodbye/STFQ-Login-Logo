jQuery(document).ready(function($) {
    $('#upload_stfq_login_logo_button').click(function(e) {
        e.preventDefault();
        var custom_uploader = wp.media({
            title: 'Choose Login Logo',
            button: {
                text: 'Choose Login Logo'
            },
            multiple: false
        });
        custom_uploader.on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#stfq_login_logo_id').val(attachment.id);
            $('#stfq_login_logo_preview').html('<img src="' + attachment.url + '" style="max-width: 300px; height: auto;" />');
        });
        custom_uploader.open();
    });
});
