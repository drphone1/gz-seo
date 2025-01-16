<?php
if (!defined('ABSPATH')) exit;
class gzseo
{
    public function create_menu(){
        if(user_can( wp_get_current_user(), 'manage_options')){
            add_menu_page(
                'جی زد سئو', // عنوان صفحه
                'جی زد سئو', // عنوان منو
                'manage_options', // توانایی مورد نیاز
                'gzseo', // slug صفحه
                array($this, 'dashboard_view') // تابعی که محتوای صفحه را رندر می‌کند
            );
            add_submenu_page( 'gzseo', 'داشبورد', 'داشبورد', 'manage_options', 'gzseo', array($this, 'dashboard_view') );
            add_submenu_page( 'gzseo', 'آپدیت دسته جمعی', 'آپدیت دسته جمعی', 'manage_options', 'bulk_update', array($this, 'bulk_edit_view') );
            add_submenu_page( 'gzseo', 'گزارشات', 'گزارشات', 'manage_options', 'reports', array($this, 'reports') );
            

        }
    }
    public function dashboard_view(){
        include(GZSEO_VIEWS_DIR.'settings/dashboard/sidebar/sidebar.php');
    }
    public function bulk_edit_view(){
        //echo current_time('timestamp');
        include(GZSEO_VIEWS_DIR.'settings/bulk_update/bulk_update.php');
    }
    public function reports(){
        include(GZSEO_VIEWS_DIR.'settings/reports/reports.php');
    }
    public static function setfont($tag){
        wp_add_inline_style($tag,'@font-face {
                font-family: gzseo;
                src: url('.esc_url(GZSEO_URI).'/assets/font/YekanBakhFaNum-VF.woff2);
                }');

    }

    public function ajax_search() {
        check_ajax_referer('gzseo_ajax_search_nonce', 'nonce');
    
    // دریافت کلمه جستجو
    $search_term = isset($_GET['query']) ? sanitize_text_field(wp_unslash($_GET['query'])) : '';
    
    if ($search_term != '') {
        // جستجوی پست‌ها با استفاده از WP_Query
        $args = array(
            'post_type' => 'post', // نوع پست، می‌توانید تغییر دهید به نوع دلخواه شما
            's' => $search_term, // کلمه جستجو
            'post_status' => 'publish', // فقط پست‌های منتشر شده
            'posts_per_page' => -1 // تعداد نامحدود، می‌توانید محدود کنید
        );
    
        $query = new WP_Query($args);
        
        if ($query->have_posts()) {
            while ($query->have_posts()) : $query->the_post();
                echo "<div class='list-group' value='".esc_js(get_the_ID())."' onclick='itemClicked(\"".esc_js(get_the_title())."\", ".esc_js(get_the_ID()).")'>" . esc_html(get_the_title()) . "</div>";
            endwhile;
        } else {
            echo "هیچ نتیجه‌ای پیدا نشد.";
        }
    
        // بازنشانی داده‌های پست برای جلوگیری از مشکلات در کوئری‌های بعدی
        wp_reset_postdata();
    }
    
    wp_die(); // مهم است که اینجا wp_die() را فراخوانی کنیم
    
    

}

    public static function get_plugin_version(){
        $version  =  get_plugin_data( GZSEO_PLUGIN_DIR . 'gzseo.php' )['Version'];
        return $version;

    }

    public function update_dashboard_content() {
        check_ajax_referer('gzseo', 'nonce');
        delete_transient('gzseo_mainsite_data');
        wp_send_json_success();
    }

    
}
