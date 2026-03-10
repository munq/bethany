<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: BSR
 
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */
 
get_header(); ?>

<?php include get_template_directory() . '/includes/bible-books-chapters.php'; ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
<!-- Main Banner -->
<?php get_template_part('content/common', 'banner'); ?>	
<!--#end of Main Banner -->
	
<!-- include breadcrumbs -->
<?php get_template_part('content/common', 'breadcrumb'); ?>	

<!-- Content -->
<section id="content" class="clearfix">
	<!-- Full-width Column -->
	<section class="col-full wrapper clearfix">
		<?php the_content(); ?>
		<h2>Find Articles</h2>
		
		<!-- Bible Study Resources -->
		<div id="bible-study-resources">			 
			<div class="bsr-filter box-generic">
					<form action="<?php echo get_permalink(get_page_by_path('bible-study-resources/bsr-search-result')->ID); ?>" method="GET" id="bible_study_form">
						<fieldset class="bsr-filter-top filter-top clearfix">
							<div class="field-wrp field-search">								
								<input name="bsr_search" type="text" placeholder="What do you want to read?" class="full-width" />
								<button id="submit_search" class="i-search ion-ios-search-strong"></button>
							</div>							
						</fieldset>						
					</form>
			</div>
			<!-- Generic Tabs -->
			<div class="tabs-generic tabs-bsr clearfix">
				<ul class="nav-tabs-generic c-two clearfix">
					<li><a href="#tab-bible-series">by Series</a></li>
					<li><a href="#tab-bible-books">by Books of the Bible</a></li>
					<!--<li><a href="#tab-bible-topics">by Topics</a></li>-->				
				</ul>
				
				<!-- Series -->
				<div id="tab-bible-series">
					<div class="bible-series clearfix">
						<div>
							<div class="list-bsr column-count-2">								
								<?php
								if(have_rows('by_series_content')) {
									while(have_rows('by_series_content')) {
										the_row();
										?>
										<h3><a href="<?php echo get_sub_field('series_link'); ?>"><?php echo get_sub_field('series_name'); ?>
										 </a><?php 
										if(get_sub_field('series_overview')) {
										?>
										<br/>
										<small><?php echo get_sub_field('series_overview'); ?></small>
										<?php
										} ?></h3>	
									<?php
									}
								}
								?>															
							</div>
						</div>
					</div>					
				</div><!--#end of Series -->
				
				<!-- Books of the Bible -->
				<div id="tab-bible-books">
					<?php echo get_field('books_of_the_bible_content'); ?>
				</div><!--#end of Books of the Bible -->

				<?php /**
				<!-- Topics -->
				<div id="tab-bible-topics">
					<?php echo get_field('by_topics_content') ;?>
				</div><!--#end of Topics -->
	            */
				?>
				
			</div><!--#end of Generic Tabs -->
		</div><!--#end of Bible Study Resources -->		
		<hr/>		
	</section><!--#end of Full-width Column -->
	
	<!-- Full-width Column -->
	<section class="col-full wrapper clearfix">
		
		<?php			
			$time_today = current_time( 'timestamp' );
			$daily_devotions = array(
				'post_type'		=> 'page',
				'post_status'	=> 'publish',
				'posts_per_page' => 1,				
				'meta_query' => array(		
					    'relation' => 'AND',
						array(
						'key' => '_wp_page_template',
						'value' => 'templates/daily-devotions-detail.php',
						'compare' 	=> '=',
					  ),
					   array(
						'key' 		=> 'article_date',
						'value' 	=> date('Ymd', $time_today),
						'compare' 	=> '=',
						'type'		=> 'DATE'
					  ),
				)
			);
			
			$query	= new WP_Query($daily_devotions);
			
			if($query->have_posts()) {
			
				while($query->have_posts()) {
				
					$query->the_post();
					setup_postdata($post);					
					$post_devotion = $post;
					
					$article_date = get_field('article_date', $post_devotion->ID);
					$text_refrence = get_field('text_refrence', $post_devotion->ID);					
					$quote = get_field('quote', $post_devotion->ID);
				?>
					<div class="box-banner full-width">
						<div class="box-banner-top clearfix">
							<span>Daily Devotions</span>
							<span><span class="numeric"><?php echo date('d', strtotime($article_date)) ;?></span> <?php echo date('F', strtotime($article_date)) ;?> <span class="numeric"><?php echo date('Y', strtotime($article_date)) ;?></span></span>
						</div>
						<div class="box-banner-content">
							<?php echo ($quote) ? '<blockquote><p>' . $quote . '</p>Text:' . $text_refrence . '<span></span></blockquote>'  : ''; ?>
							<p class="txt-right"><a href="<?php echo get_permalink($post_devotion->ID); ?>">Read Devotion</a></p>
							<hr/>							
							<p class="txt-center"><a href="<?php echo get_permalink(get_page_by_path('bible-study-resources/daily-devotions')->ID); ?>">View all daily devotion series</a></p>
						</div>
					</div>
				<?php
				} wp_reset_postdata();
			} wp_reset_query();
			?>		
		
		<div id="subscribe-daily-devotions" class="box-gray valign-middle">
			<div>
				<h2>Subscribe</h2>
			</div>
			<div>
				<form>
					<fieldset>
						<div class="field-wrp">
							<p>Receive the daily devotions in your inbox each morning							
							<a class="btn-secondary btn-submit" href="<?php echo get_permalink(get_page_by_path('join-our-mailing-list')->ID); ?>">Sign up &nbsp;<i class="ion-ios-arrow-right"></i></a>
							</p>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</section> <!--#end of Full-width Column -->
</section><!--#end of Content -->
	
<?php endwhile; endif; ?>

<?php
function search_bsr_msg_javascript() { 
?>
	<script type="text/javascript">
	(function($){
		$(document).ready(function(){
			// removed default click event
		
			$('#submit_search').click(function(){
				var bsr_search = $.trim($('#bible_study_form input[name="bsr_search"]').val());
				if(bsr_search.length < 3 || bsr_search == '' ) {
					//console.log('bsr_search: ' + bsr_search);
					$('#bible_study_form input[name="bsr_search"]').attr("placeholder", "Enter minimum 3 characters");
					return false;
				} else {
					
					$('#bible_study_form').submit();
				}
			});
		});
	
	})(jQuery);
	</script>
<?php
}
add_action('wp_footer', 'search_bsr_msg_javascript');
?>

<?php get_footer(); ?>
