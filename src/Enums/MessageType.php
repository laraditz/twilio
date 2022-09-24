<?php

namespace Laraditz\Twilio\Enums;

enum MessageType: int
{
    use EnumTrait;

    case SMS = 1;

    case Whatsapp = 2;
}
