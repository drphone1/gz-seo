// ساخت کامنت
jQuery(document).ready(function($) {

    $("#create_comment_by_ai").click(function(e) {
        e.preventDefault(); // جلوگیری از ارسال فرم به صورت پیش فرض
        buttonelement = $(this);
        // جمع‌آوری کامنت‌های تیک خورده
        var checkboxes = document.querySelectorAll("input[type='checkbox'][id^='gzseo_ai_keywords']:checked");
        let gzseo_ajax_create_comment_nonce = document.getElementById("gzseo_ajax_create_comment_nonce").value;
        var keywords = [];
        checkboxes.forEach(function(checkbox) {
            keywords.push(checkbox.value);
        });
        //comments = JSON.stringify(comments);
        if (keywords.length === 0) {
            alert("هیچ کامنتی انتخاب نشده است.");
            return;
        }
        //var selectedModel = $("input[name="ai_model"]:checked").val();
        // بررسی اینکه کدام مدل انتخاب شده است
        var selectedModel = 'standard';
       /* if ($("#eco").is(":checked")) {
            selectedModel = "eco";
        } else if ($("#standard").is(":checked")) {
            selectedModel = "standard";
        } else {
            // اگر هیچ مدلی انتخاب نشده باشد، می‌توانید پیام خطا نمایش دهید یا کار خاصی انجام دهید
            alert("لطفاً یک مدل هوش مصنوعی انتخاب کنید.");
            return; // خروج از تابع برای جلوگیری از انجام عمل AJAX
        }*/


        Tone_of_Content=  document.getElementById("Tone_of_Content").value;
        Word_Count = document.getElementById("Word_Count").value;
        
        $.ajax({
            url: gzseo_ajax_data.ajaxurl, // URL دسترسی به AJAX
            type: "POST",
            data: {
                action: "gzseo_ajax_create_comment", // نام تابع PHP
                postid: gzseo_ajax_data.postid,
                keyword: keywords,
                ai_model: selectedModel,
                gzseo_ajax_create_comment_nonce : gzseo_ajax_create_comment_nonce ,
                Tone_of_Content,
                Word_Count
            },
            success: function(response) {
                $(".comments-list").html(response);               
            },
            error: function(xhr) {
                //console.log(xhr);
                alert(xhr.responseJSON.data);
                $(".comments-list").html("<p>خطا: " + xhr.status + " - " + JSON.stringify(xhr.responseJSON.data) + "</p>");
                //$("#registerResult").html("<p>خطا: " + xhr.status + " - " + JSON.stringify(xhr.statusText) + "</p>");
            },
            beforeSend: function(msg){
                document.querySelector("#create_comment_by_ai").innerHTML ="در حال ساختن";
                buttonelement.attr("disabled","disabled");
            },
            complete: function (data) {
                document.querySelector("#create_comment_by_ai").innerHTML ="شروع به ساخت کامنت";
                buttonelement.removeAttr("disabled");
            }
        });
    });
});

function editComment(key) {
    var editContainer = document.getElementById("edit_container_" + key);
    editContainer.style.display = "block";

    var commentTextSpan = document.getElementById("comment_text_" + key);
    var currentComment = commentTextSpan.textContent.trim();

    var editInput = document.getElementById("edit_input_" + key);
    editInput.value = currentComment;
}

function confirmEdit(key) {
    var editInput = document.getElementById("edit_input_" + key);
    var newComment = editInput.value;

    var commentTextSpan = document.getElementById("comment_text_" + key);
    commentTextSpan.textContent = newComment;

    var checkbox = document.getElementById("gzseo_ai_comment" + key);
    checkbox.value = newComment;

    var editContainer = document.getElementById("edit_container_" + key);
    editContainer.style.display = "none";
}

function cancelEdit(key) {
    var editContainer = document.getElementById("edit_container_" + key);
    editContainer.style.display = "none";
}

function submitComments() {
    // جمع‌آوری کامنت‌های تیک خورده
    var checkboxes = document.querySelectorAll("input[type='checkbox'][id^='gzseo_ai_comment']:checked");
    var comments = [];
    checkboxes.forEach(function(checkbox) {
        comments.push(checkbox.value);
    });
    //comments = JSON.stringify(comments);
    if (comments.length === 0) {
        alert("هیچ کامنتی انتخاب نشده است.");
        return;
    }

    // ارسال کامنت‌ها از طریق AJAX
    var data = {
        action: "gzseo_submit_selected_comments",
        comments: comments,
        post_id: gzseo_ajax_data.postid, // شناسه‌ی پست جاری
        gzseo_ajax_create_comment_nonce : document.getElementById("gzseo_ajax_create_comment_nonce").value

    };

    // اطمینان از اینکه ajaxurl در دسترس است

    // ارسال درخواست AJAX با استفاده از fetch API
    fetch(gzseo_ajax_data.ajaxurl, {
        method: "POST",
        credentials: "same-origin",
        headers:{
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams(data)
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(response) {
        if (response.success) {
            alert("کامنت‌ها با موفقیت ارسال شدند.");
            // در صورت نیاز، می‌توانید بخش کامنت‌ها را به‌روزرسانی کنید
        } else {
            alert("خطا در ارسال کامنت‌ها: " + response.data);
        }
    })
    .catch(function(error) {
        console.error("Error:", error);
    });
}
