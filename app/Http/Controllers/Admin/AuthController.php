<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Wechat;
use App\Model\Admin;
use App\Model\Role;
use App\Model\Power;

class AuthController extends Controller
{
	/*管理员列表*/
    public function adminIndex()
    {
    	$data = Admin::paginate(5);
    	
    	return view('admin/adminIndex',compact('data'));
    }

    /*管理员添加*/
    public function adminAdd(Request $request)
    {	
    	if ($request->isMethod('post')) {
    		$data = $request->except('_token');
    		$res = Admin::insert($data);
    		if ($res) {
    			return redirect('adminIndex');
    		}
    	}
    	
    	return view('admin/adminAdd');
    }

    /*角色列表*/
    public function roleList()
    {
    	$data = Role::paginate(5);
    	return view('admin/roleList',compact('data'));
    }

    /*角色编辑*/
    public function roleEdit($role_id)
    {
    	$role_name = Role::where('role_id',$role_id)->first('role_name')->toArray();
    	$role_name = implode(',',$role_name);
    	// dd($role_name);
    	return view('admin/roleEdit',compact('role_name'));
    }

    /*角色添加*/
    public function roleAdd(Request $request)
    {
    	if ($request->isMethod('post')) {
    		$role_name = $request->input();
    		
    		$roleData = Role::where('role_name',$role_name)->count();
    		
    		if (!empty($roleData)) {
    			return ['code'=>0,'msg'=>'角色名已存在'];
    		}else{
    			$res = Role::insert($role_name);
    			if ($res) {
    				return ['code'=>1,'msg'=>'添加成功'];
    			}    			
    		}    		
    	}
    	return view('admin/roleAdd');
    }

    /*权限列表*/
    public function powerList()
    {
    	$data = Power::get();
    	$data = json_decode($data,true);
        $data = Wechat::createTree($data,0,1,'power_id');
       if ($data) {
           foreach ($data as $key => $v) {
               $data[$key]['level'] = str_repeat("☆", $v['level']-1);
           }
       }
    	return view('admin/powerList',compact('data'));
    }

     /*权限添加*/
    public function powerAdd(Request $request)
    {
    	if ($request->isMethod('post')) {
    		$data = $request->input();
    		if ($data) {
    			$res = Power::insert($data);
    			if ($res) {
    				return redirect('powerList');
    			}
    		}
    	}

    	$data = Power::get();	
    	$data = json_decode($data,true);
        $data = Wechat::createTree($data,0,1,'power_id');
       if ($data) {
           foreach ($data as $key => $v) {
               $data[$key]['level'] = str_repeat("☆", $v['level']-1);
           }
       }
       return view('admin/powerAdd',['data'=>$data]);
    }
}
