<?php
global $sub_page;
?>

<!-- Side Column -->
<section class="col-side">
	<div class="box-side sticky-side-nav">
		<!-- Our Heritage Sticky Nav -->
		<ul class="nav-our-heritage clearfix">
		<?php
		if(have_rows('heritage_timeline', $sub_page->ID)) {
			$timeline_count = 1;
			while(have_rows('heritage_timeline', $sub_page->ID)) {
				the_row();
			?>
				<li><a href="#<?php echo $sub_page->post_name; ?>-<?php echo $timeline_count; ?>"><?php echo get_sub_field('title');?></a></li>
			<?php
				$timeline_count++;
			}
		}
		?>
		</ul><!--#end of Our Heritage Sticky Nav -->
	</div>
</section><!--#end of Side Column -->

<section class="col-main" id="our-heritage">
	<!-- Section -->
	<?php
	if(have_rows('heritage_timeline', $sub_page->ID)) {
		$timeline_count = 1;
		while(have_rows('heritage_timeline', $sub_page->ID)) {
			the_row();
			
			$title = get_sub_field('title');
			$year = get_sub_field('year');
			$html = apply_filters('the_content', get_sub_field('html'));
			?>
			<div class="our-heritage-section clearfix" id="<?php echo $sub_page->post_name; ?>-<?php echo $timeline_count; ?>">
				<!-- Overview Content -->
				<div class="our-heritage-overview">
					<div class="header-top">
						<h3><?php echo $title; ?></h3>
						<h4><?php echo $year; ?></h4>
					</div>
					<div class="box-generic">
						<?php echo $html; ?>
					</div>
				</div><!--#end of Overview Content -->
			
				<!-- Timeline -->
				<div class="our-heritage-timeline clearfix">
				<?php
					if(have_rows('timeline')) {			
						while(have_rows('timeline')) {
							the_row();
							
							$timeline_year 			= get_sub_field('timeline_year');
							$timeline_description 	= apply_filters('the_content', get_sub_field('timeline_description'));
							$timel_line_image 		= get_sub_field('timel_line_image');
							$image_url				= ($timel_line_image) ? $timel_line_image['url'] : '' ;
							$position_right			= get_sub_field('position_right');
							?>
							<!-- Sequence -->
							<div class="timeline-seq">
								<div class="<?php echo ($position_right) ? 'oh-right' : 'oh-left'?>">
									<div class="oh-item box-img">
										<h3><?php echo $timeline_year; ?></h3>
										<?php echo $timeline_description; ?>
										<?php 
										if($image_url) {
										?>
											<img src="<?php echo $image_url;?>" alt="" />
										<?php
										}
										?>
										
									</div>
								</div>
							</div>	
						<?php
						}
					}
					?>
					
				</div><!-- Timeline -->
		</div><!--#end of Section -->
		<?php
			$timeline_count++;
		}
	}
	?>
</section><!--#end of Main Column -->

