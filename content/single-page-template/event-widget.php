<?php
// YAG YPG Event Widget		
$featured_event	= get_sub_field('featured_event');
if($featured_event->post_status == 'publish') {
?>
<section class="col-full wrapper clearfix">
<?php 
	$start_date		= get_field('start_date', $featured_event->ID);	
	$event_time		= get_field('event_time', $featured_event->ID);						
	$event_time 	= explode(' ', $event_time);
	$event_title	= get_the_title($featured_event->ID);
	$event_summary	= get_field('event_summary', $featured_event->ID);
	?>
	<div class="box-banner v2 box-generic full-width clearfix">
		<div class="box-banner-top clearfix">
			<span><span class="numeric"><?php echo $event_time[0]; ?></span><?php echo $event_time[1]; ?></span>
			<span><span class="numeric"><?php echo date('d', strtotime($start_date));?></span> <?php echo date('M', strtotime($start_date));?> <span class="numeric"><?php echo date('Y', strtotime($start_date));?></span></span>
		</div>
		<div class="box-banner-content">
			<h3><?php echo $event_title; ?></h3>
			<p><?php echo  $event_summary; ?> <a class="link-view-details" href="<?php echo get_permalink(get_page_by_path('news-events')->ID); ?>">View Event Details</a></p>
		</div>
	</div><!--#end of Full-width Event Banner -->

</section>
<?php
}
?>
