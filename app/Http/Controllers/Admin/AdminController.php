<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Wechat;
use App\Model\Qrcode;
use DB;

class AdminController extends Controller
{   
    //主页显示图表
    public function admin()
    {
        /*图表统计*/
        $data =Qrcode::all();
        $qrcode_name = '';
        $attention = '';
        foreach ($data as $key => $value) {
            $qrcode_name .="'".$value['qrcode_name']."',";
            $attention .=$value['attention'].","; 
        }

        $qrcode_name = rtrim($qrcode_name,',');
        $attention = rtrim($attention,',');


        /*****没有完善*** 前台**(admin.blade.php) *****/
        /*验证配置*/
        $data = Wechat::getSignPackage();

        /*微信支付 调用 --获得 付款二维码*/
        $code_url = Wechat::getCodeUrl();
        // dd($resObj);
        return view('admin/admin',['qrcode_name'=>$qrcode_name,'data'=>$data,'attention'=>$attention,'code_url'=>$code_url]);
    }
     //主页
    public function index()
    {
        return view('admin/index');
    }
    //列表页
    public function list()
    {
    	// $query = request()->all()
    	$data = DB::table('fodder')->orderBy('id','desc')->paginate(5);
    	// dd($data);
    	// $data = json_decode($data,true);
    	// dd($data);
    	
    	return view('admin/fodderList',compact('data'));
    }
    //添加页面
    public function add()
    {
    	return view('admin/fodderAdd');
    }
    //添加处理
      public function doadd(Request $request)
    { 
    	if ($request->isMethod('post')) {
    		if (!$request->hasfile || !$request->file('file')->isValid()) {
    			//报错
    		}
            //上传图片到本地
            $path = $request->file->store(date('Ymd'));
            //上传成功后，把图片同步到微信的到media_id
            $file = public_path()."/uploads/".$path;    //线上路径
            // var_dump($file);
            
           $imgtype = $request->imgtype;

            //调用上传素材
            $media_id = Wechat::uploadMedia($file,'image',$imgtype);		
    		
            // var_dump($media_id);
    		if ($media_id) {
    			//添加到数据库
    			$post_data = $request->except('_token');
    			// dd($post_data);
    			$post_data['img_url'] = $path;
    			$post_data['media_id'] = $media_id;
    			$post_data['add_time'] = time();
    			// dd($post_data);
    			$res = DB::table('fodder')->insert($post_data);
    			if ($res) {
    				return redirect('list')->with('msg','添加成功');
    			}
    		}
    		
    			
    	}
    }

    //删除图片
    public function del($id)
    {
        echo 111;
        if ($id) {
           $res = DB::table('fodder')->where(['id'=>$id])->delete();

            if ($res) {
                return redirect('list');
            }
         
        }
        
    }
    
}
