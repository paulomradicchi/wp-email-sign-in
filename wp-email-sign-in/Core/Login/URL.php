<?php
namespace WPEmailSignIn\Core\Login;

use WPEmailSignIn\Lib\Singleton;

/**
 * Class URL
 * @package WPEmailSignIn\Core\Login
 */
class URL extends Singleton{

    /**
     * WP Nonce action name
     */
    const NONCE_ACTION = 'emailsign';
    /**
     * User request param name
     */
    const USER_PARAM = 'u';
    /**
     * Token request param name
     */
    const TOKEN_PARAM = 't';
    /**
     * Nonce request param name
     */
    const NONCE_PARAM = 'n';

    /**
     * Creates sign in link
     * @param \WP_User $User
     * @param $token
     * @return string|void
     */
    public function generate(\WP_User $User, $token) {
        $url = \home_url();
        $url = \add_query_arg( self::USER_PARAM, $User->ID, $url );
        $url = \add_query_arg( self::TOKEN_PARAM, $token, $url );
        $url = \add_query_arg( self::NONCE_PARAM, \wp_create_nonce(self::NONCE_ACTION), $url );
        return $url;
    }

}