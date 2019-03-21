<?php
namespace App\Http\Controllers;
use App\Helper\MessageHelper;
use App\Users;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Levels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Validator;
use Response;


class LevelsController extends BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function indexLevels()
  {
      $orderBy='level_number';
      $DescAsk='ASC';
      $search='';
      $levels = Levels::where([]);
      if (request()->isMethod('GET')) {
          $input = request()->all();
          if(isset($input['sorting'])&&$input['sorting']!=''){
              $orderBy=$input['sorting'];
          }
          if(isset($input['descask'])&&$input['descask']!=''){
              $DescAsk=$input['descask'];
          }

          if (isset($input['search'])&&$input['search'] != '') {
              $search=$input['search'];
            $levels->where('ltitle_ar','like', '%' . $search . '%')->orWhere('ltitle_en', 'like', '%' . $search . '%');
          }
          if(isset($input['level'])&&$input['level']!=''){
               $levels->where('level_id',$input['level']);
          }
          if((isset($input['teacher'])&&$input['teacher']!=-1)){
              $levelid=Users::find($input['teacher'])->level;
              $idLevels= DB::table('schedule')->select('classes.level')
                  ->join('classes', 'classes.class_id', '=', 'schedule.class')
                    ->where('schedule.teacher',$input['teacher'])->distinct()->get()
                 ->pluck('level')
                  ->toArray();
               $levels->whereIn('level_id', $idLevels);
              $levels->orwhere('level_id', $levelid);
          }
          if(Users::isTeacher()){
              $idLevels= DB::table('schedule')->select('classes.level')
                  ->join('classes', 'classes.class_id', '=', 'schedule.class')
                  ->where('schedule.teacher',Auth::user()->userid)
                  ->distinct()
                  ->get()
                  ->pluck('level')
                  ->toArray();
            $levels->whereIn('level_id', $idLevels);
              $levels->orwhere('level_id',  Auth::user()->level);
          }
          $levels = $levels->orderBy($orderBy,$DescAsk)->paginate(10);
          return view('levels.index',compact('levels'),['search'=>$search,'descask'=>$DescAsk,'orderBy'=>$orderBy]);
      }
  }
    public function viewAddLevel(){
        return view('levels.add');
    }
    public function addLevels()
    {
        $orderBy = 'level_number';
        $DescAsk = 'ASC';
        $search = '';

        $rules = array(
            'ltitle_ar' => 'required',
            'ltitle_en' => 'required',

        );

        $validator = Validator::make(Input::all(), $rules);


        if ($validator->fails())
        {
            $errors=$validator->errors()->all();
            return Response::json([
                'errors' => $errors
            ], 201);
        }

            $input = request()->all();

        $levels=Levels::where([]);

            if(isset($input['ltitle_ar'])&&$input['ltitle_ar']!=''&&isset($input['ltitle_en'])&&$input['ltitle_en']!=''){
                $Level = new Levels();
                $Level->ltitle_ar = $input['ltitle_ar'];
                $Level->ltitle_en = $input['ltitle_en'];
                $Level->school = $input['school'];
                $Level->level_number = $Level->get()->count() + 1;
                $Level->save();
            }

             if(isset($input['sorting'])&&$input['sorting']!=''){
                 $orderBy=$input['sorting'];
             }
             if(isset($input['descask'])&&$input['descask']!=''){
                 $DescAsk=$input['descask'];
             }

        if (isset($input['search']) && $input['search'] != '') {
            $search = $input['search'];
            $levels = $levels->where('ltitle_ar', 'like', '%' . $search . '%')->orWhere('ltitle_en', 'like', '%' . $search . '%');
        }
        $levels = $levels->orderBy($orderBy,$DescAsk)->paginate(10);
        $levels->setPath('');
        return view('levels.index',compact('levels'),['search' => $search,'descask' => $DescAsk,'orderBy' => $orderBy])->renderSections()['content'];

    }

    public function editLevels()
    {
        $orderBy = 'level_number';
        $DescAsk = 'ASC';
        $search = '';
        $rules = array(
            'title_ar' => 'required',
            'title_en' => 'required',

        );

        $validator = Validator::make(Input::all(), $rules);


        if ($validator->fails())
        {
            $errors=$validator->errors()->all();
            return Response::json([
                'errors' => $errors
            ], 201);
        }
        $input = request()->all();
        $Level = Levels::where('level_id', $input['id'])->first();
        $Level->ltitle_ar = $input['title_ar'];
        $Level->ltitle_en = $input['title_en'];
        $Level->update();

        $levels=Levels::where([]);

        if(isset($input['sorting'])&&$input['sorting']!=''){
            $orderBy=$input['sorting'];
        }
        if(isset($input['descask'])&&$input['descask']!=''){
            $DescAsk=$input['descask'];
        }

        if (isset($input['search']) && $input['search'] != '') {
            $search = $input['search'];
            $levels = $levels->where('ltitle_ar', 'like', '%' . $search . '%')->orWhere('ltitle_en', 'like', '%' . $search . '%');
        }

        $levels = $levels->orderBy($orderBy,$DescAsk)->paginate(10);

        $levels->setPath('');
        return view('levels.index',compact('levels'),['search' => $search,'descask' => $DescAsk,'orderBy' => $orderBy])->renderSections()['content'];



    }
    public function viewedit($lang,$id)
    {
        $levels = Levels::find($id);
        return view('levels.edit',compact('levels'));
    }
    public function deletelevels()
    {
        $orderBy = 'level_number';
        $DescAsk = 'ASC';
        $search = '';
        if (request()->isMethod('GET')) {
            $input = request()->all();
            $id = $input['id'];
            if ($id != '') {
                $levels = Levels::find($id)->delete();
                 }
        }
        $levels=Levels::where([]);
        if(isset($input['sorting'])&&$input['sorting']!=''){
            $orderBy=$input['sorting'];
        }
        if(isset($input['descask'])&&$input['descask']!=''){
            $DescAsk=$input['descask'];
        }

        if (isset($input['search']) && $input['search'] != '') {
            $search = $input['search'];
            $levels = $levels->where('ltitle_ar', 'like', '%' . $search . '%')->orWhere('ltitle_en', 'like', '%' . $search . '%');
        }

        $levels = $levels->orderBy($orderBy,$DescAsk)->paginate(10);
        $levels->setPath('');
        return view('levels.index',compact('levels'),['search' => $search,'descask' => $DescAsk,'orderBy' => $orderBy])->renderSections()['content'];
    }

    /**
     * @create_by hasan kiwan
     * @param $lang
     * @param $id
     * @return BaseController|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @job show view for send message
     */
    public function sendMessageView($lang,$id){
      return view('levels.sendmessage')->with('id',$id);
    }

    /**
     * @create_by hasan kiwan
     * @param $lang
     * @param $id
     * @param Request $request
     * @job for send message for all user in group selected
     */
    public function sendMessageToLevelStu($lang,$id,Request  $request){
        MessageHelper::sendMessageTolevelUsers($request->message,$id);
    }

}

?>