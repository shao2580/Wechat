
@extends('layouts.admin')
@section('title', '标签添加')
@section('content')


  <h2>标签管理-添加</h2>
  <form action="{{url('tag/doadd')}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
      <label for="exampleInputEmail1">标签名称</label>
      <input type="text"  class="form-control" name='tag_name' id="tag_name" placeholder="标签名称">
    </div>
   <input type="button" class="btn btn-success" value="提交">
  </form>
<script type="text/javascript">
  $(function(){
      $('#tag_name').blur(function(){
          var tag_name = $(this).val();
          $(this).next().remove();
          if (tag_name=='') {
            $(this).after("<span style='color:red'>标签名称不能为空</span>");
            return false;
          }

          $.ajaxSetup({
          headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          method: "POST",
          url: "/tag/checkTagName",
          data: { tag_name: tag_name }
        }).done(function( msg ) {
          $("#tag_name").next().remove();
            if (msg.code==0) {
              $("#tag_name").after("<span style='color:red'>"+msg.msg+"<span>")
            }
        }); 

      })
      //提交
      $('.btn').click(function(){
          var tag_name = $('#tag_name').val();
          $('#tag_name').next().remove();
          if (tag_name=='') {
            $('#tag_name').after("<span style='color:red'>标签名称不能为空</span>");
            return false;
          }
          // 检测名称唯一性
        $.ajaxSetup({
          headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        var falg = false;

        $.ajax({
          method: "POST",
          url: "/tag/checkTagName",
          data: { tag_name: tag_name }
        }).done(function( msg ) {
          $("#tag_name").next().remove();
            if (msg.code==0) {
              $("#tag_name").after("<span style='color:red'>"+msg.msg+"<span>");
              falg1 = false;
            }else{
              falg1 = true;
            }
        });
        if (falg1 == true) {
           $('form').submit();
        }  
        
      })
  })
</script>

 @endsection