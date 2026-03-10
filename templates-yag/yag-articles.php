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
			<?php the_content(); ?>
			<div class="list-articles with-line" id="listing">
				
				<?php $series_name = get_field('series_name'); ?>				
				
				<?php
				$args = array(
					'post_type'      => 'page',
					'post_status'    => 'publish',
					'post_parent'    => get_the_ID(),
					'orderby'        => 'meta_value',
					'meta_key'       => 'article_date',
					'order'          => 'DESC',
					'posts_per_page' => -1
				);

				$query = new WP_Query($args);
				if ($query->have_posts()) {
					while ($query->have_posts()) {
						$query->the_post();
						
						$article_title  = get_the_title();
						$article_date   = get_field('article_date');
						$text_reference = get_field('text_reference');
						$author         = get_field('author');
						
						if (get_field('notes')) {
							$notes     = get_field('notes');
							$notes_url = wp_get_attachment_url($notes['id']);
						} else {
							$notes_url = get_field('notes_url');
						}						
				?>
				<div class="clearfix">
					<p><?php echo ($article_date) ? date('d M Y', strtotime($article_date)) : ''; ?><br/>
					<strong><a href="<?php the_permalink(); ?>"><?php echo esc_html($article_title); ?></a></strong><br/>
					<?php echo ($text_reference) ? 'Text: ' . esc_html($text_reference) : ''; ?></p>
					
					<p><?php echo ($author) ? 'by ' . esc_html($author) . ' | ' : ''; ?> <a href="javascript:;"><?php echo esc_html($series_name); ?></a></p>
					
					<?php 
					generate_print_email_save(
						array(
							'url'       => get_permalink(),
							'notes_url' => esc_url($notes_url),
						)
					); 
					?>
				</div>
				<?php
					}
					wp_reset_postdata();
				}
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
