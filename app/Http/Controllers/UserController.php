<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;



class UserController extends Controller
{
 
    public function register(Request $req){

        $data = $req->only('firstname', 'lastname', 'mobile', 'email', 'password','age', 'gender', 'city');
        $checker = $this->sign_up_val($data);

        if ($checker->stopOnFirstFailure()->fails()){

            $val = $checker->messages()->toArray();
            if(array_key_exists('firstname',$val)){
                $msg = implode('', $val['firstname']);
            } 
            elseif(array_key_exists('lastname',$val)){
                $msg = implode('', $val['lastname']); 
            } 
            elseif(array_key_exists('mobile',$val)){
                $msg = implode('', $val['mobile']); 
            }   
            elseif(array_key_exists('email',$val)){
                $msg = implode('', $val['email']); 
            }  
            elseif(array_key_exists('password',$val)){
                $msg = implode('', $val['password']); 
            }
            elseif(array_key_exists('age',$val)){
                $msg = implode('', $val['age']); 
            }  
            elseif(array_key_exists('gender',$val)){
                $msg = implode('', $val['gender']); 
            }  
            elseif(array_key_exists('city',$val)){
                $msg = implode('', $val['city']); 
            }  
            return response()->json(['error' => $msg], 200);
        }
         
        //If the request is valid, create new user

        $user = User::create([
        	'firstname' => $req->firstname,
            'lastname' => $req->lastname,
            'mobile' => $req->mobile,
        	'email' => $req->email,
        	'password' => bcrypt($req->password),
            'age' => $req->age,
            'gender' => $req->gender,
            'city' => $req->city
        ]);

        $result = ($user) ? ['status' => 'success', 'msg' => 'User created successfully'] : ['status' => 'failed', 'msg' => 'Operation failed'];
        return response()->json($result);
        
    }

    public function authenticate(Request $req){

        $credentials = $req->only('email', 'password');

        $validator = Validator::make($credentials, [
                                                    'email' => 'required|regex:/(.+)@(.+)\.(.+)/i',
                                                    'password' => [
                                                                    'required',Password::min(8)
                                                                    ->letters()
                                                                    ->mixedCase()
                                                                    ->numbers()
                                                                    ->symbols() 
                                                                ]
                                                    ]);
    
        //Send failed response if request is not valid
        if ($validator->stopOnFirstFailure()->fails()) {
            $val = $validator->messages()->toArray();
            if(array_key_exists('email',$val)){

                $msg = implode('',$val['email']);
            } 
            else{
                $msg = implode('',$val['password']); 
            }  
            return response()->json(['error' => $msg], 200);
        }

        //Create token
        try{
            $myTTL = 60;
            JWTAuth::factory()->setTTL($myTTL);

            if (! $token = JWTAuth::attempt($credentials)) {
              return response()->json(['status' => 'failed', 'msg' => 'Wrong Username & Password']);

            }
        }catch (JWTException $e) {
            return response()->json(['error' => $e, 'msg' => 'Could not create token']);
            
        }
 	
 		//Token created, return with success response and jwt token

        return response()->json(['status' => 'success', 'token' => $token]);
    }

    public function profile_edit(Request $req, $id){

        $data = $req->only('firstname', 'lastname', 'mobile', 'email', 'password','age', 'gender', 'city');
        $checker = $this->sign_up_val($data);

        if ($checker->stopOnFirstFailure()->fails()){

            $val = $checker->messages()->toArray();
            if(array_key_exists('firstname',$val)){
                $msg = implode('', $val['firstname']);
            } 
            elseif(array_key_exists('lastname',$val)){
                $msg = implode('', $val['lastname']); 
            } 
            elseif(array_key_exists('mobile',$val)){
                $msg = implode('', $val['mobile']); 
            }   
            elseif(array_key_exists('email',$val)){
                $msg = implode('', $val['email']); 
            }  
            elseif(array_key_exists('password',$val)){
                $msg = implode('', $val['password']); 
            }
            elseif(array_key_exists('age',$val)){
                $msg = implode('', $val['age']); 
            }  
            elseif(array_key_exists('gender',$val)){
                $msg = implode('', $val['gender']); 
            }  
            elseif(array_key_exists('city',$val)){
                $msg = implode('', $val['city']); 
            }  
            return response()->json(['error' => $msg], 200);
        }

        //If the request is valid, update the user profile
        $data = User::find($id);

        $data->firstname = $req['firstname'];
        $data->lastname = $req['lastname'];
        $data->mobile = $req['mobile'];
        $data->email = $req['email'];
        $data->password = bcrypt($req['password']);
        $data->age = $req['age'];
        $data->gender = $req['gender'];
        $data->city = $req['city'];
        $data->save();

        return response()->json(['status' => 'success', 'msg' => 'User updated successfully']);
    }

    public function delete($id){
        $res = User::find($id)->delete();
        return response()->json(['status' => 'success', 'msg' => 'User deleted successfully']);
    }

    private function sign_up_val($data){

        $validator = Validator::make($data, [
            'firstname' => 'required|string|regex:/^[a-zA-Z ]+$/',
            'lastname' => 'required|string|regex:/^[a-zA-Z ]+$/',
            'mobile' => 'required|numeric|digits:10',
            'email' => 'required|regex:/(.+)@(.+)\.(.+)/i',
            'password' => [
                            'required',Password::min(8)
                            ->letters()
                            ->mixedCase()
                            ->numbers()
                            ->symbols() 
                        ],
            'age' => 'required|numeric',
            'gender' => 'required|string|', 
            'city' => 'required|string'
        ]);
        return $validator;
    }
 
}
