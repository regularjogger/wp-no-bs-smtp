<?php declare(strict_types=1);

/**
 * Plugin Name:       No Bullsh*t SMTP
 * Plugin URI:        https://github.com/regularjogger/wp-no-bs-smtp
 * Description:       The simplest, safest and most dev-friendly way to send emails from WordPress via SMTP.
 * Version:           1.0.0
 * Requires at least: 6.3
 * Requires PHP:      8.0
 * Author:            Miroslav Krogner
 * Author URI:        https://github.com/regularjogger
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Update URI:        https://github.com/regularjogger/wp-no-bs-smtp
 */

namespace NoBSSMTP;

use PHPMailer\PHPMailer\PHPMailer;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class SMTPConfig {
	/**
	 * Filter from address and name to make sure defaults from wp-config.php are only used
	 * if these headers aren't already being passed via the $headers argument of wp_mail().
	 *
	 * WordPress sets these to WordPress <wordpress@yourdomain.xyz> by default so we
	 * check if that's the case and only then overwrite the values with our defaults.
	 */

	private static function setDefaultFromEmail( string $from_email ) : string {
		if ( str_starts_with( $from_email, 'wordpress@' ) ) {
			$from_email = \SMTP_FROM;
		}

		return $from_email;
	}

	private static function setDefaultFromName( string $from_name ) : string {
		if ( $from_name === 'WordPress' ) {
			$from_name = \SMTP_FROM_NAME;
		}

		return $from_name;
	}

	/**
	 * Instructs PHPMailer to send mail via SMTP using configuration stored in wp-config.php.
	 */
	private static function sendViaSMTP( PHPMailer $phpmailer ) : void {
		$phpmailer->isSMTP();
		$phpmailer->Host       = \SMTP_HOST;
		$phpmailer->Port       = \SMTP_PORT;
		$phpmailer->SMTPAuth   = \SMTP_AUTH;
		$phpmailer->Username   = \SMTP_USER;
		$phpmailer->Password   = \SMTP_PASS;
		$phpmailer->SMTPSecure = \SMTP_SECURE;
		$phpmailer->SMTPDebug  = \SMTP_DEBUG;

		if ( empty( \SMTP_SENDER ) ) {
			$phpmailer->Sender   = $phpmailer->From;
		} else {
			$phpmailer->Sender   = \SMTP_SENDER;
		}
	}

	/**
	 * Prints debug info to screen and/or log depending on WP_DEBUG and SMTP_DEBUG values set in wp-config.php.
	 */
	private static function printDebugInfo( WP_Error $wp_error ) : void {
		if ( \WP_DEBUG === FALSE || \SMTP_DEBUG === 0 ) {
			return;
		}

		ob_start();
		var_dump( $wp_error );
		$debug_dump = ob_get_clean();

		if ( \WP_DEBUG_DISPLAY === TRUE ) {
			echo '<pre>' . $debug_dump . '</pre>';
		}

		if ( \WP_DEBUG_LOG === TRUE ) {
			error_log( $debug_dump );
		}
	}

	/**
	 * Hooks the class methods to corresponding filters and actions.
	 */
	public static function hookIntoWordpress() : void {
		\add_filter( 'wp_mail_from', [self::class, 'setDefaultFromEmail'], 7 );     // Hooking into the filters at lower priority to not accidentally
		\add_filter( 'wp_mail_from_name', [self::class, 'setDefaultFromName'], 7 ); // overwrite any values filtered around default priority or higher.
		\add_action( 'phpmailer_init', [self::class, 'sendViaSMTP'] );
		\add_action( 'wp_mail_failed', [self::class, 'printDebugInfo'] );
	}
}

SMTPConfig::hookIntoWordpress();
