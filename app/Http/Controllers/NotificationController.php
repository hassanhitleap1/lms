<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Response;
use  App\Notification;

class NotificationController extends BaseController
{
    public function get($lang){
       $notifications=Notification::where('user_id',Auth::user()->userid)->where('mss_read',Notification::UNREAD)->orderBy('created_at', 'desc');
        $count =$notifications->count();
        return Response::json([
            'notification' => $notifications->get(),
            'count'=>$count,
        ], 201);
    }


    public function viewAll($lang){
        $notifications=Notification::where('user_id',Auth::user()->userid);
        $notificationsUnRead = $notifications->get();
        $notifications->update([
            'mss_read' => Notification::READ,
        ]);
        return view('viewnotification')->with('notifications' ,$notificationsUnRead);
    }


    public function readLastNotif($lang){
        $notifications=Notification::where('user_id',Auth::user()->userid)
            ->where('mss_read',Notification::UNREAD)
            ->update(['mss_read' => Notification::READ]);
        return Response::json([
            'code' => 201,
        ], 201);
    }

    public function loadMore($lang){
        $notifications=Notification::where('user_id',Auth::user()->userid)->paginate(5);
        return Response::json([
            'notifications' => $notifications,
        ], 201);
    }
}
