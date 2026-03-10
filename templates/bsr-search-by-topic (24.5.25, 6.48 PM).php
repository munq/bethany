<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: BSR Search by Topic
 
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */
 
get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post();
?>
	
<?php
$book = $_GET['book'];
$chapter = $_GET['chapter'];
$val = $_GET['chapter'];
?>
	
	<!-- Main Banner -->
<section id="banner-main" class="banner-hidden" style="background-image:url(<?php echo $banner_image_url; ?>);">
	<div class="wrapper">
		<div class="title"><?php echo get_field('custom_page_title') ? get_field('custom_page_title'): get_the_title(); ?></div>
	</div>
</section><!--#end of Main Banner -->
	
<!-- include breadcrumbs -->
<?php get_template_part('content/common', 'breadcrumb'); ?>

<!-- Content -->
<section id="content" class="clearfix">
	<!-- Full-width Column -->
	<section class="col-full wrapper clearfix">
		<?php the_content(); ?>
		
		<?php
		$topic = get_term_by('slug', $_GET['t'], 'topic');
		?>
		<h1>Topic: <?php echo _e($topic->name); ?></h1>
		
		<!-- Bible Study Resources -->
		<div id="bible-study-resources">
			
			<?php include get_template_directory() . '/content/bsr/browse-bsr.php'; ?>
			
			<section class="col-full wrapper clearfix">
				<div class="list-articles with-line" id="listing">
					<?php

					$show_pagination = FALSE;
					
					if(isset($_GET) && !empty($_GET)) {
						
						$allowed_templates = array(
							'templates/bsr-series-detail.php', 
							'templates/daily-devotions-detail.php',
							'templates/meditation-detail.php',
						);
						
						$meta_query_others_args = array(
							'relation' => 'AND',
							array(
								'key' => '_wp_page_template',
								'value' => $allowed_templates,
								'compare' 	=> 'IN',
							),
						);
						
						$args_others = array(
							'post_type' 	=> 'page',
							'post_status'	=> 'publish',
							'orderby' 		=> 'meta_value', 
							'meta_key' 		=> 'article_date',
							'meta_query'	=> $meta_query_others_args,
						    'posts_per_page' => -1,
						);
						
						$args_others['tax_query'] = array(
								'relation'	=> 'AND',
								array(
									'taxonomy'	=> 'topic',
									'field'		=> 'slug',
									'terms'		=> esc_attr($_GET['t']),
								),
							);
							
						$query_others = new WP_Query($args_others);
						if($query_others->have_posts()) {
							while($query_others->have_posts()) {
								$query_others->the_post();
								$post_others = $query_others->post;
								if ( get_field('message', $post_others->ID) ) {
									$messages			= get_field('message', $post_others->ID);
									$article_date		= get_field('article_date', $messages->ID);		
									$article_title		= get_field('article_title', $messages->ID);								
									$text_refrence		= get_field('text_refrence', $messages->ID);
									$speaker			= get_field('speaker', $messages->ID);								
									$video_url			= get_field('video_url', $messages->ID);								
									$audio_url			= get_field('audio_url', $messages->ID);							
									$author				= get_field('author', $messages->ID);
									$post_permalink = get_permalink($post_others->ID);
								} else {
									$article_title = get_field('article_title', $post_others->ID);
									$article_date = get_field('article_date', $post_others->ID);
									$text_refrence = get_field('text_refrence', $post_others->ID);
									$author = get_field('author', $post_others->ID);								
									$post_permalink = get_permalink($post_others->ID);								
								}
								?>

								<?php if($video_url) : ?>
									<div class="has-video clearfix">
								<?php elseif($audio_url) : ?>
									<div class="has-audio clearfix">
								<?php else : ?>
										<div class="clearfix">
								<?php endif; ?>
											<p><?php echo date('d M Y', strtotime($article_date)); ?><br/>
												<strong><a href="<?php echo $post_permalink; ?>"><?php echo $article_title; ?></a></strong><br/>
												<?php echo ($text_refrence) ? 'Text: ' . $text_refrence : '' ; ?></p>
												
												<?php $series = get_post($post->post_parent); ?>
											
											<?php if ($author) : ?>
											<p><?php echo 'by ' . $author; ?></p>
											<?php endif; ?>
											
											<?php 
											generate_print_email_save( 
													array(
														'url'		=> get_permalink($post_others->ID),
														'video_url'	=> $video_url,
														'audio_url'	=> $audio_url,
													)
												); 
											?>
											
										</div>								
							<?php
							} // end of while loop
							
						} // end of query have posts
						else {
						?>
						<p>There are no articles found for this topic.</p>
						<?php	
						}
					} // end of if $_GET
					?>					
				</div>

				<!-- Pagination -->
				<div class="pagination-wrp">
					<?php if ($query_others->post_count > 20): ?>
						<div class="pagination"></div>
					<?php else: ?>
						<div class="inline-block"></div>
					<?php endif; ?>
				</div><!--#end of Pagination -->
				
			</section><!--#end of Full-width Column -->
		</div><!--#end of Bible Study Resources -->
	</section><!--#end of Full-width Column -->
</section><!--#end of Content -->
	
<?php endwhile; endif; ?>
<?php get_footer(); ?>
