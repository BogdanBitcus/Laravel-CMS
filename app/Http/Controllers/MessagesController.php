<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $list = ContactMessage::orderByDesc('id')->paginate(100);

        return view(
            'system.contact_form_messages',
            [
                'list'=>$list,
                'user'=>$user,
            ]
        );
    }

    public function deleteMessage($message){
        ContactMessage::where('id', $message)->delete(); // forceDelete()
        return response()->json(['message' => __('Message deleted successfully')]);
    }

}
