<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Poems
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
	
	<?php include get_template_directory() . '/content/poems/browse-poems.php'; ?>
	
	<h1><?php echo the_title(); ?></h1>
	<?php echo the_content(); ?>
		
		
			<!-- Full-width Column -->
		<section class="col-full clearfix">
			<div class="bsr-filter-top box-gray clearfix">
				<div class="field-wrp"></div>
				<div>
					<!-- Pagination -->
					<div class="pagination-wrp">
						<div class="pagination-legend"></div>
					</div><!--#end of Pagination -->
				</div>
			</div>
			<div class="list-articles with-padding" id="listing">					
				
				<?php
				$args_series = array(
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
					
				$query_series	= new WP_Query($args_series);
					
				if($query_series->have_posts()) {
		
					while($query_series->have_posts()) {	
						$query_series->the_post();
						setup_postdata($post);					
						$series = $post;								
						$start_date		=	get_field('start_date',$series->ID);
						if ( '19700101' == $start_date )
							$start_date = '';
						
						$end_date		=	get_field('end_date',$series->ID);
						if ( '19700101' == $end_date )
								$end_date = '';
					?>
					
					<div>
						<p>
						<?php 
						if(!empty($start_date)) {
							echo date('M Y', strtotime($start_date));
						}
						
						if(!empty($end_date)) {
							echo ' - ' . date('M Y', strtotime($end_date)) ;
						}?><br/>
							<strong><a href="<?php echo get_permalink($series->ID); ?>"><?php echo $series->post_title; ?></a></strong>
						</p>
					</div>							
				<?php
					} wp_reset_postdata();
				} wp_reset_query();
				?>					
			</div>				
			<!-- Pagination -->
			<div class="pagination-wrp">
				<div class="pagination"></div>
			</div><!--#end of Pagination -->
		</section><!--#end of Full-width Column -->	

		</div>
	</section><!--#end of Full-width Column -->
</section><!--#end of Content -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>
