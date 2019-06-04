
@extends('layouts.admin')
@section('title', '权限添加')
@section('content')


  <h2>权限管理-添加</h2>
  <form action="{{url('powerAdd')}}" method="post" >
    <div class="form-group">
      <label for="exampleInputEmail1">权限名称</label>
      <input type="text"  class="form-control" name='power_name' id="power_name" placeholder="权限名称">
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">权限路由地址</label>
      <input type="text"  class="form-control" name='power_url' id="power_url" placeholder="权限路由地址">
    </div> 
    <div class="form-group">
      <label for="exampleInputEmail1">上级权限名称</label>
      <select type="text"  class="form-control" name="parent_id">
          <option value="0">顶级权限</option>
        @foreach($data as $k => $v)
          <option value="{{$v['power_id']}}">{{$v['level']}}{{$v['power_name']}}</option>
        @endforeach
      </select>
    </div>

   <input type="button" class="btn btn-success" value="提交">
  </form>
<script type="text/javascript">
  $(function(){
      $('#power_name').blur(function(){
          var power_name = $(this).val();
          $(this).next().remove();
          if (power_name=='') {
            $(this).after("<span style='color:red'>权限名称不能为空</span>");
            return false;
          }
      })
      $('#power_url').blur(function(){
          var power_url = $(this).val();
          $(this).next().remove();
          if (power_url=='') {
            $(this).after("<span style='color:red'>权限路由地址不能为空</span>");
            return false;
          }
      })

      //提交
      $('.btn').click(function(){

          var power_name = $('#power_name').val();
          $('#power_name').next().remove();
          if (power_name=='') {
            $('#power_name').after("<span style='color:red'>权限名称不能为空</span>");
            return false;
          }
      
          var file = $('#power_url').val();
          $('#power_url').next().remove();
          if (power_url=='') {
            $('#power_url').after("<span style='color:red'>权限路由地址不能为空</span>");
            return false;
          }
          
          // 检测名称唯一性

          $('form').submit();
      })
  })
</script>

 @endsection