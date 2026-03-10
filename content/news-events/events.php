<div id="events">
	<!-- Full-width Column -->
	<section class="col-full clearfix">
		<h1>Featured Events</h1>
		
		<!-- Featured Events -->
		<ul class="grid-events g-two match-height clearfix">
			
			<?php
			
			$time_today = current_time( 'timestamp' );
			$args	= array(				
				'post_type'		=> 'Event',
				'post_status'	=> 'publish',				
				'orderby'		=> 'start_date',
				'order'			=> 'ASC',
				'meta_key'		=> 'start_date',
				'posts_per_page'	=> 2,
				'meta_query' => array(
					 'relation' => 'AND',					 
					    array(
						'key' => 'featured_event',
						'value' => '1',
						'compare' => '=='
					  ),				 
					  array(
						'key' 		=> 'start_date',
						'value' 	=> date('Y-m-d', $time_today),
						'compare' 	=> '>=',
						'type'		=> 'DATE'
					  )
				)
			);
			
			$query	= new WP_Query($args);
			
			if($query->have_posts()) {
			
				while($query->have_posts()) {
				
					$query->the_post();
					setup_postdata($post);					
					$post_featured = $post;
					
					$title				= get_the_title($post_featured->ID);
					$start_date			= get_field('start_date', $post_featured->ID);					
					$featured_image 	= get_post_thumbnail_id($post_featured->ID);
					$featured_image_url	= wp_get_attachment_url( $featured_image );
					$location			= get_field('location', $post_featured->ID);
					$event_summary		= get_field('event_summary', $post_featured->ID);
					$event_time			= get_field('event_time', $post_featured->ID);
					$event_time 		= explode(' ', $event_time);
					$form_fields		= get_field('event_form_fields', $post_featured->ID);
					
					?>					
					<li class="box-img">
						<figure>
							<img src="<?php echo $featured_image_url; ?>" alt="" />
							<figcaption>
								<h3><?php echo $title; ?></h3>
								<ul class="c-two clearfix">
									<li>
										<ul class="info-list">
											<li class="day"><?php echo date('d M Y',strtotime($start_date)); ?></li>
										</ul>
									</li>
									<li>
										<ul class="info-list">
											<li class="time"><?php echo $event_time[0].$event_time[1]; ?></li>
										</ul>
									</li>
								</ul>
								<div class="buttons-wrp">
									<?php
									if($form_fields) {
									?>
									<a class="btn-primary btn-submit lightbox-styled" href="#register_event_<?php echo $post_featured->ID;?>">Register &nbsp;<i class="ion-ios-arrow-right"></i></a>
									<?php
									}
									?>	
									<a class="btn-primary btn-gray btn-add-to-calendar" id="" href="<?php echo get_permalink(get_page_by_path('download-ics')->ID).'?event_id='. $post_featured->ID; ?>" target="_blank"><i class="ion-calendar"></i>&nbsp; Add to Calendar</a>									
								</div>
							</figcaption>
						</figure>
					</li>
				<?php
				}
			}	wp_reset_postdata();		
			?>			
		</ul><!--#end of Featured Events -->
		
		<!-- Lightbox - Add to Calendar -->
		
		<!---------------------------------------------------- Events ---------------------------------------------------------->
		<div id="events">
			<div class="events-filter box-generic">
				<div class="events-filter-top filter-top clearfix">
					<div class="filter-view list-calendar clearfix">
						<a href="javascript:;"><i class="ion-navicon-round"></i> List View</a>
						<a href="javascript:;"><i class="ion-calendar"></i> Calendar View</a>
					</div>
					<div class="carousel-event">
						<div class="carousel-events">
							<?php include get_template_directory() . '/content/news-events/months.php';?>
						</div>
					</div>
					<div>
						<a class="btn-primary btn-expand-filter" href="javascript:;">Filter &nbsp;<i class="ion-ios-arrow-down"></i></a>
					</div>
				</div>
				<div class="filter-category events-filter-cat">
					<hr/>
					<a href="javascript:;" rel="">All</a>
					<a href="javascript:;" rel="children">Children</a>
					<a href="javascript:;" rel="young-people">Young People</a>
					<a href="javascript:;" rel="young-adults">Young Adults</a>
				</div>
			</div>
			
			<!-- Events Slider -->
			<div class="slider-events">
				<?php include get_template_directory() . '/content/news-events/calendar.php';?>
			</div><!--#end of Events Slider -->
		</div><!--#end of Events -->
		
		
	</section><!--#end of Full-width Column -->
</div><!--#end of Tab #1 -->