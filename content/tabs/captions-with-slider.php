<article>
<?php 
global $sub_page;

// START
// Loop "caption_with_images" from $sub_page
// Get the "caption" and "description" sub field from $sub_page
// Show the Caption and image in a slider
?>

<section class="col-full clearfix">
	<!-- Generic Slider -->
	<div class="slider-generic slider-photo-caption">
	<?php
		if(have_rows('caption_with_images', $sub_page->ID))
		{
			// Loop Caption Reapeater Post Object
			
			while(have_rows('caption_with_images', $sub_page->ID))
			{
				the_row();
				
				$image = get_sub_field('image');
				$image_url		= ($image) ? $image['url'] : '' ;	
				$description =  get_sub_field('description', $sub_page->ID);
			?>	
				<div>
					<div class="box-img"><img src="<?php echo $image_url; ?>" alt="" /></div>
					<?php 
					if($description) {
					?>
						<div class="box-generic">
							<p><?php echo $description; ?></p>
						</div>
						<?php
					} 
					?>
					
				</div>
			<?php
			}
		}
		// END
	?>
	</div>
</section><!--#end of Full-width Column -->
<hr/>

