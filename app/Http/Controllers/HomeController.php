<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

class HomeController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[

            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required',

        ]);

        if($validator->fails()){
            return response()->json(['status'=>'fail','$validation_errors'=>$validator->errors()]);
        }

        $input = $request->all();
        //  $request->password = bcrypt($request->password);
        $input['password']=bcrypt($input['password']);
        $user = User::Create($input);

        event(new Registered($user));

        $success["token"] =$user->createToken('user')->plainTextToken;
        $success["name"] =$user->name;
        if($user){
            return response()->json(['status'=>'success','message'=>'User Create Successfully','data'=>$user,'token'=>$success]);

           }
           return response()->json(['status'=>'fail','message'=>'User Create fail']);
    }

    public function login(Request $request)
    {
        $input = $request->all();
        $validation = Validator::make($input,
        [
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if($validation->fails()){
             return response()->json(['errors'=>$validation->errors()->all()]);
        }
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            $user = Auth::user();
            $token['token'] = $user->createToken('usertoken')->plainTextToken;

            return response()->json(['status'=>'success','login'=>true,'token'=>$token, 'data'=>$user]);
        }else{
            return response()->json(['status'=>'fail','message'=>'fails']);
        }
    }
    
}

