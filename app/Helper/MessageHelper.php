<?php
/**
 * Created by PhpStorm.
 * User: Saleh
 * Date: 12/11/2018
 * Time: 1:26 PM
 */

namespace App\Helper;


use App\Classes;
use App\Groups;
use App\Levels;
use App\Messages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageHelper
{
    /**
     * @create_by hasan kiwan
     * @param $messsage
     * @param $userid
     * store message in db when send message single message
     */
    public static function   sendMessageToUser($messsage,$userid){
        $message= new Messages();
        $message->text=$messsage;
        $message->sender_user_id=Auth::user()->userid;
        $message->receiver_user_id=$userid;
        $message->mss_read_sender=Messages::READ;
        $message->mss_read_receiver=$message::UNREAD;
        $message->save();
    }

    /**
     * @create_by hasan kiwan
     * @param $message
     * @param $id
     * @job store message form all users in level selected
     */
    public static  function  sendMessageTolevelUsers($message,$id){
        $countRow=0;
        $arrRow=Array();
        $levelModel=Levels::find($id);
        foreach ($levelModel->students as $student){
            $arrRow[$countRow]=[
                'text'=>$message,
                'sender_user_id'=>Auth::user()->userid,
                'receiver_user_id'=>$student->userid,
                'mss_read_sender'=>Messages::READ,
                'mss_read_receiver'=>Messages::UNREAD,
                'created_at'=>date('Y-m-d H:m:s'),
                'updated_at'=>date('Y-m-d H:m:s'),
            ];
            $countRow++;
        }

        if($countRow){
            DB::table('messages')->insert($arrRow);
        }
    }

    /**
     * @create_by hasan kiwan
     * @param $message
     * @param $id
     * @job store message form all users in class selected
     */
    public static  function  sendMessageToClassUsers($message,$id){
        $countRow=0;
        $arrRow=Array();
        $classModel=Classes::find($id);
        foreach ($classModel->students as $student){
            $arrRow[$countRow]=[
                'text'=>$message,
                'sender_user_id'=>Auth::user()->userid,
                'receiver_user_id'=>$student->userid,
                'mss_read_sender'=>Messages::READ,
                'mss_read_receiver'=>Messages::UNREAD,
                'created_at'=>date('Y-m-d H:m:s'),
                'updated_at'=>date('Y-m-d H:m:s'),
            ];
            $countRow++;
        }

        if($countRow){
            DB::table('messages')->insert($arrRow);
        }
    }

    /**
     * @create_by hasan kiwan
     * @param $message
     * @param $id
     * @job store message form all users in group selected
     */
    public static  function  sendMessageToGroupUsers($message,$id){
        $countRow=0;
        $arrRow=Array();
        $groupModel=Groups::find($id);
        echo $groupModel->assings->count();
        foreach ($groupModel->assings as $assing){
            $arrRow[$countRow]=[
                'text'=>$message,
                'sender_user_id'=>Auth::user()->userid,
                'receiver_user_id'=>$assing->ref_id,
                'mss_read_sender'=>Messages::READ,
                'mss_read_receiver'=>Messages::UNREAD,
                'created_at'=>date('Y-m-d H:m:s'),
                'updated_at'=>date('Y-m-d H:m:s'),
            ];
            $countRow++;
        }

        if($countRow){
            DB::table('messages')->insert($arrRow);
        }
    }
}