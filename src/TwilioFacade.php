<?php

namespace Laraditz\Twilio;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Laraditz\Twilio\Skeleton\SkeletonClass
 */
class TwilioFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'twilio';
    }
}
