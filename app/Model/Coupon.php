<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
   	protected $table = 'coupon';
   	protected $primaryKey = 'coupon_id';
   	public $timestamps = true;
   	protected $guarded = [];
}