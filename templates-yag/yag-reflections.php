<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: YAG Reflections
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */
?>
<?php get_header('yag'); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<!-- Main Banner -->
<?php get_template_part('content/ypg-yag/subpage', 'banner'); ?>
<!--#end of Main Banner -->

<!-- include breadcrumbs -->
<?php get_template_part('content/ypg-yag/fellowship', 'breadcrumb'); ?>
 
<!-- Content -->
<section id="content" class="clearfix">
	<div class="wrapper">
	<!-- Inside Page Tabs -->
		<div class="tabs inside-nav clearfix">
			<?php get_template_part('content/ypg-yag/sub', 'page-tabs'); ?>
		</div><!--#end of Inside Page Tabs -->
		
		<section class="col-full wrapper clearfix">			
			<?php echo the_content(); ?>
			
			<!-- Testimonies -->
			<ul class="grid-testimonies match-height load-next clearfix">
			<?php
			
			$args = array(
				'post_type' 		=> 'page',
				'post_status'		=> 'publish',
				'child_of'			=> get_the_ID(),
				'post_parent' 		=> get_the_ID(),
				'depth' 			=> 1,
				'orderby'			=> 'menu_order',
				'order'				=>'DESC',
				'posts_per_page' 	=> -1
			);

			$query	= new WP_Query($args);
			$count_post = $query->found_posts;
			
			if($query->have_posts()) {
				
				while($query->have_posts()) {
					$query->the_post();
					
					setup_postdata($post);
					$post_ref = $post;
					
					// don't use get_field on testimonies because it's taking time to get the data
					// but instead I use the WordPress get_post_meta and get_post to supply to $testimony
					$testimony_ID 		= get_post_meta($post_ref->ID, 'testimonies' );
					$testimony 			= get_post( $testimony_ID[0] );
					
					$quote				= get_field('quote', $testimony->ID);					
					$author 			= get_field('author', $testimony->ID);

					$big_picture 		= get_field('big_picture', $testimony->ID);
					$overview_image 	= get_field('overview_image', $testimony->ID);

					
					if($overview_image) {
						$overview_image_url = ($overview_image) ? $overview_image['url'] : '' ;
					} else {
						$overview_image_url = get_field('overview_image_url', $testimony->ID);
					}
					
					?>
					<li class="box-img g-<?php echo ($big_picture) ? 'two' : 'four'?>">
						<a href="<?php echo get_permalink($post_ref->ID); ?>">
							<figure>								
								<?php if($overview_image_url) { ?>
								<img src="<?php echo $overview_image_url ?>" alt="" />
								<?php
								}
								?>
								<figcaption>
									<h3><?php echo get_the_title(); ?></h3>
									<p><em><?php echo ($author) ? 'by ' . $author : '' ?></em></p>
									<?php 
									if($quote && $big_picture) {
									?>
										<blockquote>
										<p>
										<?php 
										echo get_quote_charlength(144, strip_tags($quote, ''));								
										?>
										</p>
										</blockquote>
									<?php
									echo 'Read more';
									}
									?>									
								</figcaption>
							</figure>
						</a>
					</li>			
				<?php
				} wp_reset_postdata();
			} wp_reset_query();
			?>										
			</ul><!--#end of Testimonies -->
			
			<?php			
			if(intval($count_post) > 20) {
			?>
				<div class="add-separator clearfix">
					<a id="btn-load-next" class="btn-primary" href="#">Load more <i class="ion-android-add"></i></a>
				</div>
			<?php
			}
			?>	
		</section><!--#end Full-width Column -->
	</div>
</section><!--#end of Content -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>