@extends('layouts.admin')
@section('title', '渠道列表')
@section('content')

<h2>渠道管理-列表</h2>
<table class="table table-bordered">
	<tr>
		<th>ID</th>
		<th>渠道名</th>
		<th>菜单标识</th>
		<th>渠道图片</th>
		<th>关注人数</th>
		<th>操作</th>
	</tr>
	@foreach ($data as $k => $v)
		<tr>
			<td>{{$v->qrcode_id}}</td>
			<td>{{$v->qrcode_name}}</td>
			<td>{{$v->qrcode_key}}</td>
			<td><img class="qrcode_img" width="60" src="{{$v->qrcode_img}}"></td>
			<td>{{$v->attention}}</td>
			<td>
				<a href="{{url('qrcode/update',['id'=>$v->qrcode_id])}}" class="btn btn-success">修改</a>
				<a href="{{url('qrcode/del',['id'=>$v->qrcode_id])}}" class="btn btn-success">删除</a>
			</td>
		</tr>
	@endforeach
	

</table>
<div>
	<a href="{{url('qrcode/add')}}" class="btn btn-primary btn-lg">添加</a>
	<div class="btn ">
		{{$data->links()}}
	</div>
</div>

<!-- 背景 二维码 -->
<div class="bg_div" style="display:none;background:#ccc;width:500px;height:500px;position:absolute;top:10%;left:30%;opacity:0.8;text-align:center;padding-top:5%">
	<div class="clone_div" style="padding-left:65%">
		<b>关闭</b>
	</div>
	<img src="" style="width: 350px">
</div>

<script type="text/javascript">
	$('.qrcode_img').click(function(){
		//背景层显示
		$('.bg_div').show();

		var src = $(this).attr('src');
		$('.bg_div img').attr('src',src);
	})
	//点关闭 隐藏背景
	$('.clone_div').click(function(){
		$('.bg_div').hide();
	})
</script>

@endsection