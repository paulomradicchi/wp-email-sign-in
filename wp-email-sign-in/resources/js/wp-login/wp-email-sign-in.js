var re_route_form = false;
jQuery(document).ready(function () {
    //Toggle password input and form ajax re-routing
    jQuery('#wm_email_sign_in').bind('change', function() {
        if (jQuery(this).is(':checked')) {
            jQuery('#user_pass').parents('p').fadeOut();
            re_route_form = true;
        } else{
            jQuery('#user_pass').parents('p').fadeIn();
            re_route_form = false;
        }
    });

    //Capture form submission and re route using ajax if needed
    jQuery('#loginform').on('submit', function (e) {
        if (re_route_form) {
            //Prevent form submission
            e.preventDefault();

            //disable submit button
            jQuery('#wp-submit').prop('disabled', true);


            jQuery.post(locale.url, jQuery(this).serialize(), function (response) {

                //If response is not a json object, something went wrong
                if (typeof response != 'object') {
                    response = {
                        "status":false,
                        "message": locale.error
                    }
                }

                //Check response status and show reponse message
                if (response.status) {
                    jQuery('#loginform').before('<p class="message">' + response.message +'</p>');
                } else {
                    if (jQuery('#login_error').length) {
                        jQuery('#login_error').html(response.message);
                    } else {
                        jQuery('#loginform').before('<div id="login_error">' + response.message +'</div>');
                    }
                }


                //enable submit button
                jQuery('#wp-submit').prop('disabled', false);

            }, 'json')
        }
    });
});