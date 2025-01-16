<?php
if ( ! defined( 'ABSPATH' ) ) exit; // در صورت دسترسی مستقیم از آن خارج شوید 
wp_enqueue_style( 'gzseo_ai_table_style', GZSEO_VIEWS_URI.'metaboxes/aitable/aitable.css' , '', gzseo::get_plugin_version(), 'all' );
wp_register_script( 'gzseo_ai_table_script', GZSEO_VIEWS_URI.'metaboxes/aitable/aitable.js', '', gzseo::get_plugin_version(), true );
wp_localize_script( 'gzseo_ai_table_script', 'gzseo_ajax_data', [
    'postid' => get_the_ID(),
    'ajaxurl' => esc_url(admin_url("admin-ajax.php"))
] );

wp_enqueue_script( 'gzseo_ai_table_script');
$gzseo_ai_atatus = $this->status();
global $post;
$gzseo_table=(get_post_meta( $post->ID, 'gzseo_table', true ));

?>

<div class="detail">
    <span>وضعیت اکانت: <span class="status-on">متصل / فعال</span></span>
    <div>
        <span class="credit">اعتبار: <?php $eco =$gzseo_ai_atatus['standard'];  if($eco==null) $eco=0; echo  number_format($eco);	?></span>
    </div>
</div>
<div class="help">
    <span>شما میتوانید با استفاده از این بخش براساس محتوای داخل نوشته خود ساخت جدول با هوش مصنوعی را انجام دهید!</span>
    <button id="gzseo_create-table" class="create-table" type="button">شروع به ساخت جدول</button>
    <?php wp_nonce_field( 'gzseo_tables_action', 'gzseo_tables_action_nonce'); ?>
</div>

<div id="gzseo_rendered_table" class="table">
<?php
if ($gzseo_table) {
    $allowed_tags = array(
        'table' => array(),
        'tr' => array(),
        'td' => array(),
        'th' => array(),
        'thead' => array(),
        'tbody' => array(),
        'tfoot' => array(),
    );
    echo wp_kses(gzseo_ai::tabletoarray($gzseo_table), $allowed_tags);
}
?>

</div>
<textarea hidden  class="gzseo_table_created" name="" id="gzseo_table_created" cols="30" rows="10">
<?php if($gzseo_table) echo esc_html($gzseo_table);  ?>
</textarea>

<?php
    $gzseo_table_position = get_post_meta(get_the_ID(),'gzseo_table_position',true);
    if(!$gzseo_table_position) $gzseo_table_position = 'default';
?>



<div class="action" <?php if(!$gzseo_table) echo esc_attr( 'hidden' );  ?> >
    <label for="table_positions_select">موقعیت جدول</label>
    <select name="table_positions" id="table_positions_select">
        <option value="default" <?php echo $gzseo_table_position == 'default' ? 'selected' : ''; ?>>استفاده از تنظیمات پیشفرض</option>
        <option value="before_content" <?php echo $gzseo_table_position == 'before_content' ? 'selected' : ''; ?>>ابتدا محتوا</option>
        <option value="end_content" <?php echo $gzseo_table_position == 'end_content' ? 'selected' : ''; ?>>انتها محتوا</option>
        <option value="after_first_paragraph" <?php echo $gzseo_table_position == 'after_first_paragraph' ? 'selected' : ''; ?>>بعد از اولین پاراگراف</option>
        <option value="after_first_heading" <?php echo $gzseo_table_position == 'after_first_heading' ? 'selected' : ''; ?>>بعد از اولین تیتر</option>
    </select>
<button <?php if(!$gzseo_table) echo esc_attr( 'hidden' );  ?> id="gzseo_copy_table" class="copy-table" type="button"><?php echo esc_html('کپی کد جدول'); ?></button>
<button <?php if(!$gzseo_table) echo esc_attr( 'hidden' );  ?>  id="gzseo_subbmit_table" class="subbmit-table" type="button"><?php echo esc_html('درج جدول'); ?></button>
<button <?php if(!$gzseo_table) echo esc_attr( 'hidden' );  ?>  id="gzseo_delete_table" class="delete-table" post-id="<?php echo esc_attr(get_the_ID()); ?>" type="button"><?php echo esc_html('حذف جدول','gzseo'); ?></button>
</div>