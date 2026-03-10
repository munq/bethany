<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Ecards
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */
 
get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post();

$main_template_folder = _get_template_slug_name(get_the_ID());
?>

<!-- Main Banner -->
<?php get_template_part('content/common', 'banner'); ?>	

<!-- include breadcrumbs -->
<?php get_template_part('content/common', 'breadcrumb'); ?>	

<!-- Content -->
<section id="content" class="clearfix">
	<!-- Full-width Column -->
	<section class="col-full wrapper clearfix">		
		<?php the_content(); ?>
		
		<!-- Side Column -->
		<section class="col-side">
			<?php get_template_part('content/sidebar/right', 'find-ecard'); ?>
		</section><!--#end of Side Column -->
		
		<!-- Main Column -->
		<section class="col-main">
			<?php
			$args = array(
				'post_type' 		=> 'page',
				'post_status'		=> 'publish',
				'child_of'			=> get_the_ID(), 
				'post_parent' 		=> get_the_ID(),
				'depth' 			=> 1,
				'orderby' 			=> 'menu_order',
				'order'				=> 'DESC',
				'posts_per_page'	=> -1,
				'meta_query'	=> array(
						'relation' => 'AND',
						array(
						 'key' => '_wp_page_template',                 
						 'value' => 'templates-poems/ecards-items.php',
						 'compare' => '='
					   )
				)
			);
			
			if(isset($_GET) && !empty($_GET)) {
				$ecard_category 	= $_GET['ecard_category'];
				$search 			= $_GET['search'];
				
				if(!empty($ecard_category)) {
					$args['category_name'] = $ecard_category;
				}
				
				if(!empty($search)) {
					$args['post_title_like'] = $search;
					add_filter('posts_where', 'where_search_keyword_title_like', 10, 2);
					add_filter('posts_join','join_search_keyword');
				}
				
			}
			
			$query	= new WP_Query($args);

			//echo "Last SQL-Query: {$query->request}";

			// remove the attached filter for WP_Query
			if (!empty($search)) {
				remove_filter('posts_where', 'where_search_keyword_title_like');
				remove_filter('posts_join', 'join_search_keyword');
			}

			$count_post = $query->found_posts;
			
			if($query->have_posts()) { ?>
				<!-- Gallery -->
				<ul class="grid-testimonies g-three match-height load-next clearfix">
				<?php while($query->have_posts()) {
					$query->the_post();
					setup_postdata($post);
					$post_ecards = $post;
					
					$featured_image_url = wp_get_attachment_url(get_post_thumbnail_id($post_ecards->ID));
					?>
					<li class="box-img"><a href="<?php echo get_permalink($post_ecards->ID); ?>">
						<figure>
							<img src="<?php echo $featured_image_url; ?>" alt="" />
							<figcaption>
								<p><?php echo get_the_title($post_ecards->ID); ?></p>
							</figcaption>
						</figure>
					</a></li>
				<?php
				} wp_reset_postdata(); ?>
				</ul>
				<!--#end of Gallery -->

			<?php } wp_reset_query();

			if ($count_post == 0) { ?>
				<p>There were no results found. Please try another search term or category.</p>
			<?php } ?>

			
			<?php			
			if(intval($count_post) > 20) {
			?>
				<div class="add-separator clearfix">
					<a id="btn-load-next" class="btn-primary" href="#">Load more <i class="ion-android-add"></i></a>
				</div>
			<?php
			}
			?>			
		</section><!--#end of Main Column -->
	</section> <!--#end of Full-width Column -->
</section><!--#end of Content -->

<?php endwhile; endif; ?>

<?php get_footer(); ?>


