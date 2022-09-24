<?php

namespace Laraditz\Twilio\Enums;

enum MessageStatus: int
{
    use EnumTrait;
    
    case Accepted = 1;

    case Queued = 2;

    case Sending = 3;

    case Sent = 4;

    case Failed = 5;

    case Delivered = 6;

    case Undelivered = 7;

    case Receiving = 8;

    case Received = 9;
    
    case Read = 10;
}
