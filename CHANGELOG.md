# Changelog

All notable changes to `laraditz/twilio` will be documented in this file

## 1.0.1 - 2022-10-09


### Added
- Add webhook params support for `TwilioMessageDTO`.
- Add `saveMessage`, `updateMessageStatus`, `updateLogError` to `Twilio` class.
- Add `error_message` to `twilio_logs` and `twilio_messages` tables.

### Changed
- Refactor webhook.


## 1.0.0 - 2022-09-26

- Initial release

### Added
- Add `TwilioChannel` with support for `TwilioSmsMessage` and `TwilioWhatsappMessage`.
- Add `Twilio` class to handle logic to interact with Twilio SDK.
- Add webhook URLs for message receive and status updates.
- Add `MessageReceived` and `StatusCallback` events.
- Add `MessageDirection`, `MessageStatus` and `MessageType` enums.
- Add `TwilioMessageDTO` for message data transfer object.
- Add `twilio_logs` and `twilio_messages` tables.