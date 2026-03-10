
<section class="col-full wrapper clearfix">	
	<!-- Profile's Expanded Grid -->
	<!-- Profile's Expanded Grid -->
<ul class="grid-expand g-four clearfix">
<?php

 if(have_rows('teachers')) {
	
 // Loop through 'teachers' repeater
	
	while(have_rows('teachers')) {
		
		the_row();
		
		$name = get_sub_field('name');		
		$image = get_sub_field('image');		
		$class_title = get_sub_field('class_title');
		$class_content = get_sub_field('class_content');		
		?>
		
	<li>
		<figure class="box-img">
			<a class="grid-click" href="#"><img src="<?php  echo $image; ?>" alt="" /></a>
			<figcaption class="grid-expanded">
				<dl class="grid-expanded-inner clearfix">
					<dt>
						<h3>Teacher <?php echo $name;?></h3>
					</dt>
					<?php 
					
					if($class_title) {
					
					?>
					<dd>
						<p><?php echo $class_content; ?></p>
						<dl class="clearfix">
							<dt>Class:</dt>
							<dd><?php echo $class_title; ?></dd>
							<?php
							if(have_rows('students')) { 
							?>
							
							<dt>Students:</dt>							
							<dd>			
							<?php
							if(have_rows('students')) {
							
							// Loop through 'students' repeater of a 'class'
								
								while(have_rows('students')) {
									the_row();
									
									$name = get_sub_field('student_name');
									
									echo $name .'<br/>';									
								}
							}
							?>
							</dd>
							
							<?php
							}							
							?>
						</dl>
					</dd>
					<?php 
					}
					?>
				</dl>
			</figcaption>
		</figure>
	</li>
	<?php
	}
 }
?>
</ul><!--#end of Profile's Expanded Grid -->
</section>