@extends('layouts.admin')
@section('title', '抽奖页')
@section('content')

<h2>抽奖页</h2>
<form action="{{url('lottery')}}" method="post" class="control">
	<p>亲爱的用户：</p>
	<p>欢迎来到xx平台抽奖</p>
	<p>规则：</p>
	<p>1、xxxxxxxxxxxxxxxxx</p>
	<p>2、sssssssssssssssssssss</p>
	<input type="hidden" name="openid" value="{{$openid}}">
	<input type="submit" class="btn btn-success" value="参与抽奖"></a>
	<p>说明：</p>
	<p>1、vvvvvvvvvvvvvvv</p>
	<p>2、gggggggggggggggggggg</p>
</form>


@endsection