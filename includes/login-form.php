<?php
// user login form
function bethany_login_form() {
 
	if(!is_user_logged_in()) {
		// set this to true so the CSS is loaded
		$bethany_load_css = true;
 
		$output = bethany_login_form_fields();
	} else {
		wp_redirect(site_url());
	}
	return $output;
}
add_shortcode('login_form', 'bethany_login_form');

// login form fields
function bethany_login_form_fields() {
	// Check if the user just requested a new password
	$attributes['lost_password_sent'] = isset( $_REQUEST['checkemail'] ) && $_REQUEST['checkemail'] == 'confirm';

	// Check if the user has invalid link
	$attributes['invalidkey'] = isset( $_REQUEST['login'] ) && $_REQUEST['login'] == 'invalidkey';

	// Check if the user has expired key
	$attributes['expiredkey'] = isset( $_REQUEST['login'] ) && $_REQUEST['login'] == 'expiredkey';

	// Check if the user has expired key
	$attributes['reset'] = isset( $_REQUEST['password'] ) && $_REQUEST['password'] == 'changed';

	ob_start(); ?>		
		
		<section class="col-main">
		<?php if ( $attributes['lost_password_sent'] ) : ?>
			<p class="login-info">
				<?php _e( 'Check your email for a link to reset your password.', 'personalize-login' ); ?>
			</p>
		<?php endif; ?>
		<?php if ( $attributes['invalidkey'] ) : ?>
			<p class="login-info">
				<?php _e( 'Your password reset link appears to be invalid. Please request a new link below.' , 'invalidkey'); ?>
			</p>
		<?php endif; ?>
		<?php if ( $attributes['expiredkey'] ) : ?>
			<p class="login-info">
				<?php _e( 'Your password reset link has expired. Please request a new link below.' , 'expiredkey'); ?>
			</p>
		<?php endif; ?>
		<?php if ( $attributes['reset'] ) : ?>
			<p class="login-info">
				<?php _e( 'Your password has been changed. You can sign in now.' , 'reset'); ?>
			</p>
		<?php endif; ?>
		<?php
		// show any error messages after form submission
		echo '<p>'.bethany_show_error_messages().'</p>'; ?>
		
		<form id="bethany_login_form"  class="bethany_form test-class" action="" method="post">
			<fieldset>
				<h3>Username</h3>
				<div class="field-wrp">					
					<input name="bethany_user_login" id="bethany_user_login" class="required" type="text"/>
				</div>
				<h3>Password</h3>
				<div class="field-wrp">					
					<input name="bethany_user_pass" id="bethany_user_pass" class="required" type="password"/>
				</div>
				<div class="field-wrp">
					<input type="hidden" name="bethany_login_nonce" value="<?php echo wp_create_nonce('bethany-login-nonce'); ?>"/>
					<a id="bethany_login_submit"class="btn-primary btn-submit" href="javascript:;">Login</a>
					<?php $forgot_password_page = get_page_by_path('bethany-forgot-password'); ?>
					<a id="bethany-forget-password" href="<?php echo $forgot_password_page ? get_permalink($forgot_password_page->ID) : wp_lostpassword_url(); ?>">Forgot Password?</a>
				</div>
			</fieldset>
		</form>
		</section>
	<?php
	return ob_get_clean();
}

// logs a member in after submitting a form
function bethany_login_member() {
 
	if ( isset( $_POST['bethany_user_login'], $_POST['bethany_login_nonce'] ) && wp_verify_nonce( $_POST['bethany_login_nonce'], 'bethany-login-nonce' ) ) {
 
		$user_login = isset( $_POST['bethany_user_login'] ) ? sanitize_user( wp_unslash( $_POST['bethany_user_login'] ) ) : '';
		$user_pass = isset( $_POST['bethany_user_pass'] ) ? (string) wp_unslash( $_POST['bethany_user_pass'] ) : '';
		$user = $user_login !== '' ? get_user_by( 'login', $user_login ) : false;

		if ( ! $user ) {
			// if the user name doesn't exist
			bethany_errors()->add('empty_username', __('Please enter valid username'));
		}
 
		if ( $user_pass === '' ) {
			// if no password was entered
			bethany_errors()->add('empty_password', __('Please enter a valid password'));
		}
 
		// check the user's login with their password
		if ( $user && $user_pass !== '' && ! wp_check_password( $user_pass, $user->user_pass, $user->ID ) ) {
			// if the password is incorrect for the specified user
			bethany_errors()->add('empty_password', __(''));
		}
 
		// retrieve all error messages
		$errors = bethany_errors()->get_error_messages();
 
		// only log the user in if there are no errors
		if(empty($errors)) {
 
			$signon = wp_signon(
				array(
					'user_login'    => $user_login,
					'user_password' => $user_pass,
					'remember'      => true,
				),
				is_ssl()
			);

			if ( is_wp_error( $signon ) ) {
				bethany_errors()->add( 'login_failed', __( 'Please enter valid username or password' ) );
				return;
			}

			$current_user = wp_set_current_user( $user->ID, $user_login );
			do_action('wp_login', $user_login, $user);
			//var_dump($current_user);exit;

			if ( in_array( 'sst', (array) $user->roles ) ) {
				wp_redirect(get_permalink(get_page_by_path('user-sst')->ID));
				exit;
			}

			if ( in_array( 'login_member', (array) $user->roles ) ) {
				wp_redirect(get_permalink(get_page_by_path('user-login_member')->ID));
				exit;
			}

			if ( in_array( 'pastor', (array) $user->roles ) ) {
				wp_redirect(get_permalink(get_page_by_path('user-pastor')->ID));
				exit;
			}

			if ( in_array( 'ymg', (array) $user->roles ) ) {
				wp_redirect(get_permalink(get_page_by_path('user-ymg')->ID));
				exit;
			}

			if ( in_array( 'ipcindia', (array) $user->roles ) ) { 
				wp_redirect(get_permalink(get_page_by_path('user-ipcindia')->ID));
				exit;
			}

 			
			if ( in_array( 'testrole', (array) $user->roles ) ) {
				wp_redirect(get_permalink(get_page_by_path('user-testrole')->ID));
				exit;
			}

		}
	}
}
add_action('init', 'bethany_login_member');

// used for tracking error messages
function bethany_errors(){
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

// displays error messages from form submissions
function bethany_show_error_messages() {

	if($codes = bethany_errors()->get_error_codes()) {
		echo '<div class="bethany_errors">';
		    // Loop error codes and display errors

		  if(!empty($codes)) {
			echo '<span class="msg-error" style="display:block">Please enter valid username or password</span>';
		  }
		  /*  foreach($codes as $code){
				echo $code;
		        $message = bethany_errors()->get_error_message($code);
		        echo '<span class="msg-error" style="display:block">' . $message . '</span><br/>';
		    } */
		echo '</div>';
	}
}

// Bethany Custom Submit
function bethany_custom_login_javascript() { ?>
<script type="text/javascript" >
	(function($) {
		$(document).ready(function() {
			$('#bethany_login_submit').click(function(e){				
				e.preventDefault();
				
				$('#bethany_login_form').submit();
				
			});
		});
	})(jQuery);	
</script>
<?php 
}
add_action( 'wp_footer', 'bethany_custom_login_javascript' );
?>

