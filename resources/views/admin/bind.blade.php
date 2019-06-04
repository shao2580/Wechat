
@extends('layouts.admin')
@section('title', '绑定')
@section('content')

  <h2>绑定管理员微信账号</h2>
  <form action="{{url('dobind')}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
      <label for="exampleInputEmail1">用户名</label>
      <input type="text"  class="form-control" name='name' id="name" placeholder="请输入用户名">
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">密码</label>
      <input type="text"  class="form-control" name='password' id="password" placeholder="请输入密码">
      
    </div>   
   <input type="button" class="btn btn-success" value="绑定">
  </form>
<script type="text/javascript">
  $(function(){
      $('#name').blur(function(){
          var name = $(this).val();
          $(this).next().remove();
          if (name=='') {
            $(this).after("<span style='color:red'>用户名不能为空</span>");
            return false;
          }
      })
      $('#password').blur(function(){
          var password = $(this).val();
          $(this).next().remove();
          if (password=='') {
            $(this).after("<span style='color:red'>密码不能为空</span>");
            return false;
          }
      })

      //提交
      $('.btn').click(function(){

          var name = $('#name').val();
          $('#name').next().remove();
          if (name=='') {
            $('#name').after("<span style='color:red'>用户名不能为空</span>");
            return false;
          }
      
          var password = $('#password').val();
          $('#password').next().remove();
          if (password=='') {
            $('#password').after("<span style='color:red'>密码不能为空</span>");
            return false;
          }
          
          // 检测名称唯一性

          $('form').submit();
      })
  })
</script>

 @endsection