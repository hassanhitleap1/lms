<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use App\Users;
use App\Levels;


class Classes extends Model {

	protected $table = 'classes';
	public $timestamps = true;
	protected $primaryKey = 'class_id';
	//protected $fillable=[];

	 //use SoftDeletingTrait;

	protected $dates = ['deleted_at'];

    public function users(){
       return $this->hasMany(Users::class,'class','class_id');
	}
	
	public function level(){
        return $this->belongsTo(Levels::class,'level_id','level');
	}

    public function students(){
        return $this->hasMany(Users::class,'class','class_id')->where('permession',Users::USER_STUDENT);
    }


    public function assignsForHomeworks(){
        return $this->hasMany(HomeworkAssign::class,'id_target','class_id')
            ->where('assigntype','classes');
    }

    public function assignsForQuiz(){
        return $this->hasMany(QuizAssign::class,'id_target','class_id')
            ->where('assigntype','class');
    }
}