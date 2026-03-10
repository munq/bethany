<?php
$banner_image 		= get_field('banner_image', $post->post_parent);
$banner_image_url 	= ($banner_image) ? $banner_image['url'] : '';
$tag_line 			= get_field('tag_line', $post->post_parent);
$day				= get_field('day', $post->post_parent);
$time				= get_field('time', $post->post_parent);
$time 				= explode(' ', $time);
$locations			= get_field('locations', $post->post_parent);
$location_sc 		= '';
$age_group			= get_field('age_group', $post->post_parent);
?>

<section id="banner-main" class="<?php echo (!$banner_image) ? 'banner-hidden' : ' ' ?>" style="background-image:url(<?php echo $banner_image_url; ?>);">
	<div class="wrapper">
		<h1 class="title"><?php echo get_field('custom_page_title', $post->post_parent) ? get_field('custom_page_title',$post->post_parent): get_the_title($post->post_parent); ?>
		<?php echo ($tag_line) ? '<small>' . $tag_line . '</small>' : ''; ?>
		</h1>
		<?php 
		if($day) {
			?>
			<div class="box-banner">
				<div class="box-banner-top clearfix">
					<span><span class="numeric"><?php echo $time[0]; ?></span><?php echo $time[1]; ?></span>
					<span><?php echo $day; ?></span>
				</div>
				<div class="box-banner-content">
					<ul class="info-list">						
						<li class="location">
						<?php
						if(have_rows('locations', $post->post_parent)) {
							while(have_rows('locations', $post->post_parent)) {
								
								the_row();
								$location	= 	get_sub_field('location');
								$location = str_replace('<br>', ' ', $location);
								
								$location_sc[] = $location;
							}
							echo implode(', ', $location_sc);
						}
						?>
						</li>
						<li class="age"><?php echo $age_group ?></li>
					</ul>
				</div>
			</div>			
		<?php
		}
		?>		
	</div>
</section><!--#end of Main Banner -->