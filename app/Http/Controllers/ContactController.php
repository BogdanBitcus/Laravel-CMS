<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pages;
use App\Models\Templates;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;



class ContactController extends Controller
{

    public function index(Request $request, Templates $template, Pages $page){

        // Якщо форма відправлена
        if ($request->isMethod('post')) {
            return $this->send($request, $template, $page);
        }


        // Якщо потрібно — витягуємо додаткові дані
        //$offices = Office::all();

        return view(
            'views.'.$template->view_tpl,
            [
                'page' => $page,
                //'offices' => $offices,
            ]
        );

    }


    protected function send(Request $request, Templates $template, Pages $page)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        $contact_message = ContactMessage::create([
            'name'=>$validated['name'],
            'email'=>$validated['email'],
            'message'=>$validated['message'],
            'status'=>'new',
            'ip'=>$request->ip(),
            'user_agent'=>$request->userAgent(),
        ]);

        try {

            Mail::to(config('mail.contact_email'))->send(new ContactMail($validated));

            $contact_message->update([
                'status' => 'sent'
            ]);

        } catch (\Throwable $e) {

            $contact_message->update([
                'status' => 'error'
            ]);

            report($e);
            return back()->withErrors(['mail' => __('Unable to send email.')]);
        }

        return back()->with('message', __('Message sent.'));
    }
}
