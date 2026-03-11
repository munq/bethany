<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Series Article Detail 
 *
 *This Template is used to display Article Content From Message Post Type
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */
 
 // Get Message post type tied to the page and display its fields
 
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
	<?php
	$user = wp_get_current_user();
	$allowed_roles = array('administrator','login_member','sstt','pastor','ipcindia','ymg');

	$messages	= get_field('message', get_the_ID());
	
	if($messages->post_status == 'publish') {
	
	$article_date		= get_field('article_date', $messages->ID);		
	$article_title		= get_field('article_title', $messages->ID);
	$quote				= get_field('quote', $messages->ID);
	$text_refrence		= get_field('text_refrence', $messages->ID);
	$speaker			= get_field('speaker', $messages->ID);
	$chairman			= get_field('chairman', $messages->ID);
	
	$bulletin			= get_field('bulletin', $messages->ID);
	$bulletin_url 		= ($bulletin) ? wp_get_attachment_url( $bulletin['id'] ) : '';
	
	if(!get_field('notes_url', $messages->ID)) {
		$notes		= get_field('notes', $messages->ID);
		$notes_url	= ($notes) ? wp_get_attachment_url( $notes['id'] ) : '';
	} else {
		$notes_url	= get_field('notes_url', $messages->ID);
	}		
	
	$video_url			= get_field('video_url', $messages->ID);
	$full_video_url			= get_field('full_video_url', $messages->ID);
	if ( array_intersect($allowed_roles, $user->roles) && !empty($full_video_url) ) {
		$video_url			= $full_video_url;
	}

	$video_cover_image	= get_field('video_cover_image', $messages->ID);			
	$cover_image_url 	= ($video_cover_image) ? $video_cover_image['url'] : '';		
	
	$audio_title_1			= get_field('audio_title_1', $messages->ID);
	$audio_url			= get_field('audio_url', $messages->ID);
	$audio_title_2			= get_field('audio_title_2', $messages->ID);	
	$audio_url_2		= get_field('audio_url_2', $messages->ID);	
	?>
		<h2 class="h2-lead"><span class="numeric"><?php echo date('d',strtotime($article_date)).'  ' ;?></span><?php echo date('F',strtotime($article_date)).'  ';?><span class="numeric"><?php echo date('Y',strtotime($article_date));?></span></h2>
		
		<?php
		if( current_user_can('edit_post', $messages->ID) ) {
			edit_post_link('Edit this page', '<p>', '</p>', $messages->ID);
		}
		?>
		
		<h1><?php echo $article_title;?></h1>

		<!-- Full-width Column -->
		<section class="col-full clearfix">
			<!-- Video -->
			<?php
			if ( $video_url ) { ?>
				<div class="video-embed box-img">
					<img src="<?php echo ($cover_image_url) ? $cover_image_url : 'http://placehold.co/980x504/369/333&text=video' ;?>" />
					<div class="video-overlay">
						<span class="video-duration"></span>
					</div>
					<div class="video-iframe">
						<?php echo do_shortcode( '[video src="' . $video_url . '" width="980" height = "448" preload="metadata"]' ); ?>
					</div>
				</div><!--#end of Video -->
			<?php
			}
			?>
			<!-- Media Bar -->
			<?php 
			if($audio_url) {
			?>
				<div class="media-player"></div>
				<div class="media-bar-wrp">
					<a class="media-bar jp-play clearfix" href="<?php echo $audio_url;?>">
						<span class="media-bar-icon"><i class="ion-ios-play"></i></span>
						<span class="media-bar-title"><?php echo ($audio_title_1) ? $audio_title_1 : 'Listen to audio #1';?></span>
						<span class="jp-progress">				
							<div class="jp-seek-bar">
								<div class="jp-play-bar"></div>
							</div>
						</span>
						<span class="media-bar-duration">
							<span class="jp-current-time"></span>/
							<span class="jp-duration"></span>
						</span>
					</a>
					<div class="media-bar-pause jp-pause"></div>
				</div>
			<?php 
			} ?>	
			
			<!-- Resource Download -->
			<div class="download-resource clearfix">
				<div class="resource">					
					<h4>
						<strong><?php echo $article_title; ?></strong>
						<?php echo ($text_refrence) ? '<br/>Text: ' . $text_refrence : ''; ?>
					</h4>
					
					<dl class="clearfix">
					<?php 							
						echo ($speaker) ? '<dt>Speaker:</dt><dd>' . $speaker . '</dd>' : '';
						echo ($chairman) ? '<dt>Chairman:</dt><dd>' . $chairman . '</dd>' : '';
						?>
						<dt>Series:</dt>
						<dd><a href="<?php echo get_permalink($post->post_parent);?>"><?php echo get_the_title($post->post_parent);?></a></dd>
					</dl>
				</div>
				
				<?php
												
				generate_download_media(
					array(
						'url'			=> get_permalink(get_the_ID()),
						'bulletin_url'  => $bulletin_url,
						'notes_url'		=> $notes_url,
						'video_url' 	=> $video_url,
						'audio_url' 	=> $audio_url,
					)				
				);
				
				?>
				
			</div><!--#end of Resource Download -->
			
			
		</section><!--#end of Full-width Column -->

		<!-- Full-width Column -->
		<section class="col-full clearfix">
			
			<!-- Media Bar 2 -->
			<?php 
			if($audio_url_2) {
			?>
				<div class="media-player"></div>
				<div class="media-bar-wrp">
					<a class="media-bar jp-play clearfix" href="<?php echo $audio_url_2;?>">
						<span class="media-bar-icon"><i class="ion-ios-play"></i></span>
						<span class="media-bar-title"><?php echo ($audio_title_2) ? $audio_title_2 : 'Listen to audio #2';?></span>
						<span class="jp-progress">				
							<div class="jp-seek-bar">
								<div class="jp-play-bar"></div>
							</div>
						</span>
						<span class="media-bar-duration">
							<span class="jp-current-time"></span>/
							<span class="jp-duration"></span>
						</span>
					</a>
					<div class="media-bar-pause jp-pause"></div>
				</div>
				
				<!-- Resource Download -->
				<div class="download-resource clearfix">
					<div class="resource">					
						<h4>
							<strong><?php echo $article_title; ?></strong>
							<?php echo ($text_refrence) ? '<br/>Text: ' . $text_refrence : ''; ?>
						</h4>
						
						<dl class="clearfix">
						<?php 							
							echo ($speaker) ? '<dt>Speaker:</dt><dd>' . $speaker . '</dd>' : '';
							echo ($chairman) ? '<dt>Speaker:</dt><dd>' . $chairman . '</dd>' : '';
							?>
							<dt>Series:</dt>
							<dd><a href="<?php echo get_permalink($post->post_parent);?>"><?php echo get_the_title($post->post_parent);?></a></dd>
						</dl>
					</div>
					
					<?php
													
					generate_download_media(
						array(
							'url'			=> get_permalink(get_the_ID()),
							'bulletin_url'  => $bulletin_url,
							'notes_url'		=> $notes_url,
							'video_url' 	=> $video_url,
							'audio_url' 	=> $audio_url_2,
						)
					);
					
					?>
					
				</div><!--#end of Resource Download -->
				
			<?php 
			} ?>
			
			<?php
			// PREV and NEXT pagination
			// Don't print empty markup if there's nowhere to navigate.
			
			$args_nav = array(
				'post_type' 	=> 'page', 
				'child_of'		=> $post->post_parent,
				'post_parent' 	=> $post->post_parent,
				'depth' 		=> 1, 
				'orderby' 		=> 'meta_value', 
				'meta_key' 		=> 'article_date', 
				'order' 		=> 'ASC',
				'nopaging' => true
			);
				
			$query_nav	= new WP_Query($args_nav);
			//var_dump($query_nav->request);
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
				
			<div class="pagination">
				<?php 
				if (!empty($prevID)) { ?>				
					<a class="jp-previous" href="<?php echo get_permalink($prevID); ?>">Prev</a>
				<?php 
				}
				if(!empty($nextID)) { ?>
					<a class="jp-next" href="<?php echo get_permalink($nextID); ?>">Next</a>
				<?php
				}
				?>
			</div>		
		<?php  // End if ( $next || $previous )?>
			
		</section>
		
		<hr/>		
		<!-- Full-width Column -->
		<section class="col-full clearfix">
		<?php 
		if($messages->post_content) {
		?>
			<h2>Message Notes</h2>		
			<?php			
			echo apply_filters('the_content', $messages->post_content);
		}			
		?>			
		</section><!--#end of Full-width Column -->
		<?php 
		} 
		?>
	</section> <!--#end of Full-width Column -->
</section>
<style>
  .responsive-google-slides {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 Ratio */
    height: 0;
    overflow: hidden;
  }
  .responsive-google-slides iframe {
    border: 0;
    position: absolute;
    top: 0;
    left: 0;
    width: 100% !important;
    height: 100% !important;
  }
</style>

<!--#end of Content -->
	
<?php endwhile; endif; ?>

<?php get_footer(); ?>
