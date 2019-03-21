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


class ScromHomework
{
    /**
     * write by hasan kiwan
     * get username authrization
     * @return \Illuminate\Http\JsonResponse
     */
    public function getusername()
    {
        return response()->json(['username' => auth()->user()->uname]);
    }

    /**
     *  write by hasan kiwan
     * set success stautus for result homework
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function setsuccess_status()
    {
        $input = request()->all();
        $status = '';
        $mediaid = '';
        $result = '';
        $homeworkid='';
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
            $homeworkid = $input['lessonid'];
        }
        if ($mediaid == '' || $status == ''|| $homeworkid == '') {
            return 'error';
        }

        $media = $this->chexkedmedia($mediaid,$homeworkid);
        if (!$media->isEmpty()) {
            $media = DB::table('homeworkresult')->where('id_user', auth()->user()->userid)->where('id_assign', $mediaid)->where('id_homework', $homeworkid)->update(['status' => $status]);
            $flag = 'update';
        } else {
            $flag = 'create result';
            DB::table('homeworkresult')->insert(
                ['id' => null, 'id_user' => auth()->user()->userid, 'id_assign' => $mediaid, 'result' => $result, 'id_homework'=>$homeworkid,'created_at' => date('Y-m-d h:m:h'), 'updated_at' => date('Y-m-d h:m:h'), 'status' => $status]
            );
        }
        //return $flag;
        return response()->json(['flag'=>$flag,'code'=>201,'action_media'=>'setsuccess_status']);
    }

    /**
     * write by hasan
     * method for check media id exist in result media homework
     * @param $mediaid
     * @return \Illuminate\Support\Collection
     */
    private function chexkedmedia($mediaid,$homeworkid)
    {
        $media = DB::table('homeworkresult')->where('id_user', auth()->user()->userid)
            ->where('id_assign', $mediaid)->where('id_homework',$homeworkid)->get();
        return $media;
    }

