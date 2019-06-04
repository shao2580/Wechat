<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\Wechat;
use DB;

class GroupsController extends Controller
{
    public function send()
    {
    	//查粉丝用户表
    	$user = User::where(['status'=>1])->orderBy('user_id','desc')->get();
        $tag = DB::table('tag')->get();

    	return view('admin/groupsSend',compact('user','tag'));
    }

    public function dosend()
    {
    	$data = request()->except('_token');
    	
    	//发送的内容
    	$groups_content = $data['groups_content'];
    	$groups_type = $data['groups_type'];

        //调用群信息接口 
       Wechat::groupsSend($groups_type,$groups_content,$data);

    	
    }
}
