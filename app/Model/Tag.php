<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Wechat;

class Tag extends Model
{
   	protected $table = 'tag';
   	protected $primaryKey = 'tag_id';
   	public $timestamps = true;
   	protected $guarded = [];
}