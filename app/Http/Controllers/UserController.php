<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

    public function addUser(Request $request){

        //validation for inputs
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email'
        ]);
        $return['message'] = 'Please enter valid inputs';
        if ($validator->fails()) {
            return $return;
        }

        //Add user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make(rand(10000,99999));
        $user->save();
        return $user;
    }

    public function editUser(Request $request){
        //validation for inputs
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email'
        ]);
        $return['message'] = 'Please enter valid inputs';
        if ($validator->fails()) {
            return $return;
        }

        //Update user
        DB::table('users')->where('id',$request->id)->update(['name'=> $request->name]);
        return true;
    }
}
