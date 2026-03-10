<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Wall Art Display
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */ 
 
get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<!-- Main Banner -->
<?php get_template_part('content/common', 'banner'); ?>	
<!-- End Main Banner -->

<!-- include breadcrumbs -->
<?php get_template_part('content/common', 'breadcrumb'); ?>

<!-- Content -->
<section id="content" class="clearfix">
	<!-- Full-width Column -->
	<section class="col-full wrapper clearfix">
		<h1>Wall Art Display</h1>
		<?php the_content(); 
				
		$featured_args = array(
		'post_type' 		=> 'page',
		'post_status'		=> 'publish',
		'child_of'			=> get_the_ID(),
		'order_by'			=> 'post_date',
		'order'				=> 'DESC',
		'depth' 			=> 2,
		'posts_per_page'	=> 1,
		'meta_query' => array(
			 'relation' => 'AND',					 
				array(
				'key' => 'featured_gallery',
				'value' => '1',
				'compare' => '=='
			  ),
			)
		); 
		
		$query_featured = new WP_Query($featured_args);
		
		if($query_featured->have_posts()) {
		?>
		<!-- Full-width Column -->
		<section class="col-full clearfix">
			<!-- Generic Slider -->
			<div class="slider-generic slider-photo-caption">
			<?php
			while($query_featured->have_posts()){
				$query_featured->the_post();
				setup_postdata($post);						
				$post_featured = $post;
				
				$images = get_field('wall_art_display_gallery', $post_featured->ID);
				
				if($images) {
					foreach($images as $image) {
					?>	
						<div>
							<div class="box-img">
								<img src="<?php echo $image['url']; ?>" alt="" />
							</div>
							<?php 							
							if($image['description']) { ?>
							<div class="box-generic">
								<p><?php echo $image['description'];?></p>
							</div>
							<?php 							
							} 							
							?>
						</div>
					<?php
					}
				}
			} wp_reset_postdata();
			?>
		</div><!--#end of Generic Slider -->
		</section><!--#end of Full-width Column -->
		<hr/>		
		<?php
		} wp_reset_query();				
		?>				
			
		
		<!-- Full-width Column -->
		<section class="col-full clearfix">
		<?php
		
		$args = array(
		'post_type' 		=> 'page',
		'post_status'		=> 'publish',
		'child_of'			=> get_the_ID(), 
		'post_parent' 		=> get_the_ID(),
		'order'				=> 'ASC',
		'orderby' 			=> 'menu_order',						
		'posts_per_page'	=> -1,
		);
		
		$query = new WP_Query($args);
		
		if($query->have_posts()) {
			while($query->have_posts()) {
				$query->the_post();
				
				$wall_theme = $query->post;
				
				$start_date 		= get_field('start_date', $wall_theme->ID);
				if ( '19700101' == $start_date )
					$start_date = '';
			?>
				<ul class="grid-gallery wall-display g-four match-height clearfix">
					<li class="box-gray">
						<div class="txt-center">
							<div class="date"><?php echo ($start_date) ? date('d M Y', strtotime($start_date)) : '' ; ?></div>
							<h3><?php echo get_the_title($wall_theme->ID); ?></h3>
							<!-- <p><a class="link-txt" href="javascipt:;">Browse this Gallery <i class="ion-ios-arrow-right"></i></a></p>-->
							
						</div>
					</li>
					
					<?php
					
					$args_wall_art_pages = array(
						'post_type' 		=> 'page',
						'post_status'		=> 'publish',
						'child_of'			=> $wall_theme->ID,
						'post_parent'  		=> $wall_theme->ID,
						'depth' 			=> 1, 
						'order'				=> 'ASC',
						'orderby' 			=> 'menu_order',						
						'posts_per_page'	=> 6,						
					);
					
					$wall_art_pages = new WP_Query($args_wall_art_pages);
					
					if($wall_art_pages->have_posts()) {
					
						$gallery_count = 0;
						while($wall_art_pages->have_posts()) {
							$wall_art_pages->the_post();
							
							$wall_art = $wall_art_pages->post;
							
							$featured_image 	= 	get_post_thumbnail_id($wall_art->ID);	
							$featured_image_url	=	wp_get_attachment_url( $featured_image );
							$images 			= get_field('wall_art_display_gallery', $wall_art->ID);
						?>
						
							<li>
								<figure class="box-img">
									<img src="<?php echo $featured_image_url; ?>" alt="" />
									<figcaption>										
										<div><?php echo get_the_title($wall_art->ID); ?></div>
										<a class="lightbox" href="#gallery-<?php echo $wall_art->ID; ?>"></a>
									</figcaption>
								</figure>
								
								<!-- Lightbox -->
								<div class="lightbox-content" id="gallery-<?php echo $wall_art->ID; ?>">
									<div class="gallery-lightbox">
										<h1 class="gallery-header"><?php echo get_the_title($wall_art->ID); ?></h1>
										
										<!-- Gallery Slider -->
										<div class="slider-gallery">
										<?php
										if($images) {
											foreach($images as $image) {
											?>
												<div>
													<div class="box-img">
														<img src="<?php echo $image['url']; ?>" alt="" />
													</div>
													<p class="gallery-caption"><?php echo $image['description']; ?></p>
												</div>
											<?php
											}
										}
										?>											
										</div><!--#end of Gallery Slider -->
										
										<!-- Gallery Carousel -->
										<div class="carousel-gallery">
										<?php
										if($images) {
											foreach($images as $image) {
											?>
												<div><img src="<?php echo $image['url']; ?>" alt="" /></div>
										<?php
											}
												
										} 
										?>
										</div><!--#end of Gallery Carousel -->
									</div>
								</div><!--#end of Lightbox -->
							</li>	
						<?php						
						$gallery_count++;
						}
					}
					
					?>					
					
				</ul><!--#end of Wall Display -->
			<?php
			}
		}			
		
		?>		
		
		</section><!--#end of Full-width Column -->			
	</section><!--#end of Full-width Column -->
</section><!--#end of Content -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>
