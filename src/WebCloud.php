<?php

namespace Serv5group\Whatscloudapi;
use Illuminate\Support\Facades\Http;

 
/**
 * _________________________________________________________________________________________
 * -----------------------------------------------------------------------------------------
 * # WebCloud Library 
 * -    use in connect with whatsapp service  
 * -    you can send message by needbot
 * -    you can send media by needbot
 * -    you can build otp authentication
 * -----------------------------------------------------------------------------------------
 * _________________________________________________________________________________________
 **/

class WebCloud{
    
    private static $domain;
    private static $prefix;
    private static $token = null;
    private static $instance = null;
    private static $response;
    private static $__instanceid = NULL;
    private static $_phone=null,$_message=null,$_media=null;

    public function __construct()  {
        if( session()->has("whatsappGroupId")){
            session()->forget("whatsappGroupId");
        }
    }
    /**
     * _________________________________________________________________________________________
     * -----------------------------------------------------------------------------------------
     * @see save your token 
     * @return SELF
     * -----------------------------------------------------------------------------------------
     * _________________________________________________________________________________________
    **/
    public static function accessToken(string $token) {
        self::$domain = env('WHATSAPP_DOMAIN');
        self::$token = $token;
        self::$__instanceid = new self;
        return self::$__instanceid;
    }



    /**
     * _________________________________________________________________________________________
     * -----------------------------------------------------------------------------------------
     * @see get your instance id 
     * @return SELF 
     * @return SESSION 
     * -----------------------------------------------------------------------------------------
     * _________________________________________________________________________________________
    **/

    public static function getInstance()  {

        self::$__instanceid = new self;
        return self::$instance??session()->get("create_instance");
    }   


    /**
     * _________________________________________________________________________________________
     * -----------------------------------------------------------------------------------------
     * @see get your instance id 
     * @return SELF 
     * @return SESSION 
     * -----------------------------------------------------------------------------------------
     * _________________________________________________________________________________________
    **/

    public static function setInstance($instance)  {
        session()->put("create_instance",$instance);
        self::$instance = $instance; 
        return new self;
    }   


    /**
     * _________________________________________________________________________________________
     * -----------------------------------------------------------------------------------------
     * @see create new instance id and connection with server 
     * @return SELF 
     * -----------------------------------------------------------------------------------------
     * _________________________________________________________________________________________
    **/
    public static function connect() {

        $token = self::$token;
        $domain = self::$domain;
        $response  = Http::get("$domain/create_instance?access_token=$token");
        $res  = $response->object();
        if($res->status == 'error'){
            self::$instance = ""; 
            return $res;
        }
        session()->put("create_instance",$res->instance_id);
        self::$instance = $res->instance_id; 

        $response = new self;
        return $response;

    }





    /**
     * _________________________________________________________________________________________
     * -----------------------------------------------------------------------------------------
     * @see get QrcCode After Connection with server
     * @return SELF 
     * -----------------------------------------------------------------------------------------
     * _________________________________________________________________________________________
    **/
    public static function getQrCode(){
        $token = self::$token;
        $instance =  self::$instance;
        $domain = self::$domain;
        $response  = Http::get("$domain/get_qrcode?instance_id=$instance&access_token=$token");
        $res  = $response->object();
        if($res->status == 'error'){
            return $res;
        }
        self::$response = $response;
        $response = new self;
        return $response;
    }


    /**
     * _________________________________________________________________________________________
     * -----------------------------------------------------------------------------------------
     * @see save QrCode In System
     * @return BOOLEAN 
     * -----------------------------------------------------------------------------------------
     * _________________________________________________________________________________________
    **/
    public static function save(string $path){

        $image  = collect(self::$response->object())->get('base64');
        $split  = explode("data:image/png;base64,",$image);
        $result = end($split);
        $result = self::makeImageBase64($result,$path);
        return $result;

    }
    


    /**
     * _________________________________________________________________________________________
     * -----------------------------------------------------------------------------------------
     * @see get QrCode Status Active OR Not Active
     * @return OBJECT 
     * @return BOOLEAN 
     * -----------------------------------------------------------------------------------------
     * _________________________________________________________________________________________
    **/
    public static function qrStatus()  {

        $token = self::$token;
        $instance =  self::$instance??session()->get("create_instance");
        if(session()->get("create_instance") != null || $instance != null){
            if($token != null){
                $domain = self::$domain;
                $response  = Http::get("$domain/set_webhook?webhook_url=https://webhook.site/7a93f13a-0819-4382-a351-7558480364ae&enable=true&instance_id=$instance&access_token=$token");
                $res  = $response->object();
                return $res;
            }
        }
        
        return false;
        
    }



    /**
     * _________________________________________________________________________________________
     * -----------------------------------------------------------------------------------------
     * @see Logout Remove Current Instance 
     * @return OBJECT 
     * @return BOOLEAN 
     * -----------------------------------------------------------------------------------------
     * _________________________________________________________________________________________
    **/
    public static function logout()  {
        $token = self::$token;
        $instance =  self::$instance??session()->get("create_instance");
        if(session()->get("create_instance") != null || $instance != null){
            if($token != null){
                $domain = self::$domain;
                $response  = Http::get("$domain/reboot?instance_id=$instance&access_token=$token");
                $res  = $response->object();
                return $res;
            }
        }
        
        return false;
        
    }

    

