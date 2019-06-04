<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Model\Wechat;
use DB;

class LoginController extends Controller
{
    
    //登录 生成二维码         
    public function login()
    {
        //调用联图接口 生成二位码
        $id = time().rand(1000,9999);
        $createUrl = "http://www.shao2580.top/login/wechat?id=".$id;

        /*验证配置*/
        $data = Wechat::getSignPackage();

        return view('admin/login',['createUrl'=>$createUrl,'id'=>$id,'data'=>$data]);
    }
  
    //授权 -扫码登录
    public function wechat(Request $request)
    {   
        $id = $request->input('id');

        //调用授权接口  获得openID
        $openid = Wechat::getOpenid();

        //把当前openID 写入缓存中 1分钟过期
        Cache::put($id,$openid,120);
       
        return "<h1 style='color:red;'>扫码登录成功,请等待电脑响应！<h1>";die;
    }

    //扫码 --检测是否跳转
    public function checkLogin(Request $request)
    {
        //读缓存
        $id = $request->input('id');
        //获取openID
        $openid = Cache::get($id);
        // dd($openid);
        //通过openid 查询数据库
        if ($openid) {
            $admin = DB::table('admin')
                    ->where(['openid'=>$openid])
                    ->get()->toArray();
            // dd($admin);
            if ($admin) {
                
               //登陆成功 存session
                session(['admin'=>$admin]);
                //返回json 数据
                $returnMsg = [
                    'ret' =>1,
                    'msg'=>'登陆成功'
                ];
                return json_encode($returnMsg); 
            }else{
                 $returnMsg = [
                    'ret' =>0,
                    'msg'=>'登陆失败'
                ];
                return json_encode($returnMsg); 
            }
            
        }

    }

    //账号-密码-登录处理
    public function dologin(Request $request)
    {
        $data = $request->input();
        $code = session('code');
        //验证码校验
        if ($data['code'] != $code) {
            return json_encode(['code'=>3,'msg'=>'验证码错误']);
        }
        //登录查询数据库
        $name = $data['name'];
        $password = $data['password'];
         $admin = DB::table('admin')
                ->where(['name'=>$name,'password'=>$password])
                ->get()->toArray();
        if ($admin) {
            //登陆成功
            session(['admin'=>$admin]);
            return json_encode(['code'=>1,'msg'=>'登陆成功']);
        }else{
            return json_encode(['code'=>0,'msg'=>'账号或密码错误，请从新登陆']);
        }
        
    }

    //发送 模板消息 验证码
    public function send()
    {
        $name = request()->input('name');
        $password = request()->input('password');

        //查詢用戶是否存在
        $admin = DB::table('admin')->where(['name'=>$name,'password'=>$password])->first();

        if (!$admin) {
            return ['code'=>0,'msg'=>'账号或密码错误,请从新输入'];
        }
        $openid = $admin->openid;
       $code = rand(1000,9999);
       session(['code'=>$code],300);

       //调用发送模板信息接口
       $template_id = "odUqCe6Jnf5guAeSZXLKPS-BrHZiA0mhtpgdk14Ms6U";
       $templateData = [
            'name'=>[
                'value'=>$name,
                'color'=>'#173177'
            ],
            'code'=>[
                'value'=>$code,
                'color'=>'#173177'
            ]
       ];       
       // dd($templateData);
       $res = Wechat::template($openid,$template_id,$templateData);
       if ($res) {
           return json_encode(['ret'=>1,'msg'=>'发送成功','code'=>$code]);
       }else{
           return json_encode(['ret'=>0,'msg'=>'请确定是否绑定微信号']); 
       }
    }

   
}
