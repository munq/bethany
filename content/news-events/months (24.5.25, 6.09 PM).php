<?php
$month_count	= 0;
$current_month = date('n') ;
$current_year 	=  date('Y') ;

while ($count < 15) {
	$monthName = date('F', mktime(0, 0, 0, $current_month, 10));
?>
	<div><?php echo $monthName .' '. $current_year; ?></div>
<?php
	$count++;
	$current_month++;

	if( $current_month > 12 ) {
		$current_month = 1;
		$current_year++;
	}
 
} 
?>