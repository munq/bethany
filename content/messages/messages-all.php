<?php

// Get the Series subpages of the category
// Get the Messages subpages of a Series
// Display the message subpage

?>

<?php
	$parent = $post->post_parent;
	$args = array(
		'post_type'		=> 'page',
		'post_status'	=> 'publish',
		'child_of'		=> $parent,
		'parent'		=> $parent,
		'meta_key' 		=> 'start_date',
		'sort_column' 	=> 'start_date',
		'sort_order'   	=> 'DESC'
	);
	$subpages	=	get_pages($args);
?>	
<!-- Content -->
<section id="content" class="clearfix">
	<!-- Full-width Column -->
	<section class="col-full wrapper clearfix">
		<h2>All Messages</h2>
		
		<!-- Side Column -->
		<section class="col-side">
			<div class="box-side sticky-box">
				<div class="box-side-top clearfix">
					<h3>Find a message</h3>
				</div>
				<div class="box-side-content">
					<form>
						<fieldset>
							<div class="field-wrp field-search">
								<input type="text" placeholder="Search" class="full-width" />
								<a class="i-search ion-ios-search-strong" href="javascript:;"></a>
							</div>
							<h4>Date</h4>
							<div class="field-wrp">
								<select class="select-month">
									<option>January</option>
									<option>February</option>
									<option>March</option>
									<option>April</option>
									<option>May</option>
									<option>June</option>
									<option>July</option>
									<option>August</option>
									<option>September</option>
									<option>October</option>
									<option>November</option>
									<option>December</option>
								</select>
								<select class="select-year">
									<option>2015</option>
									<option>2014</option>
									<option>2013</option>
									<option>2012</option>
									<option>2011</option>
									<option>2010</option>
									<option>2009</option>
									<option>2008</option>
								</select>
							</div>
						</fieldset>
						<p><a class="btn-primary btn-gray" href="#">Apply</a></p>
					</form>
				</div>
			</div>
		</section><!--#end of Side Column -->
		
		<!-- Main Column -->
		<section class="col-main">
			<ul class="list-messages" id="listing">
			<?php
			foreach($subpages as $subpage) {
			
				$series_title	=	get_the_title($subpage->ID);
				$start_date		=	get_field('start_date',$subpage->ID);
				$end_date		=	get_field('end_date',$subpage->ID);
				?>				
				<li class="clearfix">
					<p><strong><a href="<?php echo get_permalink($subpage->ID);?>"><?php echo $series_title; ?></a></strong><br/>
					<?php echo date('M Y', strtotime($start_date)) . ' - ' . date('M Y', strtotime($end_date)) ; ?></p>
				</li>
				<?php						
				}
				?>
			</ul>
			<!-- Pagination -->
			<div class="pagination-wrp">
				<div class="pagination"></div>
			</div><!--#end of Pagination -->
			
		</section><!--#end of Main Column -->
	</section> <!--#end of Full-width Column -->
</section><!--#end of Content -->
	


