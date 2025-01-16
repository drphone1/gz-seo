<?php
if (!defined('ABSPATH')) exit;
class gzseo_ai
{
    public static function status(){
        $response = get_transient( 'gzseo_ai_status' );


        if($response){
            return $response;
            
        }

        $ai_data = get_option('gzseo_ai_data',['status' => 'empty']);
        if(array_key_exists('status',$ai_data)){
            $ai_data = $ai_data['status'];
            if($ai_data =='empty') return ['status' => 'empty'];
        }
        // دریافت اطلاعات ورودی
        $username = $ai_data['username'];
        $password = $ai_data['password'];
        // ارسال درخواست به API
        $response = wp_remote_post('https://api.gzseo.in/login', array(
            'method'    => 'POST',
            'body'      => wp_json_encode(array('email' => $username, 'password' => $password)),
            'headers'   => array('Content-Type' => 'application/json'),
        ));
        // بررسی وضعیت پاسخ
        if (is_wp_error($response)) {
            return( ['status' => 'خطا در ارتباط با API']);  // اگر خطایی در درخواست به وجود آمد
        } else {
            // دریافت و پردازش پاسخ
            $response_body = wp_remote_retrieve_body($response);
            $data = json_decode($response_body, true);
            if(array_key_exists('message',$data)){
                if($data['message'] == 'Unauthorized'){
                    return ['status' => 'Unauthorized'];
                }
            }else{
                $data = ['status' => 'ok','credit' => $data['words'], 'standard' =>  $data['gpt_4o_credits_prepaid'] , 'eco' =>  $data['gpt_4o_mini_credits_prepaid']];
                set_transient( 'gzseo_ai_status', $data, 3600);

                return $data;
            }
        }
    }
    public static function delete_ai_status(){
        delete_transient('gzseo_ai_status');

    }
        
