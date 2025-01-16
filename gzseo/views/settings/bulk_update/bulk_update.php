<?php
    if (!defined('ABSPATH')) exit;

    wp_enqueue_style( 'gzseo_bulk_update_style', GZSEO_VIEWS_URI.'settings/bulk_update/bulk_update.css' , '', gzseo::get_plugin_version(), 'all' );
    wp_enqueue_script( 'gzseo_bulk_update_script', GZSEO_VIEWS_URI.'settings/bulk_update/bulk_update.js', '', gzseo::get_plugin_version(), true );
    wp_enqueue_style( 'font-awesome', GZSEO_URI.'assets/css/all.min.css' , '', '6.0.0', 'all' );

    gzseo::setfont('gzseo_bulk_update_style');

    // Init ajax url
    wp_localize_script( 'gzseo_bulk_update_script', 'gzseo_ajax_object', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
    ) );

    $saved_data = get_option('gzseo_bulks_updates', []);

    // Get Subscription status
    $gzseo_check_ai_status = gzseo_ai::status();
?>
<?php wp_nonce_field( 'gzseo_bulk_actions' ,'gzseo_bulk_actions_nonce'); ?>
<div class="warp">
    <?php if($gzseo_check_ai_status['status'] != "ok"): ?>
        <div class="alert-subscription">
            <div class="alert-subscription-header"> <i class="fa-solid fa-circle-exclamation"></i> <span><?php echo esc_html('خطا اتصال.','gzseo'); ?></span> </div>
            <div class="alert-subscription-content"><?php echo esc_html('لطفا جهت استفاده از امکانات کامل افزونه لطفا ابتدا اتصال افزونه خود را به هوش منصوعی انجام دهید.','gzseo'); ?></div>
            <div class="alert-subscription-action">
                <a href="<?php echo esc_url(site_url().'/wp-admin/admin.php?page=gzseo&tab=ai') ; ?>"><i class="fas fa-plug"></i><span><?php echo esc_html('اتصال','gzseo'); ?></span></a>
            </div>
        </div>
    <?php else: ?>
        <div class="main-setting-title">
            <div class="setting-title"><h2>مدیریت آپدیت های دسته جمعی</h2></div>
            <div class="help-btn"><span>مشاهده راهنما</span><i class="fa-solid fa-lightbulb"></i></div>
        </div>
        <div class="card-container">

        <?php foreach($saved_data as $value => $card) {
            $args = array(
                'post_type' => $card['post_type'],
                'post_status' => 'publish',
                'date_query' => array(
                    array(
                        'column' => 'post_modified_gmt',
                        'before' => $card['update_interval'] . ' day ago',
                        'inclusive' => true,
                    ),
                ),
                'fields' => 'ids',
                'posts_per_page' => -1,
            );

            if($card['update_type'] === "table") {
                $args['meta_query'] = array(
                    array(
                        'key' => 'gzseo_table',
                        'value' => '',
                        'compare' => 'NOT EXISTS',
                    ));
            }


            $posts = get_posts( $args );
            $post_count = count( $posts );
            $post_ids = $posts;

            ?>
            <div class="card-row" data-post-type="<?php echo esc_attr( $card['post_type'], 'gzseo' ) ; ?>" data-update-interval="<?php echo esc_html($card['update_interval']); ?>">
                <span class="item-details"><strong>نوع نوشته :</strong> <?php echo esc_html(get_post_type_object( $card['post_type']  )->label) ; ?></span>
                <span class="item-details"><strong>آخرین زمان آپدیت :</strong> <?php echo esc_html($card['update_interval']); ?> روز گذشته</span>
                <span class="item-details"><strong>نوع آپدیت :</strong> <?php echo esc_attr( $card['update_type']); ?></span>
                <span class="item-details"><strong>تعداد <?php echo esc_html(get_post_type_object( $card['post_type']  )->label) ; ?> مطابق با انتخاب شما :</strong> <?php echo esc_html($post_count ." ". get_post_type_object( $card['post_type']  )->label); ?></span>
                <div class="view-list-btn-wrapper">
                    <button type="button" class="view-list-btn posts-preivew"
                        data-post-ids="<?php echo esc_html(implode(',', $post_ids)); ?>"
                        data-post-type="<?php echo esc_html($card['post_type']); ?>">مشاهده لیست</button>
                    <input type="number" name="" min="0" max="<?php echo esc_html($post_count); ?>" class="last-update"
                           value="<?php echo esc_html($post_count); ?>">
                    <button type="button" class="run-btn"
                        data-update-type="<?php echo esc_html($card['update_type']); ?>"
                        data-post-type="<?php echo esc_html($card['post_type']); ?>"
                        data-update-interval="<?php echo esc_html($card['update_interval']); ?>">اجرا</button>
                    <div class="spinner_loading">
                        <img src="<?php echo admin_url()."/images/spinner.gif" ?>"/>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    <?php endif; ?>
</div>

<div class="warp warp-response">
    <div class="main-setting-title">
            <div class="setting-title"><h2>نتایج</h2></div>
    </div>
    <div class="card-container">
        <table class="response">
        <thead>
            <td>آیدی</td>
            <td>عنوان</td>
            <td>وضعیت آپدیت</td>
        </thead>
        <tbody>
        </tbody>
        </table>
    </div>
</div>
