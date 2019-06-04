<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <title> - 登录</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link href="{{url('wechat/css')}}/bootstrap.min.css" rel="stylesheet">
    <link href="{{url('wechat/css')}}/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="{{url('wechat/css')}}/animate.css" rel="stylesheet">
    <link href="{{url('wechat/css')}}/style.css" rel="stylesheet">
    <link href="{{url('wechat/css')}}/login.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script>
        if (window.top !== window.self) {
            window.top.location = window.location;
        }
    </script>
    <script src="{{url('wechat/js')}}/jquery-3.3.1.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="signin">
    <div class="signinpanel">
        <div class="row">
            <div class="col-sm-12">
                <form method="post">
                    <h4 class="no-margins" style="color: #636363">微信公众号后台登录：</h4>
                    
                    <div><input type="text" class="form-control uname" name="name"  placeholder="用户名" /></div>
                    <div><input type="password" class="form-control pword m-b" name='password' placeholder="密码" /></div>
                    <div class="form-group">
                    <input type="text" class="form-control" name="code" id="code" style="width:60%;float: left" placeholder="微信验证码">&nbsp;
                    <input type="button" class="btn btn-info" value="发送验证码" id="send">
                    </div>
                    <div class="form-group">
                      <a class="qrcode_img1" class="control">二维扫描码登录</a>
                      <a class="qrcode_img" style="float: right;" class="control">关注公众号-绑定账号</a>
                    </div>
                    <div >
                      <p style="color:blue">二级账号管理员发放：微信：shao25800</p>
                    </div>
                    <input type="button" class="btn btn-success btn-block" value="提交">
                </form>
            </div>
        </div>
        <!-- 登录 二维码 -->
        <div class="bg_div1" style="display:none;background:#ccc;width:500px;height:500px;position:absolute;top:10%;left:30%;opacity:0.8;text-align:center;padding-top:5%">
          <div class="clone_div1" style="padding-left:65%">
            <b>关闭</b>
          </div>
          <img src="http://qr.liantu.com/api.php?text=<?= $createUrl?>" style="width: 350px">
        </div>

         <!-- 绑定 二维码 -->
        <div class="bg_div" style="display:none;background:#ccc;width:500px;height:500px;position:absolute;top:10%;left:30%;opacity:0.8;text-align:center;padding-top:5%">
          <div class="clone_div" style="padding-left:65%">
            <b>关闭</b>
          </div>
          <img src="http://www.shao2580.top/qrcode/0b24e06ba2dfa66c1410fbe4f644a166.jpg" style="width: 350px">
        </div>
        
    </div>
