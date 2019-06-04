@extends('layouts.admin')
@section('title', '图表')
@section('content')
  
<div class="control">
    <h2 >
        <a class="qrcode_img1" class="control">扫二维码打赏</a>
       <h4 style="color:red">慎重考虑</h4> 
    </h2>
</div>
<body>
    <!-- 图表容器 DOM -->
    <div id="container" class="container" style="width:100%;height:100%;" ></div>
    <!-- 引入 highcharts.js -->
    <script src="http://cdn.highcharts.com.cn/highcharts/highcharts.js"></script>
    <script>
        // 图表配置
        var options = {
            chart: {
                type: 'bar'                          //指定图表的类型，默认是折线图（line）
            },
            title: {
                text: '渠道管理统计分析'                 // 标题
            },
            xAxis: {
                categories: [<?php echo $qrcode_name ?>]   // x 轴分类
            },
            yAxis: {
                title: {
                    text: '关注人数'                // y 轴标题
                }
            },
            series: [{                              // 数据列
                name: '推广关注',                        // 数据列名
                data: [<?php echo $attention ?>]                     // 数据
            }]
        };
        // 图表初始化函数
        var chart = Highcharts.chart('container', options);
    </script>
</body>

<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>

<!-- 登录 二维码 -->
<div class="bg_div1" style="display:none;background:#ccc;width:500px;height:500px;position:absolute;top:10%;left:30%;opacity:0.8;text-align:center;padding-top:5%">
    <div class="clone_div1" style="padding-left:65%">
         <b>关闭</b>
    </div>
    <img src="http://qr.liantu.com/api.php?text=<?= $code_url?>" style="width: 350px">
</div>

<script type="text/javascript">
    //登录二维码
          $('.qrcode_img1').click(function(){
              //背景层显示
              $('.bg_div1').show();
          })
            //点关闭 隐藏背景
            $('.clone_div1').click(function(){
              $('.bg_div1').hide();
            })
</script>
<script type="text/javascript">
     /*验证配置*/
  wx.config({
    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '<?= $data["appId"] ?>', // 必填，公众号的唯一标识
    timestamp: <?= $data["timestamp"] ?>, // 必填，生成签名的时间戳
    nonceStr: '<?= $data["nonceStr"] ?>', // 必填，生成签名的随机串
    signature: '<?= $data["signature"] ?>',// 必填，签名
    jsApiList: ['chooseWXPay'] // 必填，需要使用的JS接口列表
  });

    wx.ready(function () {
         wx.chooseWXPay({
            timestamp: 0, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
            nonceStr: '', // 支付签名随机串，不长于 32 位
            package: '', // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=\*\*\*）
            signType: '', // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
            paySign: '', // 支付签名
            success: function (res) {
            // 支付成功后的回调函数
            }
        });

  });

   
</script>
@endsection
