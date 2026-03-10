
<?php
$sub_pages = get_pages( 
	array (
		'child_of' 	=> get_the_ID(),
		'parent' 	=> get_the_ID(),
		'sort_column' 	=> 'menu_order',
	) 
);
if(count($sub_pages)>0) {
?>
<ul class="nav-tabs clearfix sticky">
	<li class="r-tabs-state-active"><a href="javascript:;"><?php echo get_the_title();?></a></li>
	<?php
	foreach($sub_pages as $sub_page) {
	?>
		
		<li><a id="<?php echo $sub_page->post_name; ?>" href="<?php echo get_permalink($sub_page->ID).'#'.$sub_page->post_name;?>"><?php echo $sub_page->post_title; ?></a></li>	
	<?php 
	}
	?>
</ul>
<?php
}
?>
