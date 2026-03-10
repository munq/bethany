<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Page Listing
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */
 
get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post();

	$main_template_folder = _get_template_slug_name(get_the_ID());

	// Get the queried object and sanitize it
	$current_page = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );
	// Get the page slug
	$slug = $current_page->post_name;

	// Get the Main Nav Menu
	$term = get_term_by('slug', 'main-menu', 'nav_menu');
	$menu_id = $term->term_id;

	// Get the Main Nav Menu Items
	$items = wp_get_nav_menu_items( $menu_id );

	// Get the ID of the slug from items
	$parent_menu_id = 0;
	$sub_menus = array();
	foreach ( $items as $item ) {
		if ( ($item->post_name == $slug) && ($item->url == '#') ) {
			$parent_menu_id = $item->ID;
			break;
		}
	}

	// To get the sub menus of the slug
	foreach($items as $item) {
		if ( ($parent_menu_id == $item->menu_item_parent) ) {
			$sub_menus[] = array(
				'ID' => $item->ID,
				'title' => $item->title,
				'url' => $item->url
			);
		}
	}
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
			<!-- Full-width Column -->
			<section class="col-full wrapper clearfix">
				<h2><?php echo the_title(); ?></h2>
				
				<!-- Listing Section -->
				<section class="col-full grid-sitemap-sect clearfix">					
					<ul class="listing listing-sitemap clearfix">

					<?php

					$overview_image	= get_field('overview_image');
					$overview_image_url	= ($overview_image) ? $overview_image['url'] : '' ;
					$summary_content = apply_filters('the_content', get_field('summary_content'));
					$summary_content = $summary_content ? $summary_content : '';

					if( !empty($sub_menus) ) {
						foreach ($sub_menus as $lists) { ?>
							<li class="page-list-<?php echo $list['ID']; ?>">
								<a href = "<?php echo $lists['url'] ? $lists['url'] : "#"; ?>">
									<?php echo $lists['title'] ? $lists['title'] : "No Title"; ?>
								</a>
							</li>
							<?php
						}
					}
					else {
					?>
						<li class="page-list-emptyy">
							<span>No Available listings for this page</span>
						</li>
					<?php
					}
					?>
					<?php if ( !empty($overview_image_url) || !empty($summary_content) ) { ?>
						<li>
							<figure class="box-img">
								<?php if ( !empty($overview_image_url) ) { ?>
								<img src="<?php echo $overview_image_url; ?>" />
								<?php } ?>
								<?php if ( !empty($summary_content) ) { ?>
								<figcaption>
									<h3><?php echo the_title(); ?></h3>
									<p><?php echo apply_filters('the_content', get_field('summary_content')); ?></p>
								</figcaption>
								<?php } ?>
							</figure>
						</li>
					<?php } ?>
					</ul>
				</section><!--#end of Listing Section -->
				
			</section><!--#end of Full-width Column -->
		</section><!--#end of Full-width Column -->
</section><!--#end of Content -->

<?php endwhile; endif; ?>

<?php get_footer(); ?>