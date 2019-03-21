<?php

namespace App;

use Eloquent;

class Domains extends Eloquent {

	protected $table = 'domains';
	public $timestamps = true;
	protected $primaryKey = 'domain_id';



	protected $dates = ['deleted_at'];

	public function pivots(){
        return $this->hasMany(Pivot::class,'domain','domain_id');
    }

    public function categoryInfo(){
        return $this->hasOne(Categories::class,'category_id','category');
    }

}