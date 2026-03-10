<?php
global $sub_page;

// Start
// Loop through 'themes' repeater custom field of sub_page
// Get the 'theme' post object
// Display the theme title
// Loop through 'lessons' repeater in 'theme' post object
// Display Lesson

?>
<!-- Accordion -->

<?php
$has_accordion	= get_sub_field('has_accordion', $sub_page->ID);
if($has_accordion) {
?>
<div class="accordion">
<?php
	if( have_rows( 'themes', $sub_page->ID ) ) {

		while(have_rows('themes', $sub_page->ID)) {		
			the_row();
			$theme_title = get_sub_field('theme_title');
			?>
			<h3><?php echo $theme_title; ?></h3>
			<div>
				<div class="list-lesson-simple with-line">
				<?php
				if( have_rows('lessons')) {

					while(have_rows('lessons')) {
						the_row();
						
						$lesson_title = get_sub_field('lesson_title');
						$lesson_date = get_sub_field('lesson_date');
						$author 	 = get_sub_field('author');
						$lesson_content 	 = get_sub_field('lesson_content');
						?>
							<div class="cal-date-event match-height">
								<div class="date">
									<span><?php echo date('d',strtotime($lesson_date)); ?></span>
									<span><?php echo date('M',strtotime($lesson_date)) .'<br />' . date('Y',strtotime($lesson_date)); ?></span>
								</div>
								<div class="event">
									<h3><?php echo $lesson_title; ?></h3>
									<?php if($lesson_content) { ?>							
									<?php echo apply_filters('the_content', $lesson_content);?>
								<?php } ?>
								</div>
							</div>
					<?php
					}
				}
				?>	
				</div>
			</div>
		<?php	
		}
	}
	?>
</div><!--#end of Accordion -->
<?php 
}
else {
	?>
	<div class="list-lesson-simple with-line">
	<?php
	if(have_rows('themes', $sub_page->ID)) {
		$chkmon = '';
		$chkyr = '';
		while(have_rows('themes', $sub_page->ID)) {
			
			the_row();
			
			$theme_title = get_sub_field('theme_title');
			$theme_date = get_sub_field('theme_date');		
			$month 	= date('M',strtotime($theme_date));
			$yr 	= date('Y',strtotime($theme_date));			
			?>
			<div class="cal-date-event match-height">
				<div class="date">
					<strong>
					<?php
					if($chkmon != $month) {
						$chkmon = $month;
						echo $month.' '.$yr;  				
					}?>
					</strong>
				</div>
				<div class="event"><?php echo $theme_title;?></div>
			</div>
			<?php
		}
	}
	?>
	</div>		
<?php
}

