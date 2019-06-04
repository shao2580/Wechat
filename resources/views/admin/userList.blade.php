@extends('layouts.admin')
@section('title', '用户列表')
@section('content')

<h2>用户管理-列表</h2>
<table class="table table-striped">
	<tr>
		<th>ID</th>
		<th>用户名</th>
		<th>性别</th>
		<th>所在省</th>
		<th>所在城市</th>
		<th>用户头像</th>
		<th>关注渠道</th>
		<th>关注时间</th>
		<th>openid</th>
		<th>操作</th>
	</tr>
	@foreach ($data as $k => $v)
		<tr>
			<td>{{$v->user_id}}</td>
			<td>{{$v->nickname}}</td>
			<td>@if($v->sex==1) 男 @else 女 @endif</td>
			<td>{{$v->province}}</td>
			<td>{{$v->city}}</td>
			<td><img width="60" height="40" src="{{$v->headimgurl}}"></td>
			<td>{{$v->qr_scene}}</td>
			<td>{{date('Y-m-d H:i:s',$v->subscribe_time)}}</td>
			<td>{{$v->openid}}</td>
			<td>
				<a href="{{url('user/del',['id'=>$v->user_id])}}" class="btn btn-success">删除</a>
			</td>
		</tr>
	@endforeach
	

</table>

<div class="btn" >
		
</div>



@endsection