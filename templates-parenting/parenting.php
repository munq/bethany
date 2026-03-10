<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Parenting
 * This Template is used For Categorizing the Daily Devotion Eg: Morning Worship is a Category and will redirect to the Latest Series of the Category
 
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */
 
 
// Redirect to current series page 

$args_chap = array(
	'post_type' 	=> 'page',
	'post_status'	=> 'publish',
	'child_of'		=> get_the_ID(), 
	'post_parent' 	=> get_the_ID(),
	'depth' 		=> 1,
	'orderby' 		=> 'meta_value', 
	'meta_key' 		=> 'start_date', 
	'order' 		=> 'ASC',				
	'posts_per_page'	=> -1
);

$query_chap	= new WP_Query($args_chap);

if($query_chap->have_posts()) {

	while($query_chap->have_posts()) {			
		$query_chap->the_post();
		setup_postdata($post);					
		$post_chap = $post;
			
		wp_redirect(get_permalink($post_chap->ID));
		exit;

	} wp_reset_postdata();

} wp_reset_query();	
?>