<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;

class UserController extends Controller
{
    public function getUsers(){
        $user = User::orderBy('created_at','desc')->paginate(5);

        return UserCollection::collection($user);
    }

    public function deleteUser($id){
        $user = User::findOrFail($id)->delete();

        return $user;
    }
}
