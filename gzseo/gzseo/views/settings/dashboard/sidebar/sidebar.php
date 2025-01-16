<?php
if ( ! defined( 'ABSPATH' ) ) exit; // در صورت دسترسی مستقیم از آن خارج شوید 
wp_enqueue_style( 'gzseo_sidebar_style', GZSEO_VIEWS_URI.'settings/dashboard/sidebar/sidebar.css' , '', gzseo::get_plugin_version(), 'all' );
wp_enqueue_style( 'font-awesome', GZSEO_URI.'assets/css/all.min.css' , '', '6.0.0', 'all' );
wp_enqueue_script( 'gzseo_sidebar_script', GZSEO_VIEWS_URI.'settings/dashboard/sidebar/sidebar.js', '', gzseo::get_plugin_version(), true );

gzseo::setfont('gzseo_sidebar_style');
if(empty($_GET['tab'])){
    $_GET['tab'] = 'dashboard';
}

?>

<div class="wrap">

    <div class="sidebar">

        <ul class="sidemanu">
            <li class=" plugin-name">
                <img src="<?php echo esc_url (GZSEO_URI.'assets/image/gzlogo.svg');?> " alt="invoice">
                <strong>تنظیمات جی زد سئو</strong>
                <br>
                <span class="version">نسخه <?php echo gzseo::get_plugin_version(); ?></span>
            </li>
            <li class="menuitem">
                <a class="<?php echo  $_GET['tab']=='dashboard' ? esc_attr("active") : "" ;?>" href="<?php echo esc_url(admin_url('admin.php?page=gzseo&tab=dashboard'),'gzseo')?>">
                    <span class="menu-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </span>
                    <div class="menu-text">
                        <div class="menu-title">داشبورد</div>
                        <div class="menu-subtitle">معرفی افزونه</div>
                    </div>
                </a>
            </li> 
            <li class="menuitem">
                <a class="<?php echo  $_GET['tab']=='gsc' ? "active" : "" ?>" href="<?php echo esc_url(admin_url('admin.php?page=gzseo'))?>&tab=gsc">
                    <span class="menu-icon">
                        <i class="fa-solid fa-globe"></i>
                    </span>
                    <div class="menu-text">
                        <div class="menu-title">سرچ کنسول</div>
                        <div class="menu-subtitle">اتصال به سرچ کنسول و رفع ایراد</div>
                    </div>
                </a>
            </li> 
            <li class="menuitem">
                <a class="<?php echo  $_GET['tab']=='comment-manager' ? "active" : "" ?>" href="<?php echo esc_url(admin_url('admin.php?page=gzseo'))?>&tab=comment-manager">
                    <span class="menu-icon">
                    <i class="fa-solid fa-comment-dots"></i>
                    </span>
                    <div class="menu-text">
                        <div class="menu-title">مدیریت کامنت</div>
                        <div class="menu-subtitle">اضافه کردن کامنت به کامنت های پیشفرض</div>
                    </div>
                </a>
            </li>
            <li class="menuitem">
                <a class="<?php echo  $_GET['tab']=='ai' ? "active" : "" ?>" href="<?php echo esc_url(admin_url('admin.php?page=gzseo'))?>&tab=ai">
                    <span class="menu-icon">
                    <i class="fa-solid fa-brain"></i>
                    </span>
                    <div class="menu-text">
                        <div class="menu-title">هوش مصنوعی</div>
                        <div class="menu-subtitle">اتصال به هوش مصنوعی و خرید شارژ</div>
                    </div>
                </a>
            </li>
            <li class="menuitem">
                <a class="<?php echo  $_GET['tab']=='bulk_update' ? "active" : "" ?>" href="<?php echo esc_url(admin_url('admin.php?page=gzseo'))?>&tab=bulk_update">
                    <span class="menu-icon">
                    <i class="fa-solid fa-rotate"></i>
                    </span>
                    <div class="menu-text">
                        <div class="menu-title">آپدیت دسته جمعی</div>
                        <div class="menu-subtitle">انتخاب محتواهای دلخواه برای بروزرسانی</div>
                    </div>
                </a>
            </li>
            <li class="menuitem">
                <a class="<?php echo  $_GET['tab']=='config' ? "active" : "" ?>" href="<?php echo esc_url(admin_url('admin.php?page=gzseo'))?>&tab=config">
                    <span class="menu-icon">
                    <i class="fa-solid fa-gear"></i>
                    </span>
                    <div class="menu-text">
                        <div class="menu-title">پیکربندی افزونه</div>
                        <div class="menu-subtitle">تنظیمات مربوط به هوش مصنوعی</div>
                    </div>
                </a>
            </li> 

            
            <!-- سایر آیتم‌ها -->
        </ul>
    </div>


<div class="setting-body">
    <?php
    //var_dump($_GET['tab']);  $a < 10 ? "Hello" : "Good Bye";
    if(empty($_GET['tab'])){
         include(GZSEO_VIEWS_DIR.'settings/dashboard/dashboard/dashboard.php');
    }elseif($_GET['tab']=='dashboard'){
        include(GZSEO_VIEWS_DIR.'settings/dashboard/dashboard/dashboard.php');
    }elseif($_GET['tab']=='gsc'){
        include(GZSEO_VIEWS_DIR.'settings/dashboard/gsc/gsc.php');
    }elseif($_GET['tab']=='comment-manager'){
        include(GZSEO_VIEWS_DIR.'settings/dashboard/comment-manager/comment-manager.php');
    }elseif($_GET['tab']=='ai'){
        include(GZSEO_VIEWS_DIR.'settings/dashboard/ai/ai.php');
    }elseif($_GET['tab']=='config'){
        include(GZSEO_VIEWS_DIR.'settings/dashboard/config/config.php');
    }elseif($_GET['tab']=='bulk_update'){
        include(GZSEO_VIEWS_DIR.'settings/dashboard/bulk_update/bulk_update.php');
    }
    ?>
</div>
    
</div>

