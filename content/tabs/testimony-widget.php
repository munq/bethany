<?php
global $sub_page;


// Start
// Loop through 'testimonies' repeater to get 'testimony' post object
// Get 'image' and 'author' field from 'testimony' post object
// Display post content
?>

<!-- Testimonies -->
 <ul class="grid-testimonies match-height load-next clearfix">
<?php

 $testimony_rows = get_sub_field( 'testimonies', $sub_page->ID );
 $rows_count = is_array( $testimony_rows ) ? count( $testimony_rows ) : 0;
 
 if(have_rows('testimonies', $sub_page->ID)) {
	
	while(have_rows('testimonies', $sub_page->ID)) {
		the_row();
		
		$testimony 			= get_sub_field('testimony');
		
		if ( ! is_object( $testimony ) || ! isset( $testimony->post_status ) || 'publish' !== $testimony->post_status )
			continue;
		
		$big 				= get_field('big_picture',$testimony->ID);
		$author 			= get_field('author',$testimony->ID);
		$quote 				= get_field('quote',$testimony->ID);
		$overview_image 	= get_field('overview_image', $testimony->ID);									
		$overview_image_url	= ( is_array( $overview_image ) && ! empty( $overview_image['url'] ) ) ? $overview_image['url'] : '' ;
		$article_date		= get_field('article_date', $testimony->ID );
		if ( '19700101' == $article_date )
			$article_date = '';
		
		?>		
		<li class="box-img g-<?php echo ($big) ? "two" : "four"; ?>">			
				<figure>
					<?php
					if( $overview_image_url) { ?>
						<img src="<?php echo $overview_image_url;?>" alt="" />
					<?php } ?>
					<figcaption>
						<h3><?php echo $testimony->post_title;?></h3>
						<p><em><?php echo ($author) ? 'by '. $author: ''?></em></p>
						<?php
						
						if ($quote && $big) {
							?>
							<blockquote>
								<p>
								<?php 
								echo get_quote_charlength(144, strip_tags($quote, ''));								
								?></p>
								<span></span>
							</blockquote>
						<?php 
						}
						?>
						
						<?php
						if($testimony->post_content) {
						?>
							<p><a class="lightbox-styled" href="#<?php echo $testimony->post_name; ?>">Read more</a></p>
						<?php
						}
						
						// check if user can edit the page/post, then show the edit link
						if ( current_user_can('edit_post', $testimony->ID) ) {
							edit_post_link('Edit this page', '<p>', '</p>', $testimony->ID);
						}
						?>
					</figcaption>
				</figure>
			<?php		
			if($testimony->post_content) {
			?>
				<!-- Lightbox - Memory Verse -->		
				<div class="lightbox-content" id="<?php echo $testimony->post_name; ?>">
					<div class="wrapper">				
						<h2><?php echo $testimony->post_title;?></h2>
						<h3>
							<?php 
							if ($article_date != '') {
							?>
								<small><?php echo date('j F Y', strtotime($article_date)); ?></small><br/>
							<?php
							}
							?>							
							
							<?php echo $author; ?>
						</h3>
						
						<!-- Full-width Column -->
						<section class="col-full clearfix">
						<?php
						// check if user can edit the page/post, then show the edit link
						if ( current_user_can('edit_post', $testimony->ID) ) {
							edit_post_link('Edit this page', '<p>', '</p>', $testimony->ID);
						}
						
						echo apply_filters('the_content', $testimony->post_content);
						?>
							
						</section><!--#end of Full-width Column -->
					</div>
				</div><!--#end of Lightbox - Memory Verse -->
			<?php
			}
			?>
		</li>		
		<?php
	}
 }
?>
</ul><!--#end of Testimonies -->
<?php
if( $rows_count > 20 ) {
?>
	
	<div class="add-separator clearfix">
		<a id="btn-load-next" class="btn-primary" href="#">Load more <i class="ion-android-add"></i></a>
	</div>
	
<?php
} 
?>
