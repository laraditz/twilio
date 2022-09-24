<?php

namespace Laraditz\Twilio\DTO;

use Illuminate\Support\Str;
use Laraditz\Twilio\Enums\MessageDirection;
use Laraditz\Twilio\Enums\MessageStatus;
use Laraditz\Twilio\Enums\MessageType;

class TwilioMessageDTO
{
    protected string $sid;

    protected string $accountSid;

    protected ?string $messagingServiceSid;

    protected MessageDirection $direction;

    protected string $from;

    protected string $to;

    protected string $body;

    protected MessageStatus $status;

    protected MessageType $type;

    public function __construct(array $data = [])
    {
        $this->sid = data_get($data, 'sid');
        $this->accountSid = data_get($data, 'account_sid');
        $this->messagingServiceSid = data_get($data, 'messaging_service_sid');
        $this->setDirection(data_get($data, 'direction'));
        $this->setFrom(data_get($data, 'from'));
        $this->setTo(data_get($data, 'to'));
        $this->body = data_get($data, 'body');
        $this->setStatus(data_get($data, 'status'));
        $this->setType(data_get($data, 'from'));
    }

    private function setDirection(string $direction): void
    {
        if (Str::contains($direction, ['inbound', 'incoming'], true)) {
            $this->direction = MessageDirection::Incoming;
        } else {
            $this->direction = MessageDirection::Outgoing;
        }
    }

    private function setFrom(string $from): void
    {
        $this->from = Str::after($from, '+');
    }

    private function setTo(string $to): void
    {
        $this->to =  Str::after($to, '+');
    }

    private function setType(string $type): void
    {
        $this->type = MessageType::SMS;

        if (Str::contains($type, 'whatsapp', true)) {
            $this->type = MessageType::Whatsapp;
        }
    }

    private function setStatus(string $status): void
    {
        $this->status = MessageStatus::getCase(ucfirst($status));
    }

    public function __call($name, $arguments)
    {
        if (Str::startsWith($name, 'get')) {
            $property =  Str::of($name)->after('get')->camel();
            if (property_exists($this, $property)) {
                return $this->{$property};
            }
        }
    }
}
