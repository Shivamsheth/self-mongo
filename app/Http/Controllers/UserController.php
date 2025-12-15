<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use MongoDB\Laravel\Eloquent\Model;
use App\Models\PersonalAccessToken;


class UserController extends Controller
{


    public function index(): JsonResponse{
       $authUser = request()->user();
       if(!$authUser->isAdmin()){
           return response()->json(['message'=>'Forbidden'],403);
       }
       
        $users = User::all();
        return response()->json($users);
    }

    public function profile($id): JsonResponse{
        $authUser = request()->user();
        if($authUser->isUser() && $authUser->_id != $id){
            return response()->json(['message'=>'Forbidden'],403);
        }
        $user = User::find($id);
        if(!$user){
            return response()->json(['message'=>'User Not Found'],404);
        }
        return response()->json($user);
    }


    public function updateProfile(Request $request, $id): JsonResponse{
        $authUser = request()->user();
        if($authUser->isUser() && $authUser->_id != $id){
            return response()->json(['message'=>'Forbidden'],403);
        }
        $user = User::find($id);
        if(!$user){
            return response()->json(['message'=>'User Not Found'],404);
        }
        $user->name = $request->name ?? $user->name;
        $user->email = $request->email ?? $user->email;
        $user->save();
        return response()->json(['message'=>'Profile Updated Successfully', 'user'=>$user]);   
     }
    
        public function deleteProfile($id): JsonResponse{
            $authUser = request()->user();
            if($authUser->isUser() && $authUser->_id != $id){
                return response()->json(['message'=>'Forbidden'],403);
            }
            $user = User::find($id);
            if(!$user){
                return response()->json(['message'=>'User Not Found'],404);
            }
            $user->delete();
            return response()->json(['message'=>'Profile Deleted Successfully']);
        }

      public function deleteAllUsers(): JsonResponse{
        $authUser = request()->user();
        if(!$authUser->isAdmin()){
            return response()->json(['message'=>'Forbidden'],403);}
        User::where('_id','!=',$authUser->_id)->delete();
        return response()->json(['message'=>'All Users Deleted Successfully']);
      }

    
    

    
}
