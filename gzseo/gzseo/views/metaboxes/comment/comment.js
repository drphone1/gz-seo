document.getElementById("gzseo_add_comment_button").addEventListener("click", function() {

    var comment = document.getElementsByClassName("gzseo_default_comment_select_radio");
    var comment = [].slice.call(comment);
    comment.forEach((element) => {
        if(element.checked) selcted = (element.value);
    });
    var keyword = document.getElementById("gzseo_default_keyword_select").value;
    var post_id = document.getElementById("selected_result_p").value;
    var nonce = document.getElementById("gzseo_add_comment_nonce").value;

    if (comment) {
        var data = {
            action: "gzseo_add_comment",
            post_id: post_id,
            thispage: gzseo_script_vars.thispage,
            comment: selcted,
            keyword: keyword,
            gzseo_add_comment_nonce: nonce
        };
        jQuery.post(ajaxurl, data, function(response) {
            if (response.success) {
                alert("کامنت با موفقیت اضافه شد");
            } else {
                alert("Failed to add comment.");
            }
        });
    }
    });
    function gzseo_liveSearch() {
        var query = document.getElementById("gzseo_search_box").value;
        var nonce =  document.getElementById("gzseo_ajax_search_nonce").value;
        var resultsDiv = document.getElementById("gzseo_search_results");

        if (query.length > 0) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    resultsDiv.innerHTML = this.responseText;
                }
            };
            xhr.open("GET", gzseo_script_vars.domain+"?action=gzseo_search&query=" + query + "&nonce=" + nonce, true);
            xhr.send();
        } else {
            resultsDiv.innerHTML = "";
            document.getElementById("selected_result").innerText = ""; // پاک کردن نتیجه در صورت خالی بودن باکس
        }
    }

    function itemClicked(item,val) {
        // نمایش نتیجه انتخاب شده
        console.log(item);
        document.getElementById("selected_result").innerText =  item;
        document.getElementById("selected_result_p").value =  val;
        // پاک کردن نتایج بعد از انتخاب
        document.getElementById("gzseo_search_results").innerHTML = "";
        // پاک کردن باکس جستجو (در صورت نیاز)
        document.getElementById("gzseo_search_box").value = "";
    }