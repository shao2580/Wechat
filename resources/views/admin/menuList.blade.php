@extends('layouts.admin')
@section('title', '菜单列表')
@section('content')

<h2>菜单管理-列表</h2>
<table class="table table-bordered">
	<tr>
		<th>ID</th>
		<th>菜单名</th>
		<th>菜单类型</th>
		<th>菜单标识</th>
		<th>操作</th>
	</tr>
	@foreach ($data as $k => $v)
		<tr>
			<td>{{$v['menu_id']}}</td>
			<td>{{$v['level']}}{{$v['menu_name']}}</td>
			<td>@if($v['menu_type']=='click') 点击类 @else 跳转类 @endif</td>
			<td>{{$v['menu_key']}}</td>
			<td>
				<a href="{{url('menu/update',['id'=>$v['menu_id']])}}" class="btn btn-success">修改</a>
				<a href="{{url('menu/del',['id'=>$v['menu_id']])}}" class="btn btn-success">删除</a>
			</td>
		</tr>
	@endforeach
	

</table>
<div>
<a href="{{url('menu/add')}}" class="btn btn-primary btn-lg">添加</a>
<a href="{{url('menu/create_menu')}}" class="btn btn-primary btn-lg">一键同步菜单</a>

</div>

@endsection