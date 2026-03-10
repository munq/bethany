<!-- Breadcrumb -->
<?php global $book; ?>
<section id="breadcrumb" class="add-separator">
	<div class="wrapper">
		<ul class="nav-breadcrumb clearfix">
			<li><a href="<?php echo site_url(); ?>">Home</a></li>
			
			<?php 
				$relations = get_post_ancestors($post->ID); 
				while(count($relations) > 0) {
					$ancestor_id = array_pop($relations);					
					$parent_title = get_the_title($ancestor_id);
					
					if( $parent_title != the_title('', '', false) ) {
					?>
						<li><a href="<?php echo get_permalink($ancestor_id)?>" title="<?php echo $parent_title; ?>"><?php echo $parent_title; ?></a></li>
					<?php
					}			
				} ?>		
			
				<li><?php echo $book; ?></li>			
		</ul>
	</div>
</section><!--#end of Breadcrumb -->