<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * The default page
 *
 * Displays either the default or home page
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */

get_header(); ?>

<!-- Content -->
<?php 
if ( post_password_required() ) {
	echo get_the_password_form();
} else {
	/*
	 * Check if page slug has corresponding page-{slug name here}.php file in the content folder
	 * If true, 
	 *	it will load that content
	 * Else, 
	 *	it will load the default page content which is the same with common template
	 */
	if (file_exists(get_template_directory() . '/content/page-' . $post->post_name . '.php')) {
		get_template_part('content/page', $post->post_name);
	} 
	else 
	{
		get_template_part('content/page', 'default');
	}
}
?>
<!-- End Content -->

<?php get_footer(); ?>