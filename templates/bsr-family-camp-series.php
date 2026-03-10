<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: BSR Family Camp Series
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
		
		<?php 
		$start_date = get_field('start_date');
		if ( '19700101' == $start_date )
			$start_date = '';
			
		$end_date 	= get_field('end_date');
		if ( '19700101' == $end_date )
			$end_date = '';
		?>	
		
		<?php include get_template_directory() . '/content/bsr/browse-bsr.php'; ?>
		
		<h2 class="h2-lead">
		<?php 
		if(!empty($start_date)) {
			echo date('M Y', strtotime($start_date));
		}
		
		if(!empty($end_date)) {
			echo ' - ' . date('M Y', strtotime($end_date)) ;
		}?>
		</h2>
		
		<h1><?php echo get_the_title(); ?></h1>
		
		<?php the_content(); ?>
		
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
		
		<!-- Main Column -->
		<section class="col-full wrapper clearfix">	
			<div class="list-articles with-line" id="listing">
				<?php					
				$args = array(
					'post_type' 	=> 'page',
					'post_status'	=> 'publish',
					'child_of'		=> get_the_ID(), 
					'post_parent' 	=> get_the_ID(), 
					'depth' 		=> 1, 
					'orderby' 		=> 'meta_value', 
					'meta_key' 		=> 'message_date', 
					'order' 		=> 'ASC',
					'posts_per_page'	=> -1
				);
				
				$query	= new WP_Query($args);				
			
				if($query->have_posts()) {
				
					while($query->have_posts()) {			
						$query->the_post();
						setup_postdata($post);					
						$post_day = $post;
						$message_date = get_field('message_date', $post_day->ID);
					?>					
						<h3><?php echo $post_day->post_title; ?> | <?php echo date('d M Y', strtotime($message_date)); ?></h3>
						<?php
						
						$args_articles = array(
						'post_type' 	=> 'page',
						'post_status'	=> 'publish',
						'child_of'		=> $post_day->ID,
						'post_parent' 	=> $post_day->ID,
						'depth' 		=> 1, 
						'orderby' 		=> 'date',						
						'order' 		=> 'ASC',
						'posts_per_page'	=> -1
					);
					
					$query_articles	= new WP_Query($args_articles);				
				
					if($query_articles->have_posts()) {
					
						while($query_articles->have_posts()) {				
							$query_articles->the_post();
							setup_postdata($post);					
							$post_articles = $post;							
							
							$messages		= get_field('message',$post_articles->ID);
							$article_date	= get_field('article_date', $post_articles->ID);							
							$article_title	= get_field('article_title', $messages->ID);							
							$text_refrence	= get_field('text_refrence', $messages->ID);
							$speaker		= get_field('speaker', $messages->ID);							
							$video_url		= get_field('video_url', $messages->ID);
							$audio_url		= get_field('audio_url', $messages->ID);
							
							if(get_field('notes', $messages->ID)) {
								$notes = get_field('notes', $messages->ID);
								$notes_url  = ($notes) ? wp_get_attachment_url( $notes['id'] ) : '';
							} else {
								$notes_url = get_field('notes_url', $messages->ID);
							}
							
						?>
						<div class="clearfix">
							<p><?php echo (!empty($article_date) ? date('d M Y', strtotime($article_date)):''); ?><br/>					
							<strong><a href="<?php echo get_permalink($post_articles->ID);?>"><?php echo $article_title; ?></a></strong><br/>
							<?php echo ($text_refrence) ? 'Text: ' . $text_refrence : ''; ?> </p>						
							<?php echo ($speaker) ? '<p>Speaker: '. $speaker . '</p>' : ''; ?>
							
							<?php 
					
							generate_print_email_save( 
								array(
									'url'		=> get_permalink($post_others->ID),
									'notes_url'	=> $notes_url,
									'audio_url'	=> $audio_url,
									'video_url'	=> $video_url,
								)
							); 
							
							?>
							
						</div>
						<?php
						} wp_reset_postdata();
					} wp_reset_query();	
						
					?>
					
				<?php
					} wp_reset_postdata();
				} wp_reset_query();					
				?>										
				</div>
			<!-- Pagination -->
			<div class="pagination-wrp">
				<div class="pagination"></div>
			</div><!--#end of Pagination -->

			<!-- Previous & Next Theme -->
			<?php
			// PREV and NEXT pagination
			// Don't print empty markup if there's nowhere to navigate.			
			
			$args_nav = get_posts(array(
				'post_type' 	=> 'page',
				'post_status'	=> 'publish',
				'child_of'		=> $post->post_parent,
				'post_parent' 	=> $post->post_parent,
				'depth' 		=> 1, 
				'orderby' 		=> 'meta_value', 
				'meta_key' 		=> 'start_date', 
				'order' 		=> 'ASC',
				'posts_per_page'	=> -1
			));
		
			$page_ids = array();
			
			foreach($args_nav as $post_nav) {
				$page_ids[] = $post_nav->ID;
			}
			
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
	</div>
	</section> <!--#end of Full-width Column -->
		
</section><!--#end of Content -->
	
<?php endwhile; endif; ?>

<?php get_footer(); ?>
