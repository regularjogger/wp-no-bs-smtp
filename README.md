# No Bullsh*t SMTP (wp-no-bs-smtp)
The simplest, safest and most dev-friendly way to send emails from WordPress via SMTP.

There's plenty of WordPress SMTP plugins out there, most are unnecessarily bloated though and save all of their settings in the db. The simpler ones aren't still as lean as I'd like them to be and often the db issue still stands as well as other issues, e.g., overwriting from headers. This one is no-frills dead simple while still offering flexibility.

WordPress' `wp_mail()` basically wraps around [PHPMailer](https://github.com/PHPMailer) which comes bundled within. It is configured to use PHP's `mail()` by default though, so this plugin provides a simple class that hooks in and changes the configuration to utilize your SMTP settings instead.

## Features
- Set-and-forget.
- Settings stored in wp-config.php file makes switching between environments a breeze. Feel free to store your SMTP settings in an `.env` file as well.
- Doesn't overwrite custom from address and name headers passed either via `$headers` of `wp_mail()` or `wp_mail`, `wp_mail_from` and `wp_mail_from_name` filters.
- A single PHP file – can be installed as a must-use plugin.
- Debug ready.
- Works for single and multi sites.

If you need/want to log and debug your emails from your WP dashboard, there's the wonderful [WP Mail Catcher](https://github.com/JWardee/wp-mail-catcher) by [James Ward](https://jamesward.io) in the WP.org repos ([link](https://wordpress.org/plugins/wp-mail-catcher/)) that's excellent companion to this plugin.

## Minimum Requirements
- WordPress 6.3
- PHP 8.0

## Configuration example
This is my local dev environment configuration for Mailpit – copy and edit this in your wp-config.php file between the lines:

`/* Add any custom values between this line and the "stop editing" line. */`

and

`/* That's all, stop editing! Happy publishing. */`

```php
// ** SMTP settings for PHPMailer – see https://github.com/PHPMailer/PHPMailer/wiki for more on debug levels, etc. ** //

/** Mail server hostname (string) */
define( 'SMTP_HOST', 'mailpit' );

/** Port number – usually 25, 465 or 587 (integer) */
define( 'SMTP_PORT', 1025 );

/** Use credentials to authenticate (true|false) */
define( 'SMTP_AUTH', false );

/** Username (string) */
define( 'SMTP_USER', '' );

/** Password (string) */
define( 'SMTP_PASS', '' );

/** Encryption method – '', 'ssl' or 'tls' (string) */
define( 'SMTP_SECURE', '' );

/** Default From address (string) */
define( 'SMTP_FROM', 'mailer@localhost.test' );

/** Default From name (string) */
define( 'SMTP_FROM_NAME', 'Localhost WP Mailer' );

/** Debug level, 0–4 (integer) */
define( 'SMTP_DEBUG', 0 );

/** Envelope sender address – usually turned into a Return-Path header by the receiver (string)
 *  Leave empty ('') or do not set at all to dynamically use From address instead */
define( 'SMTP_SENDER', 'bounces@localhost.test' );

// ** END SMTP settings ** //
```
