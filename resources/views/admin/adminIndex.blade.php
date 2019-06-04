@extends('layouts.admin')
@section('title', '管理员列表')
@section('content')

<h2>管理员管理-列表</h2>
<table class="table table-bordered">
	<tr>
		<th>ID</th>
		<th>管理员名称</th>
		<th>管理员电话</th>
		<th>管理员年龄</th>
		<th>管理员性别</th>
		<th>操作</th>
	</tr>
	@foreach ($data as $key => $val)
		<tr>
			<td>{{$val->admin_id}}</td>
			<td>{{$val->name}}</td>
			<td>{{$val->phone}}</td>
			<td>{{$val->age}}</td>
			<td>@if($val->sex == 1) 男 @else 女 @endif</td>
			<td>
				<a href="{{url('adminUpdate',['admin_id'=>$val->admin_id])}}" class="btn btn-success">修改</a>
				<a href="{{url('adminDel',['admin_id'=>$val->admin_id])}}" class="btn btn-success">删除</a>
			</td>
		</tr>
	@endforeach
	
	
</table>
<div>
<a href="{{url('adminAdd')}}" class="btn btn-primary btn-lg">添加</a>
	<div class="btn ">
		{{$data->links()}}
	</div>
</div>


@endsection