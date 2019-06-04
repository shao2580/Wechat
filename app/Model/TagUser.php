<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TagUser extends Model
{
   	protected $table = 'tag_user';
   	protected $primaryKey = 'tag_id';
   	public $timestamps = true;
   	protected $guarded = [];
}