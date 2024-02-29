<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
  public function contact(Request $request)
  {
    $formData = $request->validate([
      'fullname' => 'required|string',
      'email' => 'required|email',
      'telephone' => 'required|numeric',
      'body' => 'required|string',
    ]);

    $mailAddress = config('mail.from.address');

    // send the mail
    Mail::to($mailAddress)->send(new ContactMail($formData['fullname'], $formData['email'], $formData['telephone'], $formData['body']));

    return response()->json([
      'success' => 'Thank you for mailing us',
    ], 200);
  }
}
