<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Poem Detail
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
		<p><?php include get_template_directory() . '/content/poems/browse-poems.php'; ?></p>
		<!-- Bible Study Resources -->
		<div id="bible-study-resources">			
			
			<!-- Full-width Column -->
			<section class="col-full wrapper clearfix">				
				<?php 
				
				$notes		= get_field('notes');
				$notes_url	= ($notes) ? wp_get_attachment_url( $notes['id'] ) : '';
				$text_reference = get_field('text_reference');
				$author = get_field('author');
					
				generate_print_email_save( 
					array(
						'url'	=> get_permalink(get_the_ID()),
						'class' => 'as-header',
						'notes_url' => $notes_url,
					)
				);
					
				echo '<h1>'. get_the_title() .'</h1>';
				
				the_content();
				
				echo $author;
				echo ($text_reference) ? ': '. $text_reference : ''; 
				
				?>			
				
			</section><!--#end of Full-width Column -->
			
			<!-- Full-width Column -->
			<section class="col-full wrapper clearfix">
				<div class="tags-wrapper clearfix"></div>
				
				<!-- Pagination - Prev-Next -->
				<?php
				$args_nav = array(
					'post_type' 	=> 'page', 
					'child_of'		=> $post->post_parent,
					'post_parent' 	=> $post->post_parent,
					'depth' 		=> 1, 
					'orderby' 		=> 'menu_order', 
					'order' 		=> 'ASC'
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
					<a class="jp-previous tooltip" href="<?php echo get_permalink($prevID); ?>">Prev<span class="tooltip-content"><?php echo get_the_title($prevID); ?></span></a>
				<?php 
				}
				if(!empty($nextID)) { ?>
					<a class="jp-next tooltip" href="<?php echo get_permalink($nextID); ?>">Next<span class="tooltip-content"><?php echo get_the_title($nextID); ?></span></a>
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
