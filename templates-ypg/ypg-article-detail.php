<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: YPG Article Detail
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */
 
get_header('ypg'); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<!-- Main Banner -->
<?php $grand_parent = get_post($post->post_parent)->post_parent; 


$banner_image 	= get_field('banner_image', $grand_parent);
$banner_image_url = ($banner_image) ? $banner_image['url'] : '';
$day			= get_field('day', $grand_parent);
$time			= get_field('time', $grand_parent);
$time 			= explode(' ', $time);
$locations		= get_field('locations', $grand_parent);
$location_sc 	= '';
$age_group		= get_field('age_group', $grand_parent);
?>

<section id="banner-main" class="<?php echo (!$banner_image) ? 'banner-hidden' : ' ' ?>" style="background-image:url(<?php echo $banner_image_url; ?>);">
	<div class="wrapper">
		<h1 class="title"><?php echo get_field('custom_page_title', $grand_parent) ? get_field('custom_page_title', $grand_parent): get_the_title($grand_parent); ?></h1>
		
		<?php 
		if($day) {
			?>
			<div class="box-banner">
				<div class="box-banner-top clearfix">
					<span><span class="numeric"><?php echo $time[0]; ?></span><?php echo $time[1]; ?></span>
					<span><?php echo $day; ?></span>
				</div>
				<div class="box-banner-content">
					<ul class="info-list">						
						<li class="location">
						<?php
						if(have_rows('locations', $grand_parent)) {
							while(have_rows('locations', $grand_parent)) {
								
								the_row();
								$location	= 	get_sub_field('location');
								$location = str_replace('<br>', ' ', $location);
								
								$location_sc[] = $location;
							}
							echo implode(', ', $location_sc);
						}
						?>
						</li>
						<li class="age"><?php echo $age_group ?></li>
					</ul>
				</div>
			</div>			
		<?php
		} 
		?>		
	</div>
</section><!--#end of Main Banner -->
<!--#end of Main Banner -->

<!-- include breadcrumbs -->
<?php get_template_part('content/ypg-yag/fellowship', 'breadcrumb'); ?>

<!-- Content -->
<section id="content" class="clearfix">
	<div class="wrapper">
	<!-- Inside Page Tabs -->
	<div class="tabs inside-nav clearfix">
		<?php get_template_part('content/ypg-yag/detail', 'page-tabs'); ?>
	</div><!--#end of Inside Page Tabs -->
	
	<section class="col-full clearfix">
		<!-- Main Column -->
		<section class="col-main clearfix">
		
		<?php
		
		$author 		= get_field('author');
		$article_date	= get_field('article_date');
		if ( '19700101' == $article_date )
		$article_date = '';
		
		?>
		
		<h2><?php echo the_title(); ?></h2>
		<p>
			<?php echo ($author) ? '<strong>by ' . $author . '</strong><br>' : ''; ?>
			<?php echo ($article_date) ? date('F d, Y', strtotime($article_date)) : '' ;?>
		</p>	
		<?php echo the_content(); ?>
		
		<!-- Full-width Column -->
		<section class="col-full clearfix">
			<div class="tags-wrapper clearfix"></div>
			<!-- Pagination - Prev-Next -->		
			<?php
			$args_nav = get_posts(array(
				'post_type' 	=> 'page',
				'post_status'	=> 'publish',
				'child_of'		=> $post->post_parent,
				'post_parent' 	=> $post->post_parent,
				'depth' 		=> 1, 
				'orderby' 		=> 'meta_value', 
				'meta_key' 		=> 'article_date', 
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
			
			<div class="pagination">
			<?php 
			if (!empty($prevID)) { ?>
				
				<a class="jp-previous tooltip" href="<?php echo get_permalink($prevID); ?>">Prev<span class="tooltip-content"><?php echo get_the_title($prevID); ?></span></a>
				
			<?php 
			} 
			if(!empty($nextID)) { ?>				
				<a class="jp-next tooltip" href="<?php echo get_permalink($nextID); ?>">Next<span class="tooltip-content"><?php echo get_the_title($nextID); ?></span></a>
			<?php
			}
			?>				
			</div><!--#end of Pagination - Prev-Next -->
			
			<div class="tags-wrapper clearfix"></div>
		</section><!--#end of Full-width Column -->		
		
		<p><a class="link-arrow-back" href="<?php echo get_permalink($post->post_parent); ?>">Back to Article Listing</a></p>
		</section><!--#end of Main Column -->
		
		<!-- Side Column -->
		<section class="col-side">
			<div class="box-side">
				<div class="box-side-top clearfix">
					<h3>More Articles</h3>
				</div>
				<div class="box-side-content">
					<ul class="list-more-articles">
						<?php
					
					$args = array(
						'post_type' 	=> 'page',
						'post_status'	=> 'publish',
						'child_of'		=> $post->post_parent,
						'post_parent' 	=> $post->post_parent,
						'depth' 		=> 1, 
						'orderby' 		=> 'meta_value', 
						'meta_key' 		=> 'article_date',
						'order' 		=> 'DESC',
						'post__not_in' 		=> array(get_the_ID()),
						'posts_per_page' => 5
					);
					
					$articles	= new WP_Query($args);
					
					if($articles->have_posts()) {
						while($articles->have_posts()) { 
							
							$articles->the_post();
							setup_postdata($post);
							$article = $post;
							
							$article_date	= get_field('article_date', $article->ID);
							
							if ( '19700101' == $article_date )
							$article_date = '';
							
							?>							
							<li><p>
								<small><strong><?php echo ($article_date) ? date('F d, Y', strtotime($article_date)) : '' ;?></strong></small><br/>
								<a href="<?php echo get_permalink($article->ID); ?>"><?php echo get_the_title($article->ID); ?></a>
							</p></li>
						<?php
						} wp_reset_postdata();
					} wp_reset_query();
					?>	
					</ul>
					<p><a href="<?php echo get_permalink($post->post_parent);?>">View more</a></p>
				</div>
			</div>
		</section><!--#end of Side Column -->
	</section><!--#end of Full-width Column -->		
	</div>
</section><!--#end of Content -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>