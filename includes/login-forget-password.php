<?php
// user reset password form
function bethany_forgot_password_form() {

    if(!is_user_logged_in()) {

        // set this to true so the CSS is loaded
        $bethany_load_css = true;

        $output = bethany_forgot_password_form_fields();
    } else {
        wp_redirect(site_url());
    }
    return $output;
}
add_shortcode('forgot_password_form', 'bethany_forgot_password_form');

// forgot password form fields
function bethany_forgot_password_form_fields() {
    // Handle Error
    $error_code_forget = ( isset( $_REQUEST['error'] ) ) ? htmlspecialchars($_REQUEST['error']) : "";

    ob_start(); ?>
    <section class="col-main">

        <?php if ( isset($error_code_forget) && !empty($error_code_forget) ): ?>
            <p><?php bethany_show_forgot_password_error_messages($error_code_forget); ?></p>
        <?php endif; ?>

        <p>Please enter your username or email address. You will receive a link to create a new password via email.</p>

        <form id="bethany_forgot_password_form"  class="bethany_form" action="" method="post">
            <fieldset>
                <h3>Username or E-mail</h3>
                <div class="field-wrp">
                    <input name="user_login" id="user_login" class="required" type="text"/>
                </div>
                <div class="field-wrp">
                    <input type="hidden" name="bethany_forgot_nonce" value="<?php echo wp_create_nonce('bethany-forgot-nonce'); ?>"/>
                    <a id="bethany_forget_password_submit" class="btn-primary btn-submit" href="javascript:;">Get New Password</a>
                </div>
            </fieldset>
        </form>

    </section>
    <?php
    return ob_get_clean();
}

// retrieve password after submitting a form
function bethany_forgot_password_submit() {

    if ( ( 'POST' == $_SERVER['REQUEST_METHOD'] ) &&  wp_verify_nonce($_POST['bethany_forgot_nonce'], 'bethany-forgot-nonce') ) {
        $errors = custom_retrieve_password();
        //$errors = retrieve_password();
        if ( is_wp_error( $errors ) ) {
            // Errors found
            $redirect_url = get_permalink(get_page_by_path('bethany-forgot-password')->ID);
            $redirect_url = add_query_arg( 'error', join( ',', $errors->get_error_codes() ), $redirect_url );
        } else {
            // Email sent
            $redirect_url = get_permalink(get_page_by_path('bethany-login')->ID);
            $redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
        }
        wp_redirect( $redirect_url );
        exit;
    }
}
add_action('init', 'bethany_forgot_password_submit');

function custom_retrieve_password() {
    global $wpdb, $wp_hasher;
    $errors = new WP_Error();

    if ( empty( $_POST['user_login'] ) ) {
        $errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or email address.'));
    } elseif ( strpos( $_POST['user_login'], '@' ) ) {
        $user_data = get_user_by( 'email', trim( wp_unslash( $_POST['user_login'] ) ) );
        if ( empty( $user_data ) )
            $errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.'));
    } else {
        $login = trim($_POST['user_login']);
        $user_data = get_user_by('login', $login);
    }

    /**
     * Fires before errors are returned from a password reset request.
     *
     * @since 2.1.0
     * @since 4.4.0 Added the `$errors` parameter.
     *
     * @param WP_Error $errors A WP_Error object containing any errors generated
     *                         by using invalid credentials.
     */
    do_action( 'lostpassword_post', $errors );

    if ( $errors->get_error_code() )
        return $errors;

    if ( !$user_data ) {
        $errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or email.'));
        return $errors;
    }

    // Redefining user_login ensures we return the right case in the email.
    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;
    $key = get_password_reset_key( $user_data );

    if ( is_wp_error( $key ) ) {
        return $key;
    }

    $message = sprintf(__('You have requested for a password reset for the account "%s"'), $user_login) . "<br /><br />";
    $message .= __('You can reset your password by visiting the following link:') . "<br />";
    $message .= network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . "<br /><br />";
    $message .= __('If you did not request for a password reset, you may ignore this email.') . "<br /><br />";
    $message .= __('Thank you.') . "<br /><br />";
    $message .= "<i>".__('This is a system-generated email. Please do not reply.') . "</i>";

    if ( is_multisite() )
        $blogname = $GLOBALS['current_site']->site_name;
    else
        /*
         * The blogname option is escaped with esc_html on the way into the database
         * in sanitize_option we want to reverse this for the plain text arena of emails.
         */
        $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

    //$title = sprintf( __('[%s] Password Reset'), $blogname );

    /**
     * Filters the subject of the password reset email.
     *
     * @since 2.8.0
     * @since 4.4.0 Added the `$user_login` and `$user_data` parameters.
     *
     * @param string  $title      Default email title.
     * @param string  $user_login The username for the user.
     * @param WP_User $user_data  WP_User object.
     */
    //$title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );

    //override $title
    $title = "Reset password for Bethany Website";

    /**
     * Filters the message body of the password reset mail.
     *
     * @since 2.8.0
     * @since 4.1.0 Added `$user_login` and `$user_data` parameters.
     *
     * @param string  $message    Default mail message.
     * @param string  $key        The activation key.
     * @param string  $user_login The username for the user.
     * @param WP_User $user_data  WP_User object.
     */
    $message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );
    $headers = array('Content-Type: text/html; charset=UTF-8');

    if ( $message && !wp_mail( $user_email, wp_specialchars_decode( $title ), $message,$headers ) )
        wp_die( __('The email could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function.') );

    return true;
}

// displays error messages from form submissions
function bethany_show_forgot_password_error_messages($error_type) {
    $message = "";

    switch ($error_type) {
        case "empty_username":
            $message = "Enter a username or email address.";
        case "invalidcombo":
            $message = "Invalid username or email.";
    }

    if(!empty($message)) {
        echo '<div class="bethany_errors">';
        echo '<span class="msg-error" style="display:block">'.$message.'</span>';
        echo '</div>';
    }
}


// Bethany Custom Forgot Password Submit
function bethany_custom_forgot_password_javascript() { ?>
    <script type="text/javascript" >
        (function($) {
            $(document).ready(function() {
                $('#bethany_forget_password_submit').click(function(e){
                    e.preventDefault();

                    $('#bethany_forgot_password_form').submit();

                });
            });
        })(jQuery);
    </script>
    <?php
}
add_action( 'wp_footer', 'bethany_custom_forgot_password_javascript' );
