<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Daily Devotions Details
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */

get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post();

$current_post_id  = get_the_ID();
$parent_page_id  = $post->post_parent;
?>
	
<!-- Main Banner -->
<?php get_template_part('content/common', 'banner'); ?>	<!--#end of Main Banner -->
	
<!-- include breadcrumbs -->
<?php get_template_part('content/common', 'breadcrumb'); ?>

<!-- Content -->
<section id="content" class="clearfix">

	<!-- Full-width Column -->
	<section class="col-full wrapper clearfix">
		
		<!-- Bible Study Resources -->
		<div id="bible-study-resources">
			
			<?php include get_template_directory() . '/content/bsr/browse-bsr.php'; ?>
			
			<h2 class="h2-lead">Daily Devotions</h2>			
			<?php
			$day_title = get_the_title();
			$day_title = explode(' ', $day_title);
			
			$grand_parent = get_post($post->post_parent)->post_parent;
			$grand_parent_title = get_the_title($grand_parent);
			?>
			<h1><?php echo $grand_parent_title; ?></h1>
			
			<div class="bsr-filter box-gray">
				<form>
					<fieldset class="bsr-filter-top filter-top clearfix daily-devotion-top">
						<div></div>
						<div class="field-wrp">
							<?php $grand_parent = get_post($post->post_parent)->post_parent; ?>
							<span><strong><?php echo $grand_parent_title; ?></strong></span>&nbsp;
							<?php
							$args_chap = array(
								'post_type' 	=> 'page',
								'post_status'	=> 'publish',
								'child_of'		=> $grand_parent, 
								'post_parent' 	=> $grand_parent,
								'depth' 		=> 1,
								'order'			=> 'ASC',
								'orderby' 		=> 'menu_order',						
								'posts_per_page'	=> -1,
								'meta_query'	=> array(
									'relation' => 'AND',
									array(
									 'key' => '_wp_page_template',                 
									 'value' => 'templates/introduction.php',
									 'compare' => '!='
								   ),									
								)
							);
							
							$query_chap	= new WP_Query($args_chap);
							
							if($query_chap->have_posts()) {
								$parent_page_count = 1;
							?>	
								<select name="chapter_dropdown" class="select">
								<?php
								$day_query = array();
								while($query_chap->have_posts()) {			
									$query_chap->the_post();
									//setup_postdata($post);					
									$post_chap = $query_chap->post;
									$chapter_title = $post_chap->post_title;
									$chapter_title_no = explode(' ', $chapter_title);
									$day_args = array(
										'post_type' 	=> 'page', 
										'post_status'	=> 'publish',
										'child_of'		=> $post_chap->ID, 
										'post_parent' 	=> $post_chap->ID, 
										'depth' 		=> 1,
										'order'			=> 'ASC',
										'orderby' 		=> 'menu_order',
										'meta_key'		=> 'article_date',
										'posts_per_page' => -1,
									);
									$day_query[$post_chap->ID]	= new WP_Query($day_args);

									$option_chapter = $chapter_title_no[1];
									if ( $chapter_title_no[1] > 0 ) {
										$option_chapter = $chapter_title_no[0] ." ". $chapter_title_no[1];
									}

									?>
									<option value="<?php echo $post_chap->ID; //get_permalink($post_chap->ID); ?>" <?php echo ($post_chap->ID == $parent_page_id ) ? 'selected' : ''; ?>><?php echo ($chapter_title_no[1]) ? $chapter_title_no[0] ." ". $chapter_title_no[1] : $chapter_title_no[0]; ?></option>
								<?php
									$parent_page_count++;
								}
								?>								
								</select>
							<?php
							}
							?>

							<div class="clearfix-xs"></div>

							<span><strong>Day</strong></span>&nbsp;
							<?php if (!empty($day_query)) : ?>
								<?php
								$get_next_post = FALSE;
								$get_prev_post = TRUE;
								?>
								<?php foreach($day_query as $chapter_id => $day_row) : ?>
									<?php
									if($day_row->have_posts()) {
									?>
										<span class="chapter-day-dropdown chapter-<?php echo $chapter_id ?>-day-dropdown hidden">
											<select name="day_dropdown" class="select">
												<option value="">Day</option>
											<?php
											while($day_row->have_posts()) {
												$day_row->the_post();
												$post_day = $day_row->post;
												
												$title = $post_day->post_title;
												$title = explode(' ', $title);

												if ( TRUE === $get_next_post && !isset($next_page) ) {
													$next_page = $post_day;
													$get_next_post = FALSE; // done getting the next post
												} else if ( $current_post_id == $post_day->ID ) {
													$get_next_post = TRUE; // set to TRUE to get the next post
													$get_prev_post = FALSE; // done getting the prev post
												} else if ( TRUE === $get_prev_post ) {
													$previous_page = $post_day;
												}
												
												?>	
												<option value="<?php echo get_permalink($post_day->ID); ?>" <?php echo ($current_post_id == $post_day->ID) ? 'Selected' : ''; ?>><?php echo ($title[1]) ? $title[1]: $title[0] ; ?></option>
											<?php
											} wp_reset_postdata();
											?>
											</select>
										</span>
									<?php	
									} wp_reset_query();
									?>
								<?php endforeach; ?>
							
							<?php endif; ?>
							
							<div class="devotions-select-day">
								<?php
								if(isset($previous_page) && !empty($previous_page)) {
								?>
									<a class="btn-primary" href="<?php echo get_permalink($previous_page->ID); ?>"><i class="ion-ios-arrow-left"></i></a>
								<?php
								} else {
									?>
									<a class="btn-primary" href="javascript:;"><i class="ion-ios-arrow-left"></i></a>
								<?php
								}
								?>								
								
								<?php
								if($day_title[1]) {
								?>
									<span class="devotions-day"><strong>Day <?php echo $day_title[1]; ?></strong></span>
								<?php
								} else {
									?>
									<span class="devotions-day"><strong><?php echo $day_title[0]; ?></strong></span>
								<?php
								}
								?>								
								
								<?php
								if(isset($next_page) && !empty($next_page)) {
								?>
									<a class="btn-primary" href="<?php echo get_permalink($next_page->ID); ?>"><i class="ion-ios-arrow-right"></i></a>
								<?php
								} else {
									?>
									<a class="btn-primary" href="javascript:;"><i class="ion-ios-arrow-right"></i></a>
								<?php
								}							
								?>
							</div>
							
						</div>
												
					</fieldset>
				</form>
			</div>
			
			<!-- Full-width Column -->
			<section class="col-full wrapper clearfix">
			<?php 
			generate_print_email_save(
					array(
						'class' => 'as-header',
						'url'	=> get_permalink(get_the_ID()),
					)
				); 
			?>
			
			<?php
			$article_title 	= get_field('article_title');
			$text_refrence 	= get_field('text_refrence');				
			?>
			
			<?php echo ($article_title) ? '<h2>'. $article_title. '</h2>': '' ;?>
			<?php echo ($text_refrence) ? '<p><strong>Text: '. $text_refrence . '</strong></p>': '' ;?>
			
			<?php echo the_content(); ?>
			</section><!--#end of Full-width Column -->
			
			<!-- Full-width Column -->
			<section class="col-full wrapper clearfix">
				<?php
				$args = array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all');
				$page_topics = wp_get_post_terms( $current_post_id, 'topic', $args );
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
				
				<!-- Pagination - Prev-Next -->				
				
				<div class="pagination">
				<?php if (isset($previous_page) && !empty($previous_page)) : ?>
					<a class="jp-previous tooltip" href="<?php echo get_permalink($previous_page->ID); ?>">Prev<span class="tooltip-content"><?php echo $previous_page->post_title; ?></span></a>
				<?php endif; ?>
				<?php if (isset($next_page) && !empty($next_page)) : ?>
					<a class="jp-next tooltip" href="<?php echo get_permalink($next_page->ID); ?>">Next<span class="tooltip-content"><?php echo $next_page->post_title; ?></span></a>
				<?php endif; ?>
				</div><!--#end of Pagination - Prev-Next -->
			</section><!--#end of Full-width Column -->
		</div><!--#end of Bible Study Resources -->
	</section><!--#end of Full-width Column -->
</section><!--#end of Content -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>
