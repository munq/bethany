<?php
// Start
// Loop through 'themes' repeater custom field of sub_page
// Get the 'theme' post object
// Display the theme title
// Loop through 'lessons' repeater in 'theme' post object
// Display Lesson


?>	
<!-- Full-width Column -->
<section class="col-full wrapper clearfix">
	<?php
	$theme_title		=	get_sub_field('theme_title');
	$theme_description	=	get_sub_field('theme_description');
	?>
	<h2 class="h2-lead"><?php echo $theme_title; ?></h2>
	<h2><?php echo $theme_description; ?></h2>
	
	<?php
	if(have_rows('themes_repeater')) {
		while(have_rows('themes_repeater')) {
			the_row();
			
			$title			=	get_sub_field('title');			
			$theme_date		= 	get_sub_field('theme_date');
			$theme_author	= 	get_sub_field('theme_author');	
			$content		= 	get_sub_field('content');	
			?>	
			<div class="list-lesson-simple with-line">
				<div class="cal-date-event">
					<div class="cal-date-event">
						<div class="date">
							<span><?php echo ($theme_date)? date('d',strtotime($theme_date)) : '' ; ?></span>
							<span><?php echo ($theme_date)? date('M',strtotime($theme_date)) : ''  ?><br/><?php echo ($theme_date)? date('Y',strtotime($theme_date)) : '' ;?></span>
						</div>
					</div>
					<div class="event">						
						<?php
						$content = apply_filters('the_content', $content);
						echo $content;
						?>
						<?php echo ($theme_author) ? '<em>' . $theme_author . '</em>' : ''; ?>						
					</div>
				</div>
			</div>
		<?php
		}
	}
	?>
	
</section><!--#nd of Full-width Column -->

		