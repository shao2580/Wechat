@extends('layouts.admin')
@section('title', '标签列表')
@section('content')

<h2>标签管理-列表</h2>
<table class="table table-striped">
	<tr>
		<th>ID</th>
		<th>标签名</th>
		<th>标签标识</th>
		<th>分配人数</th>
		<th>操作</th>
	</tr>
	@foreach ($data as $key => $val)
		<tr>
			<td>{{$val->tag_id}}</td>
			<td>{{$val->tag_name}}</td>
			<td>{{$val->wechat_tag_id}}</td>
			<td>{{$val->count}}</td>
			<td>
				<a href="{{url('tag/allotUser',['tag_id'=>$val->tag_id])}}"  class="btn  btn-success">分配用户</a>
				<a href="{{url('tag/update')}}" class="btn btn-success">修改</a>
				<a href="{{url('tag/del',['wechat_tag_id'=>$val->wechat_tag_id])}}" class="btn btn-success">删除</a>
			</td>
		</tr>
	@endforeach
		
	
</table>
<div>
	<a href="{{url('tag/add')}}" class="btn btn-primary btn-lg">添加</a>
	<div class="btn" >
		{{$data->links()}}
	</div>	
</div>
<br/>

@endsection