<?php

namespace Laraditz\Twilio;

use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;

class TwilioChannel
{
    /**
     * @var Twilio
     */
    protected $twilio;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * TwilioChannel constructor.
     *
     * @param Twilio $twilio
     * @param Dispatcher $events
     */
    public function __construct(Twilio $twilio, Dispatcher $events)
    {
        $this->twilio = $twilio;
        $this->events = $events;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$to = $notifiable->routeNotificationFor('twilio')) {
            return;
        }

        $message = $notification->toTwilio($notifiable);

        if (!$message instanceof TwilioMessage) {
            return;
        }

        try {
            return $this->twilio->sendMessage($message, $to);
        } catch (\Throwable $th) {

            $event = new NotificationFailed(
                $notifiable,
                $notification,
                'twilio',
                ['message' => $th->getMessage(), 'exception' => $th]
            );

            $this->events->dispatch($event);

            throw $th;
        }
    }
}
