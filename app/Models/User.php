<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        //'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function getUserByAdmins(){
        $users = self::where('is_admin', 1)->get();
        return $users;
    }

    public static function getUserById($id){
        //if($id==0) { abort(404); }
        $user = self::find($id);
        return $user;
    }


    public static function createUserAdmin($name='Admin name',$email='new_email@website.com',$password='1',$is_admin=1){
        return self::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'is_admin' => $is_admin
        ]);
    }


    public static function removeUser($id){
        return self::where('id', $id)->delete(); // forceDelete()
    }
}
