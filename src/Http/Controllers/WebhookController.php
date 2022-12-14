<?php

namespace Laraditz\Twilio\Http\Controllers;

use Illuminate\Http\Request;
use Laraditz\Twilio\DTO\TwilioMessageDTO;
use Laraditz\Twilio\Events\MessageReceived;
use Laraditz\Twilio\Events\StatusCallback;
use Laraditz\Twilio\Models\TwilioMessage;
use Laraditz\Twilio\Enums\MessageStatus;
use Laraditz\Twilio\Twilio;

class WebhookController extends Controller
{
    public function receive(Request $request)
    {
        if ($request->all()) {
            logger()->info('Twilio message received', $request->all());

            event(new MessageReceived($request->all()));

            $data = new TwilioMessageDTO($request->toArray());

            app(Twilio::class)->saveMessage($data);
        }
    }

    public function status(Request $request)
    {
        if ($request->all()) {
            logger()->info('Twilio status callback', $request->all());

            event(new StatusCallback($request->all()));

            $data = new TwilioMessageDTO($request->toArray());

            app(Twilio::class)->updateMessageStatus($data);
        }
    }
}
