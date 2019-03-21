<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Eloquent;
use App\Pivot;
use App\Standards;
use App\Domains;
class Competencies extends Eloquent {

	protected $table = 'competencies';
	public $timestamps = true;
	protected  $primaryKey='compentence_id';

	//use SoftDeletes;

	protected $dates = ['deleted_at'];

	public function pivotInfo(){
        return $this->hasOne(Pivot::class ,'pivot_id','pivot');
    }
    public function standardInfo(){
        return $this->hasOne(Standards::class ,'standard_id','standard');
    }
    public function domainInfo(){
        return $this->hasOne(Domains::class ,'domain_id','domain');
    }
}