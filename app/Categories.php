<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model {

	protected $table = 'categories';

    protected $primaryKey='category_id';
    public $timestamps = true;

    public function lessons(){
        return $this->hasMany(Lessons::class,'category','category_id');
    }

    public  function markByUser($userid){
        return $this->hasMany(Mark::class,'category_id','category_id')
            ->where('user_id',$userid)->first();
    }

    public function infoDomains(){
        return $this->hasMany(Domains::class,'category','category_id');
    }

}