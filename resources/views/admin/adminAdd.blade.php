
@extends('layouts.admin')
@section('title', '管理员添加')
@section('content')


  <h2>管理员管理-添加</h2>
  <form action="{{url('adminAdd')}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
      <label for="exampleInputEmail1">管理员名称</label>
      <input type="text"  class="form-control" name='name' id="name" placeholder="请输入管理员名称">
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">管理员密码</label>
      <input type="password"  class="form-control" name='password' id="password" placeholder="请输入密码">
    </div>
     <div class="form-group">
      <label for="exampleInputEmail1">管理员电话</label>
      <input type="text"  class="form-control" name='phone' id="phone" placeholder="请输入管理员电话">
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">管理员年龄</label>
      <input type="text"  class="form-control" name='age' id="age" placeholder="请输入管理员年龄">
    </div>
   <div class="form-group">
      <label for="exampleInputEmail1">管理员性别：</label>
      <input type="radio" name="sex" value="1" checked>&nbsp;男&nbsp;
      <input type="radio" name="sex" value="2">&nbsp;女
    </div>
   <input type="button" class="btn btn-success" value="提交">
  </form>
<script type="text/javascript">
  $(function(){
      $('#name').blur(function(){
          var name = $(this).val();
          $(this).next().remove();
          if (name=='') {
            $(this).after("<span style='color:red'>管理员名称不能为空</span>");
            return false;
          }
            var reg = /^\w{2,11}$/;
           if (!reg.test(name)) {
               $(this).after("<span style='color:red'>管理员名称必须为2~11位数字、字母组成</span>");
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
           var reg = /^\w{5,18}$/;
           if (!reg.test(password)) {
               $(this).after("<span style='color:red'>密码必须为5~18位数字、字母组成</span>");
                return false; 
           }
      })
       $('#phone').blur(function(){
          var phone = $(this).val();
          $(this).next().remove();
          if (phone=='') {
            $(this).after("<span style='color:red'>管理员电话不能为空</span>");
            return false;
          }
      })
      $('#age').blur(function(){
          var age = $(this).val();
          $(this).next().remove();
          if (age=='') {
            $(this).after("<span style='color:red'>管理员年龄不能为空</span>");
            return false;
          }
      })

      //提交
      $('.btn').click(function(){

          var name = $('#name').val();
          $('#name').next().remove();
          if (name=='') {
            $('#name').after("<span style='color:red'>管理员名称不能为空</span>");
            return false;
          }
        
          $('#name').next().remove();
            var reg = /^\w{2,11}$/;
           if (!reg.test(name)) {
               $('#name').after("<span style='color:red'>用户名必须为2~11位数字、字母组成</span>");
                return false; 
           }

           var password = $('#password').val();
          $('#password').next().remove();
          if (password=='') {
            $('#password').after("<span style='color:red'>密码不能为空</span>");
            return false;
          }

          $('#password').next().remove();
           var reg = /^\w{5,18}$/;
           if (!reg.test(password)) {
               $('#password').after("<span style='color:red'>密码必须为5~18位数字、字母组成</span>");
                return false; 
           }

           var phone = $('#phone').val();
          $('#phone').next().remove();
          if (phone=='') {
            $('#phone').after("<span style='color:red'>管理员电话不能为空</span>");
            return false;
          }

          var age = $('#age').val();
          $('#age').next().remove();
          if (age=='') {
            $('#age').after("<span style='color:red'>管理员年龄不能为空</span>");
            return false;
          }
          
          // 检测名称唯一性

          $('form').submit();
      })
  })
</script>

 @endsection