<?php

namespace App\Http\Controllers;

use App\Helper\SendEmail;
use App\Helper\SqlHelper;
use App\Helper\UserHelper;
use App\Levels;
use App\Parents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Response;
use App\Users;
use Illuminate\Support\Facades\DB;
use App\Helper\NotificationHelper;
use File;
use Illuminate\Support\Facades\Storage;

class ParentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $path='?';
        $query= Users::where("permession",Users::USER_PARENT);
        if(isset($request->search)&& $request->search!=null){
            $search= $request->search;
            $query->where(function($query) use ($search){
                $query->orwhere('users.email','like', '%' .$search.'%' )
                    ->orwhere('users.fullname', 'like','%' . $search.'%')
                    ->orwhere('users.phone', 'like','%' . $search.'%');
            });
            $path.='search='.$search;
        }

        if (Input::has('orderby')&& $request->orderby !==NULL && Input::has('descask')&&$request->descask !==NULL ){
            $orderby= $request->orderby;
            $descask=$request->descask;
            $path.='orderby='.$orderby;
            $path.='descask='.$descask;
            $query->orderBy($orderby, $descask);
        };

       $parents= $query->paginate(config('lms.pagination'));
       $parents->setPath($path);
        return view('parents.index')->with('parents', $parents);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $students = Users::where("permession", Users::USER_STUDENT);
        $classes = DB::table('levels')
            ->join('classes', 'classes.level', '=', 'levels.level_id')
            ->select('levels.*', 'classes.*')
            ->get();
        return view('parents.add')->with('students', $students)->with('classes',$classes);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userParentModel = new Users();
        $parentModel = new Parents();

        $rules = array(
            'uname' => 'required|unique:users',
            'fullname' => 'required',
            'email'=>'email|nullable|unique:users',
            'confirm_password'=>'same:password',
            'password' => 'required',
            'avatar'=>'image|mimes:jpg,jpeg,png,gif,bmp',

        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            $errors = $validator->errors()->all();
            return Response::json([
                'errors' => $errors
            ], 201);



        }

        $userParentModel->uname = $request->uname;
        $userParentModel->password = md5($request->password);;
        //$userParentModel->avatar
        $userParentModel->permession = Users::USER_PARENT;
        $userParentModel->email = $request->email;
        $userParentModel->fullname = $request->fullname;
        $userParentModel->status = Users::USER_PARENT;
        $userParentModel->phone = $request->phone;
        $userParentModel->birthdate = $request->birthdate;
        $userParentModel->class = -1;
        $userParentModel->level = -1;
        $userParentModel->created_at = date('Y-m-d h:m:s');

        $path="images/user.png";
        if (request()->hasFile('avatar')) {
            $avatar = md5(uniqid(rand(), true)).'.'.$request->avatar->extension();
            $request->avatar->move(config('lms.path_avatar'),$avatar);
            $userParentModel->avatar=config('lms.path_avatar').'/'.$avatar;
        }else{
            $userParentModel->avatar=$path;
        }

        $userParentModel->save(); // returns false
        NotificationHelper::n_adduser('parent','/parents/filter?search='.$userParentModel->email,$userParentModel->userid);

       $accept=UserHelper::createUserOnSiteManhal($userParentModel,$request->password);
      if($accept['code']==502){
          return Response::json([
              'errors' => ['not created in manhl.com']
          ], 201);
      }else{
          $userParentModel->manhal_id=$accept['manhal_id'];
          $userParentModel->save();
      }
        $parents= Users::where("permession",Users::USER_PARENT)->paginate(config('lms.pagination'));
        $parents->setPath('');
        return view('parents.index')->with('parents', $parents)->renderSections()['content'];

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($lang, $id)
    {
        $parent = Users::where("userid", $id)->get();
        $classes = DB::table('levels')
            ->join('classes', 'classes.level', '=', 'levels.level_id')
            ->select('levels.*', 'classes.*')
            ->get();
        $data = [
            "parent" => $parent ,
            "classes" => $classes
        ];
        return view('parents.edit')->with($data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($lang, $id)
    {
        $parent = Users::where("userid", $id)->first();
        $rules = array(
            'uname' => 'required|unique:users,uname,'.$parent->userid.',userid',
            'fullname' => 'required',
            'email'=>'email|unique:users,email,'.$parent->userid.',userid',
            'avatar'=>'image|mimes:jpg,jpeg,png,gif,bmp',
        );
        $input = request()->all();
        $parent->uname = $input["uname"];
        $parent->fullname = $input["fullname"];
        $parent->email = $input["email"];
        $parent->phone = $input["phone"];
        $parent->birthdate = $input["birthdate"];
        $parent->class = -1;
        $parent->level = -1;

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return Response::json([
                'errors' => $errors
            ], 201);
        }
        $path = "";
        $path="images/user.png";
        if (request()->hasFile('avatar')) {
            if(File::exists($parent->avatar) && $parent->avatar!=$path ) {
                File::delete($parent->avatar);
            }
            $avatar = md5(uniqid(rand(), true)).'.'.$input["avatar"]->extension();
            $input["avatar"]->move(config('lms.path_avatar'),$avatar);
            $parent->avatar=config('lms.path_avatar').'/'.$avatar;
        }
        $parent->save();
        NotificationHelper::n_updateUser('parent','/parents/filter?search='.$parent->email,$parent->userid);


        $parents= Users::where("permession",Users::USER_PARENT)->paginate(config('lms.pagination'));
        $parents->setPath('');
        return view('parents.index')->with('parents', $parents)->renderSections()['content'];

    }

    /**
     * @param $lang
     * @param $id
     * @return mixed
     */
    public function destroy ($lang, $id)
    {
        $input = request()->all();
        $parent= Users::where("userid",$id)->delete();
        $parents= Users::where("permession",Users::USER_PARENT)->paginate(config('lms.pagination'));
        $parents->setPath('');
        return view('parents.index')->with('parents', $parents)->renderSections()['content'];
    }


    /**
     * @param $lang
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function restPsswordParent($lang,$id){
        $user=Users::find($id);
        return view('parents.rest-password')->with('user',$user);
    }

    /**
     * @param $lang
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function restPssword($lang,$id,Request $request){
        $user=Users::find($id);
        $rules = array(
            'password'=>'required',
            'confirm_password'=>'required|same:password',
        );
        $validator = Validator::make(Input::all(), $rules);


        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return Response::json([
                'errors' => $errors
            ], 201);

        }

        $user->password=md5($request->password);

        $user->save();
        if( ! in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ) ) ){
            SendEmail::sendEmailRestPass($user,$request->password);
        }
        NotificationHelper::n_changePasswordUser('/userprofile',$user->userid);
        $parents= Users::where("permession",Users::USER_PARENT)->paginate(config('lms.pagination'));
        $parents->setPath('');
        return view('parents.index')->with('parents', $parents)->renderSections()['content'];
    }


    /**
     * filter the specified studets.
     * @return Response
     */
    public function filter(Request $request){

        $query = Users::where("permession",Users::USER_PARENT);

        $search= $request->search;
        $query->where(function($query) use ($search){
            $query->orwhere('users.email','like', '%' .$search.'%' )
                ->orwhere('users.fullname', 'like','%' . $search.'%')
                ->orwhere('users.phone', 'like','%' . $search.'%')
            ;
        });
        if (Input::has('orderby')&& $request->orderby !==NULL && Input::has('descask')&&$request->descask !==NULL ){
            $orderby= $request->orderby;
            $descask=$request->descask;
            $query->orderBy($orderby, $descask);
        };

        $parents= $query->paginate(config('lms.pagination'));
        $parents->setPath('');
        return view('parents.index')->with("parents",$parents);
    }

    /**
     * @param $lang
     * @param $id
     * @return mixed
     */
    public  function childs($lang,$id){
        $childs=Users::find($id)
            ->students()
            ->with('studentInfo')
           ->paginate(config('lms.pagination'))
            ->pluck('studentInfo');
        $levels =Levels::all();
        $parent=Users::find($id);
        return view('parents.childs')->with('childs',$childs)
            ->with('parent',$parent)
            ->with('levels', $levels );
    }

    /**
     * @param $lang
     * @return \Illuminate\Http\JsonResponse get students
     */
    public function getUsersClass($lang){
        $query=Users::where('permession',Users::USER_STUDENT);
        $request=request()->all();
        if(!empty($request["idClass"]) &&$request["idClass"] != -1 ){
            $query->where('class',$request["idClass"]);
        }
        if(!empty($request["idlevel"]) &&$request["idlevel"] != -1 ){
            $query->where('level',$request["idlevel"]);
        }

        if(!empty($request["parent_id"])  ){
          $ides= DB::table('parents')
                ->select('student_id')
                //->where('parent_id',$request["parent_id"]) //commented to get only students without parent - By Hussam
                ->get()
              ->pluck('student_id');
           $query->whereNotIn('userid',$ides);
        }
        $query->with('userClass');
        $query->with('userLevel');
        $users=$query->paginate(config('lms.pagination'));
        $pagination=str_replace("page-link","page-link-ajax-child", (string)$users->links());
        $pagination=str_replace("href","url",$pagination);

        return response()->json(['users'=>$users,'pagination'=> $pagination]);
    }

    /**
     * @param $lang
     * @param $parent_id
     * @param $student_id
     * @return void delete chiled users 
     */
    public function addChild($lang,$parent_id,$student_id){
        $cheak= Parents::where('parent_id',$parent_id)
            ->where('student_id',$student_id)->get();
        $child=new Parents;
        if($cheak->count()==0){
            $child->parent_id=$parent_id;
            $child->student_id=$student_id;
            $child->save();
        }

    }

    public  function  deleteChild($lang){
        $request=request()->all();
        $child=Parents::where('parent_id',$request["parent_id"])
                        ->where('student_id',$request["student_id"])->first();
        $child->delete();
    }
}