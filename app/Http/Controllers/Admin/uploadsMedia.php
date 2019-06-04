<?php 

$access_token = "21_RIyDjMIWpZqafYrNaJMpEmlfmMy9GOy_KzMvYpYzPoVnxwhnJLT6S_PMGP9OpIrkg4x4T5rCSE3bGZF3xXahXwqafDMlO7yhzk35byZrSZAUfCH-DMz98ploUHw2TccxdsheYZi2iIfiNJ5FXCIjAJAQVB";

$url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$access_token}&type=image";
//素材路径 必须是绝对路径 
$img = "\phpStudy\PHPTutorial\WWW\xinxinxiangyin.jpg";
$imgPath = new CURLFile($img); //通过CURLFile处理
$post_data = [
	'media'=>$imgPath  //素材路径 
];
//发请求
$res = curlPost($url,$post_data);
var_dump($res);die;

function curlPost($url,$post_data)
{
	 //初始化
	 $curl = curl_init();
	 //设置抓取的url
	 curl_setopt($curl, CURLOPT_URL,$url);
	 //设置获取的信息以文件流的形式返回，而不是直接输出。
	 curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	 //设置post方式提交
	 curl_setopt($curl, CURLOPT_POST, 1);
	 //设置post数据
	 curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
	 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
	 //执行命令
	 $data = curl_exec($curl);
	 //关闭URL请求
	 curl_close($curl);
	 //显示获得的数据
	 return $data;
}

