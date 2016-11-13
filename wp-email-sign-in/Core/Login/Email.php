<?php
namespace WPEmailSignIn\Core\Login;

use WPEmailSignIn,
    WPEmailSignIn\Lib\Singleton,
    WPEmailSignIn\PLUGIN_TEMPLATE_PATH,
    WPEmailSignIn\Core\Login\Token,
    WPEmailSignIn\Core\Login\URL;

class Email extends Singleton {

    public function send(\WP_User $User) {
        $token = Token::getInstance()->generate($User);
        $url = URL::getInstance()->generate($User, $token);

        //Require login template and capture output using ob
        \ob_start();
        require  WPEmailSignIn\PLUGIN_TEMPLATE_PATH . '/email.php';
        $email = \ob_get_clean();
        //Try to send email and store result in var
        $sent = \wp_mail($User->user_email, \__(sprintf('Your magic sign-in link for %s', get_bloginfo( 'name' )), 'wp-email-sign-in'), $email);

        //If not sent, destroy the token
        if (!$sent) {
            Token::getInstance()->destroy($token);
        }
        return $sent;
        
    }

}