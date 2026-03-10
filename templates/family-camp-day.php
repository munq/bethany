<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Family Camp Day
 * Redirects to Parent 
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */
		
	wp_redirect(get_permalink($post->post_parent));

?>
