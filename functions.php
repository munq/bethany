<?php if (!defined('ABSPATH')) die('No direct script access allowed!');

//require_once ABSPATH . 'wp-login.php';
require_once ABSPATH . 'wp-includes/user.php';
require_once ABSPATH . 'wp-includes/class-wp-error.php';
require_once ABSPATH . 'wp-includes/pluggable.php';

include 'includes/post-types.php';
include 'includes/custom-widgets.php';
include 'includes/custom-taxonomy.php';
include 'includes/custom-query.php';
include 'includes/login-form.php';
include 'includes/login-forget-password.php';
include 'includes/login-reset-password.php';
include 'includes/login-member.php';
include 'includes/shortcodes/print-email-save.php';
include 'includes/shortcodes/media-resources.php';
include 'includes/shortcodes/bsr.php';

// Function to stop HTML removal from menu descriptions
remove_filter('nav_menu_description', 'strip_tags');

function my_plugin_wp_setup_nav_menu_item( $menu_item ) {
    if ( isset( $menu_item->post_type ) ) {
        if ( 'nav_menu_item' == $menu_item->post_type ) {
            $menu_item->description = apply_filters( 'nav_menu_description', $menu_item->post_content );
        }
    }

    return $menu_item;
}

add_filter( 'wp_setup_nav_menu_item', 'my_plugin_wp_setup_nav_menu_item' );


/* Enable Featured Image */
add_theme_support( 'post-thumbnails' ); 

/* Function to get Template slug */
function _get_template_slug_name( $post_id = false ) {
	if ( ! $post_id )
		return '';

	$templates = wp_get_theme()->get_page_templates();
	
	$template_file = get_page_template_slug( $post_id );
	
	if ( ! empty($templates[$template_file]) )
		return sanitize_title($templates[$template_file]);
		
	return '';
}

if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> 'Pulpit Theme',
		'menu_slug' 	=> 'acf-options-options',
		'redirect'		=> false
	));
}

/* if( function_exists('acf_add_options_sub_page') ) {
    acf_add_options_sub_page(array(
        'title' => 'Menu',
        'parent_slug' => 'acf-options-options'
    ));
} */



/* PAGE COLUMNS */
add_filter( 'manage_pages_columns', 'my_custom_pages_columns' ) ;

function my_custom_pages_columns( $columns ) {

	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'content_type' => __( 'Content Type' ),
		'author' => __('Author'),
		'date' => __( 'Date' ),
	);

	return $columns;
}

// Add to admin_init function
add_action('manage_pages_custom_column', 'manage_custom_pages_columns', 10, 2);
 
function manage_custom_pages_columns($column_name, $id) {
    global $wpdb;
    switch ($column_name) {
		case 'content_type':
			$templates = wp_get_theme()->get_page_templates();
			$template_file = get_page_template_slug( $id );
			if ( strpos($templates[$template_file], 'Sub Page') !== false )
				echo 'Tab';
			else if ( strpos($templates[$template_file], 'School') !== false )
				echo 'School';
		break;
    } // end switch
} 

/* END OF MASSES COLUMNS */

// Menus
register_nav_menus( array(
	'primary'   => __( 'Main menu', 'bethany' ),
	'footer' => __( 'Footer menu', 'bethany' ),
) );



// add support category for pages
add_action('init', 'reg_tax_category');
function reg_tax_category() {
      register_taxonomy_for_object_type('category', 'page');
      add_post_type_support('page', 'category');
}

add_filter( 'page_attributes_dropdown_pages_args', 'remove_pages_in_dropdown', 10, 2 );

//  Filter to remove "Page Attributes" Metabox page dropdown in admin
function remove_pages_in_dropdown( $args, $post ) {
    $args['post_status'] = array('undefined'); // force to get undefined post status
    return $args;
}


/* function to returns a quote of specified length */

function get_quote_charlength($charlength, $quote) {
	
	/* Collect all words
	each word from start sum the lenght until reaches limit
	Sum all the words from start to limit and display string */
	
	$collect_words = explode(' ', $quote);
	$final_string = array();
	
	if(strlen($quote) <= $charlength ) { 
		return $quote;
	} else {
		foreach($collect_words as $collect_word) {
			$final_string[] = $collect_word;
			
			$stitch_string = implode(' ', $final_string);
			
			if(strlen($stitch_string) > $charlength) 
				return $stitch_string . ' &hellip;';
		}
	}
}

// add_action('shutdown', 'sql_logger');
// function sql_logger() {
    // global $wpdb;
    // $log_file = fopen(ABSPATH.'/sql_log.txt', 'a');
    // fwrite($log_file, "//////////////////////////////////////////\n\n" . date("F j, Y, g:i:s a")."\n");
    // foreach($wpdb->queries as $q) {
        // fwrite($log_file, $q[0] . " - ($q[1] s)" . "\n\n");
    // }
    // fclose($log_file);
// }


// "All Pages" have issue because of so many pages
// We are going to forced to redirect it to "Tree View"
// Then add "Trash" sub menu in "Pages"
function bethany_override_all_pages() {
	$script_name = $_SERVER['SCRIPT_NAME'];
	$post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '';
	$page = isset($_GET['page']) ? $_GET['page'] : '';
	$post_status = isset($_GET['post_status']) ? $_GET['post_status'] : '';
	
	if (
		strpos($script_name, 'edit.php') !== FALSE 
		&& 'page' == $post_type 
		&& ('publish' == $post_status || empty($post_status))
		&& empty($page)
	) {
		wp_redirect( admin_url('edit.php?post_type=page&page=cms-tpv-page-page') );
		exit();
	}
}

