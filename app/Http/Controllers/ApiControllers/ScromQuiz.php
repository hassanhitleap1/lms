<?php
/**
 * Created by PhpStorm.
 * User: Saleh
 * Date: 1/13/2019
 * Time: 10:22 AM
 */

namespace App\Http\Controllers\ApiControllers;


class ScromQuiz
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
        $quizid='';
        if (isset($input['status']) && $input['status'] != '') {
            $status = $input['status'];
        }
        if (isset($input['mediaid']) && $input['mediaid'] != '') {
            $mediaid = $input['mediaid'];
        }
        if (isset($input['result']) && $input['result'] != '') {
            $result = $input['result'];
        }
        if (isset($input['quizid']) && $input['quizid'] != '') {
            $quizid = $input['quizid'];
        }
        if ($mediaid == '' || $status == ''|| $quizid == '') {
            return 'error';
        }

        $media = $this->chexkedmedia($mediaid,$quizid);
        if (!$media->isEmpty()) {
            $media = DB::table('quizresult')->where('id_user', auth()->user()->userid)->where('id_assign', $mediaid)->where('id_quiz', $quizid)->update(['status' => $status]);
            $flag = 'update';
        } else {
            $flag = 'create result';
            DB::table('quizresult')->insert(
                ['id' => null, 'id_user' => auth()->user()->userid, 'id_assign' => $mediaid, 'result' => $result, 'id_quiz'=>$quizid,'created_at' => date('Y-m-d h:m:h'), 'updated_at' => date('Y-m-d h:m:h'), 'status' => $status]
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
    private function chexkedmedia($mediaid,$quizid)
    {
        $media = DB::table('quizresult')->where('id_user', auth()->user()->userid)
            ->where('id_assign', $mediaid)->where('id_quiz',$quizid)->get();
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
        $quizid='';
        if (isset($input['mediaid']) && $input['mediaid'] != '') {
            $mediaid = $input['mediaid'];
        }
        if (isset($input['scoremax']) && $input['scoremax'] != '') {
            $scoremax = $input['scoremax'];
        }
        if (isset($input['quizid']) && $input['quizid'] != '') {
            $quizid = $input['quizid'];
        }
        if ($mediaid == '' || $scoremax == '' || $quizid == '') {
            return 'error';
        }

        $media = $this->chexkedmedia($mediaid,$quizid);
        if (!$media->isEmpty()) {
            $media = DB::table('quizresult')->where('id_user', auth()->user()->userid)->where('id_assign', $mediaid)->where('id_quiz', $quizid)->update(['max' => $scoremax]);
            $flag = 'update';
        } else {
            $flag = 'create result';
            DB::table('quizresult')->insert(
                ['id' => null, 'id_user' => auth()->user()->userid, 'id_assign' => $mediaid,'id_quiz'=>$quizid, 'created_at' => date('Y-m-d h:m:h'), 'updated_at' => date('Y-m-d h:m:h'), 'max' => $scoremax]
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
        $quizid='';
        if (isset($input['mediaid']) && $input['mediaid'] != '') {
            $mediaid = $input['mediaid'];
        }
        if (isset($input['scoremin']) && $input['scoremin'] != '') {
            $scoremin = $input['scoremin'];
        }
        if (isset($input['quizid']) && $input['quizid'] != '') {
            $quizid = $input['quizid'];
        }
        if ($mediaid == '' || $scoremin == ''|| $quizid == '') {
            return 'error';
        }

        $media = $this->chexkedmedia($mediaid,$quizid);
        if (!$media->isEmpty()) {
            $media = DB::table('quizresult')->where('id_user', auth()->user()->userid)->where('id_assign', $mediaid)->where('id_quiz', $quizid)->update(['min' => $scoremin]);
            $flag = 'update';
        } else {
            $flag = 'create result';
            DB::table('quizresult')->insert(
                ['id' => null, 'id_user' => auth()->user()->userid, 'id_assign' => $mediaid, 'id_quiz'=>$quizid,'created_at' => date('Y-m-d h:m:h'), 'updated_at' => date('Y-m-d h:m:h'), 'min' => $scoremin]
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
        $quizid='';
        if (isset($input['mediaid']) && $input['mediaid'] != '') {
            $mediaid = $input['mediaid'];
        }
        if (isset($input['time']) && $input['time'] != '') {
            $time = $input['time'];
        }
        if (isset($input['quizid']) && $input['quizid'] != '') {
            $quizid = $input['quizid'];
        }
        if ($mediaid == '' || $time == '' || $quizid == '') {
            return 'error';
        }

        $media = $this->chexkedmedia($mediaid,$quizid);
        if (!$media->isEmpty()) {
            $media = DB::table('quizresult')->where('id_user', auth()->user()->userid)->where('id_assign', $mediaid)->where('id_quiz', $quizid)->update(['time' => $time]);
            $flag = 'update';
        } else {
            $flag = 'create result';
            DB::table('quizresult')->insert(
                ['id' => null, 'id_user' => auth()->user()->userid, 'id_assign' => $mediaid,'id_quiz'=>$quizid, 'created_at' => date('Y-m-d h:m:h'), 'updated_at' => date('Y-m-d h:m:h'), 'time' => $time]
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
        $quizid='';
        if (isset($input['mediaid']) && $input['mediaid'] != '') {
            $mediaid = $input['mediaid'];
        }
        if (isset($input['result']) && $input['result'] != '') {
            $result = $input['result'];
        }
        if (isset($input['quizid']) && $input['quizid'] != '') {
            $quizid = $input['quizid'];
        }

        if ($mediaid == '' || $result == ''|| $quizid == '') {
            return 'error';
        }

        $media = $this->chexkedmedia($mediaid,$quizid);
        if (!$media->isEmpty()) {
            $media = DB::table('quizresult')->where('id_user', auth()->user()->userid)->where('id_assign', $mediaid)->where('id_quiz', $quizid)->update(['result' => $result]);
            $flag = 'update';
        } else {
            $flag = 'create result';
            DB::table('quizresult')->insert(
                ['id' => null, 'id_user' => auth()->user()->userid, 'id_assign' => $mediaid,'id_quiz'=>$quizid, 'created_at' => date('Y-m-d h:m:h'), 'updated_at' => date('Y-m-d h:m:h'), 'result' => $result]
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
        $quizid='';
        if (isset($input['mediaid']) && $input['mediaid'] != '') {
            $mediaid = $input['mediaid'];
        }
        if (isset($input['completion']) && $input['completion'] != '') {
            $completion = $input['completion'];
        }
        if (isset($input['quizid']) && $input['quizid'] != '') {
            $quizid = $input['quizid'];
        }
        if ($mediaid == '' || $completion == '' || $quizid == '') {
            return 'error';
        }
        $media = $this->chexkedmedia($mediaid,$quizid);
        if (!$media->isEmpty()) {
            $media = DB::table('quizresult')->where('id_user', auth()->user()->userid)->where('id_assign', $mediaid)->where('id_quiz', $quizid)->update(['completed' => $completion]);
            $flag = 'update';
        } else {
            $flag = 'create result';
            DB::table('quizresult')->insert(
                ['id' => null, 'id_user' => auth()->user()->userid, 'id_assign' => $mediaid, 'id_quiz'=>$quizid,'created_at' => date('Y-m-d h:m:h'), 'updated_at' => date('Y-m-d h:m:h'), 'completed' => $completion]
            );
        }
        $quiz = DB::table('quiz')
            ->join('categories', 'quiz.category', '=', 'categories.category_id')
            ->where("quiz.quiz_id",$quizid)
            ->first();
        $Media = DB::table('quiz_media')
            ->leftJoin('quizresult', function($join) use($quizid){
                $join->on('quizresult.id_assign', '=', 'quiz_media.id')
                    ->where([["quizresult.id_user","=",Auth::user()->userid]])
                    ->where([["quizresult.id_quiz","=",$quizid]]);
            })
            ->select('quiz_media.*','quizresult.*',
                DB::raw('( select category_id from categories where  category_id='.$quiz->category.') as category')
            )->where("quiz_media.id_quiz",$quizid)->get();

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