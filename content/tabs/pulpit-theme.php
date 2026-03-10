<?php 
// START
// Get the "Get Overview Content" sub field from Post Object
// Show the "Get Content"
?>

<?php
$header				= get_field('pulpit_theme_header', 'option');
$pulpit_theme_image	= get_field('pulpit_theme_image', 'option');
$image_url			= ($pulpit_theme_image) ? $pulpit_theme_image['url'] : '' ;
$image_caption		= ($pulpit_theme_image) ? $pulpit_theme_image['caption'] : '' ;
$read_more_link		= get_field('read_more_link', 'option');

echo $header;		
?>
<section class="col-full clearfix">		
	
	<?php 
	if($image_url) { 
	?>
	<!-- Overlapping Image Column -->
	<section class="col-overlap-img float-left">
		<div class="box-img">
			<img src="<?php echo $image_url; ?>" width= "588" height="307" />
		</div>
		<?php echo ($image_caption) ? '<p>' . $image_caption . '</p>' : ''; ?>
		<p><?php echo ($read_more_link) ? '<a href="' . $read_more_link . '">View more wall displays</a>' : '&nbsp;' ; ?></p>
	</section><!--#end of Overlapping Image Column -->
	<?php
	}
	?>	
	
	<!-- Overlapping Banner Column -->
	<section class="col-overlap-banner float-right">
		<!-- Carousel - Memory Verse -->
		<section id="carousel-memory-verse">
			<div class="carousel-memory-verse">			
			<?php
			$args = array (
				'post_type' 	=> 'memory_verses',
				'post_status'	=> 'publish',
				'meta_key' 		=> 'verse_date'
				'orderby'		=> 'meta_value'
			);
			
			$query	= new WP_Query($args);
			
			if($query->have_posts()) {
				
				while($query->have_posts()) {
					
					$query->the_post();
					setup_postdata($post);
					$memory_verse = $post;
					
					$verse_date 	= get_field('verse_date', $memory_verse->ID);
					$verse_quote 	= get_field('verse_quote', $memory_verse->ID);
					$text_refrence 	= get_field('text_refrence', $memory_verse->ID);
					$verse_content 	= get_the_content($memory_verse->ID);
				?>
				
				<div>
					<div class="box-banner">
						<div class="box-banner-top clearfix">
							<span>Memory Verse</span>
							<span><span class="numeric"><?php echo date('d', strtotime($verse_date)); ?></span> <?php echo date('F', strtotime($verse_date)); ?> <span class="numeric"><?php echo date('Y', strtotime($verse_date)); ?></span></span>
						</div>
						<div class="box-banner-content">
							<?php echo "<strong>" . $memory_verse->post_title . "</strong>"; ?>
							<blockquote>
								<p><?php echo $verse_quote; ?></p>
								<span><?php echo $text_refrence; ?></span>
							</blockquote>
							<?php 
							if($verse_content) {
							?>
								<p class="txt-right"><a class="lightbox-styled" href="#memory-verse_<?php echo $memory_verse->ID; ?>">Read more</a></p>
							<?php
							}
							?>
						</div>
					</div>
					
					<!-- Lightbox - Memory Verse -->
					<div class="lightbox-content" id="memory-verse_<?php echo $memory_verse->ID; ?>">
						<div class="wrapper">
							<h2>Memory Verse</h2>
							<h3>
								<small><?php echo date('d F Y', strtotime($verse_date)) ?></small><br/>
								<?php echo $verse_author; ?>
							</h3>
							<!-- Full-width Column -->
							<section class="col-full clearfix">
								<blockquote>
									<p><?php echo $verse_quote; ?></p>
								</blockquote>
								<?php echo apply_filters('the_content', $verse_content) ; ?>
							</section><!--#end of Full-width Column -->
						</div>
					</div><!--#end of Lightbox - Memory Verse -->
					
				</div>
				<?php
				} wp_reset_postdata();
			
			} 
			?>					
			</div>
		</section><!--#end of Carousel - Memory Verse -->
	</section><!--#end of Overlapping Banner Column -->
</section><!--#end of Full-width Column -->
	<hr/>

