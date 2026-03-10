<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: News and Events
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
<!--#end of Main Banner -->

<!-- include breadcrumbs -->
<?php get_template_part('content/common', 'breadcrumb'); ?>	

<!-- Content -->
<section id="content" class="clearfix">
	<div class="wrapper">
		<div class="tabs clearfix">
			<ul class="nav-tabs clearfix sticky">
				<li><a href="#events">Events</a></li>
				<li><a href="#news">News</a></li>
			</ul>
			
			<!-- Tab #1 -->
			<?php include get_template_directory() . '/content/news-events/events.php'; ?>
			
			<!-- Tab #2 -->
			<?php include get_template_directory() . '/content/news-events/news.php'; ?>			
			
		</div><!--#end of Inside Page Tabs -->	
	</div>
</section><!--#end of Content -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>