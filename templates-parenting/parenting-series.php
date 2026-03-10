<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Parenting Series
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */
 
// Get Message post type tied to the page and display its fields
 
$args = array(
		'post_type' 	=> 'page', 
		'post_status'	=> 'publish',
		'child_of'		=> get_the_ID(), 
		'post_parent' 	=> get_the_ID(), 
		'depth' 		=> 1, 
		'orderby' 		=> 'meta_value', 
		'meta_key' 		=> 'article_date', 
		'order' 		=> 'ASC'
	);
	
$query	= new WP_Query($args);

if($query->have_posts()) {
			
	while($query->have_posts()) {
	
		$query->the_post();
		setup_postdata($post);					
		$post_series = $post;
		
		wp_redirect(get_permalink($post_series->ID));	
		exit;
	}
}	
