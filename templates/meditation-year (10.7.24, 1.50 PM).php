<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Meditation Year
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */

 
get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post();
?>

<?php $current_post_id = get_the_ID(); ?>	
<!-- Main Banner -->
<?php get_template_part('content/common', 'banner'); ?>	<!--#end of Main Banner -->
	
<!-- include breadcrumbs -->
<?php get_template_part('content/common', 'breadcrumb'); ?>

<!-- Content -->
<section id="content" class="clearfix">
	<!-- Full-width Column -->
	<section class="col-full wrapper clearfix">
		<h2>Meditation</h2>
		<?php the_content(); ?>
		
		<!-- Bible Study Resources -->
		<div id="bible-study-resources">	
			
			<?php include get_template_directory() . '/content/bsr/browse-bsr.php'; ?>
			
			<div class="bsr-filter box-gray">
				<form>
					<fieldset class="bsr-filter-top filter-top clearfix">
						<div class="field-wrp">
							<span><strong>Go to</strong></span> &nbsp;
							<select class="select-year-link" data-selecter-options='{"label": "Year", "links": "true"}'>
								<?php 
								
								$args = array(
									'post_type' 	=> 'page',
									'post_status'	=> 'publish',
									'child_of'		=> $post->post_parent, 
									'post_parent' 	=> $post->post_parent, 
									'depth' 		=> 1, 
									'orderby' 		=> 'year', 
									'meta_key' 		=> 'year', 
									'order' 		=> 'DESC',
									'posts_per_page'	=> -1
									
								);
								
								$query	= new WP_Query($args);
								
								if($query->have_posts()) {
			
									while($query->have_posts()) {			
										$query->the_post();
										setup_postdata($post);					
										$post_year = $post;
										
										$medi_year = date('Y', strtotime(get_field('year', $post_year->ID)));
										?>											
										<option value="<?php echo get_permalink($post_year->ID); ?>" <?php echo ($current_post_id == $post_year->ID) ? 'Selected' : ''; ?>><?php echo $medi_year ?></option>	
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
					<h3><?php echo date('Y', strtotime(get_field('year'))); ?></h3>
					<!-- Accordion -->
					<div class="accordion">
					<?php
						$args_series_pages = array(
							'post_type' 	=> 'page',
							'post_status'	=> 'publish',
							'child_of'		=> get_the_ID(),
							'post_parent' 	=> $current_post_id,
							'depth' 		=> 1, 
							'orderby' 		=> 'meta_value', 
							'meta_key' 		=> 'start_date', 
							'order' 		=> 'DESC',
							'posts_per_page'	=> -1
						);						
											
						$series_pages	= new WP_Query($args_series_pages);	

						if($series_pages->have_posts()) {	
							while($series_pages->have_posts()) {
								
								$series_pages->the_post();
								setup_postdata($post);					
								$series_page = $post;	
								
								$series_page_title	= $series_page->post_title;
								$series_duration 	= get_field('series_duration', $series_page->ID);
								$start_date 		= get_field('start_date', $series_page->ID);
								$end_date 			= get_field('end_date', $series_page->ID);							
								
								?>
								
								<h3 class="has-right-header">
								<?php echo $series_page_title ?>							
								<span class="right-header"><?php echo date('M', strtotime($start_date)) . ' - ' . date('M', strtotime($end_date)) ;?></span>
								</h3>
							
							<div>
								<div class="list-articles with-line" id="listing">
								<?php
								$args_article_pages = array(
										'post_type' 	=> 'page',
										'post_status'	=> 'publish',
										'child_of'		=> $series_page->ID,
										'post_parent'  	=> $series_page->ID,
										'depth' 		=> 1, 
										'orderby' 		=> 'meta_value', 
										'meta_key' 		=> 'article_date', 
										'order' 		=> 'DESC',
										'posts_per_page'	=> -1
									);
									
									$article_pages	= new WP_Query($args_article_pages);
									
								//	echo "<pre>".print_r($article_pages, 1)."</pre>";
									

									if($article_pages->have_posts()) {
										while($article_pages->have_posts()) {
											
											$article_pages->the_post();
											setup_postdata($post);					
											$article_page = $post;	
											
											$article_title 	= (get_field('article_title',$article_page->ID)) ? get_field('article_title', $article_page->ID) : $article_page->post_title ;
								
											$text_refrence 	= get_field('text_refrence', $article_page->ID);
											$article_date 	= get_field('article_date', $article_page->ID);
											if ( '19700101' == $article_date )
												$article_date = '';											
											
											$author 		= get_field('author', $article_page->ID);
											
											if(get_field('notes', $article_page->ID)) {
												$notes		= get_field('notes', $article_page->ID);
												$notes_url	= ($notes) ? wp_get_attachment_url( $notes['id'] ) : '';
											} else {
												$notes_url	= get_field('notes_url', $article_page->ID);
											}
											
										?>											
											<div class="clearfix">										
												<p><?php echo ($article_date) ? date('d M Y', strtotime($article_date)) .'<br/>' : '' ; ?>
													<strong><a href="<?php echo get_permalink($article_page->ID); ?>"><?php echo $article_title; ?></a></strong><br/>
													<?php echo ($text_refrence) ? 'Text: ' . $text_refrence : ''; ?></p>
												
												<p><?php echo ($author) ? 'by ' . $author.'&nbsp;|&nbsp;' : '' ;?></p>
												
												<?php 
												generate_print_email_save( 
														array(
															'url'		=> get_permalink($post_others->ID),
															'notes_url'	=> $notes_url,
														)
													); 
												?>
												
											</div>							
										
									<?php
									} wp_reset_postdata();
								
								} wp_reset_query();
							?>
								</div>
							</div>							
							<?php
							} wp_reset_postdata();
						} wp_reset_query();
						?>
					</div><!--#end of Accordion -->
				</section>			
			<!-- Full-width Column -->			
		</div><!--#end of Bible Study Resources -->
	</section><!--#end of Full-width Column -->
	
</section><!--#end of Content -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>
