<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Levels;
use App\Classes;
use App\Parents;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class Users extends Authenticatable
{

    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'userid';
    const USER_MANHAL_ADMINISTRATOR=1;
    const USER_SCHOOL_MANGER=2;
    const USER_SCHOOL_ADMINISTRATOR=3;
    const USER_TEACHER=4;
    const USER_STUDENT=5;
    const USER_PARENT=6;
    const MALE=0;
    const FEMALE=1;
    const PR_STUDENT='student';

    protected $hidden = [
        'password','remember_token',
    ];

    // relation for git all students for parent
    public function  students(){
        return $this->hasMany(Parents::class,'parent_id','userid');
    }
    // relation for git parent one parent
    public  function parent(){
        return $this->hasOne(Parents::class ,'student_id','userid');
    }
    // get class (one)for user
    public function userClass(){
        return $this->belongsTo(Classes::class,'class','class_id');
    }
    // get level (one)for user
    public function userLevel(){
        return $this->belongsTo(Levels::class,'level','level_id');
    }
    // get all student
    public static function allStudents(){
        return  $parents= self::where("permession",self::USER_STUDENT);
    }
    // get All teacher
    public static function allTeachers(){
        return  $parents= self::where("permession",self::USER_TEACHER);
    }
    // get All admins
    public static function allAdmins(){
        return  $parents= self::where("permession",self::USER_SCHOOL_ADMINISTRATOR);
    }

    // relation for get badges for user
    public  function badges($categoryId){
        return $this->hasMany(BadgesUser::class ,'user_id','userid')
            ->where('category_id',$categoryId);
    }

    // relation for get badges for user
    public  function allBadges(){
        return $this->hasMany(BadgesUser::class ,'user_id','userid');
    }
    // relation for get Progres
    public function progres(){
        return $this->hasOne(Progress::class,'ref_id','userid')
            ->where('type',self::PR_STUDENT);
    }

    // relation for get Progres
    public function mark($categoryId){
        return $this->hasOne(Mark::class,'user_id','userid')
            ->where('category_id',$categoryId)->first();
    }

    public function marks(){
        return $this->hasMany(Mark::class,'user_id','userid');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hasTeacherSchedule(){
        return $this->hasMany(Schedule::class,'teacher','userid');
    }

    /*
   * check is school manger
   */
    public  static function  isSchoolManger(){
        if(self::USER_SCHOOL_MANGER == Auth::user()->permession){
            return true;
        }
        return false;
    }



    /*
     * check is manhal manger
     */
    public  static function  isManhalAdmin(){
        if(self::USER_MANHAL_ADMINISTRATOR == Auth::user()->permession){
            return true;
        }
        return false;
    }

    /*
    * check is school admin
    */
    public  static function  isSchoolAdmin(){
        if(self::USER_SCHOOL_ADMINISTRATOR == Auth::user()->permession){
            return true;
        }
        return false;
    }

    /*
    * check is teacher
    */
    public  static function  isTeacher(){
        if(self::USER_TEACHER == Auth::user()->permession){
            return true;
        }
        return false;
    }

    /*
    * check is student
    */
    public  static function  isStudent(){
        if(self::USER_STUDENT == Auth::user()->permession){
            return true;
        }
        return false;
    }

    /*
    * check is student
    */
    public  static function  isParent(){
        if(self::USER_PARENT == Auth::user()->permession){
            return true;
        }
        return false;
    }
    public function assignsForHomeworks(){
        return $this->hasMany(HomeworkAssign::class,'id_target','userid')
            ->where('assigntype','student');
    }

    public function assignsForQuiz(){
        return $this->hasMany(QuizAssign::class,'id_target','userid')
            ->where('assigntype','student');
    }

    public function assignsGroupsStudent(){
        return $this->hasMany(Assigns::class,'ref_id','userid')
            ->where('ref_type','student')
            ->where('product_type','group');
    }

    public function groupsInfoTeacher(){
        return $this->hasMany(Groups::class,'teacher','userid');
    }
}
