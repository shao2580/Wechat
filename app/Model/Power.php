<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Power extends Model
{
   	protected $table = 'power';
   	protected $primaryKey = 'power_id';
   	public $timestamps = true;
   	protected $guarded = [];
}