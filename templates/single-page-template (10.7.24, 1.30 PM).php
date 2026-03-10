<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Single Page Template
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
	<?php
	if(have_rows('widgets'))
	{
		while(have_rows('widgets'))
		{		
			the_row($sub_page->ID);
			
			// get the row layout.
			$layout_name = get_row_layout();
			
			// Replace the layout _ to - to match your content file Eg: free_html to free-html.
			$layout_name = str_replace('_', '-', $layout_name);
						
			// get the content file 
			$content_file 	= get_template_directory() . "/content/single-page-template/{$layout_name}.php";
			
			if (file_exists($content_file)) // if the content file exists include it.
			{
				include $content_file;
			}
			else // else include the default file
			{
				include get_template_directory() . "/content/common-default.php";
			}
		}
	}
	?>
</section><!--#end of Content -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>