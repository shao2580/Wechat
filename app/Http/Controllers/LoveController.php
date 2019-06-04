<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Wechat;
use DB;

class LoveController extends Controller
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
    	/*接收用户发送xml数据 post方式*/
		$xml = file_get_contents('php://input');
		/*把xml数据记录到本地文件里*/
		file_put_contents('1.txt',$xml);
		/*把xml转为对象*/
		$xmlObj=simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
		
		//调生成菜单接口
		Wechat::createMenu();
		$openid = $xmlObj->FromUserName;
		//判断用户点击菜单
		if ($xmlObj->MsgType == 'event' && $xmlObj->Event == 'CLICK') {
			
			//查表白
			if ($xmlObj->EventKey == 'select') {
					//记录当前用户行为
					$this->insertAct($openid,'查表白');
					
					Wechat::responseText($xmlObj,'请输入要-查询-表白人的名字');	
				}
			//发表白
			if ($xmlObj->EventKey == 'send') {
					//记录当前用户行为
					$this->insertAct($openid,'发表白');
					
					Wechat::responseText($xmlObj,'请输入要-发-表白人的名字');
				}	
		}

		//用户发文本
		if ($xmlObj->MsgType == 'text') {
			//得到用户留言
			$content = trim($xmlObj->Content);
			//查一下当前用户上一步动作
			$cat_name = $this->selectAct($openid);

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
				default:
					Wechat::responseText($xmlObj,'请点击菜单');
					break;
			}
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
		if ($carData) {
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
		DB::table('love')->where(['love_id'=>$love_id])->updata(['content'=>$content]);

   }
   
}
