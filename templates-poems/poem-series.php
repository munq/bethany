<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Poem Series
 * This Template is used For Series Eg: Morning Worship -> Series -> Articles for that Series
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */
 
 // If the 'view' parameter is SET, display all messages of the category
 // Else, Display 'Messages' subpages for the series 
 
 
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
			
		<?php 
		$start_date = get_field('start_date');
		if ( '19700101' == $start_date ) {
			$start_date = '';
		}
		$end_date 	= get_field('end_date');
		if ( '19700101' == $end_date ) {
			$end_date = '';
		}
		?>

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
		<section class="col-main">

			<?php
			$args = array(
				'post_type' 	=> 'page',
				'post_status'	=> 'publish',
				'child_of'		=> get_the_ID(),
				'post_parent' 	=> get_the_ID(),
				'depth' 		=> 1,
				'orderby' 		=> 'menu_order',
				'order' 		=> 'ASC',
				'posts_per_page'	=> -1,
//				'meta_query' => array(
//					array(
//						'key' => 'article_date',
//						// value should be array of (lower, higher) with BETWEEN
//						'value' => array(date('Y-m-d', strtotime($start_date)), date('Y-m-d', strtotime($end_date))),
//						'compare' => 'BETWEEN',
//						'type' => 'DATE'
//					),
//				),
			);
			
			$query	= new WP_Query($args);
			
			if($query->have_posts()) {
				?>
				<ul class="list-messages" id="listing"
				<?php
				while($query->have_posts()) {			
					$query->the_post();
					setup_postdata($post);					
					$post_articles = $post;					
					
					$article_date		= get_field('article_date', $post_articles->ID);
					$article_title		= get_the_title();
					if ( '19700101' == $article_date ) {
						$article_date = '';
					}
					else {
						$article_date_format = date_create_from_format('d/m/Y', $article_date);
						$article_date =  date_format($article_date_format,'d M Y');
					}
					?>

					<li class="clearfix">
						<p>
						<strong><?php echo ( !empty($article_date) ? $article_date : '' ); ?></strong><br/>
						<strong><a href="<?php echo get_permalink($post_articles->ID);?>"><?php echo $article_title; ?></a></strong><br/>
						</p>
					</li>

					<?php
				} wp_reset_postdata();
				?>
				</ul>

				<!-- Pagination -->
				<div class="pagination-wrp">
					<?php if ($query->post_count > 20): ?>
						<div class="pagination"></div>
					<?php else: ?>
						<div class="inline-block"></div>
					<?php endif; ?>
				</div><!--#end of Pagination -->

			<?php
			} else { ?>
				<div class="empty-poems">There are currently no poems for this theme.</div>
			<?php
			}

			wp_reset_query();
			?>

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
	</div>
	</section> <!--#end of Full-width Column -->
		
</section><!--#end of Content -->
	
<?php endwhile; endif; ?>

<?php get_footer(); ?>
