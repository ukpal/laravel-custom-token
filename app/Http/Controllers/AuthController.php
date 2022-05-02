<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validation->fails()) {
            $errors = $validation->errors();
            $errorMessage = '';
            if ($errors->any()) {
                foreach ($errors->all() as $error) {
                    $errorMessage = $errorMessage . $error . '\n';
                }
            }
            $returnData['errorCode'] = "Error";
            $returnData['message'] = $errorMessage;
        } else {
            $userDetails = User::where('email', $request->email)->first();
            if($userDetails){
                if(Hash::check($request->password,$userDetails->password)){
                    $token = Str::random(80);
                    $userDetails->token = $token;
                    $userDetails->token_expires_on = date("Y-m-d H:i:s", strtotime('+2 days'));
                    $userDetails->save();                   
                    $returnData['errorCode'] = "Success";
                    $returnData['token'] = $token;
                    $returnData['message'] = 'You logged in successfully';
                }else{
                    $returnData['errorCode'] = "Error";
                    $returnData['message'] = 'Invalid Password';
                }
            }else{
                $returnData['errorCode'] = "Error";
                $returnData['message'] = 'Invalid Email';
            }
        }
        return response()->json($returnData);
    }


    public function Logout(Request $request)
    {
        $token = $request->bearerToken();
        $user = User::where('token', $token)->first();
        if ($user) {
            $user->token = null;
            $user->token_expires_on = null;
            $user->save();
            return response()->json([
                'errorCode' => 'Success',
                'message' => 'User Logged Out',
            ]);
        } else {
            return response()->json([
                'errorCode' => 'Error',
                'message' => 'User not found',
            ]);
        }
    }
}
