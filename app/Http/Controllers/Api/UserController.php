<?php
 
namespace App\Http\Controllers\Api;
 
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;
 
class UserController extends Controller
{
    public $successStatus = 200;
 /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyLaravelApp')-> accessToken; 
            $success['userId'] = $user->id;
          //return(['status'=>1]);'
             return response()->json(['status' =>1, 'token' =>$success['token'] ]); 
        } 
        else{ 
            
            return response()->json(['status'=>0], 401); 
        } 
    }
 
 /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);
        if ($validator->fails()) { 
             return response()->json(['error'=>$validator->errors()], 401);            
 }
 $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); 
        $success['token'] =  $user->createToken('MyLaravelApp')-> accessToken; 
        $success['name'] =  $user->name;
 return response()->json(['status'=>1,$success], $this-> successStatus); 
    }
 
 /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function userDetails() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    }

    public function logout() 
    {
        Auth::user()->tokens->each(function($token, $key) {
            $token->delete();
        });
    
        return response()->json([
            'message' => 'Logged out successfully!',
            'status_code' => 200
        ], 200);
    }
}