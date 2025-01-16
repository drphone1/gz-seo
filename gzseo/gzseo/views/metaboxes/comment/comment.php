<?php
if ( ! defined( 'ABSPATH' ) ) exit; // در صورت دسترسی مستقیم از آن خارج شوید 
wp_enqueue_style( 'gzseo_comment_style', GZSEO_VIEWS_URI.'metaboxes/comment/comment.css' , '', gzseo::get_plugin_version(), 'all' );
wp_register_script( 'gzseo_comment_script', GZSEO_VIEWS_URI.'metaboxes/comment/comment.js', '', gzseo::get_plugin_version(), true );

wp_localize_script( 'gzseo_comment_script', 'gzseo_script_vars', array(
    'domain' => esc_url(admin_url("admin-ajax.php")),
    'thispage' => get_the_ID()
));

wp_enqueue_script( 'gzseo_comment_script');

$gzseo_comments = new gzseo_comment();
$gzseo_comments = $gzseo_comments->get_comments();

global $post;
$gzseo_key = 'gzseo_page_keywords_' . $post->ID ;

$gzseo_GSC_keywords = get_transient( $gzseo_key );


if(!$gzseo_GSC_keywords){

    $gzseo_gsc = new gzseo_gsc();
    $gzseo_GSC_keywords = $gzseo_gsc->get_pages_keywords($post);
    set_transient( $gzseo_key, $gzseo_GSC_keywords, 43200);


}
?>

<div style="display: flex;flex-direction: row;padding:0px 10px;border: solid 1px #dbdbdb;box-shadow: 0px 4px 12px 0px rgba(0, 0, 0, 0.03);margin-bottom: 15px;align-items:center;">

<div>
    <label for="gzseo_search_box_select">صفحه مقصد: (برای کامنت گذاشتن در این صفحه خالی بگذارید.)</label> <br>
</div>
<?php wp_nonce_field('gzseo_ajax_search_nonce','gzseo_ajax_search_nonce'); ?>
<div style="display:flex; flex-direction:row; padding:6px;">

    <input style="height: 40px;width: 230px;border-radius: 0px;border: solid 1px #cdcccc;" type="text" id="gzseo_search_box" placeholder="جستجو..." onkeyup="gzseo_liveSearch()" autocomplete="off" /><br>
    <div id="gzseo_search_results" style="border: 1px solid #ccc; max-width: 300px; background-color: white; position: absolute;"></div>
    <input type="hidden" id="selected_result_p" name="selected_item" value="<?php echo esc_html(get_the_ID()); ?>">
    <p id="selected_result" style="margin-right: 5px;margin-left: 10px;color: #23aa5a;font-weight: bold;" ></p>
</div>


<div>

    <label for="gzseo_default_keyword_select">کلمه کلیدی را انتخاب کنید </label>
</div>
<div style="padding:6px;">

    <select id="gzseo_default_keyword_select" style="height: 40px;width: 230px;border-radius: 0px;border: solid 1px #cdcccc;">
        <option value="">کلمه کلیدی</option>
        <?php foreach ($gzseo_GSC_keywords as $comment) : ?>
            <option value="<?php echo esc_attr($comment['keys'][0]); ?>"><?php echo esc_attr($comment['keys'][0]);  ?></option>
        <?php endforeach; ?>
    </select>
</div>
        </div>
<div style="border-bottom: solid 1px #9f9f9f;padding-bottom: 10px;margin-top: 25px;"><label style="font-weight: 600;" for="gzseo_default_comment_select">لطفا کامنت را انتخاب کنید </label> <br></div>
<div style="padding:15px;">

<?php $gzseo_default_comment_number=0;  ?>
    
<?php foreach ($gzseo_comments as $comment) : ?>
<div class="comment-list">	
<label class="container" >
<?php $gzseo_tempdata = str_replace("{gzseo_replace}"," -کلمه کلیدی- ",$comment);  echo esc_html($gzseo_tempdata); ?>
<input type="radio" id="<?php $gzseo_tempdata = "gzseo_default_comment_number".$gzseo_default_comment_number; echo esc_html($gzseo_tempdata);  ?>" name="gzseo_default_comment_select" class="gzseo_default_comment_select_radio" value="<?php echo esc_attr($comment); ?>">
<span class="checkmark"></span>
</label>
</div>    
<?php endforeach; ?>
    
        </div>
    <div style="display:flex;justify-content: center;">
        <?php wp_nonce_field('gzseo_add_comment_nonce','gzseo_add_comment_nonce'); ?>
<button type="button" id="gzseo_add_comment_button" style="cursor: pointer;background-color: white;color: #23aa5a;border: 2px solid #23aa5a;height: 45px;width: 100%;font-weight: bold;">اضافه کردن نظر جدید</button>

</div>
