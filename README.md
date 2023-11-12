
# WhatsCloudApi

A library used with the Laravel framework to enable you to send WhatsApp messages as well as deal with bulk messages and get WhatsApp qrcode and login using OTP and other things.


## Installation

Install WhatsCloudApi with composer

```bash
composer require serv5group/whatscloudapi
```
    
## Support

- you can dependent on[ app.needbots.com](https://app.needbots.com/) for get token and instance .


## Laravel env

```bash
  WHATSAPP_DOMAIN="https://app.needbots.com/"
```


## Usage/Examples

```javascript
use serv5group\whatscloudapi\WebCloud;
 
class Examples
{

  /*
  * Create WhatsCloudApi new instance
  * @return WebCloud
  */
  public function connect() 
  {
    $connect = WebCloud::accessToken("token")->setInstance("instanceId");
    return $connect;
  }


  /*
  * send whatsapp message
  * @return Void
  */
  public function sendMessage() 
  {
    $response = $this->connect()->to("phone")->message("template")->send();
    if( $response->status == 'success' ){
      // The message was sent successfuly
    }else{
      \Log::error($response->message);
    }//@endif
    
  }


  /*
  * send whatsapp bulk message
  * @return Void
  */
  public function sendBulkMessage() 
  {

    foreach ($contacts as $contact) {
      $response = $this->connect()->to("contact")->message("template")->send();

      if( $response->status == 'success' ){
      // The message was sent successfuly
      }else{
        \Log::error($response->message);
      }//@endif

    } //@endforeach
    
  }//@endfunction



  
  /*
  * send whatsapp message and media
  * @return Void
  */
  public function sendMessage() 
  {
    $response = $this->connect()->to("phone")   ->message("template")->media("path")->send();
    if( $response->status == 'success' ){
      // The message was sent successfuly
    }else{
      \Log::error($response->message);
    }//@endif
    
  }



 /*
  * send whatsapp bulk message and attachments
  * @return Void
  */
  public function sendBulkMessage() 
  {

    foreach ($contacts as $contact) {
      $response = $this->connect()->to("contact")->message("template")->media("path")->send();

      if( $response->status == 'success' ){
      // The message was sent successfuly
      }else{
        \Log::error($response->message);
      }//@endif

    } //@endforeach
    
  }//@endfunction



}
```


## 🛠 Skills
PHP , LARAVEL


## License

[MIT](https://serv5.com)

 
