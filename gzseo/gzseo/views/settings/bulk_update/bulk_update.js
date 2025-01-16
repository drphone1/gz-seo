jQuery(document).ready(function($){

    /*
    * Show List
    * Preview the list of post which need to be updated
    */
    $(".posts-preivew").click(function() {

        // Collect data
        var post_ids = $(this).attr('data-post-ids');
        var nonce = $('#gzseo_bulk_actions_nonce').val();

        // Return if no posts
        if(post_ids.length == 0) {
            alert('خطا! هیچ نوشته ای یافت نشد.');
            return;
        }

        // Show loading
        $(this).closest('.view-list-btn-wrapper').find('.spinner_loading').addClass('loading_show');
        $('.run-btn').prop('disabled',true);

        // Clear table
        $('.response > tbody').empty();

        // Send Req
        $.ajax({
            url: gzseo_ajax_object.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'gzseo_run_bulk_update_preview',
                post_ids: post_ids,
                nonce: nonce
            },
            success: function(response) {

                $('.warp-response').fadeIn();

                console.log(response);

                $.each(response.data, function (index, post) {
                    var new_table_row = '<tr><td>'+post.id+'</td><td><a href="'+post.link+'" target="_blank">'+post.title+'</a></td></tr>';
                    $(".response > tbody").append(new_table_row);
                });

            },
            error: function(response) {
                alert('خطایی در هنگام ارتباط با سرور رخ داد');
            },
            complete: function () {
                $('.spinner_loading').removeClass('loading_show');
                $('.run-btn').prop('disabled',false);
            }
        });

    });

    /*
    * Send for final Update
    */
   function sendForFinalUpdate(data,min,updateType) {

       let nonce = document.getElementById("gzseo_bulk_actions_nonce").value;

        var count = data.length;
  
        if(min > count) {

            // Hide loading
            $('.spinner_loading').removeClass('loading_show');
            $('.run-btn').prop('disabled',false);

        } else {

        $.ajax({
            url: gzseo_ajax_object.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'gzseo_perform_update',
                update_type: updateType,
                post_id:data[min - 1],
                nonce: nonce,
            },
            success: function(response){

                if (response.success) {
                    // Update response table
                    setTimeout(function() {
                        var new_table_row = '<tr><td>'+response.data.id+'</td><td><a href="'+response.data.link+'" target="_blank">'+response.data.title+'</a></td><td><span class="response_sucsses">انجام شد</span></td></tr>';
                        $(".response > tbody").append(new_table_row);
                    }, 5000);

                    // Call Function again to run next item in loop
                    sendForFinalUpdate(data,min+1,updateType);
            
                } else {
                    alert('خطایی رخ داد: ' + response.data.message);
                }
            },
            error: function(response){

                console.log(response);

                if(response.status == 501) {
                    var new_table_row = '<tr><td>'+response.responseJSON.data.id+'</td><td><a href="'+response.responseJSON.data.link+'" target="_blank">'+response.responseJSON.data.title+'</a></td><td><span class="response_error">فاقد اطلاعات در سرچ کنسول</span></td></tr>';
                    $(".response > tbody").append(new_table_row);
                    sendForFinalUpdate(data,min+1,updateType);
                    // alert('خطایی رخ داد: ' + response.status);
                }
            }
        });
        }

    
   }

    /*
    * Run Update
    */
    $('.run-btn').on('click', function(){
        var updateType = $(this).data('update-type');
        var postType = $(this).data('post-type');
        var updateInterval = $(this).data('update-interval');
        var inputField = $(this).closest('.card-row').find('.last-update');
        var quantity = inputField.val();
        let nonce = document.getElementById("gzseo_bulk_actions_nonce").value;

        if (!quantity || quantity <= 0) {
            alert('لطفاً یک مقدار معتبر وارد کنید');
            return;
        }

        // Handle Loading
        $(this).closest('.view-list-btn-wrapper').find('.spinner_loading').addClass('loading_show');
        $('.run-btn').prop('disabled',true);

        // Clear result table
        //$('.warp-response').fadeOut();
        $('.response > tbody').empty();

        $.ajax({
            url: gzseo_ajax_object.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'gzseo_run_bulk_update',
                update_type: updateType,
                quantity: quantity,
                post_type: postType,
                update_interval: updateInterval,
                nonce: nonce
            },
       
            success: function(response){

                // Show results table
                $('.warp-response').fadeIn();

                if (response.success) {

                    // Loop Data
                    sendForFinalUpdate(response.data,1,updateType);

                } else {
                    alert('خطایی رخ داد: ' + response.data.message);
                }
            },
            error: function() {
                alert('خطایی در هنگام ارتباط با سرور رخ داد');
            }
        });
    });

});