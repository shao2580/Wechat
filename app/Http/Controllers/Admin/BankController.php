<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Subject;

class BankController extends Controller
{
	/*添加*/
    public function add(Request $request)
    {
    	if ($request->isMethod('post')) {
    		$data = $request->input();
    		if ($data) {
    			$res = Subject::insert($data);
    			if ($res) {
    				return redirect('bank/add');
    			}
    		}
    	}
    	return view('admin/bankAdd');
    }

    /*列表*/
    public function list(Request $request)
    {
    	$data = Bank::get();

    	return view('admin/bankList',compact('data'));

    }

}
