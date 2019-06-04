<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Coupon;
use App\Model\Coulist;

class CouponController extends Controller
{
	/*优惠券添加*/
    public function add(Request $request)
    {	
    	if ($request->isMethod('post')) {
    		$data = [];
    		$data['coupon_name'] = $request->coupon_name;
    		$data['coupon_num'] = $request->coupon_num;
    		$data['condition'] = $request->condition;
    		$data['money'] = $request->money;
    		$data['add_time'] = time();

    		if ($data) { 
    			$finalTime =Coupon::orderBy('add_time','desc')->get('add_time')->toArray();
    			$finalTime = implode(',',$finalTime);
    		
    			if ((time() - $finalTime) < 1) {   //24小时改成 86400
    				$res = Coupon::insert($data);
	    			if ($res) {
	    				echo '添加成功';die;    			
	    			}		
    			}else{
    				echo '一天只能添加一次优惠券活动';die;
    			}		
    			
    		}
    	}
    	
    	return view('admin/couponAdd');
    }
    /*列表页*/
    public function list()
    {
    	return view('admin/couponList');
    }


    /*抽奖页面*/
    public function lottery(Request $request)
    {	
    	$admin = session('admin');
    	// dd($admin);
    	// $openid = $admin[0]->openid;
    	if ($request->isMethod('post')) {
    		$data = $request->all();
    		//查中奖表 
    		$coulist = Coulist::where(['openid'=>$openid])->count();
    		//查时间 
    		$coutime = Coulist::where(['openid'=>$openid])->first()->toArray();
    		$coutime = implode(',',$coutime);
 
    		if ($coulist < 3 || (time()-$coutime) > 86400) {
    		 	//查优惠券库 - 随机抽去一天条
	    		$res = Coupon::inRandomOrder()->first()->toArray();
	    		if ($res) {
	    			$data = [];
	    			$data['openid'] = $openid;
	    			$data['add_time'] = time();
	    			$res = Coulist::insert($data);
	    			if ($res) {
	    				echo '您已中奖';die;
	    			}
	    		}
    		 }else{
    		 	echo '一天只能抽三次，您已用完';
    		 } 

    		
    	}
    	return view('admin/lottery');//,['openid'=>$openid]
    }
}
