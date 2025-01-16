<?php 
    if ( ! defined( 'ABSPATH' ) ) exit; 

    //wp_enqueue_style( 'gzseo_dashboard_style', GZSEO_VIEWS_URI.'settings/dashboard/dashboard/dashboard.css' , '', gzseo::get_plugin_version(), 'all' );
    wp_enqueue_style( 'gzseo_gsc_style', GZSEO_VIEWS_URI.'settings/dashboard/gsc/gsc.css' , '', gzseo::get_plugin_version(), 'all' );
    wp_register_script( 'gzseo_dashboard_style', GZSEO_VIEWS_URI.'settings/dashboard/gsc/gsc.js', '', gzseo::get_plugin_version(), true );

    wp_localize_script( 'gzseo_dashboard_style', 'gzseo_script_vars', array(
        'domain'  => esc_url( site_url() ),
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'save_advanced_settings_nonce' ),
    ));
    // ارسال دامنه به JS
    wp_enqueue_script( 'gzseo_dashboard_style');
    gzseo::setfont('gzseo_dashboard_style');
    $gzseo_gsc_status = (gzseo_gsc::status());
    $gzseo_gsc_domain = (gzseo_gsc::get_gsc_domain());

?>


<div class="main-setting-title">
<div class="setting-title"><i class="fa-solid fa-globe"></i> <h2>اتصال به سرچ کنسول</h2></div>
<div class="help-btn"><span>مشاهده راهنما</span><i class="fa-solid fa-lightbulb"></i></div>
</div>



<?php
    if ( $gzseo_gsc_status == "NotData" ) {
        echo "<span class='not-conected'><i class='fa-regular fa-circle-xmark'></i> اتصال برقرار نیست!</span> <p class='msg'>لطفا برای اتصال به سرچ کنسول احراز هویت کنید</p>";
        echo '<button id="authButton" class="btn btn-auth">احراز هویت</button>';

        // نمایش چک‌باکس تنظیمات حرفه‌ای
        echo '<label><input type="checkbox" id="advancedSettingsCheckbox"> تنظیمات حرفه‌ای</label>';

        // فیلدهای تنظیمات حرفه‌ای (در ابتدا مخفی)
        echo '<div id="advancedSettingsFields" style="display:none;">';
        echo '<label>آدرس سایت: <input type="text" id="siteAddress" name="siteAddress" value="'. esc_attr($gzseo_gsc_domain).'"></label>';
        echo '<label>نوع دامنه: ';
        echo '<select id="domainType" name="domainType">';
        echo '<option value="domain">Domain</option>';
        echo '<option value="url_prefix">URL-prefix</option>';
        echo '</select></label>';
        echo '<button id="saveSettingsButton" class="btn">ذخیره تنظیمات</button>';
        echo '</div>';

    } elseif ( $gzseo_gsc_status == "NotDomain" ) {
        echo "<span class='msg'> <i class='fa-regular fa-circle-xmark'></i> این دامنه مال شما نیست</span>";
        echo '<button id="authButton" class="btn btn-auth">احراز هویت</button>';

    } else {
        echo "<span class='conected'><i class='fa-solid fa-circle-check'></i> اتصال برقرار است!</span><p class='msg'>اگر در دریافت اطلاعات مشکل دارید، مجدد احراز هویت کنید</p>";
        echo '<button id="authButton" class="btn btn-auth">احراز هویت مجدد</button>';

        // نمایش چک‌باکس تنظیمات حرفه‌ای
        echo '<label><input type="checkbox" id="advancedSettingsCheckbox"> تنظیمات حرفه‌ای</label>';

        // فیلدهای تنظیمات حرفه‌ای (در ابتدا مخفی)
        echo '<div id="advancedSettingsFields" style="display:none;">';
        echo '<label>آدرس سایت: <input type="text" id="siteAddress" name="siteAddress" value="'. esc_attr($gzseo_gsc_domain).'"></label>';
        echo '<label>نوع دامنه: ';
        echo '<select id="domainType" name="domainType">';
        echo '<option value="domain">Domain</option>';
        echo '<option value="url_prefix">URL-prefix</option>';
        echo '</select></label>';
        echo '<button id="saveSettingsButton" class="btn">ذخیره تنظیمات</button>';
        echo '</div>';
    }
?>