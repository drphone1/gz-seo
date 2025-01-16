// اتصال به گوگل
document.getElementById("authButton").addEventListener("click", function() {
    let domain = gzseo_script_vars.domain;
    let url = "https://api.gzseo.in/callback2?siteuri=" + domain + "/wp-json/gzseo/v1/importtoken&domain=" + domain;
    var authWindow = window.open(url, "popupWindow", "width=600,height=400,scrollbars=yes");
    var checkClose = setInterval(function() {
        if (authWindow.closed) {
            //location.reload();
        }
    }, 500);
});


document.addEventListener('DOMContentLoaded', function() {
    var advancedSettingsCheckbox = document.getElementById('advancedSettingsCheckbox');
    var advancedSettingsFields = document.getElementById('advancedSettingsFields');
    var saveSettingsButton = document.getElementById('saveSettingsButton');

// نمایش یا مخفی کردن فیلدهای تنظیمات حرفه‌ای
    if (advancedSettingsCheckbox) {
        advancedSettingsCheckbox.addEventListener('change', function() {
            if (this.checked) {
                advancedSettingsFields.style.display = 'block';
            } else {
                advancedSettingsFields.style.display = 'none';
            }
        });
    }

// ارسال داده‌ها با AJAX هنگام کلیک روی دکمه ذخیره تنظیمات
    if (saveSettingsButton) {
        saveSettingsButton.addEventListener('click', function(e) {
            e.preventDefault();

            var siteAddress = document.getElementById('siteAddress').value;
            var domainType = document.getElementById('domainType').value;

// اعتبارسنجی ساده (می‌توانید بهبود دهید)
            if (siteAddress === '') {
                alert('لطفاً آدرس سایت را وارد کنید.');
                return;
            }

// آماده‌سازی داده‌ها برای ارسال با AJAX
            var data = {
                action: 'gzseo_save_advanced_settings',
                security: gzseo_script_vars.nonce, // nonce برای امنیت
                siteAddress: siteAddress,
                domainType: domainType
            };

// ارسال درخواست AJAX
            jQuery.post(gzseo_script_vars.ajaxurl, data, function(response) {
                if (response.success) {
                    alert('تنظیمات با موفقیت ذخیره شد.');
                } else {
                    alert('خطا در ذخیره تنظیمات: ' + response.data.message);
                }
            });
        });
    }
});