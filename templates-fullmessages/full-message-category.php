<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Full Message
 * This Template is used For Categorizing the Messages Eg: Morning Worship is a Category and will redirect to the Latest Series of the Category
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */

if ( current_user_can('activate_plugins') ||  current_user_can('bethany_access_full_message') ) {
	
	$show = $_GET['show'];

	if ('latest' === $show) {
		// Redirect to current series page
		$args = array(
			'post_type' 	=> 'page',
			'post_status'	=> 'publish',
			'child_of'		=> get_the_ID(), 
			'post_parent' 	=> get_the_ID(), 
			'depth' 		=> 1, 
			'orderby' 		=> 'meta_value',
			'meta_key' 		=> 'start_date', 
			'order' 		=> 'DESC'
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
	}

	?>
	 
	<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<!-- Main Banner -->
	<?php get_template_part('content/common', 'banner'); ?>	
	<!--#end of Main Banner -->

	<!-- include breadcrumbs -->
	<?php get_template_part('content/common', 'breadcrumb'); ?>	

	<?php

	// Get the Series subpages of the category
	// Get the Messages subpages of a Series
	// Display the message subpage

	$args = array(
		'post_type' 	=> 'page',
		'post_status'	=> 'publish',
		'child_of'		=> get_the_ID(), 
		'post_parent' 	=> get_the_ID(), 
		'depth' 		=> 1, 
		'orderby' 		=> 'meta_value', 
		'meta_key' 		=> 'start_date', 
		'order' 		=> 'DESC',
		'posts_per_page'	=> -1
	);

	// end of search query
	$subpages = new WP_Query($args);

	?>	
	<!-- Content -->
	<section id="content" class="clearfix">
		<!-- Full-width Column -->
		<section class="col-full wrapper clearfix">
			<?php the_content(); ?>
			
			<!-- Side Column -->
			<section class="col-side">
				<?php get_template_part('content/sidebar/right', 'find-a-message'); ?>
			</section><!--#end of Side Column -->
			
			<!-- Main Column -->
			<section class="col-main">
				<ul class="list-messages" id="listing">
				<?php if ($subpages->have_posts()) : ?>
					
					<?php while ($subpages->have_posts()) : $subpages->the_post(); ?>
					
						<?php
						
						$subpage = $subpages->post;				
						$series_title	=	get_the_title($subpage->ID);
						$start_date		=	get_field('start_date',$subpage->ID);
						$end_date		=	get_field('end_date',$subpage->ID);
						?>				
						<li class="clearfix">
							<p><strong><a href="<?php echo get_permalink($subpage->ID);?>"><?php echo $series_title; ?></a></strong><br/>
							<?php echo date('M Y', strtotime($start_date)) . ' - ' . date('M Y', strtotime($end_date)) ; ?></p>
						</li>					
					<?php endwhile; ?>
					
				<?php endif; ?>
				</ul>
				<!-- Pagination -->
				<div class="pagination-wrp">
					<div class="pagination"></div>
				</div><!--#end of Pagination -->
				
			</section><!--#end of Main Column -->
		</section> <!--#end of Full-width Column -->
	</section><!--#end of Content -->

	<?php endwhile; endif;

	get_footer();
		
} else {
	
	wp_redirect(get_permalink(get_page_by_path('login')->ID)); exit;
	echo '<h2>You do not have enough permission to view this page</h2>';
}

