
@extends('layouts.admin')
@section('title', '优惠券添加')
@section('content')


  <h2>优惠券管理-添加</h2>
  <form action="{{url('coupon/add')}}" method="post" enctype="multipart/form-data">
  
    <div class="form-group">
      <label for="exampleInputEmail1">优惠券名称</label>
      <input type="text"  class="form-control" name='coupon_name' id="coupon_name" placeholder="优惠券名称">
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">优惠券数量</label>
      <input type="text"  class="form-control" name='coupon_num' id="coupon_num" placeholder="优惠券数量">
    </div>
     <div class="form-group">
      <label for="exampleInputEmail1">优惠券使用条件(元)</label>
      <input type="text"  class="form-control" name='condition' id="condition" placeholder="优惠券使用条件(元)">
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">优惠券减免金额(元)</label>
      <input type="text"  class="form-control" name='money' id="money" placeholder="优惠券减免金额(元)">
    </div>
   <input type="button" class="btn btn-success" value="提交">
  </form>
<script type="text/javascript">
  $(function(){
      $('#coupon_name').blur(function(){
          var coupon_name = $(this).val();
          $(this).next().remove();
          if (coupon_name=='') {
            $(this).after("<span style='color:red'>优惠券名称不能为空</span>");
            return false;
          }
      })
      $('#coupon_num').blur(function(){
          var coupon_num = $(this).val();
          $(this).next().remove();
          if (coupon_num=='') {
            $(this).after("<span style='color:red'>优惠券数量不能为空</span>");
            return false;
          }
      
      })
       $('#condition').blur(function(){
          var condition = $(this).val();
          $(this).next().remove();
          if (condition=='') {
            $(this).after("<span style='color:red'>优惠券使用条件(元)不能为空</span>");
            return false;
          }
      })
      $('#money').blur(function(){
          var money = $(this).val();
          $(this).next().remove();
          if (money=='') {
            $(this).after("<span style='color:red'>优惠券减免金额(元)不能为空</span>");
            return false;
          }
      })

      //提交
      $('.btn').click(function(){

          var coupon_name = $('#coupon_name').val();
          $('#coupon_name').next().remove();
          if (coupon_name=='') {
            $('#coupon_name').after("<span style='color:red'>优惠券名称不能为空</span>");
            return false;
          }
        
      

           var coupon_num = $('#coupon_num').val();
          $('#coupon_num').next().remove();
          if (coupon_num=='') {
            $('#coupon_num').after("<span style='color:red'>优惠券数量不能为空</span>");
            return false;
          }

      

           var condition = $('#condition').val();
          $('#condition').next().remove();
          if (condition=='') {
            $('#condition').after("<span style='color:red'>优惠券使用条件(元)不能为空</span>");
            return false;
          }

          var money = $('#money').val();
          $('#money').next().remove();
          if (money=='') {
            $('#money').after("<span style='color:red'>优惠券减免金额(元)不能为空</span>");
            return false;
          }
          
          // 检测名称唯一性

          $('form').submit();
      })
  })
</script>

 @endsection