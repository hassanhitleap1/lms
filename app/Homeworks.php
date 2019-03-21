<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Homeworks extends Model {

	protected $table = 'homeworks';
    protected $primaryKey='homework_id';
	public $timestamps = true;
	protected $dates = ['deleted_at'];
	public function categoryInfo(){
	    return $this->hasOne(Categories::class,'category_id','category');
    }

    /**
     * create by hasan kiwan
     * this function relation who create homework
     */
    public function createBy(){
        return $this->hasOne(Users::class,'userid','teacher');
    }

    /**
     * create by hasan kiwan
     * this function relation who create homework
     */
    public function media(){
        return $this->hasMany(Homeworkmedia::class,'id_homework','homework_id');
    }

}