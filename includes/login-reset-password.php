<?php

add_action( 'login_form_rp', 'redirect_to_custom_password_reset' );
add_action( 'login_form_resetpass', 'redirect_to_custom_password_reset' );

/**
 * Redirects to the custom password reset page, or the login page
 * if there are errors.
 */
function redirect_to_custom_password_reset() {
//    if ( ($_REQUEST['action'] == "resetpass") && ($_REQUEST['password'] == "changed") ) {
//        //var_dump('asdasd');exit;
//        $redirect_url = get_permalink(get_page_by_path('bethany-login')->ID);
//        $redirect_url = add_query_arg( 'password', 'changed' );
//        var_dump($redirect_url);
//        wp_redirect( $redirect_url );
//        exit;
//    }

    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {

        // Verify key / login combo
        $user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
        if ( ! $user || is_wp_error( $user ) ) {
            if ( $user && $user->get_error_code() === 'expired_key' ) {
                $redirect_url = get_permalink(get_page_by_path('bethany-login')->ID);
                $redirect_url = add_query_arg( 'login', 'expiredkey', $redirect_url );
                wp_redirect( $redirect_url );
            } else {
                $redirect_url = get_permalink(get_page_by_path('bethany-login')->ID);
                $redirect_url = add_query_arg( 'login', 'invalidkey', $redirect_url );
                wp_redirect( $redirect_url );
            }
            exit;
        }

        $redirect_url = get_permalink(get_page_by_path('bethany-reset-password')->ID);
        $redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
        $redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );

        wp_redirect( $redirect_url );
        exit;
    }
}

add_shortcode( 'reset_password_form', 'bethany_reset_password_form' );
/**
 * A shortcode for rendering the form used to reset a user's password.
 *
 * @param  array   $attributes  Shortcode attributes.
 * @param  string  $content     The text content for shortcode. Not used.
 *
 * @return string  The shortcode output
 */
function bethany_reset_password_form( $attributes, $content = null ) {
    // Parse shortcode attributes
    $default_attributes = array( 'show_title' => false );
    $attributes = shortcode_atts( $default_attributes, $attributes );

    if ( is_user_logged_in() ) {
        return __( 'You are already signed in.', 'personalize-login' );
    } else {
        if ( isset( $_REQUEST['login'] ) && isset( $_REQUEST['key'] ) ) {

            $attributes['login'] = $_REQUEST['login'];
            $attributes['key'] = $_REQUEST['key'];

            // Handle Error
            $error_code_reset = ( isset( $_REQUEST['error'] ) ) ? htmlspecialchars($_REQUEST['error']) : "";

            ob_start(); ?>

            <section class="col-main">

                <?php if ( isset($error_code_reset) && !empty($error_code_reset) ): ?>
                    <p><?php bethany_show_reset_password_error_messages($error_code_reset); ?></p>
                <?php endif; ?>

                <form name="bethany_reset_password_form" id="bethany_reset_password_form" action="<?php echo site_url( 'wp-login.php?action=resetpass' ); ?>" method="post" autocomplete="off" class="bethany_form">
                    <input type="hidden" id="user_login" name="rp_login" value="<?php echo esc_attr( $attributes['login'] ); ?>" autocomplete="off" />
                    <input type="hidden" name="rp_key" value="<?php echo esc_attr( $attributes['key'] ); ?>" />

                    <h3><label for="pass1"><?php _e( 'New password', 'personalize-login' ) ?></label></h3>
                    <div class="field-wrp">
                        <input type="password" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" />
                    </div>

                    <h3><label for="pass2"><?php _e( 'Repeat new password', 'personalize-login' ) ?></label></h3>
                    <div class="field-wrp">
                        <input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" />
                    </div>

                    <p class="description"><?php echo wp_get_password_hint(); ?></p>

                    <p class="resetpass-submit">
                        <input type="hidden" name="bethany_reset_nonce" value="<?php echo wp_create_nonce('bethany-reset-nonce'); ?>"/>
                        <a id="bethany_reset_password_submit" class="btn-primary btn-submit" href="javascript:;">Reset Password</a>
                    </p>
                </form>

            </section>

            <?php
            return ob_get_clean();
        } else {
            ob_start(); ?>

            <p>Invalid password reset link.</p>

            <?php
            return ob_get_clean();
        }
    }
}

add_action( 'login_form_rp','do_password_reset',11, 1 );
add_action( 'login_form_resetpass', 'do_password_reset',11, 1 );

/**
 * Resets the user's password if the password reset form was submitted.
 */
