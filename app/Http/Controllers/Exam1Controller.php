<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Wechat;
use DB;

class Exam1Controller extends Controller
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
    	// echo $_GET['echostr'];  //接入
    	$this->valid();
    	$xml = file_get_contents('php://input');  //获取数据 
    	file_put_contents('1.txt',$xml);	 //记录到文件
    	$xmlObj = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);  //数据转对象

    	//获取用户信息
    	$openid = $xmlObj->FromUserName;    //获取openid
    	$access_token = Wechat::getToken();  //获取token
    	

    	/*关注/取消*/
		if ($xmlObj->MsgType == 'event' && $xmlObj->Event == 'subscribe') {
			$data = DB::table('user')->get()->toArray();
			 if ($data) {
			 	$userdata = Wechat::getUser($openid);		//获取用户信息
    	
		    	$data = [];
		    	$data['openid'] = $openid;
		    	$data['nickname'] = $userdata['nickname'];
		    	$data['sex'] = $userdata['sex'];
		    	$data['province'] = $userdata['province'];
		    	$data['city'] = $userdata['city'];
		    	$data['headimgurl'] = $userdata['headimgurl'];
		    	// dd($data);
		    	if ($data) {
		    		$res = DB::table('user')->insert($data);
		    	}
			 }
			 
			$this->doSubscribe($xmlObj,$nickname,$sex);
		}

    }

   	/*处理关注*/
	public function doSubscribe($xmlObj,$data)
	{
		$msg = "你好,{$nickname} {$sex}
			欢迎关注shao2580
			发送1,展示班级名称
			发送2,随机展示学员姓名
			查询天气,发送城市名+天气
			发送图片,回复图片
			其他信息,机器人图灵陪你聊...";
			/*回复文本*/	
			Wechat::responseText($xmlObj,$msg);
	}




}
