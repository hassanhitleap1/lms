<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizAssign extends Model
{
    protected  $table='quizassign';
    protected $primaryKey = 'id';

   // const MessgesValidetion=['assignType.required' => 'please select type.','assignTo.required' => 'who assign to.'];


    public function infoStudent(){
        return $this->hasOne(Users::class,'userid','id_target');
    }
    public function infoGroup(){
        return $this->hasOne(Groups::class,'group_id','id_target');
    }

    public function infoClass(){
        return $this->hasOne(Classes::class,'class_id','id_target');
    }

    public function quizInfo(){
        return $this->hasOne(Quiz::class,'quiz_id','id_quiz');
    }
}
