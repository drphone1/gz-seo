<?php  if ( ! defined( 'ABSPATH' ) ) exit; // در صورت دسترسی مستقیم از آن خارج شوید

gzseo_ai::delete_ai_status();

wp_enqueue_style( 'gzseo_ai_style', GZSEO_VIEWS_URI.'settings/dashboard/ai/ai.css' , '', gzseo::get_plugin_version(), 'all' );
wp_enqueue_script( 'gzseo_ai_script', GZSEO_VIEWS_URI.'settings/dashboard/ai/ai.js', '', gzseo::get_plugin_version(), true );
?>
<div class="main-setting-title">
<div class="setting-title"><i class="fa-solid fa-brain"></i><h2><?php echo esc_html('هوش مصنوعی','gzseo') ?></h2></div>
<div class="help-btn"><span><?php echo esc_html('مشاهده راهنما','gzseo');?></span><i class="fa-solid fa-lightbulb"></i></div>
</div>
<div class="main-container">
    <div class="auth-forms">
        					
    <?php
    $gzseo_check_ai_status = gzseo_ai::status();

    if($gzseo_check_ai_status['status'] != "ok"){

        ?>
            <div class="gznotic">
                <strong class="ai-header"><i class="fa-solid fa-circle-exclamation"></i><?php echo esc_html('اتصال به اکانت هوش مصنوعی','gzseo');?></strong>
                <p>
                سلام، من سید محمد امین هاشمی متخصص و مدرس سئو هستم. حدود 10 سالی هست که در حوزه های مختلف دیجیتال مارکتینگ کار کردم (از طراحی و برنامه نویسی بگیر تا الان که سئو کار میکنم)...
                </p> 
            </div>
            	    
            <div class="tab">
                <button class="logintab tablinks active" onclick="openTab(event, 'login')"><?php echo esc_html('ورود به اکانت','gzseo');?></button>
                <button class="registertab tablinks" onclick="openTab(event, 'register')"><?php echo esc_html('ثبت نام جدید','gzseo');?></button>
            </div>
            <div class="login-div">
            <div id="login" class="tabcontent active">
                <span>ورود</span>
                <form id="loginForm">
                    <input type="email" id="username" placeholder="ایمیل" required>
                    <input type="password" id="password" placeholder="رمز عبور"  required>
                    <?php wp_nonce_field('gzseo_ai_login'); ?>
                    <button type="submit" class="btn btn-login">ورود</button>
                </form>
                <div id="loginResult"></div>
            </div>

            <div id="register" class="tabcontent ">
                <span>ثبت نام</span>
                <form id="registerForm">
                    <input type="text" id="regname" placeholder="نام" value="name" required>
                    <input type="email" id="regusername" placeholder="ایمیل" value="" required>
                    <input type="password"  id="regpassword" placeholder="رمز عبور" value="" required>
                    <input type="password"  id="regpasswordconfirm" placeholder="تایید رمز عبور" value="" required>
                    <?php wp_nonce_field('gzseo_ajax_register','gzseo_ai_register_nonce'); ?>
                    <button type="submit" class="btn btn-register"><?php echo esc_html('ثبت نام','gzseo');?></button>
                </form>
                <div id="registerResult"></div>
            </div>
            </div>
        
        <?php } else { ?>

            <div class="main-statusok">
            <span class="credit-text">
            <i class="fa-solid fa-circle-check"></i>             
            اعتبار شما: <?php $credit =$gzseo_check_ai_status['credit'];  if($credit==null) $credit=0; echo ( number_format($credit).' '); ?> کلمه میباشد.

<!--            --><?php //if($gzseo_check_ai_status['eco'] != 0): ?>
<!--                <span class="ptype">-->
<!--         اقتصادی: --><?php
//             $standard =$gzseo_check_ai_status['eco'];  if($standard==null) $standard=0; echo  number_format($standard); ?>
<!--            </span>-->
<!--            --><?php //endif; ?>


<!--            <span class="ptype">-->
<!--            استاندارد: -->
<!--             --><?php
//             $eco =$gzseo_check_ai_status['standard'];  if($eco==null) $eco=0; echo  number_format($eco);
//            ?>
<!--            </span>-->

            </span>
            <button id="deleteaioption">قطع اتصال</button><?php wp_nonce_field( 'gzseo_remove_acc', 'gzseo_remove_acc' );?>
            </div>

            <span class="title-ai">خرید یا افزایش اعتبار هوش مصنوعی</span>
            <div class="package">
                <?php
                $packages = gzseo_ai::get_package();
                foreach ($packages as $package) {
                   // var_dump($package);

                    $gpt_4o_credits_prepaid = $package["gpt_4o_credits_prepaid"];
                    $gpt_4o_mini_credits_prepaid = $package["gpt_4o_mini_credits_prepaid"];
                    $package_count = $package['gpt_4o_credits_prepaid']+ $package['gpt_4o_mini_credits_prepaid'];

                    
                    ?>
                    <div class="package-card">
                    <h4 class="package-title"><?php echo esc_html($package['plan_name']); ?></h4>
<!--                    --><?php //if($gpt_4o_credits_prepaid) echo '<span>تعداد کلمات استاندارد: '.number_format($gpt_4o_credits_prepaid).'</span>'; ?>
<!--                    --><?php //if($gpt_4o_mini_credits_prepaid) echo '<span>تعداد کلمات اقتصادی: '.number_format($gpt_4o_mini_credits_prepaid).'</span>'; ?>
                      <span style="font-weight: 600;"><?php echo "جمع کلمات: ".number_format($package_count); ?></span>
                    <span><?php echo "مبلغ: ".number_format($package['price'] / 10)." تومان"; ?> </span>
                    <button value="<?php echo esc_html($package['id']); ?>" class="packagebuybutton" id="by">افزایش اعتبار</button>
                    </div>
                    <?php
                }
                wp_nonce_field('gzseo_ajax_get_payment_url','gzseo_ajax_get_payment_url_nonce');

                
                ?>
            </div>


<?php }
?>
</div>
</div>