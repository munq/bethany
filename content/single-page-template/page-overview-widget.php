<article>
<?php 
// START
// Loop "get_overview_content_repeater"
// Get the "Get Overview Content" sub field from Post Object
// Show the "Get Overview Content"
?>

	<?php
	$has_schedule = get_sub_field('has_schedule');
	
	if($has_schedule)
	{		
		if(have_rows('get_overview_content_repeater')) {		
		?>
		<section class="col-full wrapper clearfix">
			<ul class="grid-headline g-two clearfix">
			<?php
			while(have_rows('get_overview_content_repeater')) {			
				the_row();
					
				$overview_content 	= 	get_sub_field('overview_content');				
				$page_title 		= 	(get_field('custom_page_title', $overview_content->ID)) ? get_field('custom_page_title', $overview_content->ID): get_the_title($overview_content->ID);		
				$summary_content	= 	get_field('summary_content', $overview_content->ID);
				$overview_image		= 	get_field('overview_image', $overview_content->ID);
				$overview_image_url	=	($overview_image) ? $overview_image['url'] : '' ;						
				$day				= 	get_field('day', $overview_content->ID);
				$time				= 	get_field('time', $overview_content->ID);
				$age_group			= 	get_field('age_group', $overview_content->ID);
				?>	
				<li>
					<div class="box-banner">
						<div class="box-banner-top clearfix">
							<span><?php echo $page_title; ?></span>
						</div>
						<div class="box-banner-content">
							<?php echo apply_filters('the_content', $summary_content); ?>
							<p><a class="link-txt" href="<?php echo get_permalink($overview_content->ID); ?>">Read more</a></p>
							<?php 
							if($day) {
							?>
								<hr/>
								<ul class="c-two clearfix">
									<li>
										<ul class="info-list">
											<?php echo ($time) ? '<li class="time">' . $time . '</li>' : '' ; ?>
											<?php echo ($day) ? '<li class="day">' . $day . '</li>' : '' ; ?>
											<?php echo ($age_group) ? '<li class="age">' . $age_group . '</li>' : '' ; ?>
										</ul>
									</li>
									<li>
										<ul class="info-list">
											<?php
											if(have_rows('locations', $overview_content->ID)) {
												while(have_rows('locations', $overview_content->ID)) {
													
													the_row();												
													$location	= 	get_sub_field('location');												
												?>
													<li class="location"><?php echo $location; ?></li>
												<?php
												}
											}
											?>										
										</ul>
									</li>
								</ul>
							<?php
							}
							?>							
						</div>
					</div>
				</li>							
				<?php							
				}
				?>			
			</ul><!--#end of Headline -->
		</section>	
		<?php	
		}
	}
	else {
		
		if(have_rows( 'get_overview_content_repeater')) {	
		$_count = 0;		
			while( have_rows( 'get_overview_content_repeater') ) {
			
				the_row();			
				$overview_content 	= 	get_sub_field('overview_content');			
				$page_title 		= 	(get_field('custom_page_title', $overview_content->ID)) ? get_field('custom_page_title', $overview_content->ID): get_the_title($overview_content->ID);
				$summary_content	= 	get_field( 'summary_content', $overview_content->ID);
				$overview_image		= 	get_field( 'overview_image', $overview_content->ID);			
				$overview_image_url	=	($overview_image) ? $overview_image['url'] : '' ;
				?>	
				
				<section class="col-full wrapper clearfix">
					<!-- Overlapping Banner Column -->
					<section class="col-overlap-banner float-<?php echo ($_count % 2 == 0 ) ? 'left' : 'right'; ?>">
						<div class="box-banner">
							<div class="box-banner-top clearfix">
								<div class="box-banner-header"><?php echo $page_title; ?></div>
							</div>
							<div class="box-banner-content">
								<?php echo apply_filters('the_content', $summary_content);?>
								<p class="txt-right"><a href="<?php echo get_permalink($overview_content->ID); ?>">Read more</a></p>
							</div>
						</div>
					</section><!--#end of Overlapping Banner Column -->
					
					<!-- Overlapping Image Column -->
					<section class="col-overlap-img float-<?php echo ($_count % 2 == 0 ) ? 'right' : 'left'; ?>">
						<div class="box-img">
							<img src="<?php echo $overview_image_url; ?>" />
						</div>
					</section><!--#end of Overlapping Image Column -->
				</section><!--#end of Full-width Column -->					
				
				<?php
				$_count++;
			}
		}
	
	}	

