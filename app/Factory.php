<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factory extends Model
{
	use SoftDeletes;

    public function children() {
    	return $this->hasMany('App\Child');
    }
}