function do_password_reset() {
    if (( 'POST' == $_SERVER['REQUEST_METHOD'] )  && wp_verify_nonce($_POST['bethany_reset_nonce'], 'bethany-reset-nonce') ) {
        $rp_key = $_REQUEST['rp_key'];
        $rp_login = $_REQUEST['rp_login'];

        $user = check_password_reset_key( $rp_key, $rp_login );

        if ( ! $user || is_wp_error( $user ) ) {
            if ( $user && $user->get_error_code() === 'expired_key' ) {
                $redirect_url = get_permalink(get_page_by_path('bethany-login')->ID);
                $redirect_url = add_query_arg( 'login', 'expiredkey' , $redirect_url );
                wp_redirect( $redirect_url );
            } else if ( $user && $user->get_error_code() === 'invalidkey' ) {
                $redirect_url = get_permalink(get_page_by_path('bethany-login')->ID);
                $redirect_url = add_query_arg( 'login', 'invalidkey' , $redirect_url );
                wp_redirect( $redirect_url );
            }
            exit;
        }

        if ( isset( $_POST['pass1'] ) ) {
            if ( $_POST['pass1'] != $_POST['pass2'] ) {
                // Passwords don't match
                $redirect_url = get_permalink(get_page_by_path('bethany-reset-password')->ID);

                $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                $redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );

                wp_redirect( $redirect_url );
                exit;
            }

            if ( empty( $_POST['pass1'] ) ) {
                // Password is empty
                $redirect_url = get_permalink(get_page_by_path('bethany-reset-password')->ID);

                $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                $redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );

                wp_redirect( $redirect_url );
                exit;
            }

            // Parameter checks OK, reset password
            //reset_password( $user, $_POST['pass1'] );

            $new_pass = $_POST['pass1'];

            do_action( 'password_reset', $user, $new_pass );
            wp_set_password( $new_pass, $user->ID );
            update_user_option( $user->ID, 'default_password_nag', false, true );

            do_action( 'after_password_reset', $user, $new_pass );

            
            //wp_mail('arnelbornales@convertium.com','user',print_r($user,true));

            $email_change_text = __( 'Hi ###USERNAME###,

This notice confirms that your password was changed on ###SITENAME###.

If you did not change your password, please contact the Site Administrator at
webmaster@bethanyipc.sg

Regards,
Bethany Webteam

###SITEURL###' );
            $email_change_email = array(
                'to'      => $user->data->user_email,
                'subject' => __( '[%s] Notice of Email Change' ),
                'message' => $email_change_text,
                'headers' => '',
            );

            //$email_change_email = apply_filters( 'email_change_email', $email_change_email, $user, $userdata );
            $blog_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
            //$blog_name = get_option( 'blogname' );
            $email_change_email['message'] = str_replace( '###USERNAME###', $user->data->user_login, $email_change_email['message'] );
            $email_change_email['message'] = str_replace( '###ADMIN_EMAIL###', get_option( 'admin_email' ), $email_change_email['message'] );
            $email_change_email['message'] = str_replace( '###EMAIL###', $user->data->user_email, $email_change_email['message'] );
            $email_change_email['message'] = str_replace( '###SITENAME###', $blog_name, $email_change_email['message'] );
            $email_change_email['message'] = str_replace( '###SITEURL###', home_url(), $email_change_email['message'] );

            wp_mail( $email_change_email['to'], sprintf( $email_change_email['subject'], $blog_name ), $email_change_email['message'], $email_change_email['headers'] );

			$redirect_url = get_permalink(get_page_by_path('bethany-login')->ID);
            $redirect_url = add_query_arg( 'password', 'changed' , $redirect_url);
            wp_redirect( $redirect_url );
        } else {
            echo "Invalid request.";
        }

        exit;
    }
}


// displays error messages from form submissions
function bethany_show_reset_password_error_messages($error_type) {
    $message = "";
    switch ($error_type) {
        case 'expiredkey':
        case 'invalidkey':
            $message  = 'The password reset link you used is not valid anymore.';
            break;
        case 'password_reset_mismatch':
            $message  = "The two passwords you entered don't match.";
            break;
        case 'password_reset_empty':
            $message  = "Sorry, we don't accept empty passwords.";
            break;
    }

    if ( !empty($message) ) {
        echo '<div class="bethany_errors">';
        echo '<span class="msg-error" style="display:block">'.$message.'</span>';
        echo '</div>';
    }
}

// Bethany Custom Reset Password Submit
function bethany_custom_reset_password_javascript() { ?>
    <script type="text/javascript" >
        (function($) {
            $(document).ready(function() {
                $('#bethany_reset_password_submit').click(function(e){
                    e.preventDefault();

                    $('#bethany_reset_password_form').submit();

                });
            });
        })(jQuery);
    </script>
    <?php
}
add_action( 'wp_footer', 'bethany_custom_reset_password_javascript' );
