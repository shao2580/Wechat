<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Wechat;
use DB;

class AuthController extends Controller
{
    public function bind()
    {   
        //跳转到微信给的授权地址  第一步   组装跳转地址
        $openid = Wechat::getOpenid();
        
        //授权成功，继续展示
        return view('admin/bind');
    }

    public function dobind()
    {
         $openid = Wechat::getOpenid();
    
        $name = request()->input('name');
        $password = request()->input('password');
        //查库 看有没有绑定
        $res = DB::table('admin')->where(['name'=>$name,'password'=>$password])->first();
        
        if ($res =='') {
            echo  "<h1 style='color:red;'>账号或密码错误,请重新登录！<h1>";die;
        }else if ($res && $res->openid != 0) {
            echo  "<h1 style='color:red;'>账号已绑定！<h1>";die;
        }
        
         //修改openid
        $res = DB::table('admin')
            ->where(['name'=>$name,'password'=>$password])
            ->update(['openid'=>$openid]);
        if ($res) {
            echo "<h1 style='color:red;'>绑定成功！<h1>";die;
        }
        
    }

    public function auth()
    {
        //根据code 获取access_token 第二步
         $openid = Wechat::getOpenid();
         
        //跳转到展示页
        return redirect('/bind');

    	// //通过access_token openid 调接口查用户信息 第三步
    	// $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN";
    	// $data = file_get_contents($url);
    	// $data = json_decode($data,true);
    	// dd($data);
    }
}