</body>
</html>
<script type="text/javascript">
     //每隔3秒 发送一次ajaxa请求
          var t = setInterval("check();",3000);
          
          var id = "<?= $id?>";
          //把标识发后台 用于检测用户
          function check(){
              $.ajax({
                url:"{{url('login/checklogin')}}",
                data:{id:id},
                dataType:"json",
                success:function(res){
                    if (res.ret == 1) {
                      //如果登录成功  结束定时器
                      clearInterval(t);
                      alert(res.msg);
                      location.href = "{{url('admin')}}";
                    }
                }
              })
          }
    $(function(){
          //登录二维码
          $('.qrcode_img1').click(function(){
              //背景层显示
              $('.bg_div1').show();
          })
            //点关闭 隐藏背景
            $('.clone_div1').click(function(){
              $('.bg_div1').hide();
            })

          //绑定二维码
          $('.qrcode_img').click(function(){
              //背景层显示
              $('.bg_div').show();

              var src = $(this).attr('src');
              $('.bg_div img').attr('src',src);
          })
            //点关闭 隐藏背景
            $('.clone_div').click(function(){
              $('.bg_div').hide();
            })

        $('.uname').blur(function(){
           var name = $(this).val();
           // console.log(name);
           $(this).next().remove();
           if (name=='') {
                $(this).after("<span style='color:red'>用户名不能为空</span>");
                return false;
           }
           // var reg = /^\w{2,11}$/;
           // if (!reg.test(name)) {
           //     $(this).after("<span style='color:red'>用户名必须为2~11位数字、字母组成</span>");
           //      return false; 
           // }
        })
        $('.pword').blur(function(){
            var password = $(this).val();
           // console.log(name);
           $(this).next().remove();
           if (password=='') {
                $(this).after("<p style='color:red'>密码不能为空</p>");
                return false;
           }
           var reg = /^\w{5,18}$/;
           if (!reg.test(password)) {
               $(this).after("<span style='color:red'>密码必须为5~18位数字、字母组成</span>");
                return false; 
           }
        })

        //发送验证码
        $('#send').click(function(){
          var name = $('.uname').val();
          var password = $('.pword').val();
            //发送请求到后台
             //把账号 密码通过ajax传给控制器
         $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
         if (name !=='' && password !== '') {
            $.ajax({
              method: "post",
              url: "{{url('/send')}}",
              dataType:'json',
              data: { name:name,password:password }
            }).done(function( res ) {  
               alert(res.msg);
                
            });

            //执行倒计时
            setTime(); 
         }
          
            
          
        })

        //倒计时
        var second = 5;
        function setTime()
        {
          var input = $('#send');
          //倒计时为0 改回按钮状态 可以点击再次发送
          if (second == 0) {
            input.val('重新发送');
            input.attr('disabled',false);
            second = 5;
          }else{
              //倒计时不为0 按钮状态不可点 秒数递减
              input.attr('disabled',true);
              input.val(second+'秒后发送');

              setTimeout(function(){
                second--;
                setTime();
              },1000);
          }
        }   
    })

       /*提交*/
      $('.btn').click(function(){
          var name = $('.uname').val();
           $('.uname').next().remove();
           if (name=='') {
                $('.uname').after("<span style='color:red'>用户名不能为空</span>");
                return false;
           }
          // $('.uname').next().remove();
          //   var reg = /^\w{2,11}$/;
          //  if (!reg.test(name)) {
          //      $('.uname').after("<span style='color:red'>用户名必须为2~11位数字、字母组成</span>");
          //       return false; 
          //  }
           var password = $('.pword').val();
           // console.log(name);
           $('.pword').next().remove();
           if (password=='') {
                $('.pword').after("<p style='color:red'>密码不能为空</p>");
                return false;
           }
           $('.pword').next().remove();
           var reg = /^\w{5,18}$/;
           if (!reg.test(password)) {
               $('.pword').after("<span style='color:red'>密码必须为5~18位数字、字母组成</span>");
                return false; 
           }
        //把账号 密码通过ajax传给控制器
         var code = $('#code').val();
         if (code) {
             $.ajax({
              method: "post",
              url: "{{url('/dologin')}}",
              dataType:'json',
              data: { name:name,password:password,code:code }
            }).done(function( res ) {  
               if (res.code == 1) {
                  alert(res.msg);
                  location.href="{{url('admin')}}";
               }else if (res.code == 3) {
                  alert(res.msg);
               }

            });
         }    
        });
</script>


<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<script>
  /*验证配置*/
  wx.config({
    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '<?= $data["appId"] ?>', // 必填，公众号的唯一标识
    timestamp: <?= $data["timestamp"] ?>, // 必填，生成签名的时间戳
    nonceStr: '<?= $data["nonceStr"] ?>', // 必填，生成签名的随机串
    signature: '<?= $data["signature"] ?>',// 必填，签名
    jsApiList: ['updateAppMessageShareData'] // 必填，需要使用的JS接口列表
  });
  /*分享接口*/
  wx.ready(function () {
      // 在这里调用 API
      wx.updateAppMessageShareData({ 
          title: '测试-登录页', // 分享标题
          desc: '测试-分享-登录页', // 分享描述
          link: 'http://www.shao2580.top/login', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
          imgUrl: 'http://dh.188fq.com/static/images/bd_logo1.png', // 分享图标
          success: function () {
            // 设置成功
          }
      })
       

  });

    


</script>