<?php
/**
 * Plugin Name: WP Email Sign In
 * Plugin URI: http://wordpress.org/plugins/hello-dolly/
 * Description: Enable passwordless login for your wordpress site
 * Author: Paulo Radicchi
 * Version: 0.1.1
 * Author URI: paulomradicchi@gmail.com
 * Text Domain: wp-email-sign-in
 * Domain Path: /resources/languages
 */

/* Copyright Paulo Monteiro Radicchi
 
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
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

/**
 * Class Plugin
 * @package WPEmailSignIn
 */
class Plugin extends Singleton {

    /**
     * Init 
     * @see WPEmailSignIn\Lib\Singleton::_init
     */
    protected function _init() {
        //Init 
		Login::getInstance();

        //Load translation
		add_action( 'init', array($this, 'loadTextDomain') );
	}

    /**
     * Load translation
     * @see load_plugin_textdomain
     */
    public function loadTextDomain() {
		load_plugin_textdomain( 'wp-email-sign-in', false, PLUGIN_RESOURCES_PATH. '/languages' );
	}

}

Plugin::getInstance();

?>
