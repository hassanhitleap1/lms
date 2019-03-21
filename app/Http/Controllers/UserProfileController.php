<?php

namespace App\Http\Controllers;

use App\Country;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Input;
use Session;
use File;

class UserProfileController extends BaseController
{
    public  $linkCountry=' http://country.io/names.json';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userProfile($lang)
    {
        $user=Users::find(Auth::user()->userid);
        $countris=Country::orderby('country_code','ASC')->get();
        return view('userprofile')->with('countris',$countris)->with('user',$user);
    }

    public function update($lang,Request $request){
        $user=Users::find(Auth::user()->userid);
        $countris=Country::orderby('country_code','ASC')->get();
        $rules = array(
            'uname' => 'required|unique:users,uname,'.$user->userid.',userid',
            'fullname' => 'required',
            'email'=>'email|nullable|unique:users,email,'.$user->userid.',userid',
            'avatar'=>'image|mimes:jpg,jpeg,png,gif,bmp',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            $errors=$validator->errors()->all();
            return view('userprofile')->with('countris',$countris)->with('user',$user)->withErrors($errors);
        }
        $user->uname=$request->uname;
        $user->email=$request->email;
        $user->country=$request->country;
        $user->fullname=$request->fullname;
        $user->phone=$request->phone;
        $user->gender=$request->gender;
        $user->birthdate=$request->birthdate;
        $user->address=$request->address;
        $path="images/user.png";
        if (request()->hasFile('avatar')) {
            if(File::exists($user->avatar) && $user->avatar!=$path ) {
                File::delete($user->avatar);
            }
            $avatar = md5(uniqid(rand(), true)).'.'.$request->avatar->extension();

            $request->avatar->move(config('lms.path_avatar'),$avatar);
            $user->avatar=config('lms.path_avatar').'/'.$avatar;
        }
        if($user->save()){
            Session::put('update', 'successfully update profile');
        }


        return view('userprofile')->with('countris',$countris)->with('user',$user);
    }

    private function  getCountries(){
        $service_url = 'http://country.io/names.json';
        $curl = curl_init($service_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
        $info = curl_getinfo($curl);
        curl_close($curl);
        die('error occured during curl exec. Additioanl info: ' . var_export($info));
        }
        curl_close($curl);
        return $countris = json_decode($curl_response);
}

}
