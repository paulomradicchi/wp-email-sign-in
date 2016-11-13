<?php
namespace WPEmailSignIn\Core\Login;

use WPEmailSignIn\Lib\Singleton;

/**
 * Class Token
 * @package WPEmailSignIn\Core\Login
 */
class Token extends Singleton{

    /**
     * token meta_key
     */
    const META_TOKEN = 'wp_signin_token';
    /**
     * token expiration time meta_key
     */
    const META_TOKEN_EXPIRATION = 'wp_signin_token_expiration';
    /**
     * number of bytes used to create token
     */
    const RANDOM_BYTES_LENGTH = 32;

    /**
     * Generates token and stores hashed token and token expiration in user_meta table
     * @param \WP_User $User
     * @return string
     */
    public function generate(\WP_User $User) {

        //Generate secure token
        $token = \openssl_random_pseudo_bytes(self::RANDOM_BYTES_LENGTH);
        $token = \bin2hex($token);

        //Hash
        $token_hash = \password_hash($token, PASSWORD_BCRYPT);

        //Expires in 24 hours
        $expire = \time() + DAY_IN_SECONDS;

        //stores exiration time and hash as user meta
        \update_user_meta($User->ID, self::META_TOKEN, $token_hash);
        \update_user_meta($User->ID, self::META_TOKEN_EXPIRATION, $expire);

        return $token;
    }

    /**
     * Validates token
     * @param $token
     * @param \WP_User $User
     * @return bool
     */
    public function validate($token, \WP_User $User) {
        $time = \time();

        $token_hash = \get_user_meta($User->ID, self::META_TOKEN, true);
        $expire = \get_user_meta($User->ID, self::META_TOKEN_EXPIRATION, true);

        return ($time <= $expire && \password_verify($token, $token_hash));
    }

    /**
     * Destroys token
     * @param \WP_User $User
     */
    public function destroy(\WP_User $User) {
        \delete_user_meta($User->ID, self::META_TOKEN);
        \delete_user_meta($User->ID, self::META_TOKEN_EXPIRATION);
    }
}