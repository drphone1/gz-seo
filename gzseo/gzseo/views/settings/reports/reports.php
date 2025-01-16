<?php
    if (!defined('ABSPATH')) exit;
    wp_enqueue_style('gzseo_reports_style', GZSEO_VIEWS_URI . 'settings/reports/reports.css', '', gzseo::get_plugin_version(), 'all');
    wp_enqueue_script('gzseo_reports_script', GZSEO_VIEWS_URI . 'settings/reports/reports.js', '', gzseo::get_plugin_version(), true);
    wp_enqueue_style( 'font-awesome', GZSEO_URI.'assets/css/all.min.css' , '', '6.0.0', 'all' );
    gzseo::setfont('gzseo_reports_style');
    $gzseo_generated_by = get_comments(['meta_key' => 'gzseo_generated_by']);
?>

<!-- Header -->
<h2><?php echo esc_html('گزارشات','gzseo'); ?></h2>
<div class="reports_wrapper"></div>

<!-- Tabs -->
<div class="tabs">
    <ul id="tabs-nav">
        <li class="active"><a href="#comments_container">کامنت ها</a></li>
        <li><a href="#tables_container">جدول ها</a></li>
    </ul>
    <div id="tabs-content">

        <!---------------------------------------------------------------------------------------- Comments -->
        <div id="comments_container" class="tab-content">
            <div class="bulk_action_bar">
                <input type="checkbox" class="comments_check_all_toggle">
                <button class="comments_approve_all">تایید همه</button>
                <?php wp_nonce_field('gzseo_reports_bulk_actions', 'gzseo_reports_bulk_actions'); ?>
            </div>
            <table class="wp-list-table widefat fixed striped table-view-list comments">
                <thead>
                <tr>
                    <th></th>
                    <th><?php echo esc_html('عنوان','gzseo'); ?></th>
                    <th><?php echo esc_html('نوع','gzseo'); ?></th>
                    <th><?php echo esc_html('دسته‌ها','gzseo'); ?></th>
                    <th><?php echo esc_html('برچسب‌ها','gzseo'); ?></th>
                    <th><?php echo esc_html('تاریخ','gzseo'); ?></th>
                    <th><?php echo esc_html('عملیات','gzseo'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($gzseo_generated_by as $key => $comment) {
                        //var_dump($comment);
                        $post_title = get_the_title($comment->comment_post_ID);
                        $creted_by = get_comment_meta($comment->comment_ID, 'gzseo_generated_by');
                        $content = $comment->comment_content;
                        $comment_author = $comment->comment_author;
                        $edit_link = get_edit_comment_link($comment->comment_ID);
                        $date = $comment->comment_date;
                        $date = wp_date(get_option( 'date_format' ).' - '.get_option('time_format'), strtotime($date));
                        $approved_status = $comment->comment_approved;
                        ?>
                        <tr class="comment <?php echo ($approved_status == 1) ? 'approved' : 'unapproved'; ?>">
                            <td class="item_check"><input type="checkbox" name="comments_chck" value="<?php echo esc_attr($comment->comment_ID) ; ?>"></td>
                            <td class="item_title"><?php echo esc_html($post_title); ?></td>
                            <td class="item_creation_type">
                    <span>
                        <?php
                            $created_type = esc_html($creted_by[0]);
                            switch ($created_type) {
                                case "bulk":
                                    echo esc_html('#دسته جمعی (Bulk)','gzseo');
                                    break;
                                case "user":
                                    echo esc_html('#توسط کاربر (User)','gzseo');
                                    break;
                                case "ai":
                                    echo esc_html('#توسط هوش مصنوعی (AI)','gzseo');
                                    break;
                            }
                        ?>
                    </span>
                            </td>
                            <td class="item_content"><?php echo esc_html($content); ?></td>
                            <td class="item_author"><?php echo esc_html($comment_author); ?></td>
                            <td><?php echo esc_html($date); ?></td>
                            <td class="item_edit_wrapper">
                                <a href="<?php echo esc_html($edit_link); ?>" class="item_edit"><?php echo esc_html('ویرایش','gzseo'); ?></a>
                                <?php if($approved_status == 0): ?>
                                    <button class="item_approve" id="<?php echo esc_attr($comment->comment_ID) ; ?>" value="<?php echo esc_attr($comment->comment_ID); ?>"><?php echo esc_html('تایید','gzseo'); ?></button>
                                <?php endif; ?>

                            </td>
                        </tr>

                    <?php } ?>

            </table>
            <div class="bulk_action_bar">
                <input type="checkbox" class="comments_check_all_toggle">
                <button class="comments_approve_all">تایید همه</button>
                <?php wp_nonce_field('gzseo_reports_bulk_actions', 'gzseo_reports_bulk_actions'); ?>
            </div>
        </div>

        <!---------------------------------------------------------------------------------------- Tables -->

        <div id="tables_container" class="tab-content" style="display: none">
            <?php
                $args = array(
                    'post_type' => 'any',
                    'post_status' => 'publish',
                    //'fields' => 'ids',
                    'posts_per_page' => -1,
                    'meta_key' => 'gzseo_table_data',
                );

                $posts = get_posts($args);
                if($posts && !empty($posts)):
            ?>
                <div class="bulk_action_bar">
                    <input type="checkbox" class="tables_check_all_toggle">
                    <button class="tables_bulk_delete">حذف همه جدول ها</button>
                </div>
                <table class="wp-list-table widefat fixed striped table-view-list tables">
                    <thead>
                        <tr>
                            <th></th>
                            <th><?php echo esc_html('عنوان','gzseo'); ?></th>
                            <th class="date"><?php echo esc_html('تاریخ بروزرسانی پست','gzseo'); ?></th>
                            <th class="date"><?php echo esc_html('تاریخ درج جدول','gzseo'); ?></th>
                            <th><?php echo esc_html('نوع','gzseo'); ?></th>
                            <th><?php echo esc_html('عملیات','gzseo'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $key => $post):
                                $post->post_content = '';
                                //var_dump($post);
                                $title = $post->post_title;
                                $date_post = $post->post_modified;
                                $date_post = wp_date(get_option( 'date_format' ).' - '.get_option('time_format'), strtotime($date_post));
                                $date_table_data = get_post_meta($post->ID,'gzseo_table_data',true);
                                $table_created_date = wp_date(get_option( 'date_format' ).' - '.get_option('time_format'), $date_table_data['time']);
                                $table_created_by = $date_table_data['by'];
                                $table_data_preivew = get_post_meta($post->ID,'gzseo_table',true);
                            ?>
                            <tr class="<?php echo esc_attr($post->ID); ?> table-main-row">
                                <td class="item_check"><input type="checkbox" id="<?php echo esc_attr($post->ID); ?>" name="table_chck" value="<?php echo esc_attr($post->ID); ?>"></td>
                                <td><?php echo esc_html($title); ?></td>
                                <td><?php echo esc_html($date_post); ?></td>
                                <td><?php echo esc_html($table_created_date); ?></td>
                                <td class="item_creation_type">
                                    <span>
                                        <?php
                                            switch ($table_created_by) {
                                                case "ai":
                                                    echo esc_html('#هوش مصنوعی (AI)','gzseo');
                                                    break;
                                                case "ai_bulk":
                                                    echo esc_html('#آپدیت دسته جمعی (AI Bulk)','gzseo');
                                                    break;
                                            }
                                        ?>
                                    </span>
                                </td>
                                <td class="item_edit_wrapper">
                                    <button id="<?php echo esc_html($post->ID); ?>" class="show_table_content"><?php echo esc_html('نمایش محتوا','gzseo'); ?></button>
                                    <a href="<?php echo esc_url(the_permalink($post->ID)); ?>" class="item_edit" target="_blank"><?php echo esc_html('نمایش پست','gzseo'); ?></a>
                                    <button class="delete_tables" id="<?php echo esc_attr($post->ID); ?>" value="<?php echo esc_attr($post->ID); ?>"><?php echo esc_html('حذف جدول','gzseo'); ?></button>
                                </td>
                            </tr>
                            <tr class="<?php echo esc_attr($post->ID); ?> table-preview-row table-preview-hide">
                                <td colspan="6">
                                    <?php
                                        // تعریف تگ‌ها و attributes مجاز
                                        $allowed_tags = array(
                                            'table' => array(),
                                            'tr' => array(),
                                            'td' => array(),
                                            'th' => array(),
                                            'thead' => array(),
                                            'tbody' => array(),
                                            'tfoot' => array(),
                                            // سایر تگ‌های مجاز
                                        );
                                        echo wp_kses(gzseo_ai::tabletoarray($table_data_preivew), $allowed_tags);
                                     ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>