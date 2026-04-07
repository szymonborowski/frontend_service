<?php

namespace App\Http\Controllers;

use App\Actions\SubmitForm;
use App\Mail\ContactNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function send(Request $request, SubmitForm $submitForm): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:150'],
            'phone'   => ['nullable', 'string', 'max:20'],
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

        $submitForm->handle('contact', $data, new ContactNotification(
            senderName:  $data['name'],
            senderEmail: $data['email'],
            phone:       $data['phone'] ?? null,
            contactSubject: $data['subject'],
            messageBody: $data['message'],
        ));

        return response()->json(['success' => true]);
    }
}
