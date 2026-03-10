<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Login Member
 
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */
global $post;
$post_slug = $post->post_name;
$login_success_page = str_replace("user-","",$post_slug);
$user = wp_get_current_user();
$user_role = $user->roles;
$roles = array('administrator','login_member','sst','pastor','ipcindia','ymg');
$allowed_roles = array_intersect($roles, $user_role);
$check_admin = array_intersect(array("administrator"),$user_role);
$allowed_access = array_intersect($allowed_roles, array($login_success_page));

//redirect to login page if they dont match or user is not admin
if( empty($allowed_access) && empty($check_admin)) {
	//var_dump(empty($allowed_access));
	//var_dump(empty($check_admin));
	wp_redirect(get_permalink(get_page_by_path('bethany-login')->ID));
	exit;
}

get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
	<!-- Main Banner -->
	<?php get_template_part('content/common', 'banner'); ?>
	<!--#end of Main Banner -->

	<!-- include breadcrumbs -->
	<?php get_template_part('content/common', 'breadcrumb'); ?>

	<!-- Content -->
	<section id="content" class="clearfix">
		<!-- Full-width Column -->
		<section class="col-full wrapper clearfix">
			<?php /** <a href="<?php echo wp_logout_url(); ?>">Logout</a> */ ?>

			<?php
				echo '<h3>Hi, ' . $user->user_firstname  . ' ' . $user->user_lastname  . '</h3>';
				echo the_content();
			?>
		</section> <!--#end of Full-width Column -->
	</section><!--#end of Content -->
	
<?php endwhile; endif; ?>

<?php get_footer(); ?>
