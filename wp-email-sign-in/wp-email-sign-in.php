<?php
/**
 * @package Hello_Dolly
 * @version 1.6
 */
/*
Plugin Name: WP Email Sign In
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: Enable passwordless login for your wordpress site
Author: Paulo Radicchi
Version: 0.1
Author URI: paulomradicchi@gmail.com
Text Domain: wp-email-sign-in
Domain Path: /resources/languages
*/




namespace WPEmailSignIn;

use WPEmailSignIn\Lib\Singleton,
	WPEmailSignIn\Lib\Autoloader,
	WPEmailSignIn\Core\Login as Login;

define(__NAMESPACE__ . '\PLUGIN_PATH', \WP_PLUGIN_DIR . '/' . \dirname( \plugin_basename( __FILE__ ) ));
define(__NAMESPACE__ . '\PLUGIN_RESOURCES_PATH', \realpath(PLUGIN_PATH . '/resources'));
define(__NAMESPACE__ . '\PLUGIN_TEMPLATE_PATH', \realpath(PLUGIN_RESOURCES_PATH . '/templates'));
define(__NAMESPACE__ . '\PLUGIN_LOCALE_PATH', \realpath(PLUGIN_RESOURCES_PATH . '/lang'));
define(__NAMESPACE__ . '\PLUGIN_URL', \plugin_dir_url( __FILE__ ));
define(__NAMESPACE__ . '\PLUGIN_RESOURCES_URL', PLUGIN_URL . '/resources');
define(__NAMESPACE__ . '\PLUGIN_VERSION', '0.1');

require_once PLUGIN_PATH . '/Lib/Singleton.php';
require_once PLUGIN_PATH . '/Core/Login.php';

class Plugin extends Singleton {

	protected function _init() {
		Login::getInstance();

		add_action( 'init', array($this, 'loadTextDomain') );
	}

	public function loadTextDomain() {
		load_plugin_textdomain( 'wp-email-sign-in', false, PLUGIN_RESOURCES_PATH. '/languages' );
	}

}

Plugin::getInstance();

?>
