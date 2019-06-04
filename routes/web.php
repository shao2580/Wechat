<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::any('/wx','WechatController@index');  //测试微信
Route::any('/love','LoveController@index');  //表白墙
Route::any('/exam1','Exam1Controller@index');  //关注
Route::any('/yikao','YiKaoController@index');  //周考--查商品--回复模板信息
Route::any('/erkao','ErKaoController@index');  //周考--答题


Route::any('/login','Admin\LoginController@login');			//登录页  调用联图接口生成二维码  1--扫码登录
Route::any('/send','Admin\LoginController@send');			//登录页--发送验证码
Route::any('/dologin','Admin\LoginController@dologin');		//处理登录页

Route::any('/login/wechat','Admin\LoginController@wechat');          //授权页  	   2--扫码登录
Route::any('/login/checklogin','Admin\LoginController@checkLogin');  //检测是否跳转  3--扫码登录

Route::any('/bind','AuthController@bind');   	//网页授权链接 第一步 会跳地址 绑定
Route::any('/auth','AuthController@auth');   	//网页授权链接 第二步 
Route::any('/dobind','AuthController@dobind');   //处理绑定

Route::any('coupon/list','Admin\CouponController@list');       //优惠券列表
Route::any('lottery','Admin\CouponController@lottery');       //抽奖页

Route::prefix('/')->middleware('IsLogin')->group(function(){
// Route::prefix('/')->group(function(){
	Route::get('index','Admin\AdminController@admin');  	//主页图表

	Route::any('adminIndex','Admin\AuthController@adminIndex');   	 //管理员列表
	Route::any('adminAdd','Admin\AuthController@adminAdd'); 		 //管理员添加

	Route::any('roleList','Admin\AuthController@roleList'); 		 //角色列表
	Route::any('roleEdit/{role_id}','Admin\AuthController@roleEdit'); 		 //角色编辑
	Route::any('roleAdd','Admin\AuthController@roleAdd'); 			 //角色添加

	Route::any('powerList','Admin\AuthController@powerList'); 		 //权限列表
	Route::any('powerAdd','Admin\AuthController@powerAdd'); 		 //权限添加

	Route::get('admin','Admin\AdminController@index');  	//主页
	Route::get('list','Admin\AdminController@list');		//素材列表	
	Route::get('add','Admin\AdminController@add');			//素材添加页
	Route::post('doadd','Admin\AdminController@doadd');		//素材处理页
	Route::get('del/{id}','Admin\AdminController@del');		//素材删除页

	Route::get('auth','Admin/AuthController@add');			//权限添加

	Route::get('menu/add','Admin\MenuController@add');				//菜单添加页	
	Route::post('menu/doadd','Admin\MenuController@doadd');			//菜单处理页
	Route::get('menu/list','Admin\MenuController@list');			//菜单列表	
	Route::get('menu/create_menu','Admin\MenuController@create_menu');			//一键同步菜单	
	Route::get('menu/update/{id}','Admin\MenuController@update');	//菜单修改页

	Route::get('qrcode/add','Admin\QrcodeController@add');			//渠道添加
	Route::post('qrcode/doadd','Admin\QrcodeController@doadd');			//渠道添加处理
	Route::get('qrcode/list','Admin\QrcodeController@list');			//渠道添加处理

	Route::get('user/index','Admin\UserController@index');	   //粉丝列表
	Route::get('tag/add','Admin\UserController@add');	   	   //标签添加
	Route::post('tag/doadd','Admin\UserController@doadd');	   //标签添加处理
	Route::post('tag/checkTagName','Admin\UserController@checkTagName');	   //标签唯一性验证
	Route::get('tag/list','Admin\UserController@list');	   	   //标签列表

	Route::get('tag/allotUser/{tag_id}','Admin\UserController@allotUser');	   	   //分配用户
	Route::post('doallot','Admin\UserController@doallot');	   	   				//分配用户--分配

	Route::get('groups/send','Admin\GroupsController@send');     //群发信息添加
	Route::post('groups/dosend','Admin\GroupsController@dosend');     //群发信息处理


	Route::get('user/a','Admin\UserController@addWechatUser');	  //用户列表 -获取已关注粉丝 一次性

	Route::any('bank/add','Admin\BankController@add');       //题库添加
	Route::any('coupon/add','Admin\CouponController@add');       //优惠券添加
	

	Route::any('crontab','WechatController@cronGroupSend');   //定时群发

});

