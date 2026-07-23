<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pages;
use App\Models\Templates;

class NewsListController extends Controller
{
    public function index(Request $request, Templates $template, Pages $page){

        // Subscribe form
        if ($request->isMethod('post')) {
            return $this->subscribe($request);
        }

        $news = Pages::where('parent', $page->id)
            ->orderByDesc('id')
            ->paginate(10);

        return view(
            'views.'.$template->view_tpl,
            [
                'page' => $page,
                'news' => $news,
            ]
        );

    }


    protected function subscribe(Request $request)
    {
 /*       $validated = $request->validate([
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

        return back()->with('message', __('Message sent.'));*/
    }
}
