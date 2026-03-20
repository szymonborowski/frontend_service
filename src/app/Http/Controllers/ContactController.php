<?php

namespace App\Http\Controllers;

use App\Mail\ContactNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:150'],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        Mail::to(config('mail.contact_to', config('mail.from.address')))
            ->send(new ContactNotification(
                senderName:  $data['name'],
                senderEmail: $data['email'],
                subject:     $data['subject'],
                messageBody: $data['message'],
            ));

        return response()->json(['success' => true]);
    }
}
