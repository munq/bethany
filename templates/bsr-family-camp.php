<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: BSR Family Camp
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */

get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post();
?>

<!-- Main Banner -->
<?php get_template_part('content/common', 'banner'); ?>	
<!--#end of Main Banner -->
	
<!-- include breadcrumbs -->
<?php get_template_part('content/common', 'breadcrumb'); ?>	

<!-- Content -->
<section id="content" class="clearfix">
	<!-- Full-width Column -->
	<section class="col-full wrapper clearfix">		
		
		<!-- Bible Study Resources -->
		<div id="bible-study-resources">
			
			<?php include get_template_directory() . '/content/bsr/browse-bsr.php'; ?>
			
			<h1><?php echo get_the_title(); ?></h1>
			<?php echo the_content(); ?>
			
			<div class="bsr-filter box-gray">
				<form>
					<fieldset class="bsr-filter-top filter-top clearfix">
						<div class="field-wrp"></div>
						<div>
							<!-- Pagination -->
							<div class="pagination-wrp">
								<div class="pagination-legend"></div>
							</div><!--#end of Pagination -->
						</div>
					</fieldset>
				</form>
			</div>
			
			<!-- Full-width Column -->
			<section class="col-full wrapper clearfix">
				<div class="list-lesson-simple with-line" id="listing">
				<?php
				$args = array(
					'post_type' 	=> 'page',
					'post_status'	=> 'publish',
					'child_of'		=> get_the_ID(), 
					'post_parent' 	=> get_the_ID(), 
					'depth' 		=> 1, 
					'orderby' 		=> 'meta_value', 
					'meta_key' 		=> 'year',
					'posts_per_page'	=> -1
				);
				
				$query	= new WP_Query($args);
			
				if($query->have_posts()) {
				
					while($query->have_posts()) {
						$query->the_post();
						setup_postdata($post);
						$year = $post;
						
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
						
						if($query_series->have_posts()) {
				
							while($query_series->have_posts()) {
								$query_series->the_post();
								setup_postdata($post);
								$series = $post;
								$start_date		=	get_field('start_date',$series->ID);
								$end_date		=	get_field('end_date',$series->ID);
							?>
								<div class="cal-date-event">
									<div class="cal-date-event">
										<div class="date">
											<span></span>
											<span><?php echo date('M Y', strtotime($start_date)) . ' - ' . date('M Y', strtotime($end_date)) ; ?></span>
										</div>
									</div>
									<div class="event">
										<a href="<?php echo get_permalink($series->ID); ?>"><strong><?php echo $series->post_title; ?></strong></a>
									</div>
								</div>
						<?php
							} wp_reset_postdata(); 
						} wp_reset_query();
						
					} wp_reset_postdata();
				} wp_reset_query();
				?>	
				</div>				
				<!-- Pagination -->
				<div class="pagination-wrp">
					<div class="pagination"></div>
				</div><!--#end of Pagination -->
			</section><!--#end of Full-width Column -->
		</div><!--#end of Bible Study Resources -->
	</section><!--#end of Full-width Column -->
</section><!--#end of Content -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>
