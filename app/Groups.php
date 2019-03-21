<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Groups extends Model {

	protected $table = 'groups';
	public $timestamps = true;
	protected $primaryKey = 'group_id';
	//use SoftDeletingTrait;

	protected $dates = ['deleted_at'];


	public static function messages()
	{
		return [
			'gname.required' => 'Group Name is Required',
			'gdesc.required'  => 'Group Description is Required',
			'teacher.required'  => 'Teacher  is Required',
		];
	}
    public function teacherInfo(){
	    return $this->hasOne(Users::class,'userid','teacher');
    }



	public function  getGroup(){
        $query=  DB::table('groups')
            ->leftJoin("assigns",function($join){
                $join->on('assigns.product_id' ,'=',  'groups.group_id')
                    ->where('assigns.product_type','group')
                    ->where('assigns.ref_type','teacher');
            })
            ->leftJoin('users', 'assigns.ref_id', '=', 'users.userid')
            ->select( 'groups.group_id', 'groups.title_ar', 'groups.title_en' ,'groups.description_ar', 'groups.description_en' ,
                'assigns.assign_id' ,'assigns.ref_id'  ,'assigns.ref_type' ,'users.fullname','users.userid');

        if(Users::isTeacher()){
            $query->where('users.userid',Auth::user()->userid);
        }
        if(Users::isStudent()){
            $query=  DB::table('groups')
                ->leftJoin("assigns",function($join){
                    $join->on('assigns.product_id' ,'=',  'groups.group_id')
                        ->where('assigns.product_type','group')
                        ->where('assigns.ref_id',Auth::user()->userid);
                })
                ->leftJoin('users', 'assigns.ref_id', '=', 'users.userid')
                ->select( 'groups.group_id', 'groups.title_ar', 'groups.title_en' ,'groups.description_ar', 'groups.description_en' ,
                    'assigns.assign_id' ,'assigns.ref_id'  ,'assigns.ref_type' ,'users.fullname as st_fullname','users.userid'
                    ,DB::raw("(SELECT users.fullname  FROM groups gr
                    left JOIN assigns ON 
                            assigns.product_id=gr.group_id and
                            assigns.product_type= 'group' and
                            assigns.ref_type='teacher'
                            
                    left JOIN users ON 
                            assigns.ref_id=users.userid                       
                      where groups.group_id = gr.group_id ) as fullname"));
            $query->where('users.userid',Auth::user()->userid);

        }

        if(Users::isParent()){
            $ids=array();
            foreach (Auth::user()->students  as $student ){
                  $ids[]=$student->student_id;
            }
            $query=  DB::table('groups')
                ->leftJoin('assigns', function($join) use($ids){
                    $join->on('assigns.product_id' ,'=',  'groups.group_id')
                        ->where('assigns.product_type','group')
                        ->whereIn('assigns.ref_id',$ids);
                })
                ->leftJoin('users', 'assigns.ref_id', '=', 'users.userid')
                ->select( 'groups.group_id', 'groups.title_ar', 'groups.title_en' ,'groups.description_ar', 'groups.description_en' ,
                    'assigns.assign_id' ,'assigns.ref_id'  ,'assigns.ref_type' ,'users.fullname as st_fullname','users.userid'
                    ,DB::raw("(SELECT users.fullname  FROM groups gr
                    left JOIN assigns ON 
                            assigns.product_id=gr.group_id and
                            assigns.product_type= 'group' and
                            assigns.ref_type='teacher'
                            
                    left JOIN users ON 
                            assigns.ref_id=users.userid                       
                      where groups.group_id = gr.group_id ) as fullname"));
            $query->whereIn('users.userid',$ids);
//        echo $query->toSql();
//        exit;
        }
        return $query;
    }

    public function assings(){
	    return $this->hasMany(Assigns::class,'product_id','group_id')->where('product_type','group');
    }


    public function assignsForHomeworks(){
        return $this->hasMany(HomeworkAssign::class,'id_target','group_id')
            ->where('assigntype','group');
    }

    public function assignsForQuiz(){
        return $this->hasMany(QuizAssign::class,'id_target','group_id')
            ->where('assigntype','group');
    }

}