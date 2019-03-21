<?php
/**
 * Created by PhpStorm.
 * User: Saleh
 * Date: 8/30/2018
 * Time: 9:02 AM
 */

namespace App\Helper;



use App\Messages;
use App\Notification;
use App\Users;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class NotificationHelper
{
    /*
     * set link with base url
     */
    private  static function  getlink($link)
    {
        return URL::to('/').'/'.App::getLocale().$link;
    }
    /*
     * add notifcation whene add new user
     */
  public static function n_adduser($typeUser,$link ,$userid){
      DB::table('notifications')->insert(
          [
              'message' => 'add new'.$typeUser.' by '.Auth::user()->fullname,
              'link' =>  self::getlink($link),
              'date'=>date('Y-m-d h:m:s'),
              'type'=>  Notification::TYPE_person_add,
              'color_type'=> Notification::ICON_COLOR_bg_light_green,
              'mss_read'=>Notification::UNREAD,
              'user_id'=>$userid,
              'by_user'=>Auth::user()->userid,
              'created_at'=>date('Y-m-d h:m:s'),
              'updated_at'=>date('Y-m-d h:m:s'),

          ]
      );
      DB::table('messages')->insert(
          [
              'text' => 'you are welcome ',
              'sender_user_id'=>Auth::user()->userid,
              'receiver_user_id'=>$userid,
              'mss_read_sender'=>Messages::READ,
              'mss_read_receiver'=>Messages::UNREAD,
              'created_at'=>date('Y-m-d H:m:s'),
          ]
      );
  }
    /*
     * notifaction update user by admin
     */
    public static function n_updateUser($typeUser,$link ,$userid){
        DB::table('notifications')->insert(
            [
                'message' => 'update information '.$typeUser.' by '.Auth::user()->fullname,
                'link' =>  self::getlink($link),
                'date'=>date('Y-m-d h:m:s'),
                'type'=>  Notification::TYPE_mode_edit,
                'color_type'=> Notification::ICON_COLOR_bg_blue_grey,
                'mss_read'=>Notification::UNREAD,
                'user_id'=>$userid,
                'by_user'=>Auth::user()->userid,
                'created_at'=>date('Y-m-d h:m:s'),
                'updated_at'=>date('Y-m-d h:m:s'),

            ]
        );
    }

    /*
     * notifaction change password by admin
     */
    public static function n_changePasswordUser($link ,$userid){
        DB::table('notifications')->insert(
            [
                'message' => 'change password  by '.Auth::user()->fullname,
                'link' =>  self::getlink($link),
                'date'=>date('Y-m-d h:m:s'),
                'type'=>  Notification::TYPE_settings,
                'color_type'=> Notification::ICON_COLOR_bg_purple,
                'mss_read'=>Notification::UNREAD,
                'user_id'=>$userid,
                'by_user'=>Auth::user()->userid,
                'created_at'=>date('Y-m-d h:m:s'),
                'updated_at'=>date('Y-m-d h:m:s'),

            ]
        );
    }

    /*
     * notifaction add user from group
     */
    public static function n_addUserToGroup($groupName,$link ,$userid){
        DB::table('notifications')->insert(
            [
                'message' => 'add user to group'.$groupName.' by '.Auth::user()->fullname,
                'link' =>  self::getlink($link),
                'date'=>date('Y-m-d h:m:s'),
                'type'=>  Notification::TYPE_person_add,
                'color_type'=> Notification::ICON_COLOR_bg_blue_grey,
                'mss_read'=>Notification::UNREAD,
                'user_id'=>$userid,
                'by_user'=>Auth::user()->userid,
                'created_at'=>date('Y-m-d h:m:s'),
                'updated_at'=>date('Y-m-d h:m:s'),

            ]
        );
    }

    /*
     * notifaction remove user from group
     */
    public static function n_removeUserToGroup($groupName,$link ,$userid){
        DB::table('notifications')->insert(
            [
                'message' => 'add user to group'.$groupName.' by '.Auth::user()->fullname,
                'link' =>  self::getlink($link),
                'date'=>date('Y-m-d h:m:s'),
                'type'=>  Notification::TYPE_cached,
                'color_type'=> Notification::ICON_COLOR_bg_red,
                'mss_read'=>Notification::UNREAD,
                'user_id'=>$userid,
                'by_user'=>Auth::user()->userid,
                'created_at'=>date('Y-m-d h:m:s'),
                'updated_at'=>date('Y-m-d h:m:s'),

            ]
        );
    }


    /*
     * notifaction quiz for  user
     */
    public static function n_quizForUser($target,$type,$category,$link){
        DB::table('notifications')->insert(
            [
                'message' => 'quzi from in '.$category.' by '.Auth::user()->fullname,
                'link' =>  self::getlink($link),
                'date'=>date('Y-m-d h:m:s'),
                'type'=>  Notification::TYPE_person_add,
                'color_type'=> Notification::ICON_COLOR_bg_blue_grey,
                'mss_read'=>Notification::UNREAD,
                'user_id'=>$target,
                'by_user'=>Auth::user()->userid,
                'created_at'=>date('Y-m-d h:m:s'),
                'updated_at'=>date('Y-m-d h:m:s'),

            ]
        );
    }

    /**
     * notifcation for parent when assinged quzi
     * @param $target
     * @param $type
     * @param $category
     * @param $link
     * @param $nameStudent
     */
    public static function n_quizForParent($target,$type,$category,$link,$nameStudent){
        DB::table('notifications')->insert(
            [
                'message' => 'quzi in '.$category.' by '.Auth::user()->fullname .'for '.$nameStudent,
                'link' =>  self::getlink($link),
                'date'=>date('Y-m-d h:m:s'),
                'type'=>  Notification::TYPE_person_add,
                'color_type'=> Notification::ICON_COLOR_bg_blue_grey,
                'mss_read'=>Notification::UNREAD,
                'user_id'=>$target,
                'by_user'=>Auth::user()->userid,
                'created_at'=>date('Y-m-d h:m:s'),
                'updated_at'=>date('Y-m-d h:m:s'),

            ]
        );
    }

    /**
     * add notfiecation when assin homework to students and parents
     * @param  string $nameHomework
     * @param arrary $students
     * @param string $type
     * @param string $link
     */
    public static function n_homeworkToUsersAndParents($nameHomework,$type,$students,$link){
        $arrayInsertStudents=[];
        $arrayInsertParents=[];

            foreach ($students as $student){
                $studentModel= Users::find($student);
                if($studentModel->parent()->exists()){
                    $arrayInsertParents[]=[
                            'message' => 'Homework in '.$nameHomework.' to '.$studentModel->fullname.' by '.Auth::user()->fullname,
                            'link' =>  self::getlink($link),
                            'date'=>date('Y-m-d h:m:s'),
                            'type'=>  Notification::TYPE_add_shopping_cart,
                            'color_type'=> Notification::ICON_COLOR_bg_blue_grey,
                            'mss_read'=>Notification::UNREAD,
                            'user_id'=>$studentModel->parent->parent_id,
                            'by_user'=>Auth::user()->userid,
                            'created_at'=>date('Y-m-d h:m:s'),
                            'updated_at'=>date('Y-m-d h:m:s'),
                        ];
                }

                $arrayInsertStudents[]=[
                    'message' => 'Homework in '.$nameHomework.' by '.Auth::user()->fullname,
                    'link' =>  self::getlink($link),
                    'date'=>date('Y-m-d h:m:s'),
                    'type'=>  Notification::TYPE_add_shopping_cart,
                    'color_type'=> Notification::ICON_COLOR_bg_blue_grey,
                    'mss_read'=>Notification::UNREAD,
                    'user_id'=>$studentModel->userid,
                    'created_at'=>date('Y-m-d h:m:s'),
                    'updated_at'=>date('Y-m-d h:m:s'),
                ];
            }
        if (count($arrayInsertParents)) {
            DB::table('notifications')->insert($arrayInsertParents);
        }
        if (count($arrayInsertStudents)) {
            DB::table('notifications')->insert($arrayInsertStudents);
        }
    }

    /**
     * send notfication to teatcher when student finish Homework
     * @param string $homework // homework name
     * @param  integer $teacher // id teacher
     * @param  string $link // link when finish homework
     */
    public static  function n_sendFinishHomeworkToTeacher($homework,$teacher,$link){
        if($teacher==null){
            $teacher=0;
        }
        DB::table('notifications')->insert(
            [
                'message' => Auth::user()->fullname .'has been completaed homework'.$homework,
                'link' =>  self::getlink($link),
                'date'=>date('Y-m-d h:m:s'),
                'type'=>  Notification::TYPE_cached,
                'color_type'=> Notification::ICON_COLOR_bg_light_green,
                'mss_read'=>Notification::UNREAD,
                'user_id'=>$teacher,
                'by_user'=>Auth::user()->userid,
                'created_at'=>date('Y-m-d h:m:s'),
                'updated_at'=>date('Y-m-d h:m:s'),

            ]
        );
    }

    /**
     * send notfication to teatcher when student finish Homework
     * @param string $quiz // quiz name
     * @param  integer $teacher // id teacher
     * @param  string $link // link when finish quiz
     */
    public static  function n_sendFinishQuizToTeacher($quiz,$teacher,$link){
        if($teacher==null){
            $teacher=0;
        }
        DB::table('notifications')->insert(
            [
                'message' => Auth::user()->fullname .'has been completaed quiz'.$quiz,
                'link' =>  self::getlink($link),
                'date'=>date('Y-m-d h:m:s'),
                'type'=>  Notification::TYPE_cached,
                'color_type'=> Notification::ICON_COLOR_bg_light_green,
                'mss_read'=>Notification::UNREAD,
                'user_id'=>$teacher,
                'by_user'=>Auth::user()->userid,
                'created_at'=>date('Y-m-d h:m:s'),
                'updated_at'=>date('Y-m-d h:m:s'),

            ]
        );
    }

    /**
     * send notfication to teatcher when student finish lesson
     * @param string $lesson // lesson name
     * @param  integer $teacher // id teacher
     * @param  string $link // link when finish lesson
     */
    public static  function n_sendFinishLessonToTeacher($lesson,$teacher,$link){
        if($teacher==null){
             $teacher=0;
        }
        DB::table('notifications')->insert(
            [
                'message' => Auth::user()->fullname .'has been completaed lesson'.$lesson,
                'link' =>  self::getlink($link),
                'date'=>date('Y-m-d h:m:s'),
                'type'=>  Notification::TYPE_cached,
                'color_type'=> Notification::ICON_COLOR_bg_light_green,
                'mss_read'=>Notification::UNREAD,
                'user_id'=>$teacher,
                'by_user'=>Auth::user()->userid,
                'created_at'=>date('Y-m-d h:m:s'),
                'updated_at'=>date('Y-m-d h:m:s'),

            ]
        );
    }
}