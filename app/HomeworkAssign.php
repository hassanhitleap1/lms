<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomeworkAssign extends Model
{
    protected $table='homeworkassign';
    protected $primaryKey='id';

    public  function homework(){
        return $this->hasOne(Homeworks::class,'homework_id','id_homework');
    }
}
