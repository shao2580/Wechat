<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Wechat;
use DB;

class MenuController extends Controller
{
   
    //列表页
    public function list()
    {
    	// $query = request()->all()
    	$data = DB::table('menu')->orderBy('menu_id','desc')->get();
    	// dd($data);
    	$data = json_decode($data,true);
    	// dd($data);
    	 $data = Wechat::createTree($data);
        // dd($data);
       if ($data) {
           foreach ($data as $key => $v) {
               $data[$key]['level'] = str_repeat("☆", $v['level']-1);
           }
       }
    	return view('admin/menuList',compact('data'));
    }
    //添加页面
    public function add()
    {   
        $data = DB::table('menu')->get();
        $data = json_decode($data,true);
        // dd($data);
        $data = Wechat::createTree($data);
        // dd($data);
       if ($data) {
           foreach ($data as $key => $v) {
               $data[$key]['level'] = str_repeat("☆", $v['level']-1);
           }
       }
       
    	return view('admin/menuAdd',['data'=>$data]);
    }
    //添加处理
      public function doadd(Request $request)
    { 
    	if ($request->isMethod('post')) {
    		
            $data = $request->except('_token');
            $res = DB::table('menu')->where(['parent_id'=>0])->get();
            $res = json_decode($res,true);
            
           if (count($res)>3) {
                 echo '一级菜单最多有三个'; die;
            }

             $res1 = DB::table('menu')->insert($data);
            if ($res1) {
                  return redirect('menu/list');
            } 
              
                              
                
            			
    	}
    }

    /**一键同步  一级菜单菜单   要改路由 - 跳转地址
     * [create_menu description]
     * @return [type] [description]
     */
    public function create_menu1()
    {
        $access_token = Wechat::getToken();
        //调用创建菜单接口
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$access_token}";
        //组装数据 查询数据库 =》 拼接处理 =》 转成json格式
        
        $menu_data = [];
        $typeArr = ['click'=>'key','view'=>'url']; //菜单类型
        
        $data = DB::table('menu')->get();
        // dd($data);
        $data = json_decode($data,true);
       // dd($data);
        //循环数据
        foreach ($data as $key => $value) {
            // dd($value);
            $menu_data['button'][] = [
                'type'=>$value['menu_type'],
                'name'=>$value['menu_name'],
                $typeArr[$value['menu_type']]=>$value['menu_key']
            ];
        }  
        //数组转为json
        $menu_data = json_encode($menu_data,JSON_UNESCAPED_UNICODE);  // 取出转移\字符
        //调上传接口
        $res = Wechat::curlPost($url,$menu_data);
        // var_dump($res);
        
    }

    /**一键同步  多级菜单 
     * [create_menu description]
     * @return [type] [description]
     */
    public function create_menu()
    {
        $access_token = Wechat::getToken();
        //调用创建菜单接口
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$access_token}";
        //组装数据 查询数据库 =》 拼接处理 =》 转成json格式
    
        //查数据库 -根据 parent_id 
        $data = DB::table('menu')->where('parent_id',0)->get();
        $data = json_decode($data,true);
        // dd($data);
        $menu_data = [];
        $typeArr = ['click'=>'key','view'=>'url']; //菜单类型

        foreach ($data as $key => $value) {
            if (empty($value['menu_type'])) {
                $menu_data['button'][$key]['name'] = $value['menu_name'];
                //通过一级查询二级
                 $data = DB::table('menu')->where('parent_id',$value['menu_id'])->get();
                 $data = json_decode($data,true);
                foreach ($data as $k => $v) {
                    $menu_data['button'][$key]['sub_button'][] = [
                        'type'=>$v['menu_type'],
                        'name'=>$v['menu_name'],
                         $typeArr[$v['menu_type']] => $v['menu_key']
                    ];
                }
            }else{
                $menu_data['button'][] = [
                    'type'=>$value['menu_type'],
                    'name'=>$value['menu_name'],
                    $typeArr[$value['menu_type']]=>$value['menu_key']
                ];
            }
        }
        // dd($menu_data);
        //数组转为json
        $menu_data = json_encode($menu_data,JSON_UNESCAPED_UNICODE);  // 取出转移\字符
        //调上传接口
        $res = Wechat::curlPost($url,$menu_data);
        if ($res) {
            return redirect('menu/list');
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
