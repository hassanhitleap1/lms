<?php
namespace App\Http\Controllers;
use App\Helper\NotificationHelper;
use App\Helper\SendEmail;
use App\Helper\UserHelper;
use App\Rules\UserRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
//use App\Http\Controllers\Controller;
use App\Users;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Response;
use File;






class UsersController extends BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function indexAdmin(Request $request){
      $path='?';
      $query= Users::where("permession",Users::USER_SCHOOL_ADMINISTRATOR);
      if(isset($request->search) && $request->search!=''){
          $search=$request->search ;
          $query->where(function($query) use ($search){
              $query->orwhere('users.email','like', '%' .$search.'%' )
                  ->orwhere('users.fullname', 'like','%' . $search.'%')
                  ->orwhere('users.phone', 'like','%' . $search.'%')
              ;
          });
          $path='?search='.$request->search;
      }
      if( (isset($request->orderby) && $request->orderby!='') && (isset($request->descask) && $request->descask!='') ){

          $path='&orderby='.$request->orderby .'&descask='.$request->descask ;
          $query->orderBy($request->orderby, $request->descask);
      }
      $admins= $query->paginate(config('lms.pagination'));
      $admins->setPath($path);
    return view('admins.index')->with("admins",$admins);
  }


 

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function store(Request $request)
  {
    $adminModel=new Users();

    $rules = array(
        'uname' => 'required|unique:users',
        'fullname' => 'required',
        'email'=>'email|nullable|unique:users',
        'password' => 'required',
        'confirm_password'=>'same:password',
        'avatar'=>'image|mimes:jpg,jpeg,png,gif,bmp',
    );

      $validator = Validator::make(Input::all(), $rules);

      if ($validator->fails())
      {
        $errors=$validator->errors()->all();
        return Response::json([
            'errors' => $errors
        ], 201);
      }
    
    $adminModel->uname=$request->uname;
    $adminModel->password= md5($request->password);;
    $adminModel->permession=Users::USER_SCHOOL_ADMINISTRATOR;
    $adminModel->email=$request->email;
    $adminModel->fullname=$request->fullname;
    $adminModel->status=1;
    $adminModel->phone=$request->phone;
    $adminModel->birthdate=$request->birthdate;
    $adminModel->class=-1;
    $adminModel->level=-1;
    //$adminModel->created_at=date('Y-m-d h:m:s');
    $path="images/user.png";
      if (request()->hasFile('avatar')) {
          $avatar = md5(uniqid(rand(), true)).'.'.$request->avatar->extension();
          $request->avatar->move(config('lms.path_avatar'),$avatar);
          $adminModel->avatar=config('lms.path_avatar').'/'.$avatar;
      }else{
          $adminModel->avatar=$path;
      }
    $adminModel->save();
    NotificationHelper::n_adduser('admin','/admins/filter?search='.$adminModel->email,$adminModel->userid);

      $accept=UserHelper::createUserOnSiteManhal($adminModel,$request->password);
      if($accept['code']==502){
          return Response::json([
              'errors' => ['not created in manhl.com']
          ], 201);
      }else{
          $adminModel->manhal_id=$accept['manhal_id'];
          $adminModel->save();
      }
    $admins= Users::where("permession",3)->paginate(config('lms.pagination'));
    $admins->setPath('');
     return view('admins.index')->with("admins",$admins)->renderSections()['content'];
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($lang,$id){
    $admin= Users::where("userid",$id)->get();
    $classes = DB::table('levels')
        ->join('classes', 'classes.level', '=', 'levels.level_id')
        ->select('levels.*', 'classes.*')
        ->get();
    $data=[
        "admin"=>$admin,
        "classes"=>$classes
    ];
    return view('admins.edit')->with($data);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($lang,$id){
    $input = request()->all();
    $admin= Users::where("userid",$id)->first();
    $admin->uname=$input["uname"];
    $admin->fullname=$input["fullname"];
    $admin->email=$input["email"];
    $admin->phone=$input["phone"];
    $admin->birthdate=$input["birthdate"];
    $admin->class=-1;
    $admin->level=-1;
      $rules = array(
          'uname' => 'required|unique:users,uname,'.$admin->userid.',userid',
          'fullname' => 'required',
          'email'=>'email|unique:users,email,'.$admin->userid.',userid',
          'avatar'=>'image|mimes:jpg,jpeg,png,gif,bmp',

      );
    $validator = Validator::make(Input::all(), $rules);
      if ($validator->fails())
      {
          $errors=$validator->errors()->all();
          return Response::json([
              'errors' => $errors
          ], 201);
      }

      $path="images/user.png";
      if (request()->hasFile('avatar')) {
          if(File::exists($admin->avatar) && $admin->avatar!=$path ) {
              File::delete($admin->avatar);
          }
          $avatar = md5(uniqid(rand(), true)).'.'.$input["avatar"]->extension();
          $input["avatar"]->move(config('lms.path_avatar'),$avatar);
          $admin->avatar=config('lms.path_avatar').'/'.$avatar;
      }
    $admin->save();
      NotificationHelper::n_updateUser('admin','/admins?search='.$admin->email,$admin->userid);
    $admins= Users::where("permession",3)->paginate(config('lms.pagination'));
    $admins->setPath('');
    return view('admins.index')->with("admins",$admins)->renderSections()['content'];
  }

  public function delete($lang,$id){
    $input = request()->all();
    $admin= Users::where("userid",$id)->delete();
    $admins= Users::where("permession",3)->paginate(config('lms.pagination'));
    $admins->setPath('');
    return view('admins.index')->with("admins",$admins)->renderSections()['content'];
  }

  public function newuser(){
    $admin="";
    $classes = DB::table('levels')
        ->join('classes', 'classes.level', '=', 'levels.level_id')
        ->select('levels.*', 'classes.*')
        ->get();
    $data=[
        "admin"=>$admin,
        "classes"=>$classes
    ];
    return view('admins.add')->with($data);
  }


  public function restPsswordAdmin($lang,$id){
      $user=Users::find($id);
      return view('admins.rest-password')->with('user',$user);
  }




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
        $admins= Users::where("permession",3)->paginate(config('lms.pagination'));
        $admins->setPath('');
        return view('admins.index')->with("admins",$admins)->renderSections()['content'];
    }



    /**
     * filter the specified studets.
     *
     * @return Response
     */
    public function filter(Request $request){

        $query = Users::where("permession",3);

        $search= $request->search;
        $query->where(function($query) use ($search){
            $query->orwhere('users.email','like', '%' .$search.'%' )
                ->orwhere('users.fullname', 'like','%' . $search.'%')
                ->orwhere('users.phone', 'like','%' . $search.'%')
            ;
        });

        $admins= $query->paginate(config('lms.pagination'));
        $admins->setPath('');
        return view('admins.index')->with("admins",$admins);
    }

}

?>