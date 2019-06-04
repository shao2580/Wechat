@extends('layouts.admin')
@section('title', '素材列表')
@section('content')

<h2>素材管理-列表</h2>
<button type="button" class="btn btn-primary">全部素材</button>
<button type="button" class="btn btn-success">图片素材</button>
<button type="button" class="btn btn-info">语音素材</button>
<button type="button" class="btn btn-warning">视频素材</button>
<button type="button" class="btn btn-danger">缩略图</button>
<table class="table table-bordered">
	<tr>
		<th>ID</th>
		<th>素材名</th>
		<th>素材图片</th>
		<th>media_id</th>
		<th>添加时间</th>
		<th>素材类型</th>
		<th>素材格式</th>
		<th>操作</th>
	</tr>
	@foreach ($data as $k => $v)
		<tr>
			<td>{{$v->id}}</td>
			<td>{{$v->name}}</td>
			<td><img width="60" height="40" src="/uploads/{{$v->img_url}}"></td>
			<td width="100">{{$v->media_id}}</td>
			<td>{{date('Y-m-d H:i:s',$v->add_time)}}</td>
			<td>@if($v->imgtype==1) 临时 @else 永久 @endif</td>
			<td>{{$v->format}}</td>
			<td>
				<a href="{{url('del',['id'=>$v->id])}}" class="btn btn-success">@if($v->imgtype==2) 删除 @else @endif</a>
			</td>
		</tr>
	@endforeach
	

</table>
<div>
<a href="{{url('menu/add')}}" class="btn btn-primary btn-lg">添加</a>
<div class="btn" >
	{{$data->links()}}		
</div>

</div>


@endsection