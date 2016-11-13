<?php
namespace WPEmailSignIn\Core;

require_once 'Login/Email.php';
require_once 'Login/Token.php';
require_once 'Login/URL.php';

use WPEmailSignIn,
    WPEmailSignIn\Lib\Singleton,
    WPEmailSignIn\Core\Login\Email,
    WPEmailSignIn\Core\Login\Token,
    WPEmailSignIn\Core\Login\URL;

class Login extends Singleton{
    
    protected function _init() {
        //Enqueue js & css
        add_action('login_enqueue_scripts', array($this, 'registerWpLoginScripts'));
        
        //Render checkbox at login form
        add_action('login_form', array($this, 'render'));
        
        //Bootstrap requests
        add_action('init', array($this, 'bootstrap'));
        add_action('wp_ajax_nopriv_emailsignin', array($this, 'signin'));
    }

    public function bootstrap() {
        //Verify if params are present in the current request
        if (!empty($_REQUEST[URL::USER_PARAM]) && !empty($_REQUEST[URL::NONCE_PARAM]) && !empty($_REQUEST[URL::TOKEN_PARAM])) {
            $User = get_user_by('ID', $_REQUEST[URL::USER_PARAM]);
            $nonce = $_REQUEST[URL::NONCE_PARAM];
            $token = $_REQUEST[URL::TOKEN_PARAM];

            //Is a valid User
            if ($User) {
                //Verify if nonce and token are valid
                if (wp_verify_nonce($nonce, URL::NONCE_ACTION) && Token::getInstance()->validate($token, $User)) {
                    //Sign in
                    wp_set_auth_cookie($User->ID);
                }
                //Destroy Token
                Token::getInstance()->destroy($User);
            }

            //Redirect user
            wp_redirect(home_url());
            return;
        }
    }

    public function signin() {
        // First check the nonce, if it fails the function will break
        check_ajax_referer( 'ajax-emailsignin-nonce', 'security' );
        
        // Nonce is checked, check if username/email is valid
        $user_name = sanitize_user($_POST['log']);
        $User = get_user_by( 'login', $user_name );
        if (!$User && strpos( $user_name, '@' )) {
            $User = get_user_by( 'email', $user_name );
        }

        
        //Check if user is valid
        if ($User) {
            //User is checked, try to send email
            if (Email::getInstance()->send($User)) {
                //Email sent, return json
                echo json_encode(
                    array(
                        'status' => true,
                        'message' => __('A sign in link has been sent to your email. Please follow the instructions in the email to log in.', 'wp-email-sign-in')
                    )
                );
            } else {
                //Something went wrong, inform user
                //
                echo json_encode(
                    array(
                        'status' => false,
                        'message' => __('There was a problem sending your email. Please try again or contact an admin.', 'wp-email-sign-in')
                    )
                );
            }
        } else {
            //Invalid user, return json
            echo json_encode(
                array(
                    'status' => false,
                    'message' => __('Invalid username or email.', 'wp-email-sign-in')
                )
            );
        }
        die;
        
    }
    
    public function registerWpLoginScripts() {
        wp_enqueue_script( 'jquery' );

        wp_register_script( 'sign-in-js', WPEmailSignIn\PLUGIN_RESOURCES_URL . '/js/wp-login/wp-email-sign-in.js', '0.1' );

        $translation_array = array(
            'sending' => __( 'Sending email...', 'wp-email-sign-in' ),
            'error' => __( 'There was a problem sending your email. Please try again or contact an admin.', 'wp-email-sign-in' ),
            'url' => admin_url( 'admin-ajax.php' )
        );
        wp_localize_script( 'sign-in-js', 'locale', $translation_array );
        wp_enqueue_script('sign-in-js');
    }

    public  function render() {
        //Load template script
        require  WPEmailSignIn\PLUGIN_TEMPLATE_PATH . '/login/login.php';
    }
}