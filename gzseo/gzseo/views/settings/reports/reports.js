jQuery(document).ready(function($) {

    // Tab Handler
    //$('#tabs-nav li:first-child').addClass('active');
    //$('.tab-content').hide();
    //$('.tab-content:first').show();
    $('#tabs-nav li').click(function(){
        $('#tabs-nav li').removeClass('active');
        $(this).addClass('active');
        $('.tab-content').hide();

        var activeTab = $(this).find('a').attr('href');
        $(activeTab).fadeIn();
        return false;
    });

    // Comments - Toggle all checkboxes
    $(".comments_check_all_toggle").click(function() {
        if (this.checked) {
            $("input[name=comments_chck]").prop("checked", true);
        } else {
            $("input[name=comments_chck]").prop("checked", false);
        }
    });

    // Comments - Single Approve
    $('.item_approve').on('click', function(){
        var commentIds = $(this).val();
        var elementId = $(this).attr('id');


        var nonce = $("#gzseo_reports_bulk_actions").val();
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'gzseo_reports_bulk_actions',
                actionType: 'approve',
                commentIds: commentIds,
                nonce: nonce,
            },

            success: function(response){
                $("#"+elementId).parent().parent().removeClass('unapproved');
                $("#"+elementId).remove();
                $(".reports_wrapper").html('<div class="report_msg report_success"><span>'+response.data.msg+'</span></div>');
            },
            error: function () {
                $(".reports_wrapper").html('<div class="report_msg report_error"><span>'+response.data.msg+'</span></div>');
                //alert('خطایی در هنگام ارتباط با سرور رخ داد');
            }
        });
    });

    // Comments - Bulk Approve
    $('.comments_approve_all').on('click', function(){

        // Collect comment ids
        var commentIds = $("table.comments input:checkbox:checked").map(function(){
            return $(this).val();
        }).get();

        // Nonce
        var nonce = $("#gzseo_reports_bulk_actions").val();

        // Send req
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'gzseo_reports_bulk_actions',
                actionType: 'approve',
                commentIds: commentIds,
                nonce: nonce,
            },

            success: function(response){
                if (response.success) {

                    $(".reports_wrapper").html('<div class="report_msg report_success"><span>'+response.data.msg+'</span></div>');

                } else {
                    $(".reports_wrapper").html('<div class="report_msg report_error"><span>'+response.data.msg+'</span></div>');
                }
            },
            error: function() {
                alert('خطایی در هنگام ارتباط با سرور رخ داد');
            }
        });

    });

    // Tables - Toggle all checkboxes
    $(".tables_check_all_toggle").click(function() {
        if (this.checked) {
            $("input[name=table_chck]").prop("checked", true);
        } else {
            $("input[name=table_chck]").prop("checked", false);
        }
    });

    // Tables - Data table single delete
    $('button.delete_tables').on('click', function(){
       var postID = $(this).val();

        var nonce = $("#gzseo_reports_bulk_actions").val();

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'gzseo_reports_bulk_actions',
                actionType: 'delete_table',
                postID: postID,
                nonce: nonce,
            },

            success: function(response){
                if (response.success) {
                    $(".reports_wrapper").html('<div class="report_msg report_success"><span>'+response.data.msg+'</span></div>');
                    $("#"+postID).parent().parent().remove();
                } else {
                    $(".reports_wrapper").html('<div class="report_msg report_error"><span>'+response.data.msg+'</span></div>');
                }
            },
            error: function() {
                alert('خطایی در هنگام ارتباط با سرور رخ داد');
            }
        });

    });

    // Tables - Data table bulk delete
    $('button.tables_bulk_delete').on('click',function (){

        // Collect comment ids
        var tablePostIds = $("table.tables input:checkbox:checked").map(function(){
            return $(this).val();
        }).get();

        // Nonce
        var nonce = $("#gzseo_reports_bulk_actions").val();

        // Send req
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'gzseo_reports_bulk_actions',
                actionType: 'table_bulk_delete',
                tablePostIds: tablePostIds,
                nonce: nonce,
            },

            success: function(response){
                if (response.success) {
                    $(".reports_wrapper").html('<div class="report_msg report_success"><span>'+response.data.msg+'</span></div>');

                    $.each(tablePostIds,function(key,value){
                        $("#"+value).parent().parent().remove();
                    });

                } else {
                    $(".reports_wrapper").html('<div class="report_msg report_error"><span>'+response.data.msg+'</span></div>');
                }
            },
            error: function() {
                alert('خطایی در هنگام ارتباط با سرور رخ داد');
            }
        });

    });

    // Tables - Show the content
    $('button.show_table_content').on('click',function (){
        var btnID = $(this).attr('id');
        //$('tr.'+btnID).find().toggleClass('table-preview-hide');
        var tmp = $('tr.table-preview-row.'+btnID).toggleClass('table-preview-hide');
        console.log(tmp);



        //var currentBtnRow = $("#"+btnID).parent().parent();
        //var preview = "<tr class='table_content_preview'><td colspan='6'>Hellloooo</td></tr>>";
        //$(currentBtnRow).after(preview);
        //$("#"+value).parent().parent()
    });


});