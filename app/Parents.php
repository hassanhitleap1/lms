<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Parents extends Model {

    protected $table = 'parents';

    protected  $primaryKey='id';
    public $timestamps = true;


    public function  students(){
        return $this->hasMany(Users::class,'userid','student_id');
    }
    public function  parentInfo(){
        return $this->hasOne(Users::class,'userid','parent_id');
    }

    public function  studentInfo(){
        return $this->hasOne(Users::class,'userid','student_id');
    }

}
