<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Introduction
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */

 
get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post();

$current_post_id  = get_the_ID();
?>
	
<!-- Main Banner -->
<?php get_template_part('content/common', 'banner'); ?>	<!--#end of Main Banner -->
	
<!-- include breadcrumbs -->
<?php get_template_part('content/common', 'breadcrumb'); ?>

<!-- Content -->
<section id="content" class="clearfix">
	<!-- Full-width Column -->
	<section class="col-full wrapper clearfix">
		<h2 class="h2-lead">Daily Devotions</h2>
		<h2>Revealed by the Spirit</h2>
		
		<!-- Bible Study Resources -->
		<div id="bible-study-resources">
			<div class="bsr-filter box-generic">
				<form>
					<fieldset class="bsr-filter-top filter-top clearfix">
						<div class="field-wrp">
							<?php $grand_parent = get_post($post->post_parent)->post_parent; ?>
							<span><strong>Go To</strong></span> &nbsp;
							
							<?php
							$args_chap = array(
								'post_type' 	=> 'page',
								'post_status'	=> 'publish',
								'child_of'		=> $grand_parent, 
								'post_parent' 	=> $grand_parent,
								'depth' 		=> 1,
								'order'			=> 'ASC',
								'orderby' 		=> 'menu_order',							
								'posts_per_page'	=> -1
								
							);
							
							$query_chap	= new WP_Query($args_chap);							
							?>							
							<select name="chapter_dropdown_intro" class="select">
							<option>Go To</option>
							<?php
							
							if($query_chap->have_posts()) {
								$parent_page_count = 1;
								while($query_chap->have_posts()) {			
									$query_chap->the_post();
									setup_postdata($post);					
									$post_chapter = $post;								
									?>
									<option value="<?php echo get_permalink($post_chapter->ID); ?>"><?php echo $post_chapter->post_title; ?></option>
								<?php
									$parent_page_count++;
								} wp_reset_postdata();
							
							} wp_reset_query();							
							?>
							</select>
							
						</div>						
						
						<div>
							<?php
							$args_intro = array(
								'post_type' 	=> 'page',
								'post_status'	=> 'publish',
								'child_of'		=> $post->post_parent, 
								'post_parent' 	=> $post->post_parent,
								'depth' 		=> 1,
								'order'			=> 'ASC',
								'orderby' 		=> 'menu_order',							
								'posts_per_page'	=> -1,
								'meta_query'	=> array(
										'relation' => 'AND',
										array(
										 'key' => '_wp_page_template',                 
										 'value' => 'templates/introduction.php',
										 'compare' => '='
									   ),									
								)
									
							);
							
							$query_intro	= new WP_Query($args_intro);
							
							if($query_intro->have_posts()) {
							?>
								<p><a class="link-txt" href="#mm-nav-introductory-articles"><i class="ion-navicon-round"></i> Read introductory articles</a></p>								
								<!-- Introductory Articles Listing -->
								<div class="nav-bsr-listing" id="mm-nav-introductory-articles">
									<ol>
										<?php
										while($query_intro->have_posts()) {
										
											$query_intro->the_post();
											setup_postdata($post);					
											$post_intro = $post;
										?>
											<li><a href="<?php echo get_permalink($post_intro->ID); ?>"><?php echo $post_intro->post_title; ?></a></li>
										<?php
										} wp_reset_postdata();
										?>
									</ol>
								</div><!--#end of Introductory Articles Listing -->
							<?php
							} wp_reset_query();
							
							?>
						</div>						
					</fieldset>
				</form>
			</div>
			
			<!-- Full-width Column -->
			<section class="col-full wrapper clearfix">	
			<?php echo get_the_content(); ?>	
			
			</section><!--#end of Full-width Column -->
			
			<!-- Full-width Column -->
			<section class="col-full wrapper clearfix">
				<div class="tags-wrapper clearfix">
					
				</div>
				
				<!-- Pagination - Prev-Next -->
				
				<?php
				$args_nav = array(
					'post_type' 	=> 'page',
					'post_status'	=> 'publish',
					'child_of'		=> $post->post_parent, 
					'post_parent' 	=> $post->post_parent,
					'depth' 		=> 1,
					'order'			=> 'ASC',
					'orderby' 		=> 'menu_order',							
					'posts_per_page'	=> -1,
					'meta_query'	=> array(
							'relation' => 'AND',
							array(
							 'key' => '_wp_page_template',                 
							 'value' => 'templates/introduction.php',
							 'compare' => '='
						   ),									
					)
						
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
				</div><!--#end of Pagination - Prev-Next -->
			</section><!--#end of Full-width Column -->
		</div><!--#end of Bible Study Resources -->
	</section><!--#end of Full-width Column -->
</section><!--#end of Content -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>
