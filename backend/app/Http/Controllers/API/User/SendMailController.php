<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendMailRequest;
use App\Mail\FeedbackMail;
use Illuminate\Support\Facades\Mail;

class SendMailController extends Controller
{
    public function __invoke(SendMailRequest $request)
    {
        $data = $request->validated();

        Mail::to('arthyrarthyr@icloud.com')->send(new FeedbackMail($data));

    }
}
