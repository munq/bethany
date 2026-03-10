<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Daily Devotions
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */

 
get_header(); ?>
	
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
			</div><!--#end of Bible Study Resources -->
		
			<h1>Daily Devotions</h1>
			<p class="p-head"><?php echo get_field('summary_content') ?></p>
			
			<!-- Side Column -->

			<?php
			$time_today = current_time( 'timestamp' );
			$daily_devotions = array(		
				'author_name'		=> 'SSBethany',
				'post_type'		=> 'page',
				'post_status'	=> 'publish',				
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
					<section class="col-side">
						<div class="box-side">
							<div class="box-side-top schedule clearfix">
								<span>
									<b><?php echo date('d', strtotime($article_date));?></b><br/>
									<i><?php echo date('M', strtotime($article_date));?></i>
								</span>
								<span>Today’s Devotion</span>
							</div>
							<div class="box-side-content">
								<h3><?php echo ($text_refrence) ? 'Text: ' . $text_refrence : '' ; ?></h3>
								<?php 
								if($quote) {
								?>
									<p>“<?php echo $quote ?>”</p>									
								<?php
								}
								?>
								<p><a href="<?php echo get_permalink($post_devotion->ID); ?>">Read Devotion</a></p>
							</div>
						</div>				
				
<!-- end of first query -->
		
			<?php
			$time_today = current_time( 'timestamp' );
			$daily_devotions_MSITN = array(		
				'author_name'		=> 'webmaster',
				'post_type'		=> 'page',
				'post_status'	=> 'publish',				
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

				?>	

					</section><!--#end of Side Column -->				
				<?php
				} wp_reset_postdata();
			} wp_reset_query();
			?>			
			<!-- Main Column -->
			<section class="col-main">
				<?php echo the_content(); ?>
			</section><!--#end of Main Column -->
		</section><!--#end of Full-width Column -->
		
		<!-- Full-width Column -->
		<section class="col-full wrapper clearfix">
			<hr/>
			
			<div id="subscribe-daily-devotions" class="box-gray valign-middle">
				<div>
					<h2>Subscribe</h2>
				</div>
				<div>
					<form>
						<fieldset>
							<div class="field-wrp">
								<p>Receive the daily devotions in your inbox each morning <a class="btn-secondary btn-submit" href="<?php echo get_permalink(get_page_by_path('join-our-mailing-list')->ID); ?>">Sign up &nbsp;<i class="ion-ios-arrow-right"></i></a></p>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</section><!--#end of Full-width Column -->
	</section><!--#end of Content -->

<?php get_footer(); ?>
