
@extends('layouts.admin')
@section('title', '素材添加')
@section('content')


  <h2>素材管理-添加</h2>
  <form action="{{url('doadd')}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
      <label for="exampleInputEmail1">素材名称</label>
      <input type="text"  class="form-control" name='name' id="name" placeholder="素材名称">
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">素材类别</label>
      <select type="text"  class="form-control" name="imgtype">
        <option value="1">临时素材</option>
        <option value="2">永久素材</option>
      </select>
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">素材格式</label>
      <select type="text"  class="form-control" name="format">
        <option value="image">图片</option>
        <option value="voice">语音</option>
        <option value="video">视频</option>
        <option value="thumb">缩略图</option>
      </select>
    </div>
    <div class="form-group">
      <label for="exampleInputFile">上传素材</label>
      <input type="file" name='file'  id="file">
    </div>
    <div class="checkbox">
   
    </div>
   <input type="button" class="btn btn-success" value="提交">
  </form>
<script type="text/javascript">
  $(function(){
      $('#name').blur(function(){
          var name = $(this).val();
          $(this).next().remove();
          if (name=='') {
            $(this).after("<span style='color:red'>名称不能为空</span>");
            return false;
          }
      })
      $('#file').blur(function(){
          var file = $(this).val();
          $(this).next().remove();
          if (file=='') {
            $(this).after("<span style='color:red'>素材不能为空</span>");
            return false;
          }
      })

      //提交
      $('.btn').click(function(){

          var name = $('#name').val();
          $('#name').next().remove();
          if (name=='') {
            $('#name').after("<span style='color:red'>名称不能为空</span>");
            return false;
          }
      
          var file = $('#file').val();
          $('#file').next().remove();
          if (file=='') {
            $('#file').after("<span style='color:red'>素材不能为空</span>");
            return false;
          }
          
          // 检测名称唯一性

          $('form').submit();
      })
  })
</script>

 @endsection