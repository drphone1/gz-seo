// Switch tabs
function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].classList.remove("active");
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
    }
    document.getElementById(tabName).classList.add("active");
    evt.currentTarget.classList.add("active");
}

// DOM Ready
jQuery(document).ready(function($) {

    // Login & Get user credits
    $("#loginForm").submit(function(e) {
        e.preventDefault(); // جلوگیری از ارسال فرم به صورت پیش فرض

        var username = $("#username").val(); // گرفتن ایمیل
        var password = $("#password").val(); // گرفتن رمز عبور
        var nonce = $("#_wpnonce").val();
        var buttonelement = $(".bt.login");

        $.ajax({
            url: ajaxurl, // URL دسترسی به AJAX
            type: "POST",
            data: {
                action: "gzseo_ai_login", // نام تابع PHP
                username: username,
                password: password,
                _nonce: nonce
                
            },
            beforeSend: function(msg){
                document.querySelector(".btn-login").innerHTML ="در حال بررسی اطلاعات";
                buttonelement.attr("disabled","disabled");
            },
            complete: function (data) {
                document.querySelector(".btn-login").innerHTML ="ورود";
                buttonelement.removeAttr("disabled");
            },
            success: function(response) {
                //console.log(response.data);
                if (response.success) {
                    if(JSON.stringify(response.data, null, 2) != null){
                        var creditdata=  (response.data["words"]);
                    //console.log(creditdata);
                        $("#loginResult").html("<p>اعتبار شما" +" " +  creditdata + " کلمه است</p>");
                        location.reload();

                    } 
                    if(response.data == "Unauthorized") $("#loginResult").html("<p>" +"اطلاعات ورود اشتباه است" + "</p>");

                } else {
                    $("#loginResult").html("<p>" + response.data + "</p>");
                }
            },
            error: function(xhr) {
                $("#loginResult").html("<p>خطا: " + xhr.status + " - " + xhr.statusText + "</p>");
            }
        });
    });

    // Send Registration
    $("#registerForm").submit(function(e) {
        e.preventDefault(); // جلوگیری از ارسال فرم به صورت پیش فرض

        var username = $("#regusername").val(); // گرفتن ایمیل
        var password = $("#regpassword").val(); // گرفتن رمز عبور
        var regpasswordconfirm = $("#regpasswordconfirm").val(); // گرفتن رمز عبور
        var name = $("#regname").val();
        var nonce = $("#gzseo_ai_register_nonce").val();
        var buttonelement = $(".bt.register");
        if(password != regpasswordconfirm){
            alert("رمز عبور با تاییدیه آن یکسان نیست");
            return false;
        }
        $.ajax({
            url: ajaxurl, // URL دسترسی به AJAX
            type: "POST",
            data: {
                action: "gzseo_ajax_register", // نام تابع PHP
                username: username,
                password: password,
                _nonce: nonce,
                name: name

            },
            beforeSend: function(msg){
                document.querySelector(".btn-register").innerHTML ="در حال بررسی اطلاعات";
                buttonelement.attr("disabled","disabled");
            },
            complete: function (data) {
                document.querySelector(".btn-register").innerHTML ="ثبت نام";
                buttonelement.removeAttr("disabled");
            },
            success: function(response) {
                //console.log(response);
                if (response.success) {
                    if(response.data["message"] == "Successfully created user!"){

                        //console.log(creditdata);
                        $("#registerResult").html("<p>ثبت نام با موفقیت انجام شد</p>");
                        location.reload();


                    }else{
                        $("#registerResult").html("<p>" + JSON.stringify(response.data) + "</p>");
                    }


                }else{
                    $("#registerResult").html("<p>" + JSON.stringify(response.data) + "</p>");

                }
            },
            error: function(xhr) {
                $("#registerResult").html("<p>خطا: " + xhr.status + " - " + JSON.stringify(xhr.statusText) + "</p>");
            }
        });
    });

    // Disconnect Account
    $("#deleteaioption").click(function(e) {
        e.preventDefault(); // جلوگیری از ارسال فرم به صورت پیش فرض
        var nonce = $("#gzseo_remove_acc").val();
        $.ajax({
            url: ajaxurl, // URL دسترسی به AJAX
            type: "POST",
            data: {
                action: "gzseo_ajax_remove_acc", // نام تابع PHP
                _nonce: nonce

            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                $("#registerResult").html("<p>خطا: " + xhr.status + " - " + JSON.stringify(xhr.statusText) + "</p>");
            }
        });
    });

    // Topup Account
    $(".packagebuybutton").click(function(e) {
        e.preventDefault(); // جلوگیری از ارسال فرم به صورت پیش فرض
        buttonelement = $(this);
        packageid = buttonelement.val();
        let nonce = $('#gzseo_ajax_get_payment_url_nonce').val();
        $.ajax({
            url: ajaxurl, // URL دسترسی به AJAX
            type: "POST",
            data: {
                action: "gzseo_ajax_get_payment_url", // نام تابع PHP
                id: packageid,
                nonce: nonce

            },
            success: function(response) {
                window.location.replace(response.data["url"]);
            },
            error: function(xhr) {
                $("#registerResult").html("<p>خطا: " + xhr.status + " - " + JSON.stringify(xhr.statusText) + "</p>");
            },
            beforeSend: function(msg){
                buttonelement.attr("disabled","disabled");
            },
            complete: function (data) {
                buttonelement.removeAttr("disabled");
            }
        });
    });

});