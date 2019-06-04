<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Wechat;
use App\Model\User;
use App\Model\Subject;
use App\Model\Bank;
use DB;


class WechatController extends Controller
{
	/*定时群发*/
	public function cronGroupSend()
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
    	$post_data['text']['content'] = '整点报时-23:30，该睡觉了，晚安！';
    	// dd($post_data);
    	$post_data = json_encode($post_data,JSON_UNESCAPED_UNICODE);
    	// dd($post_data);
    	$res = Wechat::curlPost($url,$post_data);
   	}

   	/*接入*/
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
    	/*接收用户发送xml数据 post方式*/
		$xml = file_get_contents('php://input');
		/*把xml数据记录到本地文件里*/
		file_put_contents('1.txt',$xml);
		/*把xml转为对象*/
		$xmlObj=simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
		
		/*获取用户信息*/
		$access_token =Wechat::getToken();

		$openid=$xmlObj->FromUserName;
		$data = Wechat::getUser($openid);

		/*关注/取消*/
		if ($xmlObj->MsgType == 'event' && $xmlObj->Event == 'subscribe') {
			$nickname=$data['nickname'];               //昵称
			$sex = $data['sex']== 1 ? '帅哥' : '靓妹';  //性别
			
			$count = User::where('openid',$openid)->count();
			if ($count) {
				//通过openid修改库里状态
				$qr_scene=$data['qr_scene'];
				$update = [
					'status'=>1,
					'qr_scene'=>$qr_scene
				];
				$res =DB::table('user')->where(['openid'=>$openid])->update($update);
			}else{
					$userdata = [
					'openid'=>$openid,
					'nickname'=>$nickname,
					'sex'=>$data['sex'],
					'province'=>$data['province'],
					'city'=>$data['city'],
					'headimgurl'=>$data['headimgurl'],
					'subscribe_time'=>$data['subscribe_time'],
					'qr_scene'=>$data['qr_scene']
				];
				//数据入库
				$res = User::insert($userdata);
			}
			
			//判断渠道标识  渠道表自增 关注数量
			if (!empty($data['qr_scene'])) {
				DB::table('qrcode')->where('qrcode_key',$data['qr_scene'])->increment('attention');
			}
			$this->doSubscribe($xmlObj,$nickname,$sex);
		}

		//取消关注
		if ($xmlObj->MsgType == 'event' && $xmlObj->Event == 'unsubscribe') {
			$this->doUnSubscribe($xmlObj);
		}

		// //调生成菜单接口
		// Wechat::createMenu();
		
		//判断用户点击菜单
		if ($xmlObj->MsgType == 'event' && $xmlObj->Event == 'CLICK') {
			
			$this->doclick($xmlObj);
		}

		/*回复文本*/
		if ($xmlObj->MsgType =='text') {
			$this->dotext($xmlObj);			
		}

		/*回复图片信息*/
		if($xmlObj->MsgType == 'image') 
		{	
			$media_id = $this->randImg();
			// dd($media_id);
			$this->doimage($xmlObj,$media_id);
		}
	}	
	
	/*处理关注*/
	public function doSubscribe($xmlObj,$nickname,$sex)
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

	/*取消关注*/
	public function doUnSubscribe($xmlObj)
	{
		//修改用户状态
		$openid = $xmlObj->FromUserName;
		//通过openid修改库里状态
		$res =DB::table('user')->where(['openid'=>$openid])->update(['status'=>0]);
		// dd($res);
		//获取当前用户关注的渠道
		$qr_scene =DB::table('user')->where(['openid'=>$openid])->value('qr_scene');
		//渠道关注量递减
		 DB::table('qrcode')->where(['qrcode_key'=>$qr_scene])->decrement('attention');
	}

	/*随机抽图片*/
	public function randImg()
	{
		$file = DB::table('fodder')->get('media_id')->toArray(); 

		$key = array_rand($file);
		$media_id = $file[$key];
		$media_id = json_encode($media_id,true);
		$media_id = json_decode($media_id,true);
		$media_id = $media_id['media_id'];
		// dd($media_id);
		return $media_id;
	}

	/*处理图片*/
	public function doimage($xmlObj,$media_id)
	{
		echo "<xml>
				  <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
				  <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
				  <CreateTime>".time()."</CreateTime>
				  <MsgType><![CDATA[image]]></MsgType>
				  <Image>
				    <MediaId><![CDATA[".$media_id."]]></MediaId>
				  </Image>
				</xml>";
	}

	//处理文本
	public function dotext($xmlObj)
	{
		//得到用户留言
			
			$openid = $xmlObj->FromUserName;
			//查一下当前用户上一步动作
			$cat_name = $this->selectAct($openid);
			$content = trim($xmlObj->Content);

			//根据用户最后一次动作，执行响应处理
			switch ($cat_name) {
				case '发表白':
					//将人名入库
					$this->insertLoveName($openid,$content);
					//记录动作
					$this->insertAct($openid,'输入表白名字');
					//回复文本
					Wechat::responseText($xmlObj,'请输入表白内容');
					break;
				case '输入表白名字':
					//将表白内容入库
					$this->insertLoveContent($openid,$content);
					//回复文本
					Wechat::responseText($xmlObj,'表白成功');
					break;
				case '查表白':
					//查询当前用户是否有表白
					$lovemsg = $this->selectLove($content);
					//回复文本
					Wechat::responseText($xmlObj,$lovemsg);
					break;
				case '答题':
					
					$data = Bank::where(['openid'=>$openid])->orderBy('bank_id','desc')->first();
					//如果没查到
					if (!$data) {
						$msg = '请点击按钮答题';
				    	//回复消息
				    	Wechat::responseText($xmlObj,$msg);
					}
					if ($data->is_success !== 0) {
						$msg = '这道题您已答过';
				    	//回复消息
				    	Wechat::responseText($xmlObj,$msg);
					}
					//对比答案
					if ($data->success == $content) {
						//正确
						$is_success = 1;
						$msg = '恭喜回答正确';
				    	//回复消息
				    	Wechat::responseText($xmlObj,$msg);

					}else{
						//错误
						$is_success = 2;
						$msg = '对不起您的回答错误';
				    	//回复消息
				    	Wechat::responseText($xmlObj,$msg);
				    	
					}
					//修改
					DB::table('bank')
		            ->where(['openid'=>$openid,'subject_id'=>$data->subject_id])
		            ->orderBy('bank_id','desc')
		            ->update(['is_success' => $is_success]);

					break;
				default:
					$this->doOrdertext($xmlObj);
					break;
			}

			

	}

	/*处理其他文本*/
	public function doOrdertext($xmlObj)
	{
		/*接收用户留言*/
			$content=trim($xmlObj->Content);
			$tq1='天气';
			$city = '';
			$city1='';
			if (substr($content,-6) == $tq1) {
				$tq = substr($content,-6);
				$city = substr($content,0,-6);
				$city1 = $city.$tq;
			}
			if ($content == '1') {
				$msg = '1810A';
				Wechat::responseText($xmlObj,$msg);
			}elseif($content == '2'){
				$arr = array('杨绍峰','刘瑞','王鑫飞','张三','李四','王五','赵六');
				$res = array_rand($arr,1);
				$msg = $arr[$res];
				Wechat::responseText($xmlObj,$msg);
			}else if ($content == $city1) {
				if ($city1 == '天气') {
					$msg = '查询天气必须 城市名+天气';
					Wechat::responseText($xmlObj,$msg);
				}else{
					/*天气回复*/
					$msg = Wechat::weather($city);			
					Wechat::responseText($xmlObj,$msg);
				}
				
			}else{	
				/*机器人回复*/
				$msg = Wechat::robot($content);
				Wechat::responseText($xmlObj,$msg);
			}
					
	}

	/*处理点击*/
	public function doclick($xmlObj)
	{
		$openid = $xmlObj->FromUserName;       
		//查表白
		if ($xmlObj->EventKey == '查表白') {
					//记录当前用户行为
					$this->insertAct($openid,'查表白');
					
					Wechat::responseText($xmlObj,'请输入要-查询-表白人的名字');	
				}
		//发表白
		if ($xmlObj->EventKey == '发表白') {
				//记录当前用户行为
				$this->insertAct($openid,'发表白');
				
				Wechat::responseText($xmlObj,'请输入要-发-表白人的名字');
		}
		//答题
		if ($xmlObj->EventKey == '答题') {
			//记录当前用户行为
			$this->insertAct($openid,'答题');	
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
	    Wechat::responseText($xmlObj,$msg);
		
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
	    	Wechat::responseText($xmlObj,$msg);
   		}
   }

	//查询表白
   public function selectLove($username)
   {
   		$loveData = DB::table('love')
   				->where(['username'=>$username])
   				->get()->toArray();
   		$msg = '没人给他表白';
   		if (!empty($loveData)) {
   			//如果有人给他表白，展示次数和内容
   			$msg = "查询用户：".$username."表白次数：".count($loveData)."表白内容：\r\n";
   			foreach ($loveData as $key => $value) {
   				$msg.= $value->content."\r\n";
   			}
   		}
   		return $msg;
   }

   //记录当前用户行为
   public function insertAct($openid,$cat_name)
   {
   		$insertData = [
			'openid'=>$openid,
			'cat_name'=>$cat_name,
			'add_time'=>time()
			];
		DB::table('cat')->insert($insertData);	
   }

   //查询当前用户最后一次动作
   public function selectAct($openid)
   {
		$carData =DB::table('cat')
				->where(['openid'=>$openid])
				->orderBy('cat_id','desc')
				->first();
		if ($carData && (time()-$carData->add_time)<60) {
			return $carData->cat_name;
		}
   }

   //将人名入库
   public function insertLoveName($openid,$username)
   {
   		$insertData = [
   			'openid'=>$openid,
   			'username'=>$username,
   			'content'=>''
   		];
   		DB::table('love')->insert($insertData);
   }

   //将表白内容入库
   public function insertLoveContent($openid,$content)
   {	
   		//找到当前openid最后一次表白的用户 更改其表白内容
   		$loveData =DB::table('love')
				->where(['openid'=>$openid])
				->orderBy('love_id','desc')
				->first();
		$love_id = $loveData->love_id;
		//更改内容
		DB::table('love')->where(['love_id'=>$love_id])->update(['content'=>$content]);

   }
	

	


}
