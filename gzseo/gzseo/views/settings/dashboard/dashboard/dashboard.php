<?php
    if ( ! defined( 'ABSPATH' ) ) exit; 
    wp_enqueue_style('gzseo_dashboard_style', GZSEO_VIEWS_URI.'settings/dashboard/dashboard/dashboard.css' , '', gzseo::get_plugin_version(), 'all' );
    wp_enqueue_script('gzseo_dashboard_script', GZSEO_VIEWS_URI.'settings/dashboard/dashboard/dashboard.js' , '', gzseo::get_plugin_version(), 'all' );
    // Init ajax url
    wp_localize_script( 'gzseo_dashboard_script', 'gzseo_ajax_object', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
    ));
    wp_nonce_field('gzseo', 'gzseo_nonce');
?>
<div class="main-setting-title">
    <div class="setting-title"><i class="fas fa-tachometer-alt"></i> <h2><?php echo esc_html('داشبورد','gzseo'); ?></h2></div>
    <div class="help-btn refresh-dashboard"><span><?php echo esc_html('بروزرسانی داشبورد','gzseo');?></span><i class="fa-solid fa-refresh"></i></div>
</div>

<?php
/**
 * Template Name: Custom Content Display
 */

// URL REST API برای گرفتن داده‌ها از افزونه قبلی.
$api_url = 'https://gzseo.in/wp-json/mcc/v1/contents';

// دریافت داده‌ها از API.
$response = get_transient( 'gzseo_mainsite_data' );


if(!$response){
    $response = wp_remote_get($api_url);
    if (is_wp_error($response)) {
        echo '<p>مشکلی در دریافت داده‌ها وجود دارد.</p>';
        return;
    }
    set_transient( 'gzseo_mainsite_data', $response, 43200);
}

$data = json_decode(wp_remote_retrieve_body($response), true);

if (empty($data)) {
    echo '<p>هیچ محتوایی یافت نشد.</p>';
    return;
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نمایش محتوای افزونه</title>

</head>
<body>

<?php
foreach ($data as $item) {
    $alignment = $item['alignment'];
    $alignment_class = $alignment === 'right' ? 'content-right' : 'content-left';
    switch ($item['type']) {
        case 'video':
            $title = $item['video_title'];
            $video_link = $item['video_link'];
            $video_description = $item['video_description'];
            $video_image = $item['video_image'];
            $video_embed = $item['video_embed'];
            ?>
            <div class='content-item <?php echo esc_html($alignment_class); ?>'>
                <div class="video"> <video controls poster="<?php echo esc_html($video_image); ?>">  <source src="<?php echo esc_html($video_link); ?>" type="video/mp4"> Your browser does not support the video tag.                </video></div>
                <div class="video_text"><h2 class='content-title'><?php echo esc_html($title); ?></h2><p class='content-text'><?php echo esc_html($video_description); ?></p></div>
            </div>
            <?php
            break;
        case 'banner':
            $banner_image = $item['banner_image'];
            $banner_link = $item['banner_link'];
            ?><div class='content-item banner'><a href='<?php echo esc_html($banner_link); ?>'><img src='<?php echo esc_html($banner_image); ?>' alt='بنر'></a></div><?php
            break;
        case 'text':
            $text_title = $item['text_title'];
            $text_description = $item['text_description'];
            ?>
            <div class='content-item <?php echo esc_html($alignment_class); ?>'>
                <div class='content-details'><h2 class='content-title'><?php echo esc_html($text_title); ?></h2><p class='content-text'><?php echo esc_html($text_description); ?></p></div>
            </div>
            <?php
            break;
        case 'image':
            $image = $item['image'];
            $image_title = $item['image_title'];
            $image_description = $item['image_description'];
            ?>
            <div class='content-item <?php echo esc_html($alignment_class); ?>'>
                <div><img src='<?php echo esc_html($image); ?>'></div>
                <div class='content-details'><h2 class='content-title'><?php echo esc_html($image_title); ?></h2><p class='content-text'><?php echo esc_html($image_description); ?></p></div>
            </div>
            <?php
            break;
        default:
            break;
    }

}
?>

</body>
</html>
