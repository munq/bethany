<section class="col-full wrapper clearfix">
<!-- Gallery -->
<ul class="grid-gallery g-three match-height clearfix">
<?php 
// START
// Loop "gallery_repeater" from $sub_page
// Get the 'gallery' sub field from $sub_page
// Show the image from 'gallery'

	$rows_count = count(get_sub_field('gallery_repeater'));
	
	if(have_rows('gallery_repeater'))
	{
		$gallery_count = 0;
		// Loop Gallery Reapeater Post Object
		
		while(have_rows('gallery_repeater'))
		{
			the_row();
			
			$gallery = get_sub_field('gallery');
			
			if($gallery->post_status != 'publish')
				continue;
			
			$featured_image_url 	= 	get_post_thumbnail_id($gallery->ID);			
			$featured_image			=	wp_get_attachment_url( $featured_image_url );
			
			$gallery_title			= 	get_the_title($gallery->ID);			
			$images 				=  	get_field('image_gallery', $gallery->ID);
			//echo $gallery_title."<br>";

?>
			<li>
				<figure class="box-img">				
					<img src="<?php echo $featured_image; ?>">
					<figcaption>
						<div><?php echo $gallery_title ;?></div>
						<a class="lightbox" href="#gallery-<?php echo $gallery_count ;?>"></a>
					</figcaption>
				</figure>
				
				<!-- Lightbox -->
				<div class="lightbox-content" id="gallery-<?php echo $gallery_count ;?>">
					<div class="gallery-lightbox">
						<h1 class="gallery-header"><?php echo $gallery_title ;?></h1>
						
						<!-- Gallery Slider -->
						<div class="slider-gallery">				
						<?php		
						if($images)
						{
							foreach($images as $image)
							{
							?>
								<div>
									<div class="box-img">
										<img src="<?php echo $image['url']; ?>" alt="" width="800" height="450"/>
									</div>
									<p class="gallery-caption"><?php echo $image['description']; ?></p>
								</div>
							<?php
							}
						}
						?>					
						</div><!--#end of Gallery Slider -->
						
						<!-- Gallery Carousel -->
						<div class="carousel-gallery">
						<?php
							if($images)
							{
								foreach($images as $image)
								{
								?>
									<div><img src="<?php echo $image['url']; ?>" alt="" /></div>
						<?php	}
								
							} 
						?>
						</div><!--#end of Gallery Carousel -->
					</div>
				</div><!--#end of Lightbox -->
			</li>
<?php
		$gallery_count++;
	}
}
?>	
</ul><!--#end of Gallery -->

<?php
//if( $rows_count > 20 ) {
?>
<!-- /* <div class="add-separator clearfix">
	<a id="btn-load-next" class="btn-primary" href="#">Load more <i class="ion-android-add"></i></a>
</div> */ -->

<?php
//} 
?>

</section>