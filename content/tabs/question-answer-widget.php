<?php global $sub_page; ?>

<section class="col-full clearfix">
	<h2><?php echo get_sub_field('title', $sub_page->ID); ?></h2>	
	<!-- Side Column -->
	<section class="col-side float-left">
		<?php echo get_sub_field('content_left', $sub_page->ID); ?>
	</section><!--#end of Side Column -->
	
	<!-- Main Column -->
	<section class="col-main float-right">
		<!-- Generic Slider -->
		<div class="slider-generic">			
			<?php
			if(have_rows('question_repeater', $sub_page->ID)) {
				while(have_rows('question_repeater', $sub_page->ID)) {
					the_row();
				?>
					<div>
						<div class="box-generic no-margin txt-center">
							<h3 class="font-normal"><?php echo get_sub_field('question'); ?></h3>
						</div>
					</div>					
				<?php
				}
			}			
			?>
			
		</div><!--#end of Generic Slider -->
		<?php echo get_sub_field('content_right', $sub_page->ID); ?>
		
	</section><!--#end of Main Column -->	
</section>
