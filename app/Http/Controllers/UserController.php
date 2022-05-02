<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {
        return response()->json([
            'errorCode'=>'Success',
            'users'=>User::all()
        ]);
    }
}
