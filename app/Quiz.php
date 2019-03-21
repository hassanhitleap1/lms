<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected  $table='quiz';
    public $timestamps = true;
    protected $primaryKey = 'quiz_id';

    public function  infoLevel(){
        return $this->hasOne(Levels::class,'level_id','level');
    }

    public function  infoCategory(){
        return $this->hasOne(Categories::class,'category_id','category');
    }
    public function  media(){
        return $this->hasMany(QuizMedia::class,'id_quiz','quiz_id');
    }

    public function createBy(){
        return $this->hasOne(Users::class,'userid','teacher');
    }
}
