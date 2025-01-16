jQuery(document).ready(function($) {
    
    $(".refresh-dashboard").click(function() {


        $.ajax({
            url: gzseo_ajax_object.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'gzseo_update_dashboard_content',
                nonce: $('#gzseo_nonce').val(),
            },
            complete: function(response){
                location.reload();
            }
        });

    });

});