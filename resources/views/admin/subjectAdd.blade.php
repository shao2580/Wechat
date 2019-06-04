
@extends('layouts.admin')
@section('content')


  <h2>题库管理-添加</h2>
  <form action="{{url('bank/add')}}" method="post" enctype="multipart/form-data">
    
    <div class="form-group">
      <label for="exampleInputEmail1">题目</label>
      <input type="text"  class="form-control" name='subject_name' id="subject_name" placeholder="题目">
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">答案A</label>
      <input type="text"  class="form-control" name='subject_a' id="subject_a" placeholder="是否正确">
    </div>
     <div class="form-group">
      <label for="exampleInputEmail1">答案B</label>
      <input type="text"  class="form-control" name='subject_b' id="subject_b" placeholder="是否正确">
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">正确答案</label>
      <input type="text"  class="form-control" name='success' id="success" placeholder="请输入正确答案">
    </div>
   <input type="button" class="btn btn-success" value="提交">
  </form>
<script type="text/javascript">
  $(function(){
      $('#subject_name').blur(function(){
          var subject_name = $(this).val();
          $(this).next().remove();
          if (subject_name=='') {
            $(this).after("<span style='color:red'>题目不能为空</span>");
            return false;
          }
         
      })
      $('#subject_a').blur(function(){
          var subject_a = $(this).val();
          $(this).next().remove();
          if (subject_a=='') {
            $(this).after("<span style='color:red'>答案A不能为空</span>");
            return false;
          }
          
      })
       $('#subject_b').blur(function(){
          var subject_b = $(this).val();
          $(this).next().remove();
          if (subject_b=='') {
            $(this).after("<span style='color:red'>答案B不能为空</span>");
            return false;
          }
      })
      $('#success').blur(function(){
          var success = $(this).val();
          $(this).next().remove();
          if (success=='') {
            $(this).after("<span style='color:red'>正确答案不能为空</span>");
            return false;
          }
      })

      //提交
      $('.btn').click(function(){

          var subject_name = $('#subject_name').val();
          $('#subject_name').next().remove();
          if (subject_name=='') {
            $('#subject_name').after("<span style='color:red'>题目不能为空</span>");
            return false;
          }
                
           var subject_a = $('#subject_a').val();
          $('#subject_a').next().remove();
          if (subject_a=='') {
            $('#subject_a').after("<span style='color:red'>答案A不能为空</span>");
            return false;
          }


           var subject_b = $('#subject_b').val();
          $('#subject_b').next().remove();
          if (subject_b=='') {
            $('#subject_b').after("<span style='color:red'>答案B不能为空</span>");
            return false;
          }

          var success = $('#success').val();
          $('#success').next().remove();
          if (success=='') {
            $('#success').after("<span style='color:red'>正确答案不能为空</span>");
            return false;
          }
          
          // 检测名称唯一性

          $('form').submit();
      })
  })
</script>

 @endsection