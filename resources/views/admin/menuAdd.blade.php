
@extends('layouts.admin')
@section('title', '菜单添加')
@section('content')


  <h2>菜单管理-添加</h2>
  <form action="{{url('menu/doadd')}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
      <label for="exampleInputEmail1">菜单名称</label>
      <input type="text"  class="form-control" name='menu_name' id="menu_name" placeholder="菜单名称">
    </div>
    
     <div class="form-group">
      <label for="exampleInputEmail1">菜单类别</label>
      <select type="text"  class="form-control" name="menu_type">
        <option value="click">点击类</option>
        <option value="view">跳转类</option>
      </select>
    </div>

    <div class="form-group">
      <label for="exampleInputEmail1">菜单级别</label>

      <select type="text"  class="form-control" name="parent_id">
        <option value="0">顶级菜单</option>
        @foreach($data as $k => $v)
          <option value="{{$v['menu_id']}}">{{$v['level']}}{{$v['menu_name']}}</option>
        @endforeach
        
      </select>
    </div>
    <div class="form-group">
      <label for="exampleInputEmail1">菜单标识</label>
      <input type="text"  class="form-control" name='menu_key' id="menu_key" placeholder="标识：跳转类必须为网址">
    </div>
   
   <input type="button" class="btn btn-success" value="提交">
  </form>
<script type="text/javascript">
  $(function(){
      $('#menu_name').blur(function(){
          var menu_name = $(this).val();
          $(this).next().remove();
          if (menu_name=='') {
            $(this).after("<span style='color:red'>名称不能为空</span>");
            return false;
          }
      })
      $('#menu_key').blur(function(){
          var menu_key = $(this).val();
          $(this).next().remove();
          if (menu_key=='') {
            $(this).after("<span style='color:red'>标识不能为空</span>");
            return false;
          }
      })

      //提交
      $('.btn').click(function(){

          var menu_name = $('#menu_name').val();
          $('#menu_name').next().remove();
          if (menu_name=='') {
            $('#menu_name').after("<span style='color:red'>名称不能为空</span>");
            return false;
          }
      
          var file = $('#menu_key').val();
          $('#menu_key').next().remove();
          if (menu_key=='') {
            $('#menu_key').after("<span style='color:red'>标识不能为空</span>");
            return false;
          }
          
          // 检测名称唯一性

          $('form').submit();
      })
  })
</script>

 @endsection