<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly. 
}
wp_enqueue_style( 'gzseo_comment_manager_style', GZSEO_VIEWS_URI.'settings/dashboard/comment-manager/comment-manager.css' , '', gzseo::get_plugin_version(), 'all' );
wp_enqueue_script( 'gzseo_comment_manager_script', GZSEO_VIEWS_URI.'settings/dashboard/comment-manager/comment-manager.js', '', gzseo::get_plugin_version(), true );
$gzseo_usercomments = (gzseo_comment::get_user_comments());
?>

<div id="gzseo-comments-repeater">
<div class="main-setting-title">
<div class="setting-title"><i class="fa-solid fa-comment-dots"></i><h2>افزودن کامنت جدید</h2></div>
<div class="help-btn"><span>مشاهده راهنما</span><i class="fa-solid fa-lightbulb"></i></div>
</div>
    <div class="repeater-container">
        <?php foreach ($gzseo_usercomments as $comment) {?>
        <div class="repeater-item">
            <textarea placeholder="متن خود را وارد کنید..." name="gzseo_user_comments[]" class="comment-text"><?php echo esc_html($comment); ?></textarea>
            <button type="button" class="add-replace-text" onclick="addReplaceText(this)">کلمه کلیدی</button>
            <button type="button" class="remove-item">حذف</button>
        </div>
        <?php } ?>
    </div>
    <button type="button" id="add-new-comment">افزودن کامنت جدید</button>
    <button type="button" id="save-comments">ذخیره کامنت‌ها</button> <!-- دکمه ذخیره -->
    <?php wp_nonce_field( 'gzseo_save_comments_nonce', 'gzseo_save_comments_nonce'); ?>
    <div id="save-message" style="color: green; display: none;">ذخیره‌سازی کامنت‌ها با موفقیت انجام شد.</div> <!-- پیام ذخیره‌سازی -->
</div>