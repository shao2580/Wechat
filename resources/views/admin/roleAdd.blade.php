
@extends('layouts.admin')
@section('title', '角色添加')
@section('content')


  <h2>角色管理-添加</h2>
  <form  method="post">
    @csrf
    <div class="form-group">
      <label for="exampleInputEmail1">角色名称</label>
      <input type="text"  class="form-control" name='role_name' id="role_name" placeholder="请输入角色名称">
    </div>
  
   <input type="button" class="btn btn-success" value="提交">
  </form>
<script type="text/javascript">
  $(function(){
      $('#role_name').blur(function(){
          var role_name = $(this).val();
          $(this).next().remove();
          if (role_name=='') {
            $(this).after("<span style='color:red'>角色名称不能为空</span>");
            return false;
          }
      })

      //提交
      $('.btn').click(function(){

          var role_name = $('#role_name').val();
          $('#role_name').next().remove();
          if (role_name=='') {
            $('#role_name').after("<span style='color:red'>角色名称不能为空</span>");
            return false;
          }
          // 检测名称唯一性
          $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });

           $.ajax({
              method: "post",
              url: "{{url('roleAdd')}}",
              dataType:'json',
              data: { role_name:role_name }
            }).done(function( res ) {  
               if (res.code == 1) {
                  alert(res.msg);
                  location.href="{{url('roleList')}}";
               }else if (res.code == 0) {
                   alert(res.msg);
               }

            });      
  
      })
  })
</script>

 @endsection