@extends('layouts.admin')
@section('title', '分配用户')
@section('content')

<h2>分配用户&nbsp;&nbsp;&nbsp;

	<input type="button" name="" class="btn btn-success" value="已分配">	
</h2>
<form action="{{url('doallot')}}" method="post" enctype="multipart/form-data">
		
	<table class="table table-striped">
			<tr>
				<td colspan="6">当前标签：<i style="color: red">{{$tag->tag_name}}</i></td>
				<input type="hidden" name="tag_id" value="{{$tag->tag_id}}">
				<input type="hidden" name="wechat_tag_id" value="{{$tag->wechat_tag_id}}">
			</tr>
			@csrf
			<tr>
				<th width="50" style="color: red">☆</th>
				<th>用户编号</th>
				<th>用户openid</th>
				<th>用户昵称</th>
				<th>用户城市</th>
				<th>关注时间</th>
			</tr>
			@foreach ($user as $k => $v)
			@if(in_array($v['openid'],$openid_list))
			@else
				<tr>
					<td><input type="checkbox" name="openid[]" value="{{$v->openid}}"></td>
					<td width="100">{{$v->user_id}}</td>
					<td width="300">{{$v->openid}}</td>
					<td>{{$v->nickname}}</td>
					<td>{{$v->city}}</td>
					<td>{{date('Y-m-d H:i:s',$v->subscribe_time)}}</td>
				</tr>
			@endif
			@endforeach		
			<tr>
				<td colspan="6" >
					<input type="checkbox" id="checkall" name="">&nbsp;&nbsp;全选&nbsp;&nbsp;
				<button type="submit" class="btn btn-success">分配</button>
			</tr>
	</table>
</form>
<div>
	<div class="btn" >
		{{$user->links()}}
	</div>	
</div>
<br/>
<script type="text/javascript">
	//全选
	$("#checkall").click(function(){ 
	  $("input[name='openid[]']").each(function(){
		  if (this.checked) {
			  this.checked = false;
		  }else {
			  this.checked = true;
		  }
	  });
	})
	

</script>


@endsection