jQuery(document).ready(function($) {
    $('#vx-check-updates').on('click', function() {
        $('#vx-update-message').text('Überprüfen auf Updates...');

        $.ajax({
            url: vxSupport.ajaxurl,
            type: 'POST',
            data: {
                action: 'vx_support_check_updates',
                nonce: vxSupport.nonce
            },
            success: function(response) {
                $('#vx-update-message').html(response);
            },
            error: function() {
                $('#vx-update-message').text('Fehler beim Überprüfen auf Updates.');
            }
        });
    });
});
