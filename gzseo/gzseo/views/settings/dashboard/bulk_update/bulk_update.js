jQuery(document).ready(function(jQuery) {
    jQuery('#add-repeater-item').click(function() {
        var newItem = jQuery('.repeater-item-hidden:last').clone();
        jQuery(newItem).removeClass("repeater-item-hidden");
        jQuery(newItem).addClass("repeater-item");

        // newItem.classList.remove("repeater-item-hidden"); // Remove mystyle class from DIV
        // newItem.classList.add("repeater-item"); // Add newone class to DIV        newItem.find('input').val('');
        newItem.find('select').prop('selectedIndex', 0);
        newItem.appendTo('#repeater-container');
    });

    jQuery('#repeater-container').on('click', '.remove-repeater-item', function() {
        jQuery(this).parent().remove();
        if (jQuery('.repeater-item').length > 1) {
            jQuery(this).closest('.repeater-item').remove();
        }
    });
});



jQuery(document).ready(function(jQuery) {
    jQuery('#save-comments').on('click', function(e) {
        e.preventDefault();
        // جمع‌آوری داده‌ها از فرم
        var data = {};
        var repeaterItems = [];
        var datavalidate = true;
        var nonce = document.getElementById("gzseo_save_bulks_updates_nonce").value;

        jQuery('#repeater-container .repeater-item').each(function() {
            var post_type = jQuery(this).find('select[name="post_type[]"]').val();
            var update_interval = jQuery(this).find('input[name="update_interval[]"]').val();
            var update_type = jQuery(this).find('select[name="update_type[]"]').val();
            if(!update_interval){
                
                datavalidate = false;


            }
            repeaterItems.push({
                post_type: post_type,
                update_interval: update_interval,
                update_type: update_type
            });
        });
        

        if(! datavalidate){
            alert('لطفا تمام فیلد ها را تکمیل کنید');
            return '00';
        }
        data.action = 'gzseo_save_bulks_updates'; // اکشن برای فراخوانی PHP
        data.nonce = nonce; // نانس
        data.items = repeaterItems;

        // ارسال درخواست AJAX
        jQuery.post(ajaxurl, data, function(response) {
            if (response.success) {
                // در صورت موفقیت
                alert('تنظیمات با موفقیت ذخیره شد.');
                // می‌توانید برای نمایش پیام موفقیت از متدهای بهتری استفاده کنید
            } else {
                // در صورت خطا
                alert('خطایی رخ داده است: ' + response.data);
            }
        });
    });
});