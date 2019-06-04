<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;		//粉丝表
use App\Model\Tag;      //标签表
use App\Model\TagUser;      //标签-用户-关系表
use App\Model\Wechat;	
use DB;

class UserController extends Controller
{
	//获取所有已关注用户
	public function addWechatUser()
	{
		die;
		//调接口获取用户列表
		$data = Wechat::getUserList();

		if ($data['total']>0) {
			//循环获取用户信息  入库
			foreach ($data['data']['openid'] as $key => $value) {
				//通过openid  获取用户信息 入库
				$userinfo = Wechat::getUser($value);
				
				$insertData = [
					'openid'=>$value,
					'nickname'=>$userinfo['nickname'],
					'sex'=>$userinfo['sex'],
					'province'=>$userinfo['province'],
					'city'=>$userinfo['city'],
					'headimgurl'=>$userinfo['headimgurl'],
					'subscribe_time'=>$userinfo['subscribe_time']
				];

				$res = User::insert($insertData);

			}
		}

	}

	/*粉丝列表*/
    public function index()
    {	
    	$data = User::where(['status'=>1])->orderBy('user_id','desc')->paginate(5);

    	return view('admin/userList',['data'=>$data]);
    }

    /*标签添加*/
    public function add()
    {
    	return view('admin/tagAdd');
    }

    /*标签添加处理*/
    public function doadd()
    {	
    	$tag_name = request()->except('_token');
    	$tag_name = implode(",", $tag_name);
		
    	//调用创建标签接口
    	$res= User::createTag($tag_name);
    	$res = json_decode($res,true);
		
    	$data =[];
    	$data['tag_name'] = $res['tag']['name'];
    	$data['wechat_tag_id'] = $res['tag']['id'];
    	if ($res) {
    		$res = Tag::insert($data);
    		if ($res) {
    			return redirect('tag/list');
    		}
    	}
    }


    /*标签名唯一性验证*/
    public function checkTagName()
    {  
        $tag_name = request()->tag_name;
        if (!$tag_name) {
            return ['code'=>0,'msg'=>'请输入标签名'];
        }

        $count = Tag::where('tag_name',$tag_name)->count();
        if ($count) {
            return ['code'=>0,'msg'=>'标签名已存在'];
        }

    }

    /*标签列表*/
    public function list()
    {	
    	/*一天500次接口次数---获取列表入库*/
    	// User::getTag();

    	// dd($userdata);
    	$data = Tag::orderBy('tag_id','desc')->paginate(5);
    	return view('admin/tagList',compact('data'));
    }

    /*分配用户--表*/
    public function allotUser($tag_id)
    {
    	//查标签表
    	$tag = Tag::where('tag_id',$tag_id)->first();

        $openid_list = $tag->openid_list;
        // dd($openid);
        if (!$openid_list) {
            $openid_list = [];
        }

    	//查粉丝用户表
    	$user = User::where(['status'=>1])->orderBy('user_id','desc')->paginate(5);

    	//关系表
    	$tagUser = TagUser::where('tag_id',$tag_id)->get()->toArray();

    	$data =[];
    	foreach ($tagUser as $key => $val) {
    		$data[] = $val['openid'];
    	}
    	return view('admin/allotUser',compact('tag','user','data','openid_list'));
    }

    /*批量为用户打标签*/
    public function doallot()
    {
    	$data = request()->except('_token');
    	// dd($data);
    	
    	$tag_id = $data['tag_id'];
    	$wechat_tag_id = $data['wechat_tag_id'];
    	// 循环入库  --并同步到微信服务器
        $insertData = [];
    	foreach ($data['openid'] as $key => $value) {
    		$insertData = [
    			'tag_id'=>$tag_id,
    			'openid'=>$value
    		];
          
    	}
    	$res = TagUser::insert($insertData);
        // dd($insertData['openid']);
    	//调用批量为用户打标签接口
    	Wechat::allot($wechat_tag_id,$data['openid']);

        return redirect('tag/list');
   		
    }


    /*标签删除*/
    public function del($wechat_tag_id)
    {
    	
    	$qrcode_key = $wechat_tag_id;
    	$data = User::deleteTag($qrcode_key);
    	
    	$res = Tag::where('wechat_tag_id',$qrcode_key)->delete();
    	if ($res) {
    		return redirect('tag/list');
    	}	
    }



}