function bethany_add_trash_menu() {
	add_submenu_page( 'edit.php?post_type=page', 'Trash', 'Trash', 'delete_pages', 'edit.php?post_status=trash&post_type=page', '' );
}
/** BSR Edit Pages Task
function bsr_can_user_edit($post_id, $can_edit = false) {
    $user = wp_get_current_user();
    $allowed_roles = array('yag','ypg');
    $check_role = array_intersect($user->roles,$allowed_roles);
    if (!empty($check_role)) {
        // Get the page as an Object
        $bsr_page = get_page_by_title('Bible Study Resources');
        $tmp_page = get_post($post_id);
        if ($tmp_page->ID == $bsr_page->ID) {
            return TRUE;
        }
        if (is_child($bsr_page->ID,$tmp_page)) {
            return TRUE;
        }
        return FALSE;
    }
    else{
        return $can_edit;
    }
}

function is_child($pageID,$tmp) {
    $ancestors = get_post_ancestors($tmp);
    if(in_array($pageID,$ancestors)){
        return true;
    }
    else {
        global $wp_admin_bar;
        if (isset($wp_admin_bar)) {
            $wp_admin_bar->remove_menu('wp_admin_bar_edit_menu');
        }
        return false;
    }
}

add_action("cms_tree_page_view_post_can_edit", function($can_edit, $post_id) {
    return bsr_can_user_edit($post_id, $can_edit);
}, 10, 2);

function hide_edit_btn_admin_bar() {
    global $wp_admin_bar;
    global $post;
    $post_id = $post->ID;
    $post_type = "page";
    $post_type_object = get_post_type_object($post_type);
    $can_edit = current_user_can( $post_type_object->cap->edit_post, $post_id);

    if (!bsr_can_user_edit($post_id, $can_edit)) {
        $wp_admin_bar->remove_menu('edit');
    }
}
add_action( 'wp_before_admin_bar_render', 'hide_edit_btn_admin_bar' );

function process_edit_post(){
    $is_edit = FALSE;
    if( strstr($_SERVER['REQUEST_URI'], 'wp-admin/post.php') && isset($_GET['post']) && ($_GET['post'] > 1)) {
        $is_edit = TRUE;
    }
    else{
        return;
    }
    $post_id = (int) preg_replace('/[^0-9]/', '', $_GET['post']);
    $post_type = "page";
    $post_type_object = get_post_type_object($post_type);
    $can_edit = current_user_can($post_type_object->cap->edit_post, $post_id);
    $bsr = bsr_can_user_edit($post_id, $can_edit);
    if (!($bsr) && ($is_edit)) {
        wp_die("You are not allowed to edit this post","Invalid",array("back_link" => true));
    }
}
//add_action('wp_loaded', 'process_edit_post',1);
*/
add_action( 'init', 'bethany_override_all_pages' );
add_action( 'admin_menu', 'bethany_add_trash_menu' );


//@return string $year_format
function get_format_date ( $start, $end, $day_format, $month_format, $year_format ) {
    $start_date = "";
    $end_date = "";
    $start_d = "";
    $end_d = "";
    $start_my = "";
    $end_my = "";
    $info_date = "";

    // if no date then return NULL
    if ( $start === "" && $end === "" ) {
        return NULL;
    }

    if ( $start !== "" ) {
        $start = new DateTime($start);
        $start_d = $start->format($day_format);
        $start_my = $start->format($month_format);
        $start_date = $start->format($year_format);
    }
    if ( $end !== "" ) {
        $end = new DateTime($end);
        $end_d = $end->format($day_format);
        $end_my = $end->format($month_format);
        $end_date = $end->format($year_format);
    }

    // if  same start_date and end_date
    if ( $start_date == $end_date ) {
        return $start_date;
    }
    // if  end_date is empty
    if ( empty($start_date) && !empty($end_date) ) {
        return $end_date;
    }
    // if  start_date is empty
    if ( empty($end_date) && !empty($start_date) ) {
        return $start_date;
    }

    // if  same month and year
    if ( $start_my == $end_my && ( !empty($start_date) && !empty($end_date) )) {
        $info_date = $start_d . '-' .$end_d . ' ' . $start_my;
    }
    else {
        $info_date = $start_date . ' - '. $end_date;
    }

    return $info_date;
}
function get_the_user_ip() {
    if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
//check ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
//to check ip is pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return apply_filters( 'wpb_get_ip', $ip );
}


//$content = '';
//$editor_id = 'edit-menu-item-description-1227';
//
//wp_editor( $content, $editor_id, array(
//    'tinymce' => array(
//        'theme_advanced_buttons1' => 'bold,italic,underline',
//        'theme_advanced_buttons2' => '',
//        'theme_advanced_buttons3' => ''
//    )
//) );
//


function bethany_password_change_email( $pass_change_email, $user ) {
    //$blog_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
    $pass_change_email['message'] = __( 'Hi ###USERNAME###,

This notice confirms that your password was changed on ###SITENAME###.

If you did not change your password, please contact the Site Administrator at
webmaster@bethanyipc.sg

Regards,
Bethany Webteam

###SITEURL###' );

    $pass_change_email['message'] = str_replace( '###USERNAME###', $user['user_login'], $pass_change_email['message'] );
    $pass_change_email['message'] = str_replace( '###ADMIN_EMAIL###', get_option( 'admin_email' ), $pass_change_email['message'] );
    $pass_change_email['message'] = str_replace( '###EMAIL###', $user['user_email'], $pass_change_email['message'] );
    $pass_change_email['message'] = str_replace( '###SITENAME###', get_option( 'blogname' ), $pass_change_email['message'] );
    $pass_change_email['message'] = str_replace( '###SITEURL###', home_url(), $pass_change_email['message'] );
    return $pass_change_email;
}
add_filter( 'password_change_email', 'bethany_password_change_email', 10, 2 );
