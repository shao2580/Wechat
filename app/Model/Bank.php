<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Wechat;

class Bank extends Model
{
   	protected $table = 'bank';
   	protected $primaryKey = 'bank_id';
   	protected $guarded = [];
}