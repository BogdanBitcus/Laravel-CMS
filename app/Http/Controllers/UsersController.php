<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class UsersController extends Controller
{

    public function index(Request $request){

        $user = Auth::user();
        /*if ( ! $user->is_admin ) {
            Auth::logout();
            return redirect('/cms')->with('error', __('You do not have permission to access this page'));
        }*/
        $userslist = User::getUserByAdmins();

        return view('system.users', ['user'=>$user, 'list'=>$userslist]);
    }



    public function createUserAdmin(){

        $user = User::createUserAdmin();
        if ( ! $user->is_admin ) {
            Auth::logout();
            return redirect('/cms')->with('error', __('You do not have permission to access this page'));
        }
        return redirect('/cms/users/'.$user->id);
    }



    public function userEdit($id){

        $user = Auth::user();
        if ( ! $user->is_admin ) {
            Auth::logout();
            return redirect('/cms')->with('error', __('You do not have permission to access this page'));
        }
        $user_edit = User::getUserById($id);

        return view('system.user_edit', ['user'=>$user, 'user_edit'=>$user_edit]);
    }



    public function save(Request $request, User $user_for_save){

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user_for_save->id),
            ],
            'password' => 'nullable|min:8|confirmed',
            //'is_admin' => 'boolean',
        ]);

        $user_for_save->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            //'is_admin' => $request->boolean('is_admin')
        ]);

        if ( ! empty($validated['password']) ) {
            $user_for_save->password = $validated['password'];
        }

        /*if ( $user_for_save->is(Auth::user()) ) {
            $user_for_save->is_admin = true;
        }*/

        $user_for_save->save();

        return redirect('/cms/users')->with('message',__('User Saved'));
    }



    public function deleteUser($id)
    {
        $user = User::removeUser($id);
        return response()->json(['message' => __('Page deleted successfully')]);
    }

}
