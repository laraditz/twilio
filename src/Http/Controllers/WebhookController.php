<?php

namespace Laraditz\Twilio\Http\Controllers;

use Illuminate\Http\Request;
use Laraditz\Twilio\Events\MessageReceived;
use Laraditz\Twilio\Events\StatusCallback;

class WebhookController extends Controller
{
    public function receive(Request $request)
    {
        if ($request->all()) {
            event(new MessageReceived($request->all()));
        }
    }

    public function status(Request $request)
    {
        if ($request->all()) {
            event(new StatusCallback($request->all()));
        }
    }
}
