document.getElementById('add-new-comment').addEventListener('click', function() {
    const container = document.querySelector('.repeater-container');
    const newItem = document.createElement('div');
    newItem.classList.add('repeater-item');
    newItem.innerHTML = `
        <textarea placeholder="متن خود را وارد کنید..." name="gzseo_user_comments[]" class="comment-text"></textarea>
        <button type="button" class="add-replace-text" onclick="addReplaceText(this)">کلمه کلیدی</button>
        <button type="button" class="remove-item">حذف</button>
    `;
    container.appendChild(newItem);
});

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('remove-item')) {
        event.target.parentElement.remove();
    }
});

function addReplaceText(button) {
    const textarea = button.previousElementSibling;
    textarea.value += ' {gzseo_replace} ';
    textarea.focus();
}

document.getElementById('save-comments').addEventListener('click', function() {
    const comments = Array.from(document.querySelectorAll('.comment-text')).map(textarea => textarea.value);
    const nonce = document.getElementById("gzseo_save_comments_nonce").value;
    

    // Ajax request to save comments
    const data = {
        action: 'gzseo_save_comments',
        comments: comments,
        nonce:nonce
    };
    
    jQuery.post(ajaxurl, data, function(response) {
        if (response.success) {
            alert("کامنت با موفقیت اضافه شد");
        } else {
            alert("Failed to add comment.");
        }
    });

});