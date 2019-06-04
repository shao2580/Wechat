@extends('layouts.admin')
@section('title', '权限列表')
@section('content')

<h2>权限管理-列表</h2>
<table class="table table-bordered">
	<tr>
		<th>ID</th>
		<th>权限名称</th>
		<th>权限路由地址</th>	
		<th>操作</th>
	</tr>
	@foreach ($data as $key => $val)
		<tr>
			<td>{{$val['power_id']}}</td>
			<td>{{$val['level']}}{{$val['power_name']}}</td>
			<td>{{$val['power_url']}}</td>			
			<td>
				<a href="{{url('powerEdit',['power_id'=>$val['power_id']])}}" class="btn btn-success">编辑</a>
				<a href="{{url('powerDel',['power_id'=>$val['power_id']])}}" class="btn btn-success">删除</a>
			</td>
		</tr>
	@endforeach
	
	
</table>
<div>
<a href="{{url('powerAdd')}}" class="btn btn-primary btn-lg">添加</a>
</div>


@endsection