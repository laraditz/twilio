<?php

namespace Laraditz\Twilio;

use Laraditz\Twilio\Models\TwilioLog;
use Twilio\Rest\Client as TwilioClient;

class Twilio
{
    /** @var client */
    protected $client;

    /** @var TwilioConfig */
    public $config;

    public function __construct(TwilioClient $client)
    {
        $this->client = $client;
    }

    public function sendMessage(TwilioWhatsappMessage|TwilioSmsMessage $message, string $to)
    {
        $to = $message->formatNumber($to);

        if ($message instanceof TwilioSmsMessage) {
            return $this->sendSmsMessage($message, $to);
        }


        if ($message instanceof TwilioWhatsappMessage) {
            return $this->sendWhatsappMessage($message, $to);
        }
    }

    public function sendSmsMessage($message, $to)
    {
        $params = [
            'from' => $message->sender,
            'body' => trim($message->content),
        ];

        return $this->createMessage($to, $params);
    }

    public function sendWhatsappMessage($message, $to)
    {
        $params = [
            'from' => $message->sender,
            'body' => trim($message->content),
        ];

        if ($message->mediaUrl) {
            $params['mediaUrl'] = $message->mediaUrl;
        }

        return $this->createMessage($to, $params);
    }

    public function createMessage(string $to, array $params = [])
    {
        try {
            $message = $this->client->messages
                ->create(
                    $to,
                    $params
                );

            if ($message->sid) {
                TwilioLog::create([
                    'source' => __METHOD__,
                    'sid' => $message->sid,
                    'request' => [
                        'to' => $to,
                        'params' => $params,
                    ],
                    'response' => $message->toArray(),
                ]);
            }

            return $message;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
