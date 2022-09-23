<?php

namespace Laraditz\Twilio;

use Illuminate\Support\Str;

class TwilioWhatsappMessage extends TwilioMessage
{
    public $numberPrefix = 'whatsapp:';

    public $mediaUrl;

    public function mediaUrl(string $mediaUrl): self
    {
        $this->mediaUrl = $mediaUrl;

        return $this;
    }
}
