<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Coulist extends Model
{
   	protected $table = 'coulist';
   	protected $primaryKey = 'coulist_id';
   	public $timestamps = true;
   	protected $guarded = [];
}