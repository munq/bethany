<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Meditation
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */

$args = array(
		'post_type' 	=> 'page',
		'post_status'	=> 'publish',
		'child_of'		=> get_the_ID(), 
		'post_parent' 	=> get_the_ID(), 
		'depth' 		=> 1, 
		'orderby' 		=> 'year', 
		'meta_key' 		=> 'year', 
		'order' 		=> 'DESC'
	);

$query	= new WP_Query($args);

if($query->have_posts()) {
			
	while($query->have_posts()) {
	
		$query->the_post();
		setup_postdata($post);					
		$post_year = $post;
		
		wp_redirect(get_permalink($post_year->ID));
		
		exit;
	}
}	
?>
