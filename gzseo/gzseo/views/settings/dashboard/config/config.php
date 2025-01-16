<?php
    if (!defined('ABSPATH')) exit;

    // Enqueue Assets
    wp_enqueue_style( 'gzseo_config_manager_style', GZSEO_VIEWS_URI.'settings/dashboard/config/config.css' , '', gzseo::get_plugin_version(), 'all' );
    wp_enqueue_script( 'gzseo_config_manager_script', GZSEO_VIEWS_URI.'settings/dashboard/config/config.js', '', gzseo::get_plugin_version(), true );

    $data = gzseo_ai::get_table_style();

    $selected_card = $data['color'];
    // $active_card = $data['use_defult_style'];
    $tableColors = explode(',', $selected_card);

    $title_back_header = isset($tableColors[0]) ? $tableColors[0] : '#ccc';
    $title_word_header = isset($tableColors[1]) ? $tableColors[1] : '#ccc';

    $title_back_odd = isset($tableColors[2]) ? $tableColors[2] : '#f4f4ff';
    $title_word_odd = isset($tableColors[3]) ? $tableColors[3] : '#000000';

    $title_back_even = isset($tableColors[4]) ? $tableColors[4] : '#ffffff';
    $title_word_even = isset($tableColors[5]) ? $tableColors[5] : '#000000';


    wp_add_inline_style( 'gzseo_config_manager_style', '
    #myTable thead tr {
        background-color: '. esc_html( $title_back_header ) .';
        color: '. esc_html($title_word_header) .';
    }
    #myTable tbody tr:nth-child(odd) {
        background-color: '. esc_html($title_back_odd) .';
        color: '. esc_html($title_word_odd) .';
    }
    #myTable tbody tr:nth-child(even) {
        background-color: '. esc_html($title_back_even) .';
        color: '. esc_html($title_word_even) .';
    }
    ' );

    // Get Subscription status
    $gzseo_check_ai_status = gzseo_ai::status();    

