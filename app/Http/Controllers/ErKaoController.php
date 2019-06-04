<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Wechat;
use App\Model\Subject;
use App\Model\Bank;
use DB;

class ErKaoController extends Controller
{
    public function valid()
    {
    	$echostr = isset($_GET['echostr']) ? $_GET['echostr'] : "";
    	if (!empty($echostr)) {
    		echo $echostr;die;
    	}
    }

    public function index()
    {
    	$this->valid();
    	$xml= file_get_contents('php://input');
    	file_put_contents('1.txt',$xml);
    	$xmlObj = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
    	/*关注*/
    	
    	if ($xmlObj->MsgType == 'event' && $xmlObj->Event == 'subscribe') {
    		$this->doSubscribe($xmlObj);
    	}

    	//判断用户点击菜单
		if ($xmlObj->MsgType == 'event' && $xmlObj->Event == 'CLICK') {
			
			$this->doclick($xmlObj);
		}

		// 用户文本回复
		if ($xmlObj->MsgType == 'text'){
			$this->doText($xmlObj);
		}
    }

    //处理文本
	public function doText($xmlObj)
	{
			//得到用户留言
			$content = trim($xmlObj->Content);
			$openid = $xmlObj->FromUserName;
		$data = Bank::where(['openid'=>$openid])->orderBy('bank_id','desc')->first();
			//如果没查到
			if (!$data) {
				$msg = '请点击按钮答题';
		    	//回复消息
		    	$this->responseText($xmlObj,$msg);
			}
			if ($data->is_success !== 0) {
				$msg = '这道题您已答过';
		    	//回复消息
		    	$this->responseText($xmlObj,$msg);
			}
			//对比答案
			if ($data->success == $content) {
				//正确
				$is_success = 1;
				$msg = '恭喜回答正确';
		    	//回复消息
		    	$this->responseText($xmlObj,$msg);

			}else{
				//错误
				$is_success = 2;
				$msg = '对不起您的回答错误';
		    	//回复消息
		    	$this->responseText($xmlObj,$msg);
		    	
			}
			//修改
			DB::table('bank')
            ->where(['openid'=>$openid,'subject_id'=>$data->subject_id])
            ->orderBy('bank_id','desc')
            ->update(['is_success' => $is_success]);
	
	}

    /*处理点击*/
	public function doclick($xmlObj)
	{
		$openid = $xmlObj->FromUserName;
		//答题
		if ($xmlObj->EventKey == '答题') {
			//记录当前用户行为
				
			//随机出题
			$this->rand($xmlObj);				
		}
			//我的成绩
		if ($xmlObj->EventKey == '我的成绩') {
			//记录当前用户行为
				
			//查询用户正确答题 和 错误答题数
			$this->select($xmlObj);
					
		}	
	}

	/*查询用户正确答题 和 错误答题数*/
	public function select($xmlObj)
	{
		$openid = $xmlObj->FromUserName;
		$error = Bank::where(['openid'=>$openid,'is_success'=>1])->count();  //错误题数量
		$success = Bank::where(['openid'=>$openid,'is_success'=>2])->count();  //正确数量
		$count = $error+$success;
		$msg = "您总共答题：".$count." 道
		错误题数：".$error." 道
		正确题数：".$success." 道";
		//回复消息
	    $this->responseText($xmlObj,$msg);
		
	}

	/*随机数据库抽题*/
   public function rand($xmlObj)
   {
   		$data = Subject::inRandomOrder()->first()->toArray();
   		$insertData = [];
   		$insertData['openid'] = $xmlObj->FromUserName;
   		$insertData['subject_id'] =$data['subject_id'];
   		$insertData['success'] =$data['success'];

   		$res = Bank::insert($insertData);

   		$subject_name = $data['subject_name'];
   		if ($res) {
   			$msg = "$subject_name
   			A：正确   B：错误";
	    	//回复消息
	    	$this->responseText($xmlObj,$msg);
   		}
   }


     /*处理关注*/
    public function doSubscribe($xmlObj)
    {
    	$msg = '你好，欢迎关注';
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
}
