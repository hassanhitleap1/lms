<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Curriculums extends Model
{
    //
    protected $table = 'curriculums';
    protected $primaryKey='curriculumsid';
    public $timestamps = true;

    /**
     * @cerateby hasan kiwan
     * function return all lessons of curriculum
     * @return Relations
     */
    public  function lessons(){
        return $this->hasMany(Lessons::class,'curricula','curriculumsid');
    }
}


