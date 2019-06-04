@extends('layouts.admin')
@section('content')

<h2>题库管理-列表</h2>
<table class="table table-bordered">
	<tr>
		<th>ID</th>
		<th>管理员名称</th>
		<th>管理员电话</th>
		<th>管理员年龄</th>
		<th>管理员性别</th>
		<th>操作</th>
	</tr>

	
	
</table>
<div>
<a href="{{url('adminAdd')}}" class="btn btn-primary btn-lg">添加</a>
	<div class="btn ">
	
	</div>
</div>


@endsection