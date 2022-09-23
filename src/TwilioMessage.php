<?php

namespace Laraditz\Twilio;

use Illuminate\Support\Str;

class TwilioMessage
{
    /** @var string|null */
    public $sender = null;

    /** @var string|null */
    public $content = null;

    /** @var string */
    public $numberPrefix = '';

    public function __construct(string $content = null)
    {
        $from  = config('twilio.from');
        $this->sender = $this->formatNumber($from);
        $this->content = $content;
    }

    public function content(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function sender(string $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function formatNumber(string $string)
    {
        $number =  Str::startsWith($string, '+') ? $string : '+' . $string;

        return $this->numberPrefix ? Str::of($number)->prepend($this->numberPrefix)->value : $number;
    }
}
