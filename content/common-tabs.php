<?php
$child_id = get_the_ID();
//var_dump($child_id);
/**$sub_pages = get_pages( 
	array (
		'child_of' 	=> $child_id,
		'sort_column' 	=> 'menu_order',
		'number' => 200,
	) 

);

$my_wp_query = new WP_Query();
$all_wp_pages = $my_wp_query->query(array('post_type' => 'page'));
$sub_pages = get_page_children( get_the_ID(), $all_wp_pages );
*/


$args = array(
	'post_parent' => get_the_ID(),
	'post_type'   => 'page',
	'numberposts' => -1,
	'post_status' => 'publish',
	'orderby' => 'menu_order',
	'order' => 'ASC'
);
$sub_pages = get_children( $args );

//var_dump($sub_pages);
//exit;

if(count($sub_pages)>1) {
?>
<ul class="nav-tabs clearfix sticky">
	<?php
	foreach($sub_pages as $sub_page) {
	?>
		<li><a href="#<?php echo '_' . $sub_page->post_name; ?>"><?php echo $sub_page->post_title; ?></a></li>	
	<?php 
	}
	?>
</ul>
<?php
}
?>