?>

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
            <div class="setting-title"><i class="fa-solid fa-gear"></i>
                <h2 id='titel'>پیکربندی افزونه</h2>
            </div>
            <div class="help-btn"><span>مشاهده راهنما</span><i class="fa-solid fa-lightbulb"></i></div>
    </div>
    <div class="main-container" id='main-container'>
        
        <div class="tab_link_bar">
            <button class='tab_link  active' tab='table'>استایل جدول </button>
            <button class='tab_link' tab='comment'>مدیریت کامنت </button>
        </div>
        <div id="tab_table_manager" class="tab tab-active">

            <div class="setings comment-settings">

                <div class="setting-field">
                    <label for="Tone_of_Content">استفاده از استایل های اماده</label>
                    <span class="field-desc">برای استفاده از استایل های قالب سایت خود، این گزینه را غیرفعال بگذارید.</span>
                    <div>
                        <span class="gzseo_label">فعال/غیرفعال</span>
                        <input type="checkbox" id="gzseo_table_style_checkbox" name="gzseo_table_style_checkbox" <?php echo $data['use_defult_style'] == 'true' ? 'checked' : '' ?>>
                    </div>
                </div>

                <div class="setting-field">
                    <label for="Word_Count">موقعیت جدول</label>
                    <span class="field-desc">مشخص کنید به صورت سراسری جداول ساخته شده در کدام قسمت محتوا نمایش داده شود.</span>
                    <select name="table_positions" id="table_positions_select">
                        <option value="before_content" <?php echo $data['position'] == 'before_content' ? 'selected' : ''; ?>>ابتدا محتوا</option>
                        <option value="end_content" <?php echo $data['position'] == 'end_content' ? 'selected' : ''; ?>>انتها محتوا</option>
                        <option value="after_first_paragraph" <?php echo $data['position'] == 'after_first_paragraph' ? 'selected' : ''; ?>>بعد از اولین پاراگراف</option>
                        <option value="after_first_heading" <?php echo $data['position'] == 'after_first_heading' ? 'selected' : ''; ?>>بعد از اولین تیتر</option>
                    </select>
                </div>

            </div>

            <div class="cards ">
                <?php 
                $cards = array(
                array('color' => '#4e4b83,#ffffff,#f4f4ff,#000000,#ffffff,#000000', 'title' => 'طرح 1'),
                array('color' => '#42a5f5,#000000,#dbefff,#000000,#bbdefb,#000000', 'title' => 'طرح 2'),
                array('color' => '#343a40,#f0f2f4,#dee2e6,#000000,#adb5bd,#000000', 'title' => 'طرح 3'),
                array('color' => '#204C01,#ffffff,#daffb8,#000000,#aad576,#000000', 'title' => 'طرح 4'),
                array('color' => '#583101,#ffffff,#f3d5b5,#5e0303,#ffedd8,#000000', 'title' => 'طرح 5'),
                );
            
                $is_active = false ;
                foreach ($cards as $key => $card) {
                    $active = '';
                    if( $selected_card == $card['color']){
                    $active = 'card-active';
                    $is_active = true ;
                } 
                    ?>
                    <div class="card <?php echo esc_html($active); ?>" tablecolor="<?php echo esc_html($card['color']); ?>">
                        <span class="card-demo-title"><?php esc_html($card['title'],'gzseo'); ?></span>
                        <img src="<?php echo esc_url (GZSEO_URI.'assets/image/imgtable/demo-'.($key+1).'.png');?>" alt="">
                    </div>
                    <?php 
            
                } ?>
                <div class="card <?php if($is_active == false ) echo 'card-active'; ?>"
                    tablecolor="#ff8fab,#000000,#fadde1,#000000,#ffc2d1,#000000">
                    <span class="card-demo-title"><?php esc_html('رنگ بندی دلخواه','gzseo'); ?></span>
                    <img src="<?php echo esc_url (GZSEO_URI.'assets/image/imgtable/demo-custom.png');?>" alt="">
                </div>
            </div>
            <div class="content-details">
                <h2 class="content-title"><?php esc_html('شخصی سازی طرح انتخاب شده','gzseo'); ?></h2>
            </div>
            <div class="senc_tmp" style="display:flex;flex-flow:column; width:100%;">
                <div class="tablecolor_info" id="tablecolor_info">
                    <div class="header-colors">
                        <span id="head"><strong>هدر </strong> </span>
                        <span>رنگ پس زمینه:</span>
                        <input type="color" id="title_back_header" value="<?php echo esc_html($title_back_header); ?>">
                        <span>رنگ متن:</span>
                        <input type="color" id="title_word_header" value="<?php echo esc_html($title_word_header); ?>">
                    </div>
                    <div class="odd-colors">
                        <span><strong>ردیف های فرد</strong> </span>
                        <span>رنگ پس زمینه:</span>
                        <input type="color" id="title_back_odd" value="<?php echo esc_html($title_back_odd); ?>">
                        <span>رنگ متن:</span>
                        <input type="color" id="title_word_odd" value="<?php echo esc_html($title_word_odd); ?>">
                    </div>
                    <div class="even-colors">
                        <span><strong>ردیف های زوج</strong> </span>
                        <span>رنگ پس زمینه:</span>
                        <input type="color" id="title_back_even" value="<?php echo esc_html($title_back_even); ?>">
                        <span>رنگ متن:</span>
                        <input type="color" id="title_word_even" value="<?php echo esc_html($title_word_even); ?>">
                    </div>
                </div>
                <div class="content-details">
                    <h2 class="content-title"><?php esc_html('پیش نمایش','gzseo'); ?></h2>
                </div>

                <div class="senc_tmp" style="padding: 5px;">
                    <table id="myTable">
                        <thead>
                            <tr>
                                <th>عنوان 1</th>
                                <th>عنوان 2</th>
                                <th>عنوان 3</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ردیف فرد </td>
                                <td>ردیف فرد </td>
                                <td>ردیف فرد </td>

                            </tr>
                            <tr>
                                <td>ردیف زوج </td>
                                <td>ردیف زوج </td>
                                <td>ردیف زوج </td>

                            </tr>
                            <tr>
                                <td>ردیف فرد </td>
                                <td>ردیف فرد </td>
                                <td>ردیف فرد </td>

                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <?php wp_nonce_field( 'gzseo_submit_table_style_nonce', 'gzseo_submit_table_style_nonce'); ?>
            <button id="gzseo_submit_table_style" type="submit">ذخیره تغییرات</button>
        </div>
        <div id="tab_comment_manager" class="tab"> <!-- comment tab -->
            <?php
                $settings = gzseo_ai::get_comment_config();

                $Tone_of_Content_1 = esc_attr($settings['Tone_of_Content']);
                $Word_Count_1 = esc_attr($settings['Word_Count']);
            
            ?>
            <div id="gzseo-config-repeater">

                <div class="setings comment-settings">
                    <div class="setting-field">
                        <label for="Tone_of_Content">لحن محتوا</label>
                        <span class="field-desc">مشخص کنید محتوای شما با چه لحنی تولید شود</span>
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
                        <span class="field-desc">مشخص کنید محتوای شما شامل چند کلمه باشد.</span>
                        <input type="number" id="Word_Count" value="<?php echo esc_attr($Word_Count_1); ?>">
                    </div>

                </div>
                <button type="button" id="save_setings">ذخیره تنظیمات</button>
                <?php wp_nonce_field('gzseo_setings_nonce', 'gzseo_setings_nonce'); ?>
            </div>

        </div>

    </div>
<?php endif; ?>
