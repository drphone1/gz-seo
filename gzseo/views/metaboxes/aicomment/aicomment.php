<?php
if (!defined('ABSPATH')) exit;
global $post;
wp_enqueue_style( 'gzseo_ai_comment_style', GZSEO_VIEWS_URI.'metaboxes/aicomment/aicomment.css' , '', gzseo::get_plugin_version(), 'all' );
wp_register_script( 'gzseo_ai_comment_script', GZSEO_VIEWS_URI.'metaboxes/aicomment/aicomment.js', '', gzseo::get_plugin_version(), true );
wp_localize_script( 'gzseo_ai_comment_script', 'gzseo_ajax_data', [
    'postid' => get_the_ID(),
    'ajaxurl' => esc_url(admin_url("admin-ajax.php"))
] );
wp_enqueue_script( 'gzseo_ai_comment_script', GZSEO_VIEWS_URI.'metaboxes/aicomment/aicomment.js', '', gzseo::get_plugin_version(), true );
$gzseo_key = 'gzseo_page_keywords_' . $post->ID ;
$gzseo_GSC_keywords = get_transient( $gzseo_key );
if(!$gzseo_GSC_keywords){
    $gzseo_gsc = new gzseo_gsc();
    $gzseo_GSC_keywords = $gzseo_gsc->get_pages_keywords($post);
    set_transient( $gzseo_key, $gzseo_GSC_keywords, 43200);
}
$gzseo_keywords = [];
foreach ($gzseo_GSC_keywords as $key => $value) {
    $gzseo_keywords[] = $value['keys'][0];
}
$gzseo_ai_atatus = $this->status(); ?>
<div class="detail">
    <span>وضعیت اکانت: <span class="status-on">متصل / فعال</span></span>
    <div>
        <span class="credit">اعتبار: <?php $eco =$gzseo_ai_atatus['standard'];  if($eco==null) $eco=0; echo  number_format($eco);	?></span>
    </div>
</div>
<div class="comments-list">

</div>



    <div class="keywordlist">
    <?php
    foreach ($gzseo_keywords as $key) {
        ?>
        <div class="comment-list">	
            <label class="container">
            <?php echo esc_html( $key ); ?>
            <input type="checkbox" id="gzseo_ai_keywords" name="gzseo_ai_keywords" class="gzseo_default_comment_select_radio" value="<?php echo esc_html( $key ); ?>">
            <span class="checkmark"></span>
            </label>
        </div>
        <?php
    }
    ?>
    </div>

<!-- دکمه ساخت -->
<div class="btn-create">
<div class="select-ai-model">

    <?php 
     $settings = gzseo_ai::get_comment_config();

     $Tone_of_Content_1 = esc_attr($settings['Tone_of_Content']);
     $Word_Count_1 = esc_attr($settings['Word_Count']);
     ?>
    
        <div class="setting-field">
            <label for="Tone_of_Content">لحن محتوا</label>
          
            <?php $tones = ["حرفه‌ای","هیجان‌انگیز","دوستانه","شوخ","طنز","قانع‌کننده","همدل","الهام‌بخش","حمایت‌کننده","اعتماد کردن","بازیگوش","هیجان‌زده","مثبت","منفی","درگیر‌کننده","نگران","فوری","پرشور","آموزنده","خنده‌دار","گاه‌به‌گاه","طعنه‌آمیز","دراماتیک"]; ?>

            <select id="Tone_of_Content" name="Tone_of_Content">
                <option value="" disabled selected>لطفاً یک گزینه انتخاب کنید</option>
                <!-- Default option -->
                <?php foreach ($tones as $tone): ?>
                <option value="<?php echo esc_attr($tone); ?>"
                    <?php selected($Tone_of_Content_1, $tone); ?>>
                    <?php echo esc_html($tone); ?>
                </option>
                <?php endforeach; ?>
            </select>

        </div>
        <div class="setting-field">
            <label for="Word_Count">تعداد کلمات</label>
            <input type="number" id="Word_Count" max="150" value="<?php echo esc_attr($Word_Count_1); ?>">
        </div>
    
</div>

<button class="create-by-ai-btn" id="create_comment_by_ai" type="button">شروع به ساخت کامنت</button>
<?php wp_nonce_field( 'gzseo_ajax_create_comment', 'gzseo_ajax_create_comment_nonce' ); ?>
</div>



