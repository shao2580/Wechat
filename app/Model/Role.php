<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
   	protected $table = 'role';
   	protected $primaryKey = 'role_id';
   	public $timestamps = true;
   	protected $guarded = [];
}