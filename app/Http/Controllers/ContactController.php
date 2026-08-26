<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    
    /**
     * Display the contact page.
     */
    public function index(): Response
    {
        return Inertia::render('Site/Contact');
    }

     /**
     * Store a contact message.
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'subject' => [
                'required',
                'string',
                'max:255',
            ],

            'message' => [
                'required',
                'string',
                'max:5000',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | CREATE CONTACT MESSAGE
        |--------------------------------------------------------------------------
        */

        ContactMessage::create($validated);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('contactDetails')
            ->with(
                'success',
                'Your message has been sent successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN CONTACT MESSAGES
    |--------------------------------------------------------------------------
    */

    public function messages(): Response
    {
        $messages = ContactMessage::with('user')
            ->latest()
            ->get();

        return Inertia::render(
            'MyFashions/Contacts/Index',
            [
                'messages' => $messages,
            ]
        );
    }

     /*
    |--------------------------------------------------------------------------
    | MARK AS READ
    |--------------------------------------------------------------------------
    */

    public function markAsRead(ContactMessage $contactMessage)
    {
        $contactMessage->update([
            'status' => 'read',
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Contact message marked as read.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | REPLY
    |--------------------------------------------------------------------------
    */

    public function reply(
        Request $request,
        ContactMessage $contactMessage
    ) {
        $validated = $request->validate([

            'reply' => [
                'required',
                'string',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | SEND EMAIL
        |--------------------------------------------------------------------------
        */

        Mail::raw(
            $validated['reply'],
            function ($mail) use ($contactMessage) {

                $mail
                    ->to($contactMessage->email)
                    ->subject(
                        'Re: ' . $contactMessage->subject
                    );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | UPDATE MESSAGE
        |--------------------------------------------------------------------------
        */

        $contactMessage->update([

            'status' => 'replied',

            'replied_at' => now(),

        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Reply sent successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()
            ->back()
            ->with(
                'success',
                'Contact message deleted successfully.'
            );
    }


   
}
