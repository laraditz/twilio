<?php

namespace Laraditz\Twilio\Enums;

enum MessageDirection: int
{
    use EnumTrait;

    case Incoming = 1;

    case Outgoing = 2;
}
