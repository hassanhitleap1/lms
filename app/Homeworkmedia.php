<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Homeworkmedia extends Model
{
    protected $table = 'homeworkmedia';
    protected $primaryKey='id';

    public function result($userid,$homeworkid=0){

        return $this->hasMany(Homeworkresult::class,'id_assign','id_media')
            ->where('id_user',$userid)
            //->where('id_homework',$homeworkid)
            ->first();



    }
}
