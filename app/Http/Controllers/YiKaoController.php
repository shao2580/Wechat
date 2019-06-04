<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use DB;

class YiKaoController extends Controller
{
      //接入
      public function valid()
      {
        $echostr = isset($_GET['echostr']) ? $_GET['echostr'] : "";
        if (!empty($echostr)) {
          echo $echostr;die;
        }
      }
    public function index()
    {
    	// echo $_GET['echostr'];
      $this->valid();
    	$xml= file_get_contents('php://input');
    	file_put_contents('1.txt',$xml);
    	$xmlObj = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
    	/*关注*/
    	if ($xmlObj->MsgType == 'event' && $xmlObj->Event == 'subscribe') {
    		$this->doSubscribe($xmlObj);
    	}

      
  
    	if ($xmlObj->MsgType =='text') {
    		$openid = $xmlObj->FromUserName;
    		 //接收用户回复内容
		    $content =trim($xmlObj->Content);
		   	// dd($content);
		   	
		   //读缓存
		   $data = Cache::get('goods_'.$content);
		   if (!$data) {
		   		//查数据库--获取商品名称
			   	$data = DB::table('goods')->where(['goods_name'=>$content])->first();
			   	Cache::put('goods_'.$content,$data,7200);	
		   }
		   	
		   	if (!empty($data)) {
		   		//回复模板信息
		   		$this->template($data,$xmlObj);
		   	}else{
		   		$msg = '没有该商品';
		    	//回复消息
		    	$this->responseText($xmlObj,$msg);
		   	}
    		   		
    	} 
    
    
    }

    /*获取token*/
    public function getToken()
    {
    	$access_token = Cache::get('access_token');
    	if (!$access_token) {
    		echo 1;
    		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx6d2e84a8e26acdb4&secret=803b993f9443d5fcc625101d070e4797";

	    	$data = file_get_contents($url);
	    	$data = json_decode($data,true);
	    	
	    	$access_token = $data['access_token'];
	    	Cache::put('access_token',$access_token,7200);
    	}   	
    	return $access_token;
    }

    /*处理关注*/
    public function doSubscribe($xmlObj)
    {
    	$msg = '请输入商品名字';
    	//回复消息
    	$this->responseText($xmlObj,$msg);
    }

    /*回复文本信息*/
    public function responseText($xmlObj,$msg)
    {
    	echo "<xml>
			  <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
			  <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
			  <CreateTime>".time()."</CreateTime>
			  <MsgType><![CDATA[text]]></MsgType>
			  <Content><![CDATA[".$msg."]]></Content>
			</xml>";
    }


    /*返回模板信息*/
    public function template($data,$xmlObj)
    {	
      $openid = $xmlObj->FromUserName;
    	$template_id = "a9XKuHpGAsIdySJExMcNkQR4gpYsfazconiUYs6Ni9A";
      $templateData = '{
                   "name": {
                       "value":"'.$data->goods_name.'",
                       "color":"#173177"
                   },
                   "price":{
                       "value":"'.$data->shop_price.'",
                       "color":"#173177"
                   },
                   "number": {
                       "value":"'.$data->goods_number.'",
                       "color":"#173177"
                   }             
           }';
    	$res = Wechat::template($openid,$template_id,$templateData);

    }



}