    /**
     * _________________________________________________________________________________________
     * -----------------------------------------------------------------------------------------
     * @see Force Logout Remove All Instance
     * @return OBJECT 
     * @return BOOLEAN 
     * -----------------------------------------------------------------------------------------
     * _________________________________________________________________________________________
    **/
    public static function logoutForce()  {
        $token = self::$token;
        $instance =  self::$instance??session()->get("create_instance");
        if(session()->get("create_instance") != null || $instance != null){
            if($token != null){
                $domain = self::$domain;
                $response  = Http::get("$domain/reset_instance?instance_id=$instance&access_token=$token");
                $res  = $response->object();
                return $res;
            }
        }
        
        return false;
        
    }



    
    /**
     * _________________________________________________________________________________________
     * -----------------------------------------------------------------------------------------
     * @see When Send Message Select Phone Target
     * @return SELF 
     * -----------------------------------------------------------------------------------------
     * _________________________________________________________________________________________
    **/
    public static function to(string $phone){
        session()->forget("whatsappGroupId");
        self::$_phone = $phone;
        self::$__instanceid = new self;
        return self::$__instanceid;
    }



      
    /**
     * _________________________________________________________________________________________
     * -----------------------------------------------------------------------------------------
     * @see When Send Message input Your Message
     * @return SELF 
     * -----------------------------------------------------------------------------------------
     * _________________________________________________________________________________________
    **/
    public static function message(string $message){
        self::$_message = $message;
        session()->put("message_type","text");
        return self::$__instanceid;
    }



    /**
     * _________________________________________________________________________________________
     * -----------------------------------------------------------------------------------------
     * @see When Send Message input Your Message
     * @return SELF 
     * -----------------------------------------------------------------------------------------
     * _________________________________________________________________________________________
    **/

    public static function togroup(string $id){
        self::$_message = $message;
        session()->put("whatsappGroupId","true");
        session()->put("whatsappGroupIdMaker",$id);
        return self::$__instanceid;
    }



    /**
     * _________________________________________________________________________________________
     * -----------------------------------------------------------------------------------------
     * @see When Send Media input Your Media Files
     * @return SELF 
     * -----------------------------------------------------------------------------------------
     * _________________________________________________________________________________________
    **/
    public static function media(string $media){
        self::$_media = $media;
        session()->put("message_type","media");
        return self::$__instanceid;
    }




    /**
     * _________________________________________________________________________________________
     * -----------------------------------------------------------------------------------------
     * @see Submit User Message
     * @return OBJECT 
     * -----------------------------------------------------------------------------------------
     * _________________________________________________________________________________________
    **/
    public static function send(){

       try {

            $token = self::$token;
            $instance =  self::$instance??session()->get("create_instance");
            $phone = self::$_phone;
            $message = self::$_message;
            $media = self::$_media;
            $domain = self::$domain;
            if(session()->has('whatsappGroupId') && session()->get('whatsappGroupId') == 'true'){
                $groupId = session()->get('whatsappGroupIdMaker');
            
                if(session()->get("message_type") == 'media'){
                    $url= "$domain/send_group?group_id=$groupId&type=media&message=$message&media_url=$media&instance_id=$instance&access_token=$token";
                }else{
                    $url= "$domain/send_group?group_id=$groupId&type=text&message=$message&instance_id=$instance&access_token=$token";
                }
            }else{

                if(session()->get("message_type") == 'media'){
                    $url= "$domain/send?number=$phone&type=media&message=$message&media_url=$media&instance_id=$instance&access_token=$token";
                }else{
                    $url= "$domain/send?number=$phone&type=text&message=$message&instance_id=$instance&access_token=$token";
                }

            }

            $response  = Http::get($url);
            $res  = $response->object();
            return $res;

       } catch (\Exception $e) {
            dd($e->getMessage());
       }

        
    }
    
    /**
     * _________________________________________________________________________________________
     * -----------------------------------------------------------------------------------------
     * @see Helper Function 
     * -----------------------------------------------------------------------------------------
     * _________________________________________________________________________________________
    **/

    public static function chats()  {
        $token = self::$token;
        $instance =  self::$instance??session()->get("create_instance");
        $webhook_url = "https://webhook.site/74d9a914-3d08-41ca-8f16-65febbb1b70f";
        $enable = true;
        $domain = self::$domain;
        $url = "$domain/set_webhook?webhook_url=$webhook_url&enable=$enable&instance_id=$instance&access_token=$token";
        $response  = Http::get($url);
        $res  = $response->object();
        $res = new self;
        return $res;
    }


    /**
     * _________________________________________________________________________________________
     * -----------------------------------------------------------------------------------------
     * @see Helper Function 
     * -----------------------------------------------------------------------------------------
     * _________________________________________________________________________________________
    **/
    public static function makeImageBase64(string  $image_code,string $path=null)  {
        $base64Image = $image_code;
        $decodedImage = base64_decode($base64Image);
        if($path == null){
            return $decodedImage;
        }
        file_put_contents($path, $decodedImage);
        return true;
    }
 

    


}



