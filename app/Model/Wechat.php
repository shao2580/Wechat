<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Model\User;
use DB;

class Wechat extends Model
{
  	const appId = 'wx6d2e84a8e26acdb4';
  	const appSecret = '803b993f9443d5fcc625101d070e4797';

  	//接入
  	public static function valid()
  	{
    		$echostr = isset($_GET['echostr']) ? $_GET['echostr'] : "";
    		if (!empty($echostr)) {
    			echo $echostr;die;
    		}
  	}

  	/**无限极分类 后台
  	 * 
  	 * [CreateTree description]
  	 * @param [type]  $data      [description]  要循环的数据
  	 * @param integer $parent_id [description]	父级id  默认为0 代表一级
  	 * @param integer $level     [description]	级别		默认为1
  	 * @return array 
  	 */
  	public static function createTree($data,$parent_id=0,$level=1,$field='menu_id'){
  		static $result = [];

  		if ($data) {
  			foreach ($data as $key => $val) {
  				if ($val['parent_id']==$parent_id) {
  					$val['level'] = $level;
  					$result[]=$val;			
  					Wechat::createTree($data,$val[$field],$level+1,$field);
  				}
  			}
  			return $result;
  		}
  	}

  	/**被动回复文本
  	 * [responseText description]
  	 * @param  [type] $xmlObj [description]
  	 * @param  [type] $msg    [description]
  	 * @return [type]         [description]
  	 */
  	public static function responseText($xmlObj,$msg)
  	{
  		echo "<xml>
  			<ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
  			<FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
  			<CreateTime>".time()."</CreateTime>
  		    <MsgType><![CDATA[text]]></MsgType>
  		    <Content><![CDATA[".$msg."]]></Content>
  		    </xml>";		
  	}

