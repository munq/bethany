<!-- Breadcrumb -->
<section id="breadcrumb" class="add-separator">
	<div class="wrapper">
		<ul class="nav-breadcrumb clearfix">
			<li><a href="<?php echo site_url(); ?>">Home</a></li>
			
			<?php 
				$relations = get_post_ancestors($post->ID); 
				while(count($relations) > 0) {
					$show_path = 1;
					$ancestor_id = array_pop($relations);
					$parent_title = get_the_title($ancestor_id);
					$link_path = get_permalink($ancestor_id);
					if ( $parent_title == "By Topics" ) {
						$link_path = "javascript:;";
						$show_path = 0;
					}

					if ( $parent_title == "Prayer" ) {
						$link_path = str_replace("/prayer/","/?t=prayer",$link_path);
					}

					if ( $parent_title != the_title('', '', false) ) {
					?>
						<li>
							<?php if ( $show_path == 1 ) { ?>
							<a href="<?php echo $link_path; ?>" title="<?php echo $parent_title; ?>">
								<?php echo $parent_title; ?>
							</a>
							<?php } else { ?>
								<span class="link-disabled">
									<?php echo $parent_title; ?>
								</span>
							<?php } ?>
						</li>
					<?php
					}

				} ?>		
			
				<li><?php echo get_the_title($post->ID); ?></li>			
		</ul>
	</div>
</section><!--#end of Breadcrumb -->