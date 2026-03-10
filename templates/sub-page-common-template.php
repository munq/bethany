<?php if (!defined('ABSPATH')) die('No direct script access allowed!'); 
if (!defined('BETHANYSUBPAGE')) wp_redirect(site_url());
global $main_template_folder;
global $sub_page;
?>

<?php 
// check if user can edit the page/post, then show the edit link
if ( current_user_can('edit_post', $sub_page->ID) ) {
	edit_post_link('Edit this page', '<p>', '</p>', $sub_page->ID);
}
?>

<?php
	
	if(have_rows('widgets', $sub_page->ID))
	{
		while(have_rows('widgets', $sub_page->ID))
		{			
			the_row($sub_page->ID);
			
			// get the row layout.
			$layout_name = get_row_layout();
			
			// Replace the layout _ to - to match your content file Eg: free_html to free-html.
			$layout_name = str_replace('_', '-', $layout_name);
						
			// get the content file 
			// $content_file 	= get_template_directory() . "/content/{$main_template_folder}/{$layout_name}.php";
			
			// get the common content file 
			$common_file 	= get_template_directory() . "/content/tabs/{$layout_name}.php";
			 
			if (file_exists($common_file)) {
				include $common_file;
			}
			else { // else include the default file
			
				include get_template_directory() . "/content/common-default.php";
			}
			
		}
	}
	?>
	
