<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Meditation Detail
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
			
			<?php include get_template_directory() . '/content/bsr/browse-bsr.php'; ?>
			
			<h2>Meditation</h2>			
			<div class="bsr-filter box-gray">
				<form>
					<fieldset class="bsr-filter-top filter-top clearfix">
						<div class="field-wrp">
							<span><strong>Go to</strong></span> &nbsp;							
							<?php
							$grand_parent = get_post($post->post_parent)->post_parent;
							?>
							<select class="select-year-link" data-selecter-options='{"label": "Year", "links": "true"}'>
								<?php
								
								$grand_parent_c = wp_get_post_parent_id( $grand_parent );
									
								$args_parent_pages = array(									
									'post_type' 	=> 'page',
									'post_status'	=> 'publish',
									'child_of'		=> $grand_parent_c, 
									'post_parent' 	=> $grand_parent_c, 
									'depth' 		=> 1, 
									'orderby' 		=> 'year', 
									'meta_key' 		=> 'year', 
									'order' 		=> 'DESC',
									'posts_per_page'	=> -1									
								);
								
								$query	= new WP_Query($args_parent_pages);

								if($query->have_posts()) {
											
									while($query->have_posts()) {
									
										$query->the_post();
										setup_postdata($post);					
										$post_year = $post;
										
										$medi_year = date('Y', strtotime(get_field('year', $post_year->ID)));
										
									?>
									<option value="<?php echo get_permalink($post_year->ID); ?>"><?php echo $medi_year; ?></option>	
									<?php
									} wp_reset_postdata();
								} wp_reset_query();
								?>						
							</select>
						</div>
						<div></div>
					</fieldset>
				</form>
			</div>
			
			<!-- Full-width Column -->
			<section class="col-full wrapper clearfix">
				<?php
				$article_title 	= get_field('article_title');
				$text_refrence 	= get_field('text_refrence');
				$article_date 	= get_field('article_date');
				if(get_field('notes')) {
					$notes			= get_field('notes');
					$notes_url		= ($notes) ? wp_get_attachment_url( $notes['id'] ) : '';
				} else {
					$notes_url = 	get_field('notes_url');
				}
				
				?>
				
				<?php 
				
				generate_print_email_save( 
						array(
							'class' => 'as-header',
							'url'	=> get_permalink(get_the_ID()),
							'pdf_url'	=> $notes_url,
						) 
					); 
				?>
				
				<?php echo ($article_title) ? '<h2>'. $article_title. '</h2>': '' ; ?>
				<p>
					<?php echo ($text_refrence) ? '<strong>Text: '. $text_refrence . '</strong><br>': '' ;?>
					<?php echo ($article_date) ? date('j F  Y', strtotime($article_date)) : '' ;?>
				</p>
				
				<?php echo the_content(); ?>				
				
			</section><!--#end of Full-width Column -->
			
			<!-- Full-width Column -->
			<section class="col-full wrapper clearfix">
				<div class="tags-wrapper clearfix"></div>
				
				<!-- Pagination - Prev-Next -->
				<?php
				$args_nav = array(
					'post_type' 	=> 'page',
					'post_status'	=> 'publish',
					'child_of'		=> $post->post_parent,
					'post_parent' 	=> $post->post_parent,
					'depth' 		=> 1, 
					'orderby' 		=> 'meta_value', 
					'meta_key' 		=> 'article_date', 
					'order' 		=> 'ASC',
					'posts_per_page'	=> -1
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

				<?php
				$args = array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all');
				$page_topics = wp_get_post_terms( $post->ID, 'topic', $args );
				?>
				<div class="tags-wrapper clearfix">
					<?php
					if (!empty($page_topics)) {
						?>
						<span>Tags:</span>
						<ul class="nav-tags clearfix">
							<?php
							foreach($page_topics as $page_topic) {
								?>
								<li><a href="<?php echo get_permalink(get_page_by_path('bible-study-resources/by-topics')) . '/?t=' . $page_topic->slug ?>"><?php echo $page_topic->name; ?></a></li>
								<?php
							}
							?>
						</ul>
					<?php } ?>
				</div>
				
				<div class="pagination">
				<?php 
				if (!empty($prevID)) { ?>				
					<a class="jp-previous tooltip" href="<?php echo get_permalink($prevID); ?>">Prev<span class="tooltip-content"><?php echo get_the_title($prevID); ?><br><?php echo date('j F  Y', strtotime(get_field('article_date', $prevID))); ?></span></a>
				<?php 
				}
				if(!empty($nextID)) { ?>
					<a class="jp-next tooltip" href="<?php echo get_permalink($nextID); ?>">Next<span class="tooltip-content"><?php echo get_the_title($nextID); ?><br><?php echo date('j F  Y', strtotime(get_field('article_date', $nextID))); ?></span></a>
				<?php
				}
				?>
				</div><!--#end of Pagination - Prev-Next -->
			</section><!--#end of Full-width Column -->
			
			<!-- Full-width Column -->			
		</div><!--#end of Bible Study Resources -->
	</section><!--#end of Full-width Column -->
	
</section><!--#end of Content -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>
