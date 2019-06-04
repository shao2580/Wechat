@extends('layouts.admin')
@section('title', '群发信息添加')
@section('content')


  <h2>群发信息管理-添加</h2>
  <form action="{{url('groups/dosend')}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
      <label for="exampleInputEmail1">群发信息内容</label>
      <input type="text"  class="form-control" name='groups_content' id="groups_content" placeholder="群发信息内容">
    </div>
    <div class="form-group qrcode">
      <label for="exampleInputEmail1">信息方式</label>
      <select type="text"  class="form-control" name="groups_type">
        <option value="1">给所有用户发</option>
        <option value="2">给部分用户发</option>
        <option value="3">通过标签发送</option>
      </select>
    </div>
    <div class="form-group bg_qrcode"  style="display:none;">
      <label for="exampleInputEmail1">标签</label>
      <select type="text"  class="form-control" name="tag_id">
        @foreach ($tag as $key => $value)
            <option value="{{$value->tag_id}}">{{$value->tag_name}}</option>
        @endforeach
        
        
      </select>
    </div>
    <table class="table table-bordered" style="display: none;">
        <tr>
            <th width="80" style="color: red"><input type="checkbox" id="checkall" name="">&nbsp;&nbsp;全选</th>
            <th>用户编号</th>
            <th>用户openid</th>
            <th>用户昵称</th>
            <th>用户城市</th>
            <th>关注时间</th>
         </tr>
         @foreach ($user as $k => $v)
            <tr>
              <td><input type="checkbox" name="openid[]" value="{{$v->openid}}"></td>
              <td width="100">{{$v->user_id}}</td>
              <td width="300">{{$v->openid}}</td>
              <td>{{$v->nickname}}</td>
              <td>{{$v->city}}</td>
              <td>{{date('Y-m-d H:i:s',$v->subscribe_time)}}</td>
            </tr>
          @endforeach 
        <tr>
    </div>
   <input type="button" class="btn btn-success" value="提交">
  </form>
<script type="text/javascript">
  $(function(){
      //点发送方式显示标签
      $('.qrcode').change(function(){
          // $('.bg_qrcode').hidden();
         var type = $('select[type=text]').val();

         if (type == 2) {
            $('.table').show();
            $('.bg_qrcode').hide();
         }else if (type == 3) {
            $('.bg_qrcode').show();
            $('.table').hide();
         }else{
            $('.table').hide();
            $('.bg_qrcode').hide();
         }

      })

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

      $('#groups_content').blur(function(){
          var groups_content = $(this).val();
          $(this).next().remove();
          if (groups_content=='') {
            $(this).after("<span style='color:red'>信息内容不能为空</span>");
            return false;
          }
      })

      //提交
      $('.btn').click(function(){

          var groups_content = $('#groups_content').val();
          $('#groups_content').next().remove();
          if (groups_content=='') {
            $('#groups_content').after("<span style='color:red'>信息内容不能为空</span>");
            return false;
          }
      
          // 检测名称唯一性

          $('form').submit();
      })
  })
</script>

 @endsection