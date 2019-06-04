
@extends('layouts.admin')
@section('title', '渠道添加')
@section('content')


  <h2>渠道管理-添加</h2>
  <form action="{{url('qrcode/doadd')}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
      <label for="exampleInputEmail1">渠道名称</label>
      <input type="text"  class="form-control" name='qrcode_name' id="qrcode_name" placeholder="渠道名称">
    </div>
   <!--  <div class="form-group">
      <label for="exampleInputEmail1">渠道类别</label>
      <select type="text"  class="form-control" name="qrcode_type">
        <option value="1">32位非0整型--临时</option>
        <option value="2">1~64位字符串--临时</option>
        <option value="3">32位非0整型--永久</option>
        <option value="4">1~64位字符串--永久</option>
      </select>
    </div> -->
    <div class="form-group">
      <label for="exampleInputEmail1">渠道标识</label>
      <input type="text"  class="form-control" name='qrcode_key' id="qrcode_key" placeholder="类别整型：填纯数字；类别字符串：填任意字符">
      
    </div>
   
   <input type="button" class="btn btn-success" value="提交">
  </form>
<script type="text/javascript">
  $(function(){
      $('#qrcode_name').blur(function(){
          var qrcode_name = $(this).val();
          $(this).next().remove();
          if (qrcode_name=='') {
            $(this).after("<span style='color:red'>名称不能为空</span>");
            return false;
          }
      })
      $('#qrcode_key').blur(function(){
          var qrcode_key = $(this).val();
          $(this).next().remove();
          if (qrcode_key=='') {
            $(this).after("<span style='color:red'>渠道不能为空</span>");
            return false;
          }
      })

      //提交
      $('.btn').click(function(){

          var qrcode_name = $('#qrcode_name').val();
          $('#qrcode_name').next().remove();
          if (qrcode_name=='') {
            $('#qrcode_name').after("<span style='color:red'>名称不能为空</span>");
            return false;
          }
      
          var qrcode_key = $('#qrcode_key').val();
          $('#qrcode_key').next().remove();
          if (qrcode_key=='') {
            $('#qrcode_key').after("<span style='color:red'>渠道不能为空</span>");
            return false;
          }
          
          // 检测名称唯一性

          $('form').submit();
      })
  })
</script>

 @endsection