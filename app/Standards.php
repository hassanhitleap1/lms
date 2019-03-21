<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Eloquent;
use App\Pivot;
use App\Domains;
use App\Categories;
class Standards extends Eloquent {

	protected $table = 'standards';
	public $timestamps = true;
	protected $primaryKey = 'standard_id';

	//use SoftDeletes;

	protected $dates = ['deleted_at'];

	public  function categoryInfo(){
        return $this->hasOne(Categories::class ,'category_id','category');
    }

    public  function  domainInfo(){
        return $this->hasOne(Domains::class ,'domain_id','domain');
    }

    public  function  pivotInfo(){
        return $this->hasOne(Pivot::class ,'pivot_id','pivot');
    }
}