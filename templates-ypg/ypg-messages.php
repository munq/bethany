<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: YPG Messages
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */
?>
<?php get_header('ypg'); ?>

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
			<?php the_content(); ?>
			<!-- Accordion -->
			<div class="accordion">
			<?php
				
				$messages = get_field('messages');
				
				if(!empty($messages)) {					
					$messages_id = $messages[0];
					$args_series_pages = array(
					'post_type' 	=> 'page',
					'post_status'	=> 'publish',
					'child_of'		=> $messages_id,
					'post_parent' 	=> $messages_id,
					'depth' 		=> 1, 
					'orderby' 		=> 'meta_value', 
					'meta_key' 		=> 'start_date', 
					'order' 		=> 'DESC',
					'posts_per_page' => 10
				);					
									
				$series_pages	= new WP_Query($args_series_pages);	

				if($series_pages->have_posts()) {
					while($series_pages->have_posts()) {
						
						$series_pages->the_post();
						setup_postdata($post);					
						$series_page = $post;	
						
						$series_page_title	= $series_page->post_title;
						$series_duration 	= get_field('series_duration', $series_page->ID);
						$start_date 		= get_field('start_date', $series_page->ID);
						$end_date 			= get_field('end_date', $series_page->ID);						
						?>						
						<h3 class="has-right-header">
						<?php echo $series_page_title ?>							
						<span class="right-header"><?php echo date('M', strtotime($start_date)) . ' - ' . date('M', strtotime($end_date)) ;?></span>
						</h3>					
					<div>
						<div class="list-articles with-line" id="listing">
						<?php
						$args_article_pages = array(
								'post_type' 	=> 'page',
								'post_status'	=> 'publish',
								'child_of'		=> $series_page->ID,
								'post_parent'  	=> $series_page->ID,
								'depth' 		=> 1, 
								'orderby' 		=> 'meta_value', 
								'meta_key' 		=> 'article_date', 
								'order' 		=> 'DESC',
								'posts_per_page' => -1
							);
							
							$article_pages	= new WP_Query($args_article_pages);

							if($article_pages->have_posts()) {
								while($article_pages->have_posts()) {
									
									$article_pages->the_post();
									setup_postdata($post);					
									$article_page = $post;	
									
									$article_title 	= (get_field('article_title',$article_page->ID)) ? get_field('article_title', $article_page->ID) : $article_page->post_title ;
									
									$article_date 	= get_field('article_date', $article_page->ID);									
									
									$messages		= get_field('message', $article_page->ID);									
									
									$audio_url		= get_field('audio_url', $messages->ID);
									$video_url			= get_field('video_url', $messages->ID);
									
									if(!get_field('notes_url', $messages->ID)) {
										$notes				= get_field('notes', $messages->ID);
										$notes_url			= ($notes) ? wp_get_attachment_url( $notes['id'] ) : '';
									} else {
										$notes_url			= get_field('notes_url', $messages->ID);
									}
									
									?>
									
									<div class="clearfix">												
										<p><?php echo date('d M Y', strtotime($article_date)); ?><br/>
										<strong><a href="<?php echo get_permalink($article_page->ID); ?>"><?php echo $article_title; ?></a></strong></p>
										
										<?php 
										generate_print_email_save( 
												array(
													'url'		=> get_permalink($post_others->ID),
													'notes_url'	=> $notes_url,
													'video_url' 	=> $video_url,
													'audio_url' 	=> $audio_url,
												)
											);
										?>
										
									</div>								
									
								<?php
								} wp_reset_postdata();
							
							} wp_reset_query();
						?>
							</div>
						</div>					
						<?php
						} wp_reset_postdata();
					} wp_reset_query();
				
				}
				?>
			</div><!--#end of Accordion -->
		</section>
		
		<!-- Full-width Column -->
		<?php 
		if(get_field('page_overview')) {
		?>
			<section class="col-full clearfix">
			<?php 
			$overview_content = get_field('page_overview');
			$overview_image		= 	get_field('overview_image', $overview_content->ID);
			$summary_content	= get_field('summary_content', $overview_content->ID);
			$overview_image_url	=	($overview_image) ? $overview_image['url'] : 'http://placehold.co/800x414/bbb/fff&text=image' ;	
			?>
				<!-- Overlapping Banner Column -->
				<section class="col-overlap-banner float-right">
					<div class="box-banner">
						<div class="box-banner-top clearfix">
							<div class="box-banner-header"><?php echo get_the_title($overview_content->ID); ?></div>
						</div>
						<div class="box-banner-content">
							<p><?php echo $summary_content; ?></p>
							<p class="txt-right"><a href="<?php echo get_permalink($overview_content->ID); ?>">Read more</a></p>
						</div>
					</div>
				</section><!--#end of Overlapping Banner Column -->
			
			<!-- Overlapping Image Column -->
			<section class="col-overlap-img float-left">
				<div class="box-img">
					<img src="<?php echo $overview_image_url; ?>" />
				</div>
			</section><!--#end of Overlapping Image Column -->
		</section><!--#end of Full-width Column -->
		<?php
		}
		?>
		
	</div>
</section><!--#end of Content -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>