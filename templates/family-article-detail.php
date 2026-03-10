<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Family Article Detail 
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
<?php get_template_part('content/common', 'banner'); ?>	<!--#end of Main Banner -->
	
<!-- include breadcrumbs -->
<?php get_template_part('content/common', 'breadcrumb'); ?>

<!-- Content -->
<section id="content" class="clearfix">
	<!-- Full-width Column -->
	<section class="col-full wrapper clearfix">			
		<?php
		$messages	= get_field('message',get_the_ID());
		if ( $messages->post_status == 'publish' ) {
	
			$article_date		= get_field('article_date', $messages->ID);
			$article_title		= get_field('article_title', $messages->ID);
			$quote				= get_field('quote', $messages->ID);
			$text_refrence		= get_field('text_refrence', $messages->ID);
			$speaker			= get_field('speaker', $messages->ID);

			$bulletin			= get_field('bulletin', $messages->ID);
			$bulletin_url 		= ($bulletin) ? wp_get_attachment_url( $bulletin['id'] ) : '';
	
			if ( get_field('notes', $messages->ID) ) {
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
	
		<?php
			$category_parent = get_post($post->post_parent)->post_parent;
			$category_parent =wp_get_post_parent_id( $category_parent );
			$category_parent =wp_get_post_parent_id( $category_parent );
		?>

		<h2 class="h2-lead"><span class="numeric"><?php echo date('d',strtotime($article_date)).'  ' ;?></span><?php echo date('F',strtotime($article_date)).'  ';?><span class="numeric"><?php echo date('Y',strtotime($article_date));?></span></h2>

		<?php
		if ( current_user_can('edit_post', $messages->ID) ) {
			edit_post_link('Edit this page', '<p>', '</p>', $messages->ID);
		}
		?>
		
		<h1><?php echo $article_title; ?></h1>

		<!-- Full-width Column -->
		<section class="col-full clearfix">
			<!-- Video -->
			<?php if ($video_url) { ?>
				<div class="video-embed box-img">
					<img src="<?php echo ($cover_image_url) ? $cover_image_url : 'http://placehold.it/980x504/369/333&text=video' ;?>" />
					<div class="video-overlay">
						<span class="video-duration"></span>
					</div>
					<div class="video-iframe">
						<?php echo do_shortcode( '[video src="' . $video_url . '" width="980" height = "448" preload="none"]' ); ?>
					</div>
				</div><!--#end of Video -->
			<?php 
			} ?>
			<!-- Media Bar -->
			<?php 
			if($audio_url) {
			?>
				<div class="media-player"></div>
				<div class="media-bar-wrp">
					<a class="media-bar jp-play clearfix" href="<?php echo $audio_url;?>">
						<span class="media-bar-icon"><i class="ion-ios-play"></i></span>
						<span class="media-bar-title">Listen to audio</span>
						<span class="media-bar-duration">
							<span class="jp-current-time"></span>/
							<span class="jp-duration"></span>
						</span>
					</a>
					<div class="media-bar-pause jp-pause"></div>
				</div><!--#end of Media Bar -->
			<?php 
			} ?>
			<!-- Resource Download -->
			<div class="download-resource clearfix">
				<div class="resource">
					
					<?php echo ($text_refrence) ? 'Text: ' . $text_refrence  : '';?>
					<dl class="clearfix">
					<?php 							
						echo ($speaker) ? '<dt>Speaker:</dt><dd>' . $speaker . '</dd>' : '';
						echo ($chairman) ? '<dt>Speaker:</dt><dd>' . $chairman . '</dd>' : '';
						?>
						<dt>Series:</dt>
						<dd><a href="<?php echo get_permalink(get_post($post->post_parent)->post_parent);?>"><?php echo get_the_title(get_post($post->post_parent)->post_parent);?></a></dd>
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


		<!-- Pagination - Prev-Next -->
		<?php
		$hide_prev_next = FALSE;
		$post_id = get_the_ID();
		$message_pid = wp_get_post_parent_id($post_id);
		$parent_id = wp_get_post_parent_id($message_pid);
		$post_type = get_post_type($post_id);

		$sibling_list = get_pages(array(
			'post_type' 	=> 'page',
			'post_status'	=> 'publish',
			'child_of'		=> $parent_id,
			'sort_column' 		=> 'post_date',
			'sort_order' 		=> 'ASC',
		));

		if( !$sibling_list || is_wp_error($sibling_list) ) {
			$hide_prev_next = TRUE;
		}

		$page_ids = array();
		$top_page_ids = array();
		$prev_next_ids = array();

		foreach ($sibling_list as $sibling ) {
			$page_ids[$sibling->post_parent] = $sibling->ID;
			if ($parent_id == $sibling->post_parent) {
				$top_page_ids[] = $sibling->ID;
			}
		}

		foreach ($top_page_ids as $top_id ) {
			$prev_next_ids[] = $page_ids[$top_id];
		}

		$current = array_search($post_id, $prev_next_ids);
		$prevID = $prev_next_ids[$current-1];
		$nextID = $prev_next_ids[$current+1];
		?>

		<?php if ( $hide_prev_next == FALSE ) { ?>
		<section class="col-full clearfix">
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
		</section><!--#end of Full-width Column -->
		<?php } ?>
		<!--#end of Pagination - Prev-Next -->

		<hr/>
		<!-- Full-width Column -->
		<section class="col-full clearfix">
		<?php
		if($messages->post_content) {
		?>
			<h2>Message Notes</h2>

			<?php
			echo $messages->post_content;
		}
		?>
		</section><!--#end of Full-width Column -->
		<?php
		}
	?>
	</section> <!--#end of Full-width Column -->

</section><!--#end of Content -->
	
<?php endwhile; endif; ?>

<?php get_footer(); ?>
