<?php

namespace App;

use Eloquent;

class LessonMidea extends Eloquent
{
    protected  $table="lesson_media";
    protected $primaryKey = 'id';


    public function result($userid,$lessonid=0){
        return $this->hasOne(ResultMedia::class,'id_media','id')
            ->where('user_id',$userid)
            ->where('lesson_id',$lessonid)
            ->first();


    }
}
