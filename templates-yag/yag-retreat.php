<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: YAG Retreat
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */
?>
<?php get_header('yag'); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<!-- Main Banner -->
<?php get_template_part('content/ypg-yag/subpage', 'banner'); ?>
<!--#end of Main Banner -->

<!-- include breadcrumbs -->
<?php get_template_part('content/ypg-yag/fellowship', 'breadcrumb'); ?>

<!-- Content -->
<section id="content" class="clearfix">
	<div class="wrapper">
	<!-- Inside Page Tabs -->
		<div class="tabs inside-nav clearfix">
			<?php get_template_part('content/ypg-yag/sub', 'page-tabs'); ?>
		</div><!--#end of Inside Page Tabs -->
		
		<section class="col-full wrapper clearfix">			
			<?php echo the_content(); ?>
			<!-- Accordion -->
			<div class="accordion">
			<?php				
				
				$messages = get_field('messages');
				
				if(!empty($messages)) {
					$messages_id = $messages[0];
					$args_series_pages = array(
						'post_type' 	=> 'page',
						'post_status'	=> 'publish',
						'paged'			=> $paged,
						'child_of'		=> $messages_id,
						'post_parent' 	=> $messages_id,
						'depth' 		=> 1, 
						'orderby' 		=> 'meta_value', 
						'meta_key' 		=> 'start_date', 
						'order' 		=> 'DESC',
						'posts_per_page'	=> 10
					);						
										
					$series_pages	= new WP_Query($args_series_pages);	

					if($series_pages->have_posts()) {
						while($series_pages->have_posts()) {
							
							$series_pages->the_post();
							setup_postdata($post);					
							$series_page = $post;	
							
							$series_page_title	= $series_page->post_title;
							$start_date 		= get_field('start_date', $series_page->ID);
							$end_date 			= get_field('end_date', $series_page->ID);
							$summary_content 	= get_field('summary_content', $series_page->ID);
							?>						
							<h3 class="has-right-header">
							<?php echo $series_page_title ?>							
							<span class="right-header"><span class="numeric"><?php echo date('d', strtotime($start_date)); ?></span> 
								<?php echo ' - ' . '<span class="numeric">' . date('d', strtotime($end_date)) . ' ' . date('M', strtotime($end_date)) . ' ' . date('Y', strtotime($end_date)) . '</span>' ; ?>								
							</span>
							</h3>					
						<div>
							<p><?php echo $summary_content; ?></p>
							<p><a href="<?php echo get_permalink($series_page->ID); ?>" class="link-txt"><i class="ion-navicon-arrow"></i> Listen to messages</a></p>
						</div>					
						<?php
						} wp_reset_postdata();
					}
				}
				?>
			</div><!--#end of Accordion -->
			
		</section>		
	</div>
</section><!--#end of Content -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>