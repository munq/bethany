<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: SS Teachers Series
 * This Template is used For Series Eg: Morning Worship -> Series -> Articles for that Series
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */
 
 // If the 'view' parameter is SET, display all messages of the category
 // Else, Display 'Messages' subpages for the series 
 

if ( current_user_can('activate_plugins') ||  current_user_can('bethany_access_sstt') ) {
	 
	get_header();

	if (have_posts()) : while (have_posts()) : the_post();
		
	// Main Banner
	get_template_part('content/common', 'banner'); 
	//#end of Main Banner
		
	//include breadcrumbs
	get_template_part('content/common', 'breadcrumb');
	?>

	<!-- Content -->
	<section id="content" class="clearfix">
		<?php
		
		$series = get_pages(
			array(
				'child_of'	=> get_the_ID(),
				'meta_key' 		=> 'article_date',
				'sort_column' 	=> 'article_date',
				'sort_order'   	=> 'ASC'
			)
		); 

		$first_subpage = $series[0];
		?>
		
		<!-- Full-width Column -->
		<section class="col-full wrapper clearfix">
		<?php
		if($first_subpage->ID == get_the_ID()) {
		?>
			<h2 class="h2-lead">Current Theme</h2>
		<?php
		}
		?>
		<h2><?php echo get_the_title();?></h2>
		<?php 
		$start_date = get_field('start_date', get_the_ID());
		$end_date 	= get_field('end_date', get_the_ID());		
		?>
			<p class="p-head"><?php echo date('M Y', strtotime($start_date)) . ' - ' . date('M Y', strtotime($end_date)) ; ?></p>
			
			<!-- Side Column -->
			<section class="col-side">
				<?php get_template_part('content/sidebar/right', 'find-a-message'); ?>
			</section><!--#end of Side Column -->
					
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
					'meta_key' 		=> 'article_date', 
					'order' 		=> 'DESC',
					'posts_per_page'	=> -1
				);
				
				$query	= new WP_Query($args);
				
				if($query->have_posts()) {
				
					while($query->have_posts()) {			
						$query->the_post();
						$post_articles = $query->post;
						
						$messages		= get_field('message',$post_articles->ID);
						$article_date	= get_field('article_date', $post_articles->ID);
						
						$article_title	= get_field('article_title', $messages->ID);
						$quote			= get_field('quote', $messages->ID);
						$text_refrence	= get_field('text_refrence', $messages->ID);
						$speaker		= get_field('speaker', $messages->ID);
						
						$bulletin		= get_field('bulletin', $messages->ID);
						$bulletin_url 	= ($bulletin) ? wp_get_attachment_url( $bulletin['id'] ) : '';
						
						if(!get_field('notes_url', $messages->ID)) {
							$notes		= get_field('notes', $messages->ID);
							$notes_url	= ($notes) ? wp_get_attachment_url( $notes['id'] ) : '';
						} else {
							$notes_url	= get_field('notes_url', $messages->ID);
						}			
						
						$video_url			= get_field('video_url', $messages->ID);
						$video_cover_image	= get_field('video_cover_image', $messages->ID);			
						$cover_image_url 	= ($video_cover_image) ? $video_cover_image['url'] : '';		
						
						$audio_url			= get_field('audio_url', $messages->ID);
					?>
					<li class="clearfix">
						<p>						
						<strong><?php echo date('d',strtotime($article_date)) .'  '.date('M',strtotime($article_date)) .' '.date('Y',strtotime($article_date));?></strong><br/>
						
						<strong><a href="<?php echo get_permalink($subpage->ID);?>"><?php echo $article_title; ?></a></strong><br/>
						
						<?php echo ($text_refrence) ? 'Text: ' . $text_refrence : ''; ?> </p>
							
						<?php echo ($speaker) ? '<p>Speaker: '. $speaker . '</p>' : ''; ?>
						
						<a class="media-bar clearfix" href="<?php echo get_permalink($subpage->ID);?>">
							<span class="media-bar-title">Watch video or listen to audio</span>
						</a><!--#end of Media Bar -->
						
						<!-- Resource Download -->
						<?php												
						generate_download_media(
							array(
								'class'			=> 'tooltip',
								'url'			=> get_permalink(get_the_ID()),
								'bulletin_url'  => $bulletin_url,
								'notes_url'		=> $notes_url,
								'video_url' 	=> $video_url,
								'audio_url' 	=> $audio_url,
							)
						
						);					
						?>
						<!--#end of Resource Download -->
					</li>				
					<?php
					} wp_reset_postdata();
				} wp_reset_query();
				?>								
				</ul>			
				<!-- Pagination -->
				<div class="pagination-wrp">
					<div class="pagination"></div>
				</div><!--#end of Pagination -->

				<!-- Previous & Next Theme -->
				<?php
				// PREV and NEXT pagination
				// Don't print empty markup if there's nowhere to navigate.			
				
				$args_nav = array(
					'post_type' 	=> 'page',
					'post_status'	=> 'publish',
					'child_of'		=> $post->post_parent,
					'post_parent' 	=> $post->post_parent,
					'depth' 		=> 1, 
					'orderby' 		=> 'meta_value', 
					'meta_key' 		=> 'start_date', 
					'order' 		=> 'ASC'
				);
				
				$query_nav	= new WP_Query($args_nav);
				
				$page_ids = array();
				
				if($query_nav->have_posts()) {	
					while($query_nav->have_posts()) {
						
						$query_nav->the_post();
						setup_postdata($post);					
						$post_nav = $post;				
						$page_ids[] = $post_nav->ID;				
					} wp_reset_postdata();
				} wp_reset_query();
				
				$current = array_search($post->ID, $page_ids);
				$prevID = $page_ids[$current-1];
				$nextID = $page_ids[$current+1];			
				?>
				
				<div class="theme-prev-next">
				<?php 
				if (!empty($prevID)) { ?>
					
					<a class="btn-secondary btn-gray" href="<?php echo get_permalink($prevID); ?>"><i class="ion-ios-arrow-left"></i> &nbsp; Previous Theme</a>
					
				<?php 
				} 
				if(!empty($nextID)) { ?>				
					<a class="btn-secondary btn-gray" href="<?php echo get_permalink($nextID); ?>">Next Theme &nbsp; <i class="ion-ios-arrow-right"></i></a>
				<?php
				}
				?>
				</div><!--#end of Previous & Next Theme -->
				
			</section><!--#end of Main Column -->
		</section> <!--#end of Full-width Column -->
			
	</section><!--#end of Content -->

	<?php endwhile; endif; ?>

	<?php get_footer();
	 
}
else {
	wp_redirect(get_permalink(get_page_by_path('login')->ID)); exit;
	echo '<h2>You do not have enough permission to view this page</h2>'; 
}
