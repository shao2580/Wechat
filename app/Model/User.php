<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Wechat;
use App\Model\Tag;      //标签表

class User extends Model
{	
   	protected $table = 'user';
   	protected $primaryKey = 'user_id';
   	public $timestamps = true;
   	protected $guarded = [];

   	/*创建粉丝标签*/
   	public static function createTag($tag_name)
   	{	
   		$access_token = Wechat::getToken();

   		$url = "https://api.weixin.qq.com/cgi-bin/tags/create?access_token={$access_token}";
   		$post_data = [];
   		$post_data['tag']['name'] =$tag_name;

   		$post_data = json_encode($post_data,JSON_UNESCAPED_UNICODE);

   		$data = Wechat::curlPost($url,$post_data);

   		return $data;
   	}

      /*获取已创建的粉丝标签列表---并入库*/
      public static function getTag()
      {
         $access_token = Wechat::getToken();
         $url = "https://api.weixin.qq.com/cgi-bin/tags/get?access_token={$access_token}";

         $data = file_get_contents($url);
         $data = json_decode($data,true);
         // dd($data);
         $tagdata = [];
         foreach ($data['tags'] as $key => $value) {
            $tagdata[]=[
               'wechat_tag_id'=>$value['id'],
               'tag_name'=>$value['name']
            ];
         }
         // dd($tagdata);
         $tagdata = Tag::insert($tagdata);
      }

      /*删除标签*/
      public static function deleteTag($qrcode_key)
      {
         $access_token = Wechat::getToken();
         $url = "https://api.weixin.qq.com/cgi-bin/tags/delete?access_token={$access_token}";

         $data = '{"tag":{"id":'.$qrcode_key.'}}';

         $data = Wechat::curlPost($url,$data);
         return $data;
      }
}
