<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: YAG Articles
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
			<div class="list-articles with-line" id="listing">
				
				<?php $series_name = get_field('series_name');?>				
				
				<?php
				$args = array(
					'post_type' 	=> 'page',
					'post_status'	=> 'publish',
					'child_of'		=> get_the_ID(),
					'post_parent' 	=> get_the_ID(),
					'depth' 		=> 1, 
					'orderby' 		=> 'meta_value', 
					'meta_key' 		=> 'article_date', 
					'order' 		=> 'DESC',
					'posts_per_page' => -1
				);

			$query	= new WP_Query($args);
			if($query->have_posts()) {

				while($query->have_posts()) {	
					$query->the_post();
					setup_postdata($post);					
					$post_articles = $post;
					
					$article_title 	= get_the_title($post_articles->ID);
					$article_date	= get_field('article_date', $post_articles->ID);
					
					if ( '19700101' == $article_date )
						$article_date = '';
					
					$text_refrence 	= get_field('text_refrence', $post_articles->ID);
					$author 		= get_field('author', $post_articles->ID);
					
					if(get_field('notes', $post_articles->ID)) {
						$notes = get_field('notes', $post_articles->ID);
						$notes_url  = ($notes) ? wp_get_attachment_url( $notes['id'] ) : '';
					} else {
						$notes_url = get_field('notes_url', $post_articles->ID);
					}						
				?>
				<div class="clearfix">					
					<p><?php echo ($article_date) ? date('d M Y', strtotime($article_date)) : '' ; ?><br/>
					<strong><a href="<?php echo get_permalink($post_others->ID); ?>"><?php echo $article_title; ?></a></strong><br/>
					<?php echo ($text_refrence) ? 'Text: ' . $text_refrence : ''; ?></p>
					
					<p><?php echo ($author) ? 'by ' . $author.' | ' : ''; ?> <a href="javascript:;"><?php echo $series_name; ?></a></p>
					
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
			<!-- Pagination -->
			<div class="pagination-wrp">
				<div class="pagination"></div>
			</div><!--#end of Pagination -->
		</section><!--#end of Full-width Column -->
	</div>
</section><!--#end of Content -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>