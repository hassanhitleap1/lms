<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizMedia extends Model
{
    protected  $table='quiz_media';
    protected $primaryKey='id';


    public function result($userid,$idQuiz=0){

        return $this->hasMany(  QuizResult::class,'id_assign','id_media')
            ->where('id_user',$userid)
            //->where('id_homework',$homeworkid)
            ->first();



    }
}
