<a id="<?php echo $post->post_name; ?>"></a>
<?php
$sub_pages = get_pages( 
	array (
		'child_of' 	=> $post->post_parent,
		'parent' 	=> $post->post_parent,
		'sort_column' 	=> 'menu_order'
	) 
);
if(count($sub_pages)>0) {
?>
<ul class="nav-tabs clearfix sticky">
	<li><a href="<?php echo get_permalink($post->post_parent); ?>"><?php echo get_the_title($post->post_parent);?></a></li>
	<?php
	foreach($sub_pages as $sub_page) {
	?>		
		
		<li class="<?php echo ($post->ID == $sub_page->ID ) ? 'r-tabs-state-active' : ''; ?>">
		<a href="<?php echo get_permalink($sub_page->ID).'#'.$sub_page->post_name;?>"><?php echo $sub_page->post_title; ?></a></li>	
	<?php 
	}
	?>
</ul>
<?php
}
?>



