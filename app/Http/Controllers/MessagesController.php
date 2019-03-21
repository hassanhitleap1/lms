<?php

namespace App\Http\Controllers;

use App\Messages;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Response;

class MessagesController extends BaseController
{
    public function getAllMessages(){
        $messages=Messages::getUserChatWithHimUnRead();
        $count =Messages::getAllMessagesUserUnRead()->count();
        return Response::json([
            'messages' => $messages->get(),
            'count'=>$count,
        ], 201);
    }

    public function  showAllMessages(){
        $messages=Messages::getUserChatWithHim();
        return view('user.show_message')->with('messages',$messages->get());
    }


    public function viewchat($lang){
        $with= @$_GET['with'];
        if(!empty($with)){
            $messages=Messages::getMassPerToPer($with);

            return view('user.viewchat')->with('messages',$messages->get());
        }
        return redirect(App::getLocale().'/home');
    }


    public function sendMess($lang,Request $request){
        $message= new Messages;
        $message->text=$request->text;
        $message->sender_user_id=Auth::user()->userid;
        $message->receiver_user_id=$request->withUser;
        $message->mss_read_sender=Messages::READ;
        $message->mss_read_receiver=$message::UNREAD;
        $message->save();
        return Response::json([
            'text' => $request->text,
        ], 201);
    }

    public  function getLastMessages($lang,Request $request){
        $model=Messages::getLastMessages($request->withUser);
        $lastMessages=$model->get();
        if($lastMessages->count()){
            $ModelSaved=$model;
            $ModelSaved->update(['mss_read_receiver' => Messages::READ]);

        }
        return Response::json([
            'lastMessages' => $lastMessages,
        ], 201);
    }


    public  function readLastMessages($lang,Request $request){
        $model=Messages::readLastMessages();
       $model->update(['mss_read_receiver' => Messages::READ]);
        return Response::json([
          'code'=>201
        ], 201);
    }

    public function loadMore($lang){
        $messages=Messages::getUserChatWithHim()->simplePaginate(5);
        return Response::json([
            'messages' => $messages,
        ], 201);
    }
}
