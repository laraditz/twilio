# Laravel Twilio

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laraditz/twilio.svg?style=flat-square)](https://packagist.org/packages/laraditz/twilio)
[![Total Downloads](https://img.shields.io/packagist/dt/laraditz/twilio.svg?style=flat-square)](https://packagist.org/packages/laraditz/twilio)
![GitHub Actions](https://github.com/laraditz/twilio/actions/workflows/main.yml/badge.svg)

Twilio SDk wrapper for Laravel. Includes event for receiving messages and status updates.

## Installation

You can install the package via composer:

```bash
composer require laraditz/twilio
```

## Before Start

Configure your variables in your `.env` (recommended) or you can publish the config file and change it there.
```
TWILIO_ACCOUNT_SID=<your_account_sid>
TWILIO_AUTH_TOKEN=<your_auth_token>
TWILIO_FROM=<the_sender_number>
```

(Optional) You can publish the config file via this command:
```bash
php artisan vendor:publish --provider="Laraditz\Twilio\TwilioServiceProvider" --tag="config"
```

Run the migration command to create the necessary database table.
```bash
php artisan migrate
```

Add `routeNotificationForTwilio` method to your Notifiable model.
```php
public function routeNotificationForTwilio($notification)
{
    return $this->mobile_no;
}
```

## Usage

Now you can use the channel in your `via()` method inside the notification:

```php
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Laraditz\Twilio\TwilioChannel;
use Laraditz\Twilio\TwilioWhatsappMessage;

class TestNotification extends Notification
{
    use Queueable;  
  
    public function via($notifiable)
    {
        return [TwilioChannel::class];
    }

    public function toTwilio($notifiable)
    {
        return (new TwilioWhatsappMessage())
            ->content("Test Whatsapp message!")
            ->mediaUrl("https://news.tokunation.com/wp-content/uploads/sites/5/2022/07/Kamen-Rider-Geats-Teaser.jpeg");
    }
}
```

You can also send SMS:

```php
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Laraditz\Twilio\TwilioChannel;
use Laraditz\Twilio\TwilioSmsMessage;

class TestNotification extends Notification
{
    use Queueable;  
  
    public function via($notifiable)
    {
        return [TwilioChannel::class];
    }

    public function toTwilio($notifiable)
    {
        return (new TwilioSmsMessage())
            ->content("Test SMS message!");
    }
}
```

## Event

This package also provide an event to allow your application to listen for Twilio message receive and status callback. You can create your listener and register it under event below.

| Event                                     |  Description  
|-------------------------------------------|-----------------------|
| Laraditz\Twilio\Events\MessageReceived    | When a message comes in.
| Laraditz\Twilio\Events\StatusCallback     | Receive status update. 

## Webhook URL

You may setup the URLs below on Twilio dashboard so that Twilio will push new messages and status update to it and it will then trigger the `MessageReceived` and `StatusCallback` events above.

```
https://your-app-url/twilio/webhooks/receive //for message receive
https://your-app-url/twilio/webhooks/status //for status update
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email raditzfarhan@gmail.com instead of using the issue tracker.

## Credits

-   [Raditz Farhan](https://github.com/laraditz)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.