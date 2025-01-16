// ساخت کامنت
jQuery(document).ready(function($) {

    // Create
    $("#gzseo_create-table").click(function(e) {
        e.preventDefault(); // جلوگیری از ارسال فرم به صورت پیش فرض
        let nonce = document.getElementById("gzseo_tables_action_nonce").value;

        buttonelement = $(this);
        $.ajax({
            url: gzseo_ajax_data.ajaxurl, // URL دسترسی به AJAX
            type: "POST",
            data: {
                action: "gzseo_ajax_create_table", // نام تابع PHP
                postid: gzseo_ajax_data.postid,
                ai_model: 'standard',
                nonce : nonce
            },
            success: function(response) {
                $(".gzseo_table_created").html(response.data.orginaldata);
                $("#gzseo_rendered_table").html(response.data.rendereddata);
                $('#gzseo_subbmit_table').removeAttr('hidden');
                $('#gzseo_copy_table').removeAttr('hidden');
                $('div.action').removeAttr('hidden');
            },                               

            error: function(xhr) {
                console.log(xhr);
                $("#gzseo_rendered_table").html("<p>خطا: " + xhr.status + " - " + JSON.stringify(xhr.statusText) + "</p>");
            },
            beforeSend: function(msg){
                document.querySelector("#gzseo_create-table").innerHTML ="در حال ساختن";
                buttonelement.attr("disabled","disabled");
            },
            complete: function (data) {
                document.querySelector("#gzseo_create-table").innerHTML ="ساخت مجدد جدول";
                buttonelement.removeAttr("disabled");
            }
        });
    });

    // Submit
    $("#gzseo_subbmit_table").click(function(e) {
        e.preventDefault(); // جلوگیری از ارسال فرم به صورت پیش فرض
        let nonce = document.getElementById("gzseo_tables_action_nonce").value;
        let orginaldata = document.getElementById("gzseo_table_created").value;

        buttonelement = $(this);
        $.ajax({
            url: gzseo_ajax_data.ajaxurl, // URL دسترسی به AJAX
            type: "POST",
            data: {
                action: "gzseo_ajax_submit_table", // نام تابع PHP
                postid: gzseo_ajax_data.postid,
                orginaldata: orginaldata,
                position_table: $('#table_positions_select').find(":selected").val(),
                nonce : nonce
            },
            success: function(response) {
                alert('جدول با موفقیت ثبت شد و با توجه به تنظیمات شما در صفحه نمایش داده خواهد شد.');
            },                               

            error: function(xhr) {
                console.log(xhr);
                $("#gzseo_rendered_table").html("<p>خطا: " + xhr.status + " - " + JSON.stringify(xhr.statusText) + "</p>");
            },
            beforeSend: function(msg){
                document.querySelector("#gzseo_create-table").innerHTML ="در حال ساختن";
                buttonelement.attr("disabled","disabled");
            },
            complete: function (data) {
                document.querySelector("#gzseo_create-table").innerHTML ="ساخت مجدد جدول";
                buttonelement.removeAttr("disabled");
            }
        });
    });

    // Copy
    $("#gzseo_copy_table").click(function(e) {
        e.preventDefault(); // جلوگیری از ارسال فرم به صورت پیش فرض
        let tabledata = document.getElementById("gzseo_rendered_table").innerHTML;
        
        // ایجاد یک عنصر textarea پنهان
        let textarea = document.createElement('textarea');
        textarea.value = tabledata;
        document.body.appendChild(textarea);
        
        // انتخاب متن
        textarea.select();
        document.execCommand('copy'); // کپی کردن متن به کلیپ بورد
        
        // حذف عنصر textarea
        document.body.removeChild(textarea);

        // نمایش پیغام موفقیت
        alert('کپی با موفقیت انجام شد!');

        buttonelement = $(this);

    });


    // Delete
    $("#gzseo_delete_table").click(function(e) {
        e.preventDefault(); 

        let nonce = document.getElementById("gzseo_tables_action_nonce").value;

        $.ajax({
            url: gzseo_ajax_data.ajaxurl,
            type: "POST",
            data: {
                action: "gzseo_table_delete",
                postid: gzseo_ajax_data.postid,
                nonce : nonce
            },
            success: function(response) {
                console.log(response);
                alert('جدول با موفقیت حذف شد');
            }
        });
    });

});