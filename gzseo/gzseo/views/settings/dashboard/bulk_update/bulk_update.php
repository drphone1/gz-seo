<?php
    if (!defined('ABSPATH')) exit;
    wp_enqueue_style( 'gzseo_comment_manager_style', GZSEO_VIEWS_URI.'settings/dashboard/bulk_update/bulk_update.css' , '', gzseo::get_plugin_version(), 'all' );
    wp_enqueue_script( 'gzseo_comment_manager_script', GZSEO_VIEWS_URI.'settings/dashboard/bulk_update/bulk_update.js', '', gzseo::get_plugin_version(), true );

    // Get Options
    $saved_data = get_option('gzseo_bulks_updates', []);

    // Get Subscription status
    $gzseo_check_ai_status = gzseo_ai::status();    
?>


<?php if($gzseo_check_ai_status['status'] != "ok"): ?>
    <div class="alert-subscription">
        <div class="alert-subscription-header"> <i class="fa-solid fa-circle-exclamation"></i> <span><?php echo esc_html('خطا اتصال.','gzseo'); ?></span> </div>
        <div class="alert-subscription-content"><?php echo esc_html('لطفا جهت استفاده از امکانات کامل افزونه لطفا ابتدا اتصال افزونه خود را به هوش منصوعی انجام دهید.','gzseo'); ?></div>
        <div class="alert-subscription-action">
            <a href="<?php echo esc_url(site_url().'/wp-admin/admin.php?page=gzseo&tab=ai'); ?>"><i class="fas fa-plug"></i><span><?php echo esc_html('اتصال','gzseo'); ?></span></a>
        </div>
    </div>
<?php else: ?>
    <div class="main-setting-title">
        <div class="setting-title"><i class="fa-solid fa-rotate"></i>
            <h2 id='titel'><?php echo esc_html('آپدیت دسته جمعی','gzseo'); ?></h2>
        </div>
        <div class="help-btn"><span><?php echo esc_html('مشاهده راهنما','gzseo'); ?></span><i class="fa-solid fa-lightbulb"></i></div>
    </div>
    <div class="main-container" id='main-container'>    
        <h2><?php echo esc_html('آپدیت دسته ای','gzseo'); ?></h2>
        
        <!-- <form id="my-repeater-form" method="post" action="<?php //echo admin_url('admin-post.php'); ?>"> -->
            <input type="hidden" name="action" value="my_repeater_save">
            <?php wp_nonce_field('gzseo_save_bulks_updates', 'gzseo_save_bulks_updates_nonce'); ?>
            <div id="repeater-container">
                <?php 
                    // نمایش داده‌های ذخیره‌شده در فرم
                    foreach ($saved_data as $item) { ?>
                        <div class="repeater-item">
                            <label for="post_type"><?php echo esc_html('نوع نوشته','gzseo');?></label>
                            <select name="post_type[]" class="post_type"> 
                                <?php
                                    $post_types = get_post_types(array('public' => true), 'objects');
                                    foreach ($post_types as $post_type) {
                                        $selected = ($post_type->name == $item['post_type']) ? 'selected' : '';
                                        echo '<option value="' . esc_attr($post_type->name) . '" ' . esc_attr($selected) . '>' . esc_html($post_type->label) . '</option>';
                                    }
                                ?>
                            </select>

                            <label for="update_interval"><?php echo esc_html('آخرین آپدیت','gzseo'); ?></label>
                            <input type="number" name="update_interval[]" min="0" class="last-update" value="<?php echo esc_attr($item['update_interval']); ?>">

                            <label for="update_type"><?php echo esc_html('نوع آپدیت','gzseo');?></label>
                            <select name="update_type[]" class="update_type">
                                <option value="comment" <?php selected($item['update_type'], 'comment'); ?>><?php echo esc_html('کامنت','gzseo');?></option>
                                <option value="table" <?php selected($item['update_type'], 'table'); ?>><?php echo esc_html('جدول','gzseo');?></option>
                            </select>

                            <button type="button" class="remove-repeater-item"><?php echo esc_html('حذف','gzseo');?></button>
                        </div>
                    <?php } ?>
                    <div class="repeater-item-hidden">
                        <label for="post_type"><?php echo esc_html('نوع نوشته','gzseo');?></label>
                        <select name="post_type[]" class="post_type">
                            <?php
                            $post_types = get_post_types(array('public' => true), 'objects');
                            foreach ($post_types as $post_type) {
                                echo '<option value="' . esc_attr($post_type->name) . '">' . esc_html($post_type->label) . '</option>';
                            }
                            ?>
                        </select>

                        <label for="update_interval"><?php echo esc_html('آخرین آپدیت','gzseo');?></label>
                        <input type="number" class="last-update" name="update_interval[]" min="0">

                        <label for="update_type"><?php echo esc_html('نوع آپدیت','gzseo');?></label>
                        <select name="update_type[]" class="update_type">
                            <option value="comment"><?php echo esc_html('کامنت','gzseo');?></option>
                            <option value="table"><?php echo esc_html('جدول','gzseo');?></option>
                        </select>

                        <button type="button" class="remove-repeater-item"><?php echo esc_html('حذف','gzseo');?>
    </button>
                    </div>
                
            </div>
            <button type="button" id="add-repeater-item"><?php echo esc_html('جدید','gzseo');?></button>
            <input id="save-comments" type="submit" value="<?php echo esc_html('ذخیره','gzseo');?>">
        <!-- </form> -->
    </div>
<?php endif; ?>
