<?php

$load_wordpress  = $_SERVER['DOCUMENT_ROOT'];
include( '../../../../../wp-load.php');
if ( ! empty( $_GET )) {
	$cal_event_id 	= $_GET['event_id'];
	$event_id 		= get_post($cal_event_id);
	$event_title 	= $event_id->post_title	;
	
	$event_summary 	= get_field('event_summary', $event_id->ID);		
	$start_date		= date('Y-m-d', strtotime(get_field('start_date', $event_id->ID)));
	$end_date		= get_field('end_date', $event_id->ID);
	
	if ( '19700101' == $end_date )
		$end_date = '';
		
	if($end_date != '') {
		$end_date = date('Y-m-d', strtotime($end_date);
	}
	
	$event_time		= date('His', strtotime(get_field('event_time', $event_id->ID)));
	$location		= get_field('location', $event_id->ID);
	
	$messaje  = "BEGIN:VCALENDAR\r\n";
	$messaje .= "VERSION:2.0\r\n";
	$messaje .= "PRODID:PHP\r\n";
	$messaje .= "METHOD:REQUEST\r\n";
	$messaje .= "BEGIN:VEVENT\r\n";
	$messaje .= "DTSTART: " . $start_date . "T" . $event_time. "\r\n";	
	if($end_date != '') { 
		$messaje .= "DTEND: ". $end_date . "\r\n";
	}	
	$messaje .= "DESCRIPTION:".$event_summary . "\r\n";
	$messaje .= "SUMMARY:Bethany Event:" . $event_title . "\r\n";
	$messaje .= "ORGANIZER; CN=Bethany:mailto:webmaster@bethanyipc.sg\r\n";
	$messaje .= "Location:" . $location . "\r\n";
	$messaje .= "UID:040000008200E00074C5B7101A82E00800000006FC30E6 C39DC004CA782E0C002E01A81\r\n";
	$messaje .= "SEQUENCE:0\r\n";
	$messaje .= "DTSTAMP:".date('Y-m-d').'T'.date('His')."\r\n";
	$messaje .= "END:VEVENT\r\n";
	$messaje .= "END:VCALENDAR\r\n";
	$message .= $messaje;
	
	header('Content-Description: File Transfer');		
	header("Content-Type: text/calendar;charset=utf-8");
	header('Content-Disposition: attachment; filename="cal.ics"');	 
		
	echo $message;
	exit; 
}
?>