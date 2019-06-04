<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Wechat;
use App\Model\Qrcode;
use DB;

class QrcodeController extends Controller
{	
	/*渠道添加*/
    public function add()
    {
    	return view('admin/qrcodeAdd');
    }
    /*添加处理*/
    public function doadd(Request $request)
    {
    	if ($request->isMethod('post')) {
           
            $post_data  = $request->except('_token');
            // dd($post_data);
            $qrcode_key = $post_data['qrcode_key'];
            
            // 调用微信接口  生成带参数二位码  下载到本地
            $qrcode_img = Wechat::createQrcode($qrcode_key);
    		// var_dump($dest);die;
    		$post_data['qrcode_img'] = substr($qrcode_img,7);

    		$res = Qrcode::create($post_data);
    		return redirect('qrcode/list');
    	}
    }

   	//列表页
    public function list()
    {
        //俩表联查 --用户在关注状态 
    	// $query = request()->all()
    	$data = DB::table('qrcode')
                ->orderBy('qrcode_id','desc')
                ->paginate(5);
    	// $data = json_decode($data,true);
    	// dd($data);   	
    	return view('admin/qrcodeList',compact('data'));
    }
}
