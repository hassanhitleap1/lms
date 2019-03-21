<?php

namespace App;

use Eloquent;

class Badges extends Eloquent {

	protected $table = 'badges';
	public $timestamps = true;
    protected $primaryKey = 'badge_id';
    const ProductName='badges';
//	use SoftDeletingTrait;

	protected $dates = ['deleted_at'];


    public  function infoCategory(){
        return $this->hasOne(Categories::class ,'category_id','category');
    }
    public  function infoLevel(){
        return $this->hasOne(Levels::class ,'level_id','level_id');
    }

}