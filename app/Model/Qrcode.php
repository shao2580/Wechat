<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Qrcode extends Model
{
   	protected $table = 'qrcode';
   	protected $primaryKey = 'qrcode_id';
   	public $timestamps = false;
   	protected $guarded = [];
}