    /**
     * write by hasan kiwan
     * set scorm max in result medai homework
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function setscoremax()
    {
        $input = request()->all();
        $result = '';
        $mediaid = '';
        $homeworkid='';
        if (isset($input['mediaid']) && $input['mediaid'] != '') {
            $mediaid = $input['mediaid'];
        }
        if (isset($input['scoremax']) && $input['scoremax'] != '') {
            $scoremax = $input['scoremax'];
        }
        if (isset($input['lessonid']) && $input['lessonid'] != '') {
            $homeworkid = $input['lessonid'];
        }
        if ($mediaid == '' || $scoremax == '' || $homeworkid == '') {
            return 'error';
        }

        $media = $this->chexkedmedia($mediaid,$homeworkid);
        if (!$media->isEmpty()) {
            $media = DB::table('homeworkresult')->where('id_user', auth()->user()->userid)->where('id_assign', $mediaid)->where('id_homework', $homeworkid)->update(['max' => $scoremax]);
            $flag = 'update';
        } else {
            $flag = 'create result';
            DB::table('homeworkresult')->insert(
                ['id' => null, 'id_user' => auth()->user()->userid, 'id_assign' => $mediaid,'id_homework'=>$homeworkid, 'created_at' => date('Y-m-d h:m:h'), 'updated_at' => date('Y-m-d h:m:h'), 'max' => $scoremax]
            );
        }
        //return $flag;
        return response()->json(['flag'=>$flag,'code'=>201,'action_media'=>'setscoremax']);
    }

    /**
     * write by hasan kiwan
     * set scorm min in result medai homework
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function setscoremin()
    {
        $input = request()->all();
        $scoremin = '';
        $mediaid = '';
        $homeworkid='';
        if (isset($input['mediaid']) && $input['mediaid'] != '') {
            $mediaid = $input['mediaid'];
        }
        if (isset($input['scoremin']) && $input['scoremin'] != '') {
            $scoremin = $input['scoremin'];
        }
        if (isset($input['lessonid']) && $input['lessonid'] != '') {
            $homeworkid = $input['lessonid'];
        }
        if ($mediaid == '' || $scoremin == ''|| $homeworkid == '') {
            return 'error';
        }

        $media = $this->chexkedmedia($mediaid,$homeworkid);
        if (!$media->isEmpty()) {
            $media = DB::table('homeworkresult')->where('id_user', auth()->user()->userid)->where('id_assign', $mediaid)->where('id_homework', $homeworkid)->update(['min' => $scoremin]);
            $flag = 'update';
        } else {
            $flag = 'create result';
            DB::table('homeworkresult')->insert(
                ['id' => null, 'id_user' => auth()->user()->userid, 'id_assign' => $mediaid, 'id_homework'=>$homeworkid,'created_at' => date('Y-m-d h:m:h'), 'updated_at' => date('Y-m-d h:m:h'), 'min' => $scoremin]
            );
        }
        //return $flag;
        return response()->json(['flag'=>$flag,'code'=>201,'action_media'=>'setscoremin']);
    }

    /**
     * write by hasan kiwan
     * set time min in result medai homework
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function setsessiontime()
    {
        $input = request()->all();
        $time = '';
        $mediaid = '';
        $homeworkid='';
        if (isset($input['mediaid']) && $input['mediaid'] != '') {
            $mediaid = $input['mediaid'];
        }
        if (isset($input['time']) && $input['time'] != '') {
            $time = $input['time'];
        }
        if (isset($input['lessonid']) && $input['lessonid'] != '') {
            $homeworkid = $input['lessonid'];
        }
        if ($mediaid == '' || $time == '' || $homeworkid == '') {
            return 'error';
        }

        $media = $this->chexkedmedia($mediaid,$homeworkid);
        if (!$media->isEmpty()) {
            $media = DB::table('homeworkresult')->where('id_user', auth()->user()->userid)->where('id_assign', $mediaid)->where('id_homework', $homeworkid)->update(['time' => $time]);
            $flag = 'update';
        } else {
            $flag = 'create result';
            DB::table('homeworkresult')->insert(
                ['id' => null, 'id_user' => auth()->user()->userid, 'id_assign' => $mediaid,'id_homework'=>$homeworkid, 'created_at' => date('Y-m-d h:m:h'), 'updated_at' => date('Y-m-d h:m:h'), 'time' => $time]
            );
        }

       // return $flag;
        return response()->json(['flag'=>$flag,'code'=>201,'action_media'=>'setsessiontime']);
    }

    /**
     * write by hasan kiwan
     * set scoreraw in result medai homework
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function setscoreraw()
    {
        $input = request()->all();
        $result = '';
        $mediaid = '';
        $homeworkid='';
        if (isset($input['mediaid']) && $input['mediaid'] != '') {
            $mediaid = $input['mediaid'];
        }
        if (isset($input['result']) && $input['result'] != '') {
            $result = $input['result'];
        }
        if (isset($input['lessonid']) && $input['lessonid'] != '') {
            $homeworkid = $input['lessonid'];
        }

        if ($mediaid == '' || $result == ''|| $homeworkid == '') {
            return 'error';
        }

        $media = $this->chexkedmedia($mediaid,$homeworkid);
        if (!$media->isEmpty()) {
            $media = DB::table('homeworkresult')->where('id_user', auth()->user()->userid)->where('id_assign', $mediaid)->where('id_homework', $homeworkid)->update(['result' => $result]);
            $flag = 'update';
        } else {
            $flag = 'create result';
            DB::table('homeworkresult')->insert(
                ['id' => null, 'id_user' => auth()->user()->userid, 'id_assign' => $mediaid,'id_homework'=>$homeworkid, 'created_at' => date('Y-m-d h:m:h'), 'updated_at' => date('Y-m-d h:m:h'), 'result' => $result]
            );
        }
        //return $flag;
        return response()->json(['flag'=>$flag,'code'=>201,'action_media'=>'setscoreraw']);
    }
    /**
     * write by hasan kiwan
     * when complete media (fineshed)
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function setcompletionstatus()
    {
        $input = request()->all();
        $completion = '';
        $mediaid = '';
        $homeworkid='';
        if (isset($input['mediaid']) && $input['mediaid'] != '') {
            $mediaid = $input['mediaid'];
        }
        if (isset($input['completion']) && $input['completion'] != '') {
            $completion = $input['completion'];
        }
        if (isset($input['lessonid']) && $input['lessonid'] != '') {
            $homeworkid = $input['lessonid'];
        }
        if ($mediaid == '' || $completion == '' || $homeworkid == '') {
            return 'error';
        }
        $media = $this->chexkedmedia($mediaid,$homeworkid);
        if (!$media->isEmpty()) {
            $media = DB::table('homeworkresult')->where('id_user', auth()->user()->userid)->where('id_assign', $mediaid)->where('id_homework', $homeworkid)->update(['completed' => $completion]);
            $flag = 'update';
        } else {
            $flag = 'create result';
            DB::table('homeworkresult')->insert(
                ['id' => null, 'id_user' => auth()->user()->userid, 'id_assign' => $mediaid, 'id_homework'=>$homeworkid,'created_at' => date('Y-m-d h:m:h'), 'updated_at' => date('Y-m-d h:m:h'), 'completed' => $completion]
            );
        }
        $homework = DB::table('homeworks')
            ->join('categories', 'homeworks.category', '=', 'categories.category_id')
            ->where("homeworks.homework_id",$homeworkid)
            ->first();
        $Media = DB::table('homeworkmedia')
            ->leftJoin('homeworkresult', function($join) use($homeworkid){
                $join->on('homeworkresult.id_assign', '=', 'homeworkmedia.id_media')
                    ->where([["homeworkresult.id_user","=",Auth::user()->userid]])
                    ->where([["homeworkresult.id_homework","=",$homeworkid]]);
            })
            ->select('homeworkmedia.*','homeworkresult.*',
                DB::raw('( select category_id from categories where  category_id='.$homework->category.') as category')
            )->where("homeworkmedia.id_homework",$homeworkid)->get();
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
        return response()->json(['flag'=>$flag,'code'=>201,'action_media'=>'compleate','points'=>$user_points,'awards'=>$awards,'progress'=>$progress,'mediaId'=>$mediaid]);
    }

}