  	/*返回模板信息  --- $data为对象 */
    public static function template($openid,$template_id,$templateData)
    {	
    	$access_token =Wechat::getToken();
    	$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$access_token}";
     $templateData = json_encode($templateData);
      $post_data ='{
           "touser":"'.$openid.'",
           "template_id":"'.$template_id.'",
           "data":'.$templateData.'
       }';
       // $post_data = json_encode($post_data);
       // dd($post_data);
        $data = Wechat::curlPost($url,$post_data);
        // dd($data);
		    return $data;
    }

    /**获取token
  		 * [getToken description]
  		 * @return [type] [description]
  		 */
  	public static function getToken()
  	{
  		// /*文件缓存*/
  		// $cache_name = 'text.txt';		
  		// $access_token =Cache::pull('access_token');
  		$access_token =Cache::get('access_token');
  		if (!$access_token) {
  			//查数据
  			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::appId."&secret=".self::appSecret;
  			$data = file_get_contents($url);
  			$data = json_decode($data,true);
  			
  			$access_token = $data['access_token'];

  			Cache::put('access_token',$access_token,7200);

  		}		
  		return $access_token;
  	} 

    /**************************************************************/
    /**获取ticket（票据）  微信js-SDK 软件开发工具包
     * [getJsApiTicket description]
     * @return [type] [description]
     */
    public static function getJsApiTicket()
    {   
      // $access_token =Cache::pull('access_token');
      $ticket =Cache::get('ticket');
      if (!$ticket) {
        //查数据
        $access_token = Wechat::getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=jsapi";
        $data = file_get_contents($url);
        $data = json_decode($data,true);
        
        $ticket = $data['ticket'];
        Cache::put('ticket',$ticket,7200);

      }   
      return $ticket;
    } 

    /*js--sign数据包  ---传前台   ---分享*/
    public static function getSignPackage() 
    {
        $jsapiTicket = Wechat::getJsApiTicket();
        // dump($jsapiTicket);
        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        // dump($url);
        $timestamp = time();
        $nonceStr = Wechat::createNonceStr();
        // dump($nonceStr);
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
          "appId"     => self::appId,
          "nonceStr"  => $nonceStr,
          "timestamp" => $timestamp,
          "url"       => $url,
          "signature" => $signature,
          "rawString" => $string
        );
        return $signPackage; 
    }

    /*----要商户才能做--- 没有完善---控制器(AdminController)---*/
    /**微信支付 传参 
     * [getCodeUrl description]
     * @return [type] [description]
     */
    public static function  getCodeUrl()
    {
        $appid = 'wxd5af665b240b75d4';
        $mch_id = '1500086022';
        $nonce_str= Wechat::createNonceStr();
        $body = "扫码打赏";
        $out_trade_no = "10a".date("YmdHi").rand(1000,9999);
        $total_fee = 1;
        $spbill_create_ip = $_SERVER['REMOTE_ADDR'];
        $notify_url = "http://www.shao2580.top/pay"; 
        $trade_type = "NATIVE";

        $signArr = [
            'appid'=>$appid,        //微信支付分配的公众账号ID 
            'mch_id'=>$mch_id,      //商户号
            'nonce_str'=>$nonce_str,        //随机字符串
            'body'=>$body,                  //商品描述
            'out_trade_no'=>$out_trade_no,  //商户订单号
            'total_fee'=>$total_fee,        // 标价金额 ---钱 单位是分
            'spbill_create_ip'=>$spbill_create_ip,       //终端IP
            'notify_url'=>$notify_url,                   //异步通知地址
            'trade_type'=>$trade_type,                   //交易类型         
        ];        
        //生成签名 ------请求参数 必填10项
        //签名步骤一：按字典序排序参数7c4a8d09ca3762af61e59520943AB26Q
        ksort($signArr);
        $string = Wechat::ToUrlParams($signArr);
        //签名步骤二：在string后加入KEY
        $string = $string."&key="."7c4a8d09ca3762af61e59520943AB26Q";
        //签名步骤三：MD5加密或者HMAC-SHA256
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $sign = strtoupper($string);

        //组装xml数据
        $xml = '<xml>
           <appid>'.$appid.'</appid>
           <body>'.$body.'</body>
           <mch_id>'.$mch_id.'</mch_id>
           <nonce_str>'.$nonce_str.'</nonce_str>
           <notify_url>'.$notify_url.'</notify_url>
           <out_trade_no>'.$out_trade_no.'</out_trade_no>
           <spbill_create_ip>'.$spbill_create_ip.'</spbill_create_ip>
           <total_fee>'.$total_fee.'</total_fee>
           <trade_type>'.$trade_type.'</trade_type>
           <sign>'.$sign.'</sign>
        </xml>';

        //微信支付地址
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        //发送post请求 发送xml数据
        $res = Wechat::HtmlCurlPost($url,$xml);
        // dd($res);
        $resObj = simplexml_load_string($res);
        if($resObj->return_code == 'SUCCESS'){
            $code_url = $resObj->code_url; //二维码地址

            return $code_url;
            // return $resObj;
        }  
    }
    /*-------------------------------*/

    /**拼接签名字符串  支付用
     * [ToUrlParams description]
     * @param [type] $signArr [description]
     */
    public static function ToUrlParams($signArr)
    {
        $buff = "";
        foreach ($signArr as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        
        $buff = trim($buff, "&");
        return $buff;
    }

    /*生成签名的随机串---js-sdk */
    public static function createNonceStr($length = 16) {
      $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      $str = "";
      for ($i = 0; $i < $length; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
      }
      return $str;
    }
    /*******************************************************************/

  	/**根据openid 获取用户信息
  	 * [getopenid description]
  	 * @param  [type] $openid [description]
  	 * @return [type]         [description]
  	 */
  	public static function getUser($openid)
  	{	
  		$access_token = Wechat::getToken();
  		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$openid}&lang=zh_CN";
  		$data = file_get_contents($url);
  		$data = json_decode($data,true);
  		return $data;
  	}

	  /**获取用户列表接口
     * [getUserList description]
     * @return [type] [description]
     */
    public static function getUserList()
    {
    	$access_token = Wechat::getToken();
    	$url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token={$access_token}";
    	//调用接口
  		$data = file_get_contents($url);
  		//转成数组  不加true 成对象
  		$data = json_decode($data,true);
  		return $data;
    }

    /**调用天气接口
     * [weather description]
     * @param  [type] $city [description]
     * @return [type]       [description]
     */
  	public static function weather($city)
  	{
  		$url = "http://api.k780.com/?app=weather.future&weaid={$city}&appkey=42256&sign=cfd54d0fd3a6f403990d0446a23818dd&format=json";
  		//调用接口
  		$weathData = file_get_contents($url);
  		//转成数组  不加true 成对象
  		$weathData = json_decode($weathData,true);
  		// var_dump($weathData);
  		$msg = '';
  		foreach ($weathData['result'] as $key => $value) {
  			$msg .= $value['days']." ".$value['citynm']." ".$value['week']." ".$value['weather']." ".$value['temperature']."\n";
  		}
  		return $msg;
  	}

  	/**调用机器人接口
  	 * [robot description]
  	 * @param  [type] $content [description]
  	 * @return [type]          [description]
  	 */
  	public static function robot($content)
  	{
  		$url = "http://www.tuling123.com/openapi/api?key=40f73ef0b6cf4766bcd0640eff6b43cd&info={$content}";
  		//调用接口
  		$data = file_get_contents($url);
  		//转成数组  不加true 成对象
  		$data = json_decode($data,true);
  		$msg = $data['text'];
  		return $msg;
  	}

  	/*手动生成  菜单*/
  	public static function createMenu()
  	{	
  		$access_token=Wechat::getToken();
  		$url ="https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$access_token}";
  		$post_data ='{
  		     "button":[
  		     {    
  		          "type":"click",
  		          "name":"查表白",
  		          "key":"select"
  		      },
  		      {    
  		          "type":"click",
  		          "name":"发表白",
  		          "key":"send"
  		      },	      
  		 }';
  		 $data = Wechat::curlPost($url,$post_data);
  		 return $data;
  	}

	  /**上传素材接口
     * [uploadMedia description]
     * @param  [type] $file    [description]
     * @param  [type] $type    [description]
     * @param  [type] $imgtype [description]
     * @return [type]          [description]
     */
    public static function uploadMedia($file,$type,$imgtype)
    {
        $access_token=Wechat::getToken();

        if ($imgtype == 1) {
        	//临时素材
        	$url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$access_token}&type={$type}";
        }else{ 
        	//永久素材	
        	$url="https://api.weixin.qq.com/cgi-bin/material/add_material?access_token={$access_token}&type={$type}";
        }
        
        //素材路径必须是绝对路径
        // $file = public_path().$file;
        // dd($file);
        $imgPath = new \CURLFile($file); //通过CURLFile处理

        $post_data = [
            'media'=>$imgPath  //素材路径 
        ];
        // dd($post_data);
        //发送请求
        $res=Wechat::curlPost($url,$post_data);
		    // dd($res);
        //返回素材
        if ($res) {
            $res=json_decode($res,true);
            return $res['media_id'];
        }else{
            return false;
        }
    }

    /**推广渠道二维码
     * [createQrcode description]
     * @param  [type] $qrcode_key [description]
     * @return [type]             [description]
     */
    public static function createQrcode($qrcode_key)
    {	
      	$access_token = Wechat::getToken();
      	$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$access_token}";    

        	//临时二维码
    		// $post_data ='{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id":'.$qrcode_key.'}}}';
    		
    		//永久二维码
    		$post_data = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id":'.$qrcode_key.'}}}';

    		$res = Wechat::curlPost($url,$post_data);

    		$res = json_decode($res,true);

    		$ticket = $res['ticket'];
    		//通过ticket换取二维码
    		$codePath = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={$ticket}";

    		//原路径  目的地
    		$qrcode_img ="qrcode/".md5(time().rand(1000,9999)).".jpg";

    		$dd = copy($codePath,$qrcode_img);

    		return $qrcode_img;
    }

    /**批量为用户打标签
     * [allot description]
     * @param  [type] $wechat_tag_id [description]
     * @param  [type] $openid        [description]
     * @return [type]                [description]
     */
   	public static function allot($wechat_tag_id,$openid)
   	{
     		$access_token = Wechat::getToken();
     		$url = "https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token={$access_token}";
     		$data = [];
     		$data['openid_list']=$openid;
     		$data['tagid']=$wechat_tag_id;
     		$data = json_encode($data);
     		// dd($data);
     		$res = Wechat::curlPost($url,$data);
     		// dd($res);
   	}

   	//定时群发任务 
   	public static function cronGroupSend()
   	{
     		$access_token = Wechat::getToken();
     		//发全部  --查全部openid
      	$data = User::where(['status'=>1])->get('openid')->toArray();
      	$openid = [];
      	foreach ($data as $key => $value) {
      		$openid[]=$data[$key]['openid'];
      	}	
      	$url = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token={$access_token}";
      	$post_data = [];
      	$post_data['touser'] = $openid;
      	$post_data['msgtype'] = 'text';
      	$post_data['text']['content'] = '整点报时-11:00';
      	// dd($post_data);
      	$post_data = json_encode($post_data,JSON_UNESCAPED_UNICODE);
      	// dd($post_data);
      	$res = Wechat::curlPost($url,$post_data);
   	}

   	/*群发 文本 */
   	public static function groupsSend($groups_type,$groups_content,$data)
   	{
     		$access_token = Wechat::getToken();

     		if ($groups_type == 1) {
        		//发全部  --查全部openid
        		$data = User::where(['status'=>1])->get('openid')->toArray();
        		$openid = [];
        		foreach ($data as $key => $value) {
        			$openid[]=$data[$key]['openid'];
        		}
        		// dd($touser);
        		
        		$url = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token={$access_token}";
        		$post_data = [];
        		$post_data['touser'] = $openid;
        		$post_data['msgtype'] = 'text';
        		$post_data['text']['content'] = $groups_content;
        		// dd($post_data);
        		$post_data = json_encode($post_data,JSON_UNESCAPED_UNICODE);
        		// dd($post_data);
        		$res = Wechat::curlPost($url,$post_data);
        		dd($res);
      	}else if ($groups_type == 2) {
      		  //发部分	
        		$openid =[];
        		$openid = $data['openid'];

        		$url = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token={$access_token}";
        		$post_data = [];
        		$post_data['touser'] = $openid;
        		$post_data['msgtype'] = 'text';
        		$post_data['text']['content'] = $groups_content;
        		// dd($post_data);
        		$post_data = json_encode($post_data,JSON_UNESCAPED_UNICODE);
        		// dd($post_data);
        		$res = Wechat::curlPost($url,$post_data);
        		dd($res);
      	}else if ($groups_type == 3) {
        		//发标签
        		$url = "https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token={$access_token}";
        		$tag_id = trim($data['tag_id']);

        		$post_data = [];
        		$post_data['filter']['is_to_all'] = false;
        		$post_data['filter']['tag_id'] = $tag_id;
        		$post_data['text']['content'] =$groups_content;
        		$post_data['msgtype'] = 'text';
        		// dd($post_data);

        		$post_data = json_encode($post_data,JSON_UNESCAPED_UNICODE); 
        		$res = Wechat::curlPost($url,$post_data);
        		dd($res);   		
      	}		
   	}

    /**授权接口
     * [getOpenid description]
     * @return [type] [description]
     */
   	public static function getOpenid()
   	{
     		//从session 取openID
     		$openid = session('openid');
     		if (!empty($openid)) {
     			//如果有openid 正常返回openid
     			return $openid;
     		}
     		//没有 在正常访问网页授权流程 获取openID
     		$SERVER_NAME = $_SERVER['HTTP_HOST'];   	//获取域名
     		$REQUEST_URI = $_SERVER['REQUEST_URI'];		//获取参数
     		// dd($_SERVER);   //常量 
     		$redirect_url = urlencode('http://'.$SERVER_NAME.$REQUEST_URI);  //动态组装一个回调地址
     		$code = request('code');  //助手函数
     		if (!$code) {
     			//网页授权当scope=snsapi_urerinfo时才会提示是否授权应用
     			 $redirect_uri = urlencode("http://www.shao2580.top/bind");  //跳转地址
              //跳转到微信给的授权地址  第一步
              $autourl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".self::appId."&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";
              header("location:$autourl"); 
     		}else{
     			// 获取openid
              $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".self::appId."&secret=".self::appSecret."&code=$code&grant_type=authorization_code";
              $row = file_get_contents($url);
              $row = json_decode($row,true);
              $openid = $row['openid'];
              //获取到openid之后 存session
              session(['openid'=>$openid]);
              return $openid;
     		}
   	}

    /*GET传 curl*/
    public static function curlGet($url)
    {
         //初始化
         $curl = curl_init();
         //设置抓取的url
         curl_setopt($curl, CURLOPT_URL,$url);
         //设置获取的信息以文件流的形式返回，而不是直接输出。
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
         //忽略证书  如果访问https网址 需要设置为false
         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
         //执行命令
         $data = curl_exec($curl);
         //关闭URL请求
         curl_close($curl);
         //显示获得的数据
         return $data;
    }

    /*POST传 curl*/
    public static function curlPost($url,$post_data)
    {
         //初始化
         $curl = curl_init();
         //设置抓取的url
         curl_setopt($curl, CURLOPT_URL,$url);
         //设置获取的信息以文件流的形式返回，而不是直接输出。
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
         //设置post方式提交
         curl_setopt($curl, CURLOPT_POST, 1);
         //设置post数据
         curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
         //忽略证书  如果访问https网址 需要设置为false
         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
         //执行命令
         $data = curl_exec($curl);

         //关闭URL请求
         curl_close($curl);
         //显示获得的数据
         return $data;
    }

     /*POST传 curl HTML  heand头*/
    public static function htmlCurlPost($url,$post_data)
    {
         //定义content-type为 xml 主义是数组
         $header[] = "Content-type:text/xml"; 
         //初始化
         $curl = curl_init();
         //设置抓取的url
         curl_setopt($curl, CURLOPT_URL,$url);
         //设置获取的信息以文件流的形式返回，而不是直接输出。
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
         //HTML-header头
         curl_setopt($curl, CURLOPT_HTTPHEADER,$header);  
         //设置post方式提交
         curl_setopt($curl, CURLOPT_POST, 1);
         //设置post数据
         curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
         //忽略证书  如果访问https网址 需要设置为false
         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
         //执行命令
         $data = curl_exec($curl);
         if (curl_error($curl)) {
             print curl_error($curl);
         }

         //关闭URL请求
         curl_close($curl);
         //显示获得的数据
         return $data;
    }
	
}
