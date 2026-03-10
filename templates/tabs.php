<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Tabs Template
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */

// this global var is used for preventing user to access sub pages directly
if (!defined('BETHANYSUBPAGE')) define('BETHANYSUBPAGE', 1);

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
	<div class="wrapper">
		<!-- Full-width Column -->
		<?php
		if(the_content()) {
		?>
			<section class="col-full clearfix">
				<h2></h2>
				<?php echo the_content(); ?>
				<p>&nbsp;</p>
			</section><!--#end of Full-width Column -->
		<?php
		}
	
		$slug = get_post_field( 'post_name', get_post() );
		?>		
		
		<!-- Inside Page Tabs -->
		<div class="tabs clearfix tabs-<?php echo $slug; ?>">		
		<?php 
		get_template_part('content/common', 'tabs'); 		
		
		$sub_pages = get_pages(
			array(
				'child_of' => get_the_ID(),
				'sort_column' 	=> 'menu_order',
			    'number' => 20,
			)
		);
	    
       
$args = array(
	'post_parent' => get_the_ID(),
	'post_type'   => 'page',
	'numberposts' => -1,
	'post_status' => 'publish',
	'orderby' => 'menu_order',
	'order' => 'ASC'
);
$sub_pages = get_children( $args ); 

		foreach($sub_pages as $sub_page) {
			$template_file = get_page_template_slug( $sub_page->ID );			
			?>			
			<!-- Tabs -->
			<div id="<?php echo '_' . $sub_page->post_name; ?>">
				<section class="col-full clearfix">	
					<h2><?php echo $sub_page->post_title; ?></h2>
					<?php					
					if (is_file(get_template_directory() . '/' . $template_file)) {
						include get_template_directory() . '/' . $template_file;
					}
					else {
						include get_template_directory() . '/templates/sub-page-default.php';
					}
					?>
				</section>
			</div> <!-- End of Tab -->
		<?php
		}
		?>
		</div><!--#end of Inside Page Tabs -->
	</div>
</section><!--#end of Content -->

<?php endwhile; endif; ?>

<?php get_footer(); ?>
