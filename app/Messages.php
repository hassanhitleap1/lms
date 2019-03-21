<?php

namespace App;


use App\Helper\SqlHelper;
use Eloquent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Messages extends Eloquent
{
    const READ=1;
    const UNREAD=0;
    protected $table = 'messages';
    public $timestamps = true;
    protected $primaryKey = 'message_id';
    protected $dates = ['deleted_at'];
//message_id	text	sender_user_id	receiver_user_id	mss_read_receiver	created_at	updated_at	deleted_at


    public static  function getAllMessagesUser(){
        return Self::where(function($query){
            $query->orwhere('sender_user_id',Auth::user()->userid )
                ->orwhere('receiver_user_id', Auth::user()->userid);
        });
    }


    public static function getAllMessagesUserUnRead(){
        return Self::where('receiver_user_id',Auth::user()->userid)->
        where('mss_read_receiver',self::UNREAD)->with('senderInfo');
    }


    public function senderInfo(){
        return $this->belongsTo(Users::class,'sender_user_id','userid');
    }

    public function receiverInfo(){
        return $this->belongsTo(Users::class,'receiver_user_id','userid');
    }

    // to qeray to get user send message nade and user reicved message


    public static function getUserReceivedMess()
    {
        //select DISTINCT receiver_user_id from `messages` where sender_user_id=57
//        select DISTINCT messages.receiver_user_id , users.userid,users.fullname
//        from `messages`
//        INNER JOIN users ON users.userid = messages.receiver_user_id
//        where sender_user_id=57
        $query = DB::table('messages')
            ->select('messages.receiver_user_id','messages.created_at'  , 'users.userid' ,'users.fullname')
            ->join('users', 'users.userid', '=', 'messages.receiver_user_id')
            ->where('messages.sender_user_id',Auth::user()->userid)->distinct();

        return $query;
    }
    public static function getUserSendMess(){
//        select DISTINCT sender_user_id from `messages` where receiver_user_id=57
//        select DISTINCT messages.sender_user_id  , users.userid,users.fullname
//        from `messages`
//        INNER JOIN users ON users.userid = messages.sender_user_id
//        where receiver_user_id=57
        $query = DB::table('messages')
            ->select('messages.sender_user_id','messages.created_at' , 'users.userid' ,'users.fullname')
            ->join('users', 'users.userid', '=', 'messages.sender_user_id')
            ->where('messages.sender_user_id',Auth::user()->userid)->distinct();
        return $query;

    }


    public static function getMassPerToPer($with){
        return Self::where(function($query){
            $query->orwhere('sender_user_id',Auth::user()->userid )
                ->orwhere('receiver_user_id', Auth::user()->userid);
        })->where(function($query ) use ($with) {
            $query->orwhere('sender_user_id',$with )
                ->orwhere('receiver_user_id', $with);
        })->orderBy('created_at', 'ASC');

    }

    public static  function getLastMessages($with){
        return Self::where('receiver_user_id', Auth::user()->userid)
            ->where('sender_user_id', $with)
            ->where('mss_read_receiver', self::UNREAD);

    }

    public static function getUserChatWithHim(){
//        select parent_mess.sender_user_id as id ,parent_mess.created_at as time_mess ,users.userid ,users.fullname
//        from `messages` parent_mess
//        INNER JOIN users ON users.userid = parent_mess.sender_user_id
//        where parent_mess.receiver_user_id=57 GROUP BY parent_mess.sender_user_id
//        UNION
//        select parent_mess.receiver_user_id as id ,parent_mess.created_at as time_mess ,users.userid ,users.fullname
//        from `messages` parent_mess
//        INNER JOIN users ON users.userid = parent_mess.receiver_user_id
//        where parent_mess.sender_user_id=57 and parent_mess.receiver_user_id not in
//          ( select DISTINCT sender_user_id from `messages` where receiver_user_id=57)
//           GROUP BY parent_mess.receiver_user_id
        $queryOne = DB::table('messages as parent_mess')
            ->select('parent_mess.sender_user_id as id','parent_mess.created_at as time_mess','users.userid' ,'users.fullname')
            ->join('users', 'users.userid', '=', 'parent_mess.sender_user_id')
            ->where('parent_mess.receiver_user_id',Auth::user()->userid)
            ->groupBy('parent_mess.sender_user_id')
        ;
//        SqlHelper::printSql($queryOne);
        //select DISTINCT sender_user_id from `messages` where receiver_user_id=57
        $queryWithOut = DB::table('messages')
                        ->select('sender_user_id')
                        ->where('receiver_user_id',Auth::user()->userid)->distinct();

        $queryTwo= DB::table('messages as  parent_mess')
            ->select('parent_mess.receiver_user_id as id','parent_mess.created_at as time_mess','users.userid' ,'users.fullname')
            ->join('users', 'users.userid', '=', 'parent_mess.receiver_user_id')
            ->where('parent_mess.sender_user_id',Auth::user()->userid)
            ->whereNotIn('parent_mess.receiver_user_id',$queryWithOut)
            ->groupBy('parent_mess.receiver_user_id')
        ;
        $queryOne->union($queryTwo);
        $queryOne->orderBy('time_mess', 'DESC'); //ASC|DESC
        return $queryOne;
    }

    public static function getUserChatWithHimUnRead(){
        $queryOne = DB::table('messages as parent_mess')
            ->select('parent_mess.sender_user_id as id','parent_mess.created_at as time_mess','users.userid' ,'users.fullname')
            ->join('users', 'users.userid', '=', 'parent_mess.sender_user_id')
            ->where('parent_mess.receiver_user_id',Auth::user()->userid)
            ->where('parent_mess.mss_read_receiver',self::UNREAD)
            ->groupBy('parent_mess.sender_user_id')
        ;
//        SqlHelper::printSql($queryOne);
        //select DISTINCT sender_user_id from `messages` where receiver_user_id=57
        $queryWithOut = DB::table('messages')
            ->select('sender_user_id')
            ->where('receiver_user_id',Auth::user()->userid)->distinct();

        $queryTwo= DB::table('messages as  parent_mess')
            ->select('parent_mess.receiver_user_id as id','parent_mess.created_at as time_mess','users.userid' ,'users.fullname')
            ->join('users', 'users.userid', '=', 'parent_mess.receiver_user_id')
            ->where('parent_mess.sender_user_id',Auth::user()->userid)
            ->where('parent_mess.mss_read_receiver',self::UNREAD)
            ->whereNotIn('parent_mess.receiver_user_id',$queryWithOut)
            ->groupBy('parent_mess.receiver_user_id')
        ;
        $queryOne->union($queryTwo);
        $queryOne->orderBy('time_mess', 'ASC'); //ASC|DESC
        return $queryOne;
    }

    public static  function readLastMessages(){
        return Self::where('receiver_user_id', Auth::user()->userid)
            ->where('mss_read_receiver', self::UNREAD);
    }
}
