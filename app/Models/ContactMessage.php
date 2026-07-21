<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'message',
        'ip',
        'user_agent',
        'status'
    ];



    /*
     ContactMessage::create([
        'name' => 'Bogdan',
        'email' => 'bogdan@test.com',
        'message' => 'Hello from Tinker',
        'ip' => '127.0.0.1',
        'user_agent' => 'Tinker',
        'status' => 'new',
    ]);
     */


}
