<?php

namespace App;

use Eloquent;

class Assigns extends Eloquent {

	protected $table = 'assigns';
    protected $primaryKey = 'assign_id';
	public $timestamps = true;

//	use SoftDeletingTrait;

	protected $dates = ['deleted_at'];

    public function infoUser(){
        return $this->hasOne(Users::class,'userid','ref_id');
    }

}