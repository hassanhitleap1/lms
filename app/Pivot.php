<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Eloquent;
use App\Categories;
use App\Domains;
class Pivot extends Eloquent {

	protected $table = 'pivot';
	public $timestamps = true;
	protected $primaryKey = 'pivot_id';

	//	use SoftDeletes;

	protected $dates = ['deleted_at'];

    public  function categoryInfo(){
        return $this->hasOne(Categories::class ,'category_id','category');
    }

    public  function domainInfo(){
        return $this->hasOne(Domains::class ,'domain_id','domain');
    }

    public function standards(){
        return $this->hasMany(Standards::class,'pivot','pivot_id');
    }
}