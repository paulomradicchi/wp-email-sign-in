<?php
namespace WPEmailSignIn\Core\Login;

use WPEmailSignIn\Lib\Singleton;

class URL extends Singleton{

    const NONCE_ACTION = 'emailsign';
    const USER_PARAM = 'u';
    const TOKEN_PARAM = 't';
    const NONCE_PARAM = 'n';
    
    public function generate(\WP_User $User, $token) {
        $url = \home_url();
        $url = \add_query_arg( self::USER_PARAM, $User->ID, $url );
        $url = \add_query_arg( self::TOKEN_PARAM, $token, $url );
        $url = \add_query_arg( self::NONCE_PARAM, \wp_create_nonce(self::NONCE_ACTION), $url );
        return $url;
    }

}