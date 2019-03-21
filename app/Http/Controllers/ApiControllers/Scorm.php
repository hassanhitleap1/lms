<?php
/**
 * Created by PhpStorm.
 * User: khalid
 * Date: 08/10/2018
 * Time: 12:19 م
 */

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Scorm extends BaseController
{

    public function getusername()
    {
        return response()->json(['username' => auth()->user()->uname]);
    }

    public function setsuccess_status()
    {
        $input = request()->all();
        $status = '';
        $mediaid = '';
        $result = '';
        $lessonid='';
        if (isset($input['status']) && $input['status'] != '') {
            $status = $input['status'];
        }
        if (isset($input['mediaid']) && $input['mediaid'] != '') {
            $mediaid = $input['mediaid'];
        }
        if (isset($input['result']) && $input['result'] != '') {
            $result = $input['result'];
        }
        if (isset($input['lessonid']) && $input['lessonid'] != '') {
            $lessonid = $input['lessonid'];
        }
        if ($mediaid == '' || $status == '' || $lessonid == '') {
            return 'error';
        }
        $media = $this->chexkedmedia($mediaid,$lessonid);
        if (!$media->isEmpty()) {
            $media = DB::table('result_media')->where('user_id', auth()->user()->userid)->where('id_media', $mediaid)->where('lesson_id',$lessonid)->update(['status' => $status]);
            $flag = 'update';
        } else {
            $flag = 'create result';
            DB::table('result_media')->insert(
                ['id' => null, 'user_id' => auth()->user()->userid, 'id_media' => $mediaid, 'result' => $result,'lesson_id'=>$lessonid, 'created_at' => date('Y-m-d h:m:h'), 'updated_at' => date('Y-m-d h:m:h'), 'status' => $status]
            );
        }
        //return $flag;
        return response()->json(['flag'=>$flag,'code'=>201,'action_media'=>'setsuccess_status']);
    }

    private function chexkedmedia($mediaid,$lessonid){
        $media = DB::table('result_media')->where('user_id', auth()->user()->userid)->where('id_media', $mediaid)->where('lesson_id',$lessonid)->get();
        return $media;
    }

    public function setscoremax()
    {
        $input = request()->all();
        $result = '';
        $mediaid = '';
        $lessonid='';
        if (isset($input['mediaid']) && $input['mediaid'] != '') {
            $mediaid = $input['mediaid'];
        }
        if (isset($input['scoremax']) && $input['scoremax'] != '') {
            $scoremax = $input['scoremax'];
        }

        if (isset($input['lessonid']) && $input['lessonid'] != '') {
            $lessonid = $input['lessonid'];
        }

        if ($mediaid == '' || $scoremax == ''  || $lessonid=='') {
            return 'error';
        }

        $media = $this->chexkedmedia($mediaid,$lessonid);
        if (!$media->isEmpty()) {
            $media = DB::table('result_media')->where('user_id', auth()->user()->userid)->where('id_media', $mediaid)->where('lesson_id',$lessonid)->update(['max' => $scoremax]);
            $flag = 'update';
        } else {
            $flag = 'create result';
            DB::table('result_media')->insert(
                ['id' => null, 'user_id' => auth()->user()->userid, 'id_media' => $mediaid, 'lesson_id'=>$lessonid,'created_at' => date('Y-m-d h:m:h'), 'updated_at' => date('Y-m-d h:m:h'), 'max' => $scoremax]
            );
        }
        //return $flag;
        return response()->json(['flag'=>$flag,'code'=>201,'action_media'=>'setscoremax']);
    }

    public function setscoremin()
    {
        $input = request()->all();
        $scoremin = '';
        $mediaid = '';
        $lessonid='';
        if (isset($input['mediaid']) && $input['mediaid'] != '') {
            $mediaid = $input['mediaid'];
        }
        if (isset($input['scoremin']) && $input['scoremin'] != '') {
            $scoremin = $input['scoremin'];
        }
        if (isset($input['lessonid']) && $input['lessonid'] != '') {
            $lessonid = $input['lessonid'];
        }

        if ($mediaid == '' || $scoremin == '' || $lessonid=='') {
            return 'error';
        }

        $media = $this->chexkedmedia($mediaid,$lessonid);
        if (!$media->isEmpty()) {
            $media = DB::table('result_media')->where('user_id', auth()->user()->userid)->where('id_media', $mediaid)->where('lesson_id',$lessonid)->update(['min' => $scoremin]);
            $flag = 'update';
        } else {
            $flag = 'create result';
            DB::table('result_media')->insert(
                ['id' => null, 'user_id' => auth()->user()->userid, 'id_media' => $mediaid,  'lesson_id'=>$lessonid, 'created_at' => date('Y-m-d h:m:h'), 'updated_at' => date('Y-m-d h:m:h'),'min' => $scoremin]
            );
        }
        //return $flag;
        return response()->json(['flag'=>$flag,'code'=>201,'action_media'=>'setscoremin']);
    }

    public function setsessiontime()
    {
        $input = request()->all();
        $time = '';
        $mediaid = '';
        $lessonid='';
        if (isset($input['mediaid']) && $input['mediaid'] != '') {
            $mediaid = $input['mediaid'];
        }
        if (isset($input['time']) && $input['time'] != '') {
            $time = $input['time'];
        }
        if (isset($input['lessonid']) && $input['lessonid'] != '') {
            $lessonid = $input['lessonid'];
        }
        if ($mediaid == '' || $time == '' || $lessonid=='') {
            return 'error';
        }
        $media = $this->chexkedmedia($mediaid,$lessonid);
        if (!$media->isEmpty()) {
            $media = DB::table('result_media')->where('user_id', auth()->user()->userid)->where('id_media', $mediaid)->where('lesson_id',$lessonid)->update(['time' => $time]);
            $flag = 'update';
        } else {
            $flag = 'create result';
            DB::table('result_media')->insert(
                ['id' => null, 'user_id' => auth()->user()->userid, 'id_media' => $mediaid,  'lesson_id'=>$lessonid,'created_at' => date('Y-m-d h:m:h'), 'updated_at' => date('Y-m-d h:m:h'), 'time' => $time]
            );
        }
       //return $flag;
        return response()->json(['flag'=>$flag,'code'=>201,'action_media'=>'setsessiontime']);
    }

    public function setscoreraw()
    {
        $input = request()->all();
        $result = '';
        $mediaid = '';
        $lessonid='';
        if (isset($input['mediaid']) && $input['mediaid'] != '') {
            $mediaid = $input['mediaid'];
        }
        if (isset($input['result']) && $input['result'] != '') {
            $result = $input['result'];
        }
        if (isset($input['lessonid']) && $input['lessonid'] != '') {
            $lessonid = $input['lessonid'];
        }

        if ($mediaid == '' || $result == '' || $lessonid=='') {
            return 'error';
        }

        $media = $this->chexkedmedia($mediaid,$lessonid);
        if (!$media->isEmpty()) {
            $media = DB::table('result_media')->where('user_id', auth()->user()->userid)->where('id_media', $mediaid)->where('lesson_id',$lessonid)->update(['result' => $result]);
            $flag = 'update';
        } else {
            $flag = 'create result';
            DB::table('result_media')->insert(
                ['id' => null, 'user_id' => auth()->user()->userid, 'id_media' => $mediaid,  'lesson_id'=>$lessonid,'created_at' => date('Y-m-d h:m:h'), 'updated_at' => date('Y-m-d h:m:h'), 'result' => $result]
            );
        }
        //return $flag;
        return response()->json(['flag'=>$flag,'code'=>201,'action_media'=>'setscoreraw']);
    }

    public function setcompletionstatus()
    {
        $input = request()->all();
        $completion = '';
        $mediaid = '';
        $lessonid ='';
        if (isset($input['mediaid']) && $input['mediaid'] != '') {
            $mediaid = $input['mediaid'];
        }
        if (isset($input['completion']) && $input['completion'] != '') {
            $completion = $input['completion'];
        }
        if (isset($input['lessonid']) && $input['lessonid'] != '') {
            $lessonid = $input['lessonid'];
        }
        if ($mediaid == '' || $completion == '' || $lessonid=='') {
            return 'error';
        }
        $media = $this->chexkedmedia($mediaid,$lessonid);
        if (!$media->isEmpty()) {
            $media = DB::table('result_media')->where('user_id', auth()->user()->userid)->where('id_media', $mediaid)->where('lesson_id',$lessonid)->update(['completed' => $completion]);
            $flag = 'update';
        } else {
            $flag = 'create result';
            DB::table('result_media')->insert(
                ['id' => null, 'user_id' => auth()->user()->userid, 'id_media' => $mediaid, 'lesson_id'=>$lessonid, 'created_at' => date('Y-m-d h:m:h'), 'updated_at' => date('Y-m-d h:m:h'), 'completed' => $completion]
            );
        }
        $Media = DB::table('lesson_media')
            ->join('categories', 'lesson_media.category', '=', 'categories.category_id')
            ->leftJoin('result_media', function($join){
                $join->on('result_media.id_media', '=', 'lesson_media.id')
                    ->where([["result_media.user_id","=",Auth::user()->userid]]);
            })
            ->select('lesson_media.id as media_id','lesson_media.*','result_media.*','categories.*')
            ->where([["lesson_media.id_lesson","=",$lessonid]])
            ->get();
        $total_points=0;
        $lesson_points=0;
        $play_count=0;
        $i=0;
        foreach($Media as $media_Item){
            $total_points+=$media_Item->result;
            $lesson_points+=100;
            if($media_Item->result!='' && $media_Item->result!=null){
                $play_count+=1;
            }
            $i++;
        }
        if($total_points==0){
            $user_points=0;
        }else{
            $user_points=round($total_points/$lesson_points*100,2);
        }
        if($play_count==0){
            $progress=0;
        }else{
            $progress=round($play_count/$i*100,2);
        }
        $awards=0;
       // return $flag;
        return response()->json(['flag'=>$flag,'code'=>201,'action_media'=>'compleate','points'=>$user_points,'awards'=>$awards,'progress'=>$progress,'mediaId'=>$mediaid]);
    }
}