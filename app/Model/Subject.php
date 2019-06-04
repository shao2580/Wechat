<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Wechat;

class Subject extends Model
{
   	protected $table = 'subject';
   	protected $primaryKey = 'subject_id';
   	public $timestamps = true;
   	protected $guarded = [];
}