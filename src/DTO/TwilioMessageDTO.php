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

    protected ?string $body;

    protected MessageStatus $status;

    protected MessageType $type;

    protected ?string $errorCode;

    protected ?string $errorMessage;

    public function __construct(array $data = [])
    {
        $this->setSid(data_get($data, 'sid') ?? data_get($data, 'MessageSid') ?? data_get($data, 'SmsMessageSid') ?? data_get($data, 'SmsSid'));
        $this->setAccountSid(data_get($data, 'account_sid') ?? data_get($data, 'accountSid') ?? data_get($data, 'AccountSid'));
        $this->setMessagingServiceSid(data_get($data, 'messaging_service_sid') ?? data_get($data, 'messagingServiceSid'));
        $this->setFrom(data_get($data, 'from') ?? data_get($data, 'From'));
        $this->setTo(data_get($data, 'to') ?? data_get($data, 'To'));
        $this->setDirectionFromData($data);
        $this->setBody(data_get($data, 'body') ?? data_get($data, 'Body'));
        $this->setStatus(data_get($data, 'status') ?? data_get($data, 'MessageStatus') ?? data_get($data, 'SmsStatus'));
        $this->setTypeFromData($data);
        $this->setErrorCode(data_get($data, 'ErrorCode'));
        $this->setErrorMessage(data_get($data, 'ErrorMessage'));
    }

    private function setSid(string $sid): void
    {
        $this->sid = $sid;
    }

    private function setMessagingServiceSid(string|null $messagingServiceSid): void
    {
        $this->messagingServiceSid = $messagingServiceSid;
    }

    private function setAccountSid(string $accountSid): void
    {
        $this->accountSid = $accountSid;
    }

    private function setDirectionFromData(array $data): void
    {
        $direction = data_get($data, 'direction');

        if ($direction) {
            if (Str::contains($direction, ['inbound', 'incoming'], true)) {
                $this->setDirection(MessageDirection::Incoming);
            } else {
                $this->setDirection(MessageDirection::Outgoing);
            }
        } elseif (Str::contains(config('twilio.from'), $this->from,)) {
            $this->setDirection(MessageDirection::Outgoing);
        } elseif (Str::contains(config('twilio.from'), $this->to)) {
            $this->setDirection(MessageDirection::Incoming);
        }
    }

    private function setDirection(MessageDirection $direction): void
    {
        $this->direction = $direction;
    }

    private function setFrom(string $from): void
    {
        $this->from = Str::after($from, '+');
    }

    private function setTo(string $to): void
    {
        $this->to =  Str::after($to, '+');
    }

    private function setBody(string|null $body): void
    {
        $this->body = $body;
    }

    private function setTypeFromData(array $data): void
    {
        $this->setType(MessageType::SMS);
        $from = data_get($data, 'from') ?? data_get($data, 'From');

        if (data_get($data, 'ChannelPrefix') === 'whatsapp') {
            $this->setType(MessageType::Whatsapp);
        } elseif (isset($data['WaId']) && data_get($data, 'WaId')) {
            $this->setType(MessageType::Whatsapp);
        } elseif (Str::contains($from, 'whatsapp', true)) {
            $this->setType(MessageType::Whatsapp);
        }
    }

    private function setType(MessageType $type): void
    {
        $this->type = $type;
    }

    private function setStatus(string $status): void
    {
        $this->status = MessageStatus::getCase(ucfirst($status));
    }

    private function setErrorCode(string|null $errorCode): void
    {
        $this->errorCode = $errorCode;
    }

    private function setErrorMessage(string|null $errorMessage): void
    {
        $this->errorMessage = $errorMessage;
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
