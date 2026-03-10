<?php
// Sunday School Event Widget
global $sub_page;
$featured_event	= get_sub_field('featured_event', $sub_page->ID);
if($featured_event->post_status == 'publish') {
	?>
	<section class="col-side">
		<div class="box-side">
		<?php		
		
		$start_date		= get_field('start_date', $featured_event->ID);	
		$event_time		= get_field('event_time', $featured_event->ID);						
		$event_time 	= explode(' ', $event_time);
		$event_title	= get_the_title($featured_event->ID);
		$event_summary	= get_field('event_summary', $featured_event->ID);
		?>
			
			<div class="box-side-top schedule clearfix">
				<span>
					<b><?php echo date('d',strtotime($start_date));?></b><br/>
					<i><?php echo date('M',strtotime($start_date)); ?></i>
				</span>
				<span><?php echo $event_time[0] . $event_time[1]; ?></span>
			</div>			
			<div class="box-side-content">
				<h3><?php echo $event_title; ?></h3>
				<p><?php echo  $event_summary; ?></p>
				<p><a href="<?php echo get_permalink(get_page_by_path('news-events')->ID); ?>">View Event Details</a></p>
			</div>			
		</div>
	</section>
 
	<section class="col-main"> 
	<?php 
	if(have_rows('free_html_repeater', $sub_page->ID)) {
		
		while(have_rows('free_html_repeater', $sub_page->ID)) {			
			the_row();
			echo get_sub_field('html');		
		}
	} 
	?>	  
	</section>
<?php
}
?>
