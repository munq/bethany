<?php 
// START
// Get post object
// Get Content from post
// Display
?>

<!-- Full-width Column -->
<section class="col-full wrapper clearfix">
	<h2>Latest Message</h2>
	
	<!-- Full-width Column -->
	<section class="col-full clearfix">
		<!-- Slider - Messages -->
		<div class="slider-generic">
		<?php 
			
		$message_category	= get_sub_field('latest_message');
		
		$message_id = $message_category[0];
		
			
		$series_args = array(
			'post_type' 	=> 'page',
			'post_status'	=> 'publish',
			'child_of'		=> $message_id, 
			'post_parent' 	=> $message_id, 
			'depth' 		=> 1, 
			'orderby' 		=> 'meta_value', 
			'meta_key' 		=> 'start_date', 
			'order' 		=> 'DESC',
			'posts_per_page' => 1
		);
		
		$query	= new WP_Query($series_args);

		if($query->have_posts()) {
		
			while($query->have_posts()) {
			
				$query->the_post();
				setup_postdata($post);					
				$post_series = $post;
				
				$message_args = array(
					'post_type' 	=> 'page',
					'post_status'	=> 'publish',
					'child_of'		=> $post_series->ID, 
					'post_parent' 	=> $post_series->ID, 
					'depth' 		=> 1, 
					'orderby' 		=> 'meta_value', 
					'meta_key' 		=> 'article_date', 
					'order' 		=> 'DESC',
					'posts_per_page'	=> 1
				);
				
				$query_message = new WP_Query($message_args);
				
				if($query_message->have_posts()) {
					
					while($query_message->have_posts()) {
						$query_message->the_post();
						setup_postdata($post);					
						$post_message = $post;
						
						
						$messages			= get_field('message', $post_message->ID);			
						$article_date		= get_field('article_date', $messages->ID);
						$article_title		= get_field('article_title', $messages->ID);
						$quote				= get_field('quote', $messages->ID);
						$text_refrence		= get_field('text_refrence', $messages->ID);
						$speaker			= get_field('speaker', $messages->ID);
						$chairman			= get_field('chairman', $messages->ID);
						
						$bulletin			= get_field('bulletin', $messages->ID);
						$bulletin_url 		= ($bulletin) ? wp_get_attachment_url( $bulletin['id'] ) : '';
						
						$notes				= get_field('notes', $messages->ID);
						$notes_url			= ($notes) ? wp_get_attachment_url( $notes['id'] ) : '';			
						
						$video_url			= get_field('video_url', $messages->ID);
						$video_cover_image	= get_field('video_cover_image', $messages->ID);			
						$cover_image_url 	= ($video_cover_image) ? $video_cover_image['url'] : '';		
						
						$audio_url			= get_field('audio_url', $messages->ID);
						
						?>	
						
						<div>
							<div class="box-generic clearfix">					
								<!-- Video -->
								<?php 
								if($video_url) { ?>
								<div class="video-embed box-img">
									<img src="<?php echo ($cover_image_url) ? $cover_image_url : 'http://placehold.it/980x504/369/333&text=video' ;?>" />
									<div class="video-overlay">
										<span class="video-duration"></span>
									</div>
									<div class="video-iframe">
										<!--/* <iframe type="text/html" width="674" height="1080" frameborder="0" src="<?php echo $video_url; ?>" allowfullscreen></iframe> */ -->								
										<?php echo do_shortcode( '[video src="' . $video_url . '" width="866" height = "448" preload="none"]' ); ?>
									</div>
								</div><!--#end of Video -->
								<?php } ?>	
								
								<!-- Media Bar -->						
								<?php 
								if($audio_url) { 
								?>								
								<div class="media-player"></div>
								<div class="media-bar-wrp">
									<a class="media-bar jp-play clearfix" target="_blank" href="<?php echo $audio_url; ?>">
										<span class="media-bar-icon"><i class="ion-ios-play"></i></span>
										<span class="media-bar-title">Listen to audio</span>
										<span class="jp-progress">				
											<div class="jp-seek-bar">
												<div class="jp-play-bar"></div>
											</div>
										</span>
										<span class="media-bar-duration">
											<span class="jp-current-time"></span>
											<span class="jp-duration"></span>
										</span>
									</a>
									<div class="media-bar-pause jp-pause"></div>
								</div><!--#end of Media Bar -->	
								<?php 
								}
								?>
							
								<!-- Resource Download -->
								<div class="download-resource clearfix">
									<div class="resource">
										<h4><strong><?php echo $article_title; ?></strong>												
											<?php echo ($text_refrence) ? '<br/>Text: ' . $text_refrence : '' ?>
										</h4>											
										<dl class="clearfix">
										<?php 							
											echo ($speaker) ? '<dt>Speaker:</dt><dd>' . $speaker . '</dd>' : '';
											echo ($chairman) ? '<dt>Chairman:</dt><dd>' . $chairman . '</dd>' : '';
											?>
											<dt>Series:</dt>
											<dd><a href="<?php echo get_permalink($post_message->post_parent);?>"><?php echo get_the_title($post_message->post_parent);?></a></dd>
										</dl>											
										<p><?php echo date('d M Y', strtotime($article_date)); ?></p>
									</div>
									<ul class="nav-download clearfix">							
										<li class="bulletin <?php echo ($bulletin_url) ? '' : 'disabled'; ?>"><a target="_blank" href="<?php echo $bulletin_url;?>">Bulletin</a></li>						
									
										<li class="notes <?php echo ($notes_url) ? '' : 'disabled'; ?>"><a target="_blank" href="<?php echo $notes_url;?>">Notes</a></li>
										
										<li class="video <?php echo ($video_url) ? '' : 'disabled'; ?>"><a target="_blank" href="<?php echo $video_url;?>">Download Video</a></li>
										
										<li class="audio <?php echo ($audio_url) ? '' : 'disabled'; ?>"><a target="_blank" href="<?php echo $audio_url;?>">Download Audio</a></li>
									</ul>
								</div><!--#end of Resource Download -->
							</div>
						</div>

						
					<?php
					} wp_reset_postdata();										
				}
				
			} wp_reset_postdata();
		} wp_reset_query();						
		?>			
		</div><!--#end of Slider - Messages -->
		<div class="txt-center">
			<a href="<?php echo get_permalink($message_id); ?>">View past messages</a>
		</div>
	</section><!--#end of Full-width Column -->
</section><!--#nd of Full-width Column -->