    public static function login() {
        check_ajax_referer('gzseo_ai_login', '_nonce');
        
        // دریافت اطلاعات ورودی
        if(empty($_POST['username'])) wp_send_json_error('username in empty');
        if(empty($_POST['password'])) wp_send_json_error('password in empty');
        $username = sanitize_email(wp_unslash($_POST['username']));
        $password = sanitize_text_field(wp_unslash($_POST['password']));
        // ارسال درخواست به API
        $response = wp_remote_post('https://api.gzseo.in/login', array(
            'method'    => 'POST',
            'body'      => wp_json_encode(array('email' => $username, 'password' => $password)),
            'headers'   => array('Content-Type' => 'application/json'),
        ));

        // بررسی وضعیت پاسخ
        if (is_wp_error($response)) {
            wp_send_json_error('خطا در ارتباط با API');  // اگر خطایی در درخواست به وجود آمد
        } else {
            // دریافت و پردازش پاسخ
            $response_body = wp_remote_retrieve_body($response);
            $data = json_decode($response_body, true);
            //var_dump($data['message']);
            //if()
            if($data['message'] == 'Unauthorized'){
                wp_send_json_success('Unauthorized');
            }else{
                $aidata = [
                    'username' => $username,
                    'password' => $password
                ];

                update_option( 'gzseo_ai_data', $aidata);
                wp_send_json_success($data);
                //wp_send_json_error( $data, 201 );

            }
    
        }

        wp_die(); // پایان فرآیند
    }
    public static function get_package(){

        $response = false;
//        get_transient( 'gzseo_ai_packages' );



        if(!$response){
            $response = wp_remote_post('https://api.gzseo.in/getpackages', array(
                'method'    => 'POST',
                'body'      => wp_json_encode(array('version' => gzseo::get_plugin_version())),

                'headers'   => array('Content-Type' => 'application/json'),
            )); 
        }      
        // بررسی وضعیت پاسخ
        if (is_wp_error($response)) {
            wp_send_json_error('خطا در ارتباط با API');  // اگر خطایی در درخواست به وجود آمد
        } else {
            set_transient( 'gzseo_ai_packages', $response, 43200);
            // دریافت و پردازش پاسخ
            $response_body = wp_remote_retrieve_body($response);
            $data = json_decode($response_body, true);
            return($data);
        }
    }
    public function register(){
        check_ajax_referer('gzseo_ajax_register', '_nonce');
        if(empty($_POST['username'])) wp_send_json_error('username in empty');
        if(empty($_POST['password'])) wp_send_json_error('password in empty');
        if(empty($_POST['name'])) wp_send_json_error('name in empty');
        $username = sanitize_email(wp_unslash($_POST['username']));
        $password = sanitize_text_field(wp_unslash($_POST['password']));
        $name = sanitize_text_field(wp_unslash($_POST['name']));
        // ارسال درخواست به API 
        $response = wp_remote_post('https://api.gzseo.in/register', array(
            'method'    => 'POST',
            'body'      => wp_json_encode(array('name' => $name, 'password' => $password , 'email' => $username)),
            'headers'   => array('Content-Type' => 'application/json'),
        ));
        // بررسی وضعیت پاسخ
        if (is_wp_error($response)) {
            wp_send_json_error('خطا در ارتباط با API');  // اگر خطایی در درخواست به وجود آمد
        } else {
            // دریافت و پردازش پاسخ
            $response_body = wp_remote_retrieve_body($response);
            $data = json_decode($response_body, true);
            //wp_send_json_success($data);
            // var_dump($data);
            //var_dump($data['message']);
            //if()
            if($data['error'] != null){
                wp_send_json_error($data);
            }else{
                $aidata = [
                    'username' => $username,
                    'password' => $password
                ];
                update_option( 'gzseo_ai_data', $aidata);
                wp_send_json_success(["message" => "Successfully created user!"]);
    
            }
     
        }
    
        wp_die();
    }
    public static function remove_acc(){
        check_ajax_referer('gzseo_remove_acc', '_nonce');
        delete_option('gzseo_ai_data');
        delete_transient( 'gzseo_ai_status' );
        wp_send_json_success($data);
    }
    public function get_payment_url(){
        check_ajax_referer('gzseo_ajax_get_payment_url', 'nonce');
        $gzseo_ai_data = get_option('gzseo_ai_data',['status' => 'empty']);
        $email = $gzseo_ai_data['username'];
        $url =admin_url() .'admin.php?page=gzseo&tab=ai';
        if(empty($_POST['id'])){ 
            wp_send_json_error('شماره پکیج نامعتبر است');
        }

        $packageid = sanitize_text_field(wp_unslash( $_POST['id'] )  );
        // ارسال درخواست به API 
        $response = wp_remote_post('https://api.gzseo.in/getpaymentlink', array(
            'method'    => 'POST',
            'body'      => wp_json_encode(array('email' => $email, 'url' => $url , 'packageid' => $packageid)),
            'headers'   => array('Content-Type' => 'application/json'),
        ));
        // بررسی وضعیت پاسخ
        if (is_wp_error($response)) {
            wp_send_json_error('خطا در ارتباط با API');  // اگر خطایی در درخواست به وجود آمد
        } else {
            // دریافت و پردازش پاسخ
            $response_body = wp_remote_retrieve_body($response);
            $data = json_decode($response_body, true);
    
            wp_send_json_success(['url' =>$response_body ]);
            wp_die();
            //var_dump($data['message']);
            //if()
            if($data['error'] != null){
                wp_send_json_error($data);
            }else{
                $aidata = [
                    'username' => $username,
                    'password' => $password
                ];
            }
        }
        wp_die();
    }
    public function add_ai_comment_meta_box() {

        if($this->status()['status'] == "ok"){
            add_meta_box('gzseo_meta_ai_box_cb', 'ساخت کامنت با هوش مصنوعی', [$this , 'gzseo_meta_ai_box_cb'], '');
            add_meta_box('gzseo_table_ai_box_cb', 'ساخت جدول با هوش مصنوعی', [$this , 'gzseo_table_ai_box_cb'], '');

        }
    }
    function gzseo_meta_ai_box_cb(){
        include(GZSEO_VIEWS_DIR.'metaboxes/aicomment/aicomment.php');

    
        return true;
        
    }
    public function gzseo_table_ai_box_cb(){
        include(GZSEO_VIEWS_DIR.'metaboxes/aitable/aitable.php');


    }
    function create_comment(){

        check_ajax_referer( 'gzseo_ajax_create_comment', 'gzseo_ajax_create_comment_nonce' );
        //delete_option('gzseo_ai_data');
        
        $gzseo_ai_data = get_option('gzseo_ai_data',['status' => 'empty']);
        $email = $gzseo_ai_data['username'];
        $password = $gzseo_ai_data['password'];

        if(empty($_POST['postid'])){
            wp_send_json_error(' اطلاعات نامعتبر است');
        }
        $content = get_the_content('','',sanitize_text_field(wp_unslash($_POST['postid'])));
        if(empty($_POST['keyword'])){
            wp_send_json_error(' اطلاعات نامعتبر است');
        }
        //$keyword = rest_sanitize_array(wp_unslash($_POST['keyword']));
        $keyword =  map_deep( wp_unslash(  $_POST['keyword'] ), 'sanitize_text_field' );


        if(empty($_POST['ai_model'])){
            wp_send_json_error(' اطلاعات نامعتبر است');
        }
        $ai_model = sanitize_text_field(wp_unslash($_POST['ai_model']));
        $keyword = implode("\n" , $keyword);

        ///
        if(empty($_POST['Tone_of_Content'])){
            wp_send_json_error(' اطلاعات نامعتبر است');
        }
        $Tone_of_Content = sanitize_text_field(wp_unslash($_POST['Tone_of_Content']));
        if(empty($_POST['Word_Count'])){
            wp_send_json_error(' اطلاعات نامعتبر است');
        }
        $Word_Count =sanitize_text_field(wp_unslash($_POST['Word_Count']));
        ////
        // var_dump($keyword);
        // die();
        // ارسال درخواست به API 
        $response = wp_remote_post('https://api.gzseo.in/createadvancedcomment', array(
            'method'    => 'POST',
            'timeout'     => 600,
            'body'      => wp_json_encode(array('email' => $email, 'password' => $password , 'content' => $content,'keyword' =>$keyword,'ai_model' => $ai_model ,'Tone_of_Content' => $Tone_of_Content , 'Word_Count' => $Word_Count)),
            'headers'   => array('Content-Type' => 'application/json'),
        ));
        //var_dump(array('email' => $email, 'password' => $password , 'content' => $content,'keyword' =>$keyword));
        // بررسی وضعیت پاسخ
        if (is_wp_error($response)) {
            wp_send_json_error($response['body']);  // اگر خطایی در درخواست به وجود آمد
        } else {
            //var_dump($response);
            $validdata= json_decode($response['body']);
            if($validdata->code == 401)
            {
                wp_send_json_error( $validdata->message, 401);
                die();
    
            } 
            // دریافت و پردازش پاسخ
            $response_body = wp_remote_retrieve_body($response);
            // var_dump($response_body);
            // die();
            //$data = json_decode($response_body, true);
            $data=$this->commentstoarray($response_body);
            $data = $this->dom_ai_creator($data);
            //wp_send_json_success(['comments' =>$data ]);
            wp_die();
        }
    
        wp_die();
    }
    private function commentstoarray($text){
    

        $pattern = '/\[start\](.*?)\[end\]/s';
        preg_match_all($pattern, $text, $matches);
    
        $comments = $matches[1];
        return $comments;
    
    }
    private function dom_ai_creator($comments){
        foreach ($comments as $key=>$comment) {
            ?>
            <!-- بخش نمایش کامنت -->
            <div class="comment-list">
                <label class="container" id="comment_label_<?php echo esc_html($key); ?>">
                    <span id="comment_text_<?php echo esc_html($key); ?>"><?php $gzseo_tempdata = htmlspecialchars($comment); echo esc_html($gzseo_tempdata); ?></span>
                    <input type="checkbox" id="gzseo_ai_comment<?php echo esc_html($key); ?>" name="comments[]" value="<?php $gzseo_tempdata =  htmlspecialchars($comment); echo esc_html($gzseo_tempdata); ?>">
                    <span class="checkmark"></span>
                </label>
                <button class="editbtn" id="edit_<?php echo esc_html($key); ?>" type="button" onclick="editComment(<?php echo esc_html($key) ; ?>)">ویرایش</button>
            </div>
    
            <!-- بخش ویرایش -->
            <div class="edit-container" id="edit_container_<?php echo esc_html($key); ?>" style="display: none;">
                <input type="text" class="edit_comment" id="edit_input_<?php echo esc_html($key); ?>" value="<?php $gzseo_tempdata = htmlspecialchars($comment); echo esc_html( $gzseo_tempdata ); ?>">
                <button class="ok" id="confirm_<?php echo esc_html($key); ?>" type="button" onclick="confirmEdit(<?php echo esc_html($key); ?>)">تایید</button>
                <button class="cancel" id="cancel_<?php echo esc_html($key); ?>" type="button" onclick="cancelEdit(<?php echo esc_html($key); ?>)">لغو</button>
            </div>
    
            <?php
        }
        // اضافه کردن دکمه‌ی ارسال کامنت‌های انتخاب شده
        ?>
        <button id="submit_comments" type="button" onclick="submitComments()">ارسال کامنت‌های انتخاب شده</button>
        <?php
    }
    public function submit_comment()
    {
        check_ajax_referer( 'gzseo_ajax_create_comment', 'gzseo_ajax_create_comment_nonce' );
        $comments = isset($_POST['comments']) ? sanitize_text_field(wp_unslash($_POST['comments'])) : [];
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        if (!$post_id || empty($comments)) {
            wp_send_json_error('پارامترهای ارسالی نامعتبر هستند.');
        }
        $comments = explode(",", $comments);
        //$comments = json_decode($comments,true);
        foreach ($comments as $comment_content) {
            $comment_content = sanitize_text_field($comment_content);
            $name = gzseo_comment::generatename();
            $comment_data = [
                'comment_post_ID' => $post_id,
                'comment_content' => $comment_content,
                'comment_approved' => 1,
                'comment_author' => $name,
                'comment_meta'         => [
                    'gzseo_generated_by' => 'ai',
                    ]
            ];

            // وارد کردن کامنت در پایگاه داده
            if (wp_insert_comment($comment_data)) {
                //wp_send_json_success();
            } else {
                wp_send_json_error();
            }
        }

        wp_send_json_success();

    }
    public function create_table(){

        check_ajax_referer( 'gzseo_tables_action', 'nonce' );
        // var_dump($_POST);
        if(empty($_POST['postid'])) wp_send_json_error('eror',403);
        
        $post_id = sanitize_text_field(wp_unslash($_POST['postid']));
            // $tableData = "[startrow][startcol]عنوان [endcol][startcol]ویژگی‌ها [endcol][startcol]مزایا [endcol][startcol]معرفی کوتاه [endcol][endrow]  <br>[startrow][startcol]درگاه سیزپی [endcol][startcol]لینک پرداخت اینترنتی پرسرعت، مشاوره تخصصی، حمایت از استارت‌آپ‌ها [endcol][startcol]امکان ثبت لوگو، تسهیل در پرداخت [endcol][startcol]یکی از بهترین گزینه‌ها برای کسب‌وکارهای آنلاین [endcol][endrow]  <br>[startrow][startcol]درگاه زرین پال [endcol][startcol]پیاده‌سازی ساده، پنل مدیریتی، رایگان [endcol][startcol]امنیت بالا، آسانی در مدیریت مالی [endcol][startcol]مناسب برای بهبود فرایند پرداخت وردپرسی [endcol][endrow]  <br>[startrow][startcol]درگاه سپ [endcol][startcol]وب‌سرویس رایگان، پشتیبانی از چندین IP، سرعت بارگذاری بالا [endcol][startcol]امنیت معتبر، مناسب برای مدیریت مالی [endcol][startcol]یکی از بزرگ‌ترین شرکت‌های فعال در حوزه پرداخت [endcol][endrow]  <br>[startrow][startcol]درگاه آقای پرداخت [endcol][startcol]تسویه حساب سریع، پشتیبانی از ماژول‌های مختلف [endcol][startcol]بدون محدودیت در استفاده، مستندات ساده [endcol][startcol]گزینه‌ای مناسب برای سایت‌های فروشگاهی [endcol][endrow]  <br>[startrow][startcol]درگاه آسان پرداخت [endcol][startcol]شخصی‌سازی ابزارها، پشتیبانی از انواع واحدهای پولی [endcol][startcol]امنیت بالا، امکان استفاده همراه با دیگر درگاه‌ها [endcol][startcol]افزونه‌ای خوب برای سایت‌های وردپرسی [endcol][endrow]  <br>[startrow][startcol]درگاه بانک ملت [endcol][startcol]امکان تعیین عنوان دلخواه، پشتیبانی از تمامی قالب‌ها [endcol][startcol]بازگشت مبلغ در صورت خطا، شخصی‌سازی پیغام خطا [endcol][startcol]قابلیت‌های متنوع برای پرداخت‌ها [endcol][endrow]  <br>[startrow][startcol]سوالات رایج [endcol][startcol]معروف‌ترین درگاه‌ها [endcol][startcol]ویژگی مهم درگاه [endcol][startcol]زرین‌پال، ملت پرداخت، آسان پرداخت، آقای پرداخت [endcol][endrow]  <br>[startrow][startcol] [endcol][startcol] [endcol][startcol]ساده و آسانی استفاده [endcol][endrow]";
            // return $tableData;
            // die();
            //delete_option('gzseo_ai_data');
            $gzseo_ai_data = get_option('gzseo_ai_data',['status' => 'empty']);
            $email = $gzseo_ai_data['username'];
            $password = $gzseo_ai_data['password'];
            $content = get_the_content('','',$post_id);
            $ai_model = 'standard';

             // ارسال درخواست به API 
            $response = wp_remote_post('https://api.gzseo.in/createtable', array(
                'method'    => 'POST',
                'timeout'     => 1000,
                'body'      => wp_json_encode(array('email' => $email, 'password' => $password , 'content' => $content,'ai_model' => $ai_model)),
                'headers'   => array('Content-Type' => 'application/json'),
            ));
            // بررسی وضعیت پاسخ
            if (is_wp_error($response)) {
                wp_send_json_error($response,401);  // اگر خطایی در درخواست به وجود آمد
            } else {
                $validdata= json_decode($response['body']);
                if($validdata->code == 401)
                {
                    wp_send_json_error( $validdata->message, 401);
                    die();
                }elseif($validdata->code == 'http_request_failed'){
                    wp_send_json_error( $validdata->message, 401);
                    die();
                }
                // دریافت و پردازش پاسخ
                $response_body = wp_remote_retrieve_body($response);


                //$response_body="[startrow][startcol]عنوان [endcol][startcol]ویژگی‌ها [endcol][endcol]<br/>[endrow]<br/>[startrow][startcol]درگاه پرداخت سیزپی [endcol][startcol]لینک پرداخت پرسرعت، <br>مشاوره تخصصی، حمایت از استارتاپ‌ها، قابلیت ثبت لوگو[endcol][endrow]<br/>[startrow][startcol]درگاه پرداخت زرین پال [endcol][startcol]پیاده‌سازی آسان، پنل مدیریتی، رایگان، امنیت بالا [endcol][endrow]<br/>[startrow][startcol]درگاه پرداخت سپ [endcol][startcol]وب‌سرویس رایگان، درگاه موبایلی، بدون محدودیت IP، امنیت بالا، سرعت بارگذاری بالا [endcol][endrow]<br/>[startrow][startcol]درگاه آقای پرداخت [endcol][startcol]تسویه حساب سریع، پشتیبانی قوی، ماژول‌های متعدد، مستندسازی ساده [endcol][endrow]<br/>[startrow][startcol]درگاه آسان پرداخت [endcol][startcol]شخصی‌سازی آسان، ارائه شماره کارت، پشتیبانی از واحدهای پولی، امنیت بالا [endcol][endrow]<br/>[startrow][startcol]درگاه پرداخت بانک ملت [endcol][startcol]استرداد خودکار، تعیین عنوان دلخواه، شخصی‌سازی پیغام خطا، سازگاری با ووکامرس [endcol][endrow]<br/>[startrow][startcol]محبوب‌ترین درگاه‌ها [endcol][startcol]زرین‌پال، ملت پرداخت، آسان پرداخت، آقای پرداخت [endcol][endrow]<br/>[startrow][startcol]ویژگی مهم درگاه‌های ایرانی [endcol][startcol]سادگی و آسانی استفاده [endcol][endrow]";

                
                $pattern = '/\[start\](.*?)\[end\]/s';
                preg_match_all($pattern, $text, $matches);
                
                $htmldata = $this->tabletoarray($response_body);
                $data =[
                    'orginaldata' => $response_body,
                    'rendereddata' => $htmldata

                ];
                //$data = json_decode($response_body, true);
                
                // $data=tabletoarray($response_body);
                // return $response_body;
                wp_send_json_success( $data );
                //$data = lq_dom_ai_creator($data);
                //wp_send_json_success(['comments' =>$data ]);
                wp_die();

            }

    }
    public static function tabletoarray($input){
        // تقسیم ورودی به ردیف ها
        $input = str_replace('[STARTROW]', '[startrow]', $input);
        $input = str_replace('[ENDROW]', '[endrow]', $input);
        $input = str_replace('[STARTCOL]', '[startcol]', $input);
        $input = str_replace('[ENDCOL]', '[endcol]', $input);
    
        $rows = explode('[endrow]', $input);
        $table = '<table class="gzseo_table">';
    
        foreach ($rows as $row) {
            // حذف فضاهای اضافی و بررسی اینکه آیا ردیف خالی نیست
            if (trim($row) === '') continue;
    
            // تقسیم ردیف ها به ستون ها
            $columns = explode('[endcol]', $row);
            $table .= '<tr>';
    
            foreach ($columns as $column) {
                // حذف فضاهای اضافی و بررسی اینکه آیا ستون خالی نیست
                $content = trim($column);
                if ($content === '[startcol]' || $content === '') continue;
    
                // اضافه کردن محتوا به جدول
                $table .= '<td>' . htmlspecialchars($content) . '</td>';
            }
    
            $table .= '</tr>';
        }
    
        $table .= '</table>';
        $table = str_replace(['[startrow]', '[endcol]', '[startcol]','&lt;br/&gt;'], '', $table);
        return $table;
    
    }
    public function submit_table(){
        check_ajax_referer( 'gzseo_tables_action', 'nonce' );
        $post_id = isset($_POST['postid']) ? intval($_POST['postid']) : 0;

        $data = isset($_POST['orginaldata']) ? sanitize_text_field(wp_unslash($_POST['orginaldata'])) : '';

        $position_table = isset($_POST['position_table']) ? sanitize_text_field(wp_unslash($_POST['position_table'])) : '';

        $gzseo_table_data = [
            'time' => current_time('timestamp'),
            'by' => 'ai'
        ];
        (update_post_meta( $post_id, 'gzseo_table', $data ));
        (update_post_meta( $post_id, 'gzseo_table_data', $gzseo_table_data ));
        update_post_meta($post_id, 'gzseo_table_position', $position_table);
        wp_send_json_success( 'table saved in meta' );

        //var_dump($data);

        // if (!$post_id || empty($comments)) {
        //     wp_send_json_error('پارامترهای ارسالی نامعتبر هستند.');
        // }

        // var_dump($_POST);
        die();

        return $table;
    
    }
    public function show_table_in_single_pages($content) {

        if (is_single()) {
            $meta_value = get_post_meta(get_the_ID(), 'gzseo_table', true);
            if (!empty($meta_value)) {
                if($this->get_table_style()['use_defult_style']=='true'){
                    wp_enqueue_style( 'gzseo_table_style_in_front', GZSEO_URI.'assets/css/table_front.css', [], gzseo::get_plugin_version(), 'all' );
                    $selected_card = $this->get_table_style()['color'];


                    // $active_card = $data['use_defult_style'];
                    $tableColors = explode(',', $selected_card);

                    $title_back_header = isset($tableColors[0]) ? $tableColors[0] : '#ccc';
                    $title_word_header = isset($tableColors[1]) ? $tableColors[1] : '#ccc';

                    $title_back_odd = isset($tableColors[2]) ? $tableColors[2] : '#f4f4ff';
                    $title_word_odd = isset($tableColors[3]) ? $tableColors[3] : '#000000';

                    $title_back_even = isset($tableColors[4]) ? $tableColors[4] : '#ffffff';
                    $title_word_even = isset($tableColors[5]) ? $tableColors[5] : '#000000';

                    wp_add_inline_style( 'gzseo_table_style_in_front', '
                       
                   
                        .gzseo_table tbody tr:nth-child(odd) {
                            background-color: '. esc_html($title_back_odd) .';
                            color: '. esc_html($title_word_odd) .';
                        }
                        .gzseo_table tbody tr:nth-child(even) {
                            background-color: '. esc_html($title_back_even) .';
                            color: '. esc_html($title_word_even) .';
                        }
                        table.gzseo_table tr:first-child {
                            background-color: '. esc_html($title_back_header) .';
                            color: '. esc_html($title_word_header) .';
                        }
                    ' );
                }
                $meta_value = $this->tabletoarray($meta_value);

                $position = get_post_meta(get_the_ID(), 'gzseo_table_position', true);
                if($position == 'default') $position = $this->get_table_style()['position'];
                if (!$position) $position = $this->get_table_style()['position'];

                $table_html = '<div class="gzseo_ganarated_table">' . ($meta_value) . '</div>';

                switch ($position) {
                    case 'after_first_paragraph':
                        $paragraphs = explode('</p>', $content);
                        if (count($paragraphs) > 1) {
                            $paragraphs[0] .= $table_html; // اضافه کردن جدول بعد از پاراگراف اول
                            $content = implode('</p>', $paragraphs);
                        }
                        break;
                    case 'before_content':
                        $content = $table_html . $content; // قرار دادن جدول قبل از پاراگراف اول
                        break;
                    case 'end_content':
                        $content .= $table_html; // قرار دادن جدول در انتهای محتوا
                        break;
                    case 'after_first_heading':
                        $headings = preg_split('/(<h[1-6]>.*?<\/h[1-6]>)/', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
                        if (count($headings) > 1) {
                            $headings[1] .= $table_html; // اضافه کردن جدول بعد از آخرین عنوان
                            $content = implode('', $headings);
                        }
                        break;
                    default: // 'before_content'
                        $content = $table_html . $content; // قرار دادن جدول در ابتدای محتوا
                        break;
                }


            }
        }

        return $content;
    }
    public static function get_table_style(){
        $data =get_option("gzseo_table_style", ['color' => "#4e4b83,#ffffff,#f4f4ff,#000000,#ffffff,#000000",'use_defult_style'=>false,'position'=>'before_content']);
        if(empty($data['position'])){
            $data['position'] = 'before_content';
        }
        return $data;
    }
    public function submit_table_style(){
        check_ajax_referer( 'gzseo_submit_table_style_nonce', 'nonce' );

        if(empty($_POST['data']['color'])){
            wp_send_json_error(' اطلاعات نامعتبر است');
        }else{
            $color = sanitize_text_field( wp_unslash( $_POST['data']['color'] ) );
        }

        if(empty($_POST['data']['use_defult_style'])){
            wp_send_json_error(' اطلاعات نامعتبر است');
        }else{
            $use_defult_style = sanitize_text_field( wp_unslash( $_POST['data']['use_defult_style'] ) );
        }

        if(empty($_POST['data']['table_position'])){
            wp_send_json_error(' اطلاعات نامعتبر است');
        }else{
            $table_position = sanitize_text_field( wp_unslash( $_POST['data']['table_position'] ) );
        }

        $data = ['color' => $color ,'use_defult_style'=> $use_defult_style,'position'=>$table_position];
        update_option('gzseo_table_style', $data);
        wp_send_json_success( $data );
    }
    public static function get_comment_config()
    {
        return get_option("gzseo_comment_config", ['Word_Count' => 25 , 'Tone_of_Content' =>'حرفه‌ای']);

    }
    public function save_setings_comments()
    {
        check_ajax_referer('gzseo_setings_nonce', 'nonce');
        if(empty($_POST['setings']['Word_Count'])){
            wp_send_json_error(' اطلاعات نامعتبر است');
        }else{
            $Word_Count = sanitize_text_field( wp_unslash( $_POST['setings']['Word_Count'] ) );
        }
        if(empty($_POST['setings']['Tone_of_Content'])){
            wp_send_json_error(' اطلاعات نامعتبر است');
        }else{
            $Tone_of_Content = sanitize_text_field( wp_unslash( $_POST['setings']['Tone_of_Content'] ) );
        }
        $data = ['Word_Count' => $Word_Count ,'Tone_of_Content'=> $Tone_of_Content];
        update_option( 'gzseo_comment_config',$data);
        wp_send_json_success($data );
    }

    public function bulk_update_preview() {
        check_ajax_referer('gzseo_bulk_actions', 'nonce');
        $post_ids = isset( $_POST['post_ids'] ) ? sanitize_text_field( wp_unslash($_POST['post_ids'])) : '';

        if(empty($post_ids)) wp_send_json_error(['msg'=>'هیچ ID پستی برای پیش نمایش ارسال نشده است.']);

        $post_ids = explode(',', $post_ids);

        $results = [];
        $args = array(
            'post__in' => $post_ids,
            'post_type' => 'post',
            'posts_per_page' => -1,
        );
        $post_items = get_posts($args);
        foreach ($post_items as $post) {
            $results[] = [
                    'id' => $post->ID,
                    'title' => $post->post_title,
                    'link' => get_the_permalink($post->ID),
            ];
        }
        wp_reset_postdata();
        wp_reset_query();

        // Send result
        wp_send_json_success($results);
    }

    public function save_bulks_updates()
    {
        check_ajax_referer('gzseo_save_bulks_updates', 'nonce');
        //var_dump($_POST);     $saved_data = get_option('gzseo_bulks_updates', []);

        if(empty($_POST['items'])){
            delete_option( 'gzseo_bulks_updates' );
            wp_send_json_success( 'delete all' );
        }else{
            // var_dump($_POST['items']);
            $items =  map_deep( wp_unslash(  $_POST['items'] ), 'sanitize_text_field' );
            update_option( 'gzseo_bulks_updates', $items );
            wp_send_json_success( 'saved all' );

        }
        
    }

    public static function get_bulks_posts() {
        check_ajax_referer( 'gzseo_bulk_actions' , 'nonce');
        $post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( wp_unslash($_POST['post_type'])) : '';
        $update_type = isset( $_POST['update_type'] ) ? sanitize_text_field( wp_unslash($_POST['update_type'])) : '';
        $update_interval = isset( $_POST['update_interval'] ) ? sanitize_text_field( wp_unslash($_POST['update_interval'])) : '';
        $quantity = isset( $_POST['quantity'] ) ? sanitize_text_field(wp_unslash($_POST['quantity'])) : '';

        $args = array(
            'post_type' => $post_type,
            'post_status' => 'publish',
            'date_query' => array(
                array(
                    'column' => 'post_modified_gmt',
                    'before' => $update_interval  . ' day ago',
                    'inclusive' => true,
                ),
            ),
            'fields' => 'ids',
            'posts_per_page' => $quantity,
        );

        if($update_type === "table") {
            $args['meta_query'] = array(
                array(
                    'key' => 'gzseo_table',
                    'value' => '',
                    'compare' => 'NOT EXISTS',
                ));
        }

        if($update_type === "comment") {
        
        }

        
        $result = get_posts($args);

        wp_send_json_success($result);
        wp_die();

        
    }
    public function bulk_update_single() {

        check_ajax_referer('gzseo_bulk_actions', 'nonce');

        $post_id = isset( $_POST['post_id'] ) ? sanitize_text_field(wp_unslash($_POST['post_id'])) : '';
        $update_type = isset( $_POST['update_type'] ) ? sanitize_text_field(wp_unslash($_POST['update_type'])) : '';
        if($update_type == 'table'){
            $this->bulk_create_table($post_id);
        }elseif($update_type == 'comment'){
            $this->bulk_create_comment($post_id);
        }
        $data = [
            'id' => $post_id,
            'title' => get_the_title($post_id),
        ];
        wp_send_json_success($data);
        wp_die();

    }
    public function bulk_create_table($post_id)
    {


        $gzseo_ai_data = get_option('gzseo_ai_data',['status' => 'empty']);
        $email = $gzseo_ai_data['username'];
        $password = $gzseo_ai_data['password'];
        $content = get_the_content('','',$post_id);
        $ai_model = 'standard';

        $response = wp_remote_post('https://api.gzseo.in/createtable', array(
            'method'    => 'POST',
            'timeout'     => 1000,
            'body'      => wp_json_encode(array('email' => $email, 'password' => $password , 'content' => $content,'ai_model' => $ai_model)),
            'headers'   => array('Content-Type' => 'application/json'),
        ));

        if (is_wp_error($response)) {
            wp_send_json_error($response,401);
        } else {
            $validdata= json_decode($response['body']);
            if($validdata->code == 401)
            {
                wp_send_json_error( $validdata->message, 401);
                die();
            }elseif($validdata->code == 'http_request_failed'){
                wp_send_json_error( $validdata->message, 401);
                die();
            }
            $response_body = wp_remote_retrieve_body($response);

            $pattern = '/\[start\](.*?)\[end\]/s';
            preg_match_all($pattern, $text, $matches);

            $gzseo_table_data = [
                'time' => current_time('timestamp'),
                'by' => 'ai_bulk'
            ];

            update_post_meta($post_id, 'gzseo_table_data', $gzseo_table_data );

            update_post_meta($post_id, 'gzseo_table', $response_body );

            $data = [
                'id' => $post_id,
                'title' => get_the_title($post_id),
                'link' => get_the_permalink($post_id),
            ];
            wp_send_json_success($data);
            //wp_send_json_success( $response_body );
            //$data = lq_dom_ai_creator($data);
            //wp_send_json_success(['comments' =>$data ]);
            wp_die();
        }
    }

    function bulk_create_comment($post_id) {
        $gzseo_ai_data = get_option('gzseo_ai_data',['status' => 'empty']);
        $email = $gzseo_ai_data['username'];
        $password = $gzseo_ai_data['password'];
        $config = gzseo_ai::get_comment_config();
        $ai_model = 'standard';
        $content = get_the_content('','',sanitize_text_field(wp_unslash($post_id)));
        $Tone_of_Content = $config['Tone_of_Content'];
        $Word_Count = $config['Word_Count'];
        $gzseo_gsc = new gzseo_gsc();
        $post = get_post($post_id);
        $keyword = $gzseo_gsc->get_pages_keywords($post);

        if(empty($keyword)) wp_send_json_error( ['id' => $post->ID, 'title'=> $post->post_title,'link' => get_the_permalink($post->ID)], 501);

        $keywords = [];
        foreach ($keyword as $key => $value) {
            $keywords[] = $value['keys'][0];
        }
        
        $keyword = rest_sanitize_array(wp_unslash($keywords));
        
        $keyword = implode("\n" , $keyword);

        $response = wp_remote_post('https://api.gzseo.in/createadvancedcomment', array(
            'method'    => 'POST',
            'timeout'     => 600,
            'body'      => wp_json_encode(array('email' => $email, 'password' => $password , 'content' => $content,'keyword' =>$keyword,'ai_model' => $ai_model ,'Tone_of_Content' => $Tone_of_Content , 'Word_Count' => $Word_Count)),
            'headers'   => array('Content-Type' => 'application/json'),
        ));

        if (is_wp_error($response)) {
            wp_send_json_error($response['body']);
        } else {
            $validdata= json_decode($response['body']);
            if($validdata->code == 401)
            {
                wp_send_json_error( $validdata->message, 401);
                die();
    
            }
            $response_body = wp_remote_retrieve_body($response);

            $data=$this->commentstoarray($response_body);

            foreach ($data as $comment_content) {
                $comment_content = sanitize_text_field($comment_content);
                $name = gzseo_comment::generatename();
                $comment_data = [
                    'comment_post_ID' => $post_id,
                    'comment_content' => $comment_content,
                    'comment_approved' => 0,
                    'comment_author' => $name,
                    'comment_meta'         => [
                        'gzseo_generated_by' => 'bulk',
                        ]
                ];

                if (wp_insert_comment($comment_data)) {
                    //wp_send_json_success();
                } else {
                    wp_send_json_error();
                }
            }



            //$data = $this->dom_ai_creator($data);
            //wp_send_json_success(['comments' =>$data ]);
            //update_post_meta( $post_id, 'gzseo_table', $response_body );

            $data = [
                'id' => $post_id,
                'title' => get_the_title($post_id),
                'link' => get_the_permalink($post_id),
            ];
            wp_send_json_success($data);
            wp_die();
      
     
        }
    
        wp_die();
    }

    public function table_delete() 
    {
        check_ajax_referer( 'gzseo_tables_action', 'nonce');

        $post_id = isset( $_POST['postid'] ) ? sanitize_text_field(wp_unslash($_POST['postid'])) : '';
        
        if(isset($post_id) && !empty($post_id)) {
            delete_post_meta($post_id,'gzseo_table');
            delete_post_meta($post_id,'gzseo_table_data');
            wp_send_json_success($post_id);
        } else {
            wp_send_json_error('خطا! آیدی پست یافت نشد.');
        }

        wp_die();
    }

    /**
     * Reports | Ajax callback
     * Approve all checked comments.
     *
     * @return json
     * @author @sinaboromand
     * @since  1.0.0
     */
    public function reports_bulk_actions() {
        check_ajax_referer( 'gzseo_reports_bulk_actions', 'nonce');

        // Get actions type
        $action_type = isset( $_POST['actionType'] ) ? sanitize_text_field(wp_unslash($_POST['actionType'])) : '';

        // Comments - Loop comment ids, sanitize and update them
        if($action_type == 'approve') {
            if(empty($_POST['commentIds'])) {
                wp_send_json_error(['msg' => 'لیست آیدی کامنت ها خالی است.']);
            }else{
                $postids = array_unique(array_map('absint', $_POST['commentIds']));
                if(is_array($postids)) {
                    foreach ($postids as $key => $comment_id) {
                        $postids[$key] = sanitize_text_field(wp_unslash($comment_id));
                        wp_update_comment(['comment_ID' => $postids[$key], 'comment_approved' => 1]);
                    }
                    $resp_msg = "تایید کامنت ها انجام شد.";
                    wp_send_json_success(['msg'=>$resp_msg]);
                } else {
                    $comment_id = isset( $postids ) ? sanitize_text_field( wp_unslash($postids)) : '';
                    wp_update_comment(['comment_ID' => $comment_id, 'comment_approved' => 1]);
                    $resp_msg = "کامنت تایید شد.";
                    wp_send_json_success(['msg'=>$resp_msg]);
                }
            }
        }

        // Tables - Delete Single
        if($action_type == 'delete_table') {

            $post_id = isset( $_POST['postID'] ) ? sanitize_text_field(wp_unslash($_POST['postID'] )) : '';
            if($post_id) {
                delete_post_meta($post_id,'gzseo_table');
                delete_post_meta($post_id,'gzseo_table_data');
                $resp_msg = "جدول حذف شد.";
                wp_send_json_success(['msg' => $resp_msg]);
            } else {
                $resp_msg = "خطا";
                wp_send_json_success(['msg' => $resp_msg]);
            }



        }

        // Tables - Bulk delete
        if($action_type == 'table_bulk_delete') {
            if(empty($_POST['tablePostIds'])) {
                wp_send_json_error(['msg' => 'لیست آیدی پست ها خالی است.']);
            } else {
                
                $postids = array_unique(array_map('absint', $_POST['tablePostIds']));
                foreach ($postids as $key => $post_id) {
                    delete_post_meta($post_id,'gzseo_table');
                    delete_post_meta($post_id,'gzseo_table_data');
                }
                wp_send_json_success([$post_ids,'msg' => 'حذف جداول از نوشته های انتخابی انجام شد.']);
            }

        }

        wp_die();
    }

}