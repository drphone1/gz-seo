<?php
if (!defined('ABSPATH')) exit;
class gzseo_gsc
{
     /*  بررسی وضعیت api گوگل */
     public static function status(){
        if(get_option('gzseo_ga_data') == ""){
            return "NotData";
        }elseif(get_option('gzseo_ga_data')['domain']==""){
            return "NotDomain";
        }else{
            return "OK";
        }
    }

    /*  ساخت rest برای ذخیره اطلاعات کاربر از گوگل  */
    function register_rest_route(){
        register_rest_route( 'gzseo/v1', 'importtoken', [
            'methods' => 'POST',
            'callback' => [$this,'importe_ga_data']
        ]);
    }

    /*  دریافت اطلاعات از api    */
    function importe_ga_data(WP_REST_Request $request){
        (update_option( 'gzseo_ga_data', $request->get_params()));
        if($request->get_header('referer') == "https://api.gzseo.in/"){
               (update_option( 'gzseo_ga_data', $request->get_params()));
        }
   }

   /* حذف rest از مسیرهای وردپرس*/
   function unsetrests( $response ){
        $response->data['namespaces'] = array_diff($response->data['namespaces'] , ['gzseo/v1']);
        unset($response->data['routes']['/gzseo/v1']);
        unset($response->data['routes']['/gzseo/v1/importtoken']);
        return $response;
    }

    /*  آماده سازی اطلاعات برای دریافت کوئری ها */
    public function get_gsc_data($post){
        
        $gzseo_ga_data = get_option('gzseo_ga_data');
       
        $access_token = $gzseo_ga_data['access_token'];
        $expires_in = $gzseo_ga_data['expires_in'];
        $refresh_token = $gzseo_ga_data['refresh_token'];
        $created = $gzseo_ga_data['created'];
        $domain =  $gzseo_ga_data['domain'];

        if(get_site_url()=='http://192.168.1.162/lq'){
            $uri = 'https://injaboro.com/best-halim-shop-in-mashhad/';
        } else {
            $uri = get_permalink($post->ID);
            $uri = preg_replace_callback(
                '/%([a-z0-9]{2})/',
                function ($matches) {
                    return '%' . strtoupper($matches[1]);
                },
                $uri
            );
        }
        $data = [
            'access_token' => $access_token,
            'expires_in' => $expires_in,
            'refresh_token' => $refresh_token,
            'created' => $created,
            'domain' => $domain,
            'uri' => $uri,
        ];

        return $data;
    }

    public function get_pages_keywords($post){
       
        
        $url='https://api.gzseo.in/searchword';
        $data = [
            'body' => $this->get_gsc_data($post), // داده‌ها باید در اینجا قرار بگیرند
        ];
        
        $response = wp_remote_post( $url, $data );



        if(!is_array($response)) {
            echo "این صفحه در سرچ کنسول فاقد اطلاعات است.";
            return [];
        }

        /*if(!gettype($response) == "array") {
            echo "این صفحه در سرچ کنسول فاقد اطلاعات است.";
            return [];
        }*/

        $response = $response['body'];

          if($response != "Please Send access_token,expires_in,refresh_token,created and uri Parameters with POST Method!") {
            $response = json_decode($response,true);
            
            if($response== null){
                //wp_send_json_error( 'dd', 500 );
                //echo "این صفحه در سرچ کنسول فاقد اطلاعات است.";
                return [];

            }else{
                return $response;
            }
          }
    }
    
    public static function get_gsc_domain(){
        return get_option('gzseo_ga_data')['domain'];
    }
    public static function update_gsc_domain($url){
        $gzseo_ga_data = get_option('gzseo_ga_data');
        $gzseo_ga_data['domain'] = $url;
        $result = update_option( 'gzseo_ga_data', $gzseo_ga_data);
        if ( $result ) {
            wp_send_json_success();
        } else {
            wp_send_json_error( array( 'message' => 'خطا در ذخیره اطلاعات.' ) );
        }

    }
    
}
