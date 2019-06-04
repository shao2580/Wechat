@extends('layouts.admin')
@section('title', '角色列表')
@section('content')

<h2>角色管理-列表</h2>
<table class="table table-bordered">
	<tr>
		<th>ID</th>
		<th>角色名称</th>
		<th>操作</th>
	</tr>
	@foreach ($data as $key => $val)
		<tr>
			<td>{{$val->role_id}}</td>
			<td>{{$val->role_name}}</td>
			<td>
				<a href="{{url('roleEdit',['role_id'=>$val->role_id])}}" class="btn btn-success">编辑</a>
				<a href="{{url('adminDel',['role_id'=>$val->role_id])}}" class="btn btn-success">删除</a>
			</td>
		</tr>
	@endforeach
	
	
</table>
<div>
<a href="{{url('roleAdd')}}" class="btn btn-primary btn-lg">添加</a>
	<div class="btn ">
		{{$data->links()}}
	</div>
</div>


@endsection