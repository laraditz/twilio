<?php

namespace Laraditz\Twilio;

use Illuminate\Support\Facades\DB;
use Laraditz\Twilio\DTO\TwilioMessageDTO;
use Laraditz\Twilio\Models\TwilioLog;
use Laraditz\Twilio\Models\TwilioMessage;
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
                $source = __METHOD__;
                DB::transaction(function () use ($source, $to, $params, $message) {
                    TwilioLog::create([
                        'source' => $source,
                        'sid' => $message->sid,
                        'request' => [
                            'to' => $to,
                            'params' => $params,
                        ],
                        'response' => $message->toArray(),
                    ]);

                    $messageData = new TwilioMessageDTO($message->toArray());
                    $this->saveMessage($messageData);
                });
            }

            return $message;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function saveMessage(TwilioMessageDTO $data)
    {
        TwilioMessage::updateOrCreate([
            'sid' => $data->getSid(),
        ], [

            'account_sid' => $data->getAccountSid(),
            'messaging_service_sid' => $data->getMessagingServiceSid(),
            'direction' => $data->getDirection(),
            'from' => $data->getFrom(),
            'to' => $data->getTo(),
            'body' => $data->getBody(),
            'type' => $data->getType(),
            'status' => $data->getStatus(),
        ]);
    }

    public function updateMessageStatus(TwilioMessageDTO $data)
    {
        $twilioMessage = TwilioMessage::where('sid', $data->getSid())->first();

        if ($twilioMessage) {
            $twilioMessage->update([
                'status' =>  $data->getStatus(),
                'error_message' =>  $data->getErrorMessage(),
            ]);

            if ($data->getErrorMessage()) {
                $this->updateLogError($data);
            }
        } else {
            $this->saveMessage($data);
        }
    }

    public function updateLogError(TwilioMessageDTO $data)
    {
        $twilioLog = TwilioLog::where('sid', $data->getSid())->first();

        if ($twilioLog) {
            $twilioLog->update([
                'error_code' =>  $data->getErrorCode(),
                'error_message' =>  $data->getErrorMessage(),
            ]);
        }
    }
}
