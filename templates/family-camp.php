<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Family Camp
 * This Template is used For Categorizing the Messages Eg: Morning Worship is a Category and will redirect to the Latest Series of the Category
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */
 
$camp_sessions = array(
		'morning' => 'Morning',
		'afternoon' => 'Afternoon',
		'evening' => 'Evening',
	);

get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<!-- Main Banner -->
<?php get_template_part('content/common', 'banner'); ?>	<!--#end of Main Banner -->
	
<!-- include breadcrumbs -->
<?php get_template_part('content/common', 'breadcrumb'); ?>	

<!-- Content -->
<section id="content" class="clearfix">
	<!-- Full-width Column -->
	<section class="col-full wrapper clearfix">
		<?php the_content(); ?>		
		
		<!-- Main Column -->
		<section class="col-main">
			
			<ul class="list-messages" id="listing">
				<?php
				$args = array(
					'post_type' 	=> 'page',
					'post_status'	=> 'publish',
					'child_of'		=> get_the_ID(), 
					'post_parent' 	=> get_the_ID(), 
					'depth' 		=> 1, 
					'orderby' 		=> 'meta_value', 
					'meta_key' 		=> 'year', 
					'order' 		=> 'DESC',
					'post_perpage'	=> -1
				);
				
				$query	= new WP_Query($args);
				
				if($query->have_posts()) :
				
					while($query->have_posts()) :
						$query->the_post();
						$year = $query->post;
						
						$args_series = array(
							'post_type' 	=> 'page',
							'post_status'	=> 'publish',
							'child_of'		=> $year->ID, 
							'post_parent' 	=> $year->ID,
							'depth' 		=> 1, 
							'orderby' 		=> 'meta_value', 
							'meta_key' 		=> 'start_date', 
							'order' 		=> 'DESC',
							'posts_per_page'	=> -1
						);						
						
						$query_series	= new WP_Query($args_series);						
						
						if($query_series->have_posts()) :
				
							while($query_series->have_posts()) :			
								$query_series->the_post();
								setup_postdata($post);					
								$series = $post;								
								$start_date		=	get_field('start_date',$series->ID);
								$end_date		=	get_field('end_date',$series->ID);
							?>
								<li class="clearfix">
									<p><strong><a href="<?php echo get_permalink($series->ID); ?>"><?php echo $series->post_title; ?></a></strong><br/>
										<?php echo date('M Y', strtotime($start_date)) . ' - ' . date('M Y', strtotime($end_date)) ; ?></p>
								</li>
							<?php endwhile; // end of while($query_series->have_posts()) ?>
						<?php endif; // end of if($query_series->have_posts()) ?>
					<?php endwhile; // end of while($query->have_posts()) ?>
				<?php endif; // end of if($query->have_posts()) ?>								
			</ul>
			
			<!-- Pagination -->
			<div class="pagination-wrp">
				<div class="pagination"></div>
			</div><!--#end of Pagination -->			
		</section><!--#end of Main Column -->
		<!-- Side Column -->
		<section class="col-side">
			<?php get_template_part('content/sidebar/right', 'find-a-message'); ?>
		</section><!--#end of Side Column -->
	</section> <!--#end of Full-width Column -->
</section><!--#end of Content -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>