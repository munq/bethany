<?php include get_template_directory() . '/includes/bible-books-chapters.php'; ?>
<div class="bsr-filter box-generic">
	<div class="bsr-filter-top filter-top clearfix">
		<div>
			<p class="nav-browse-bsr"><a href="#mm-nav-browse-bsr"><i class="ion-navicon-round"></i> Browse Bible Study Resources</a></p>													
			<!-- Browse Bible Study Resources -->
			<div class="nav-bsr-listing" id="mm-nav-browse-bsr">
				<ul>
					<li>
						<a href="#">Browse by Books of the Bible</a>
						<ul>
							<li class="Label">Old Testament</li>
							<?php 
							foreach($old_testament_books as $key => $val_num ) { 
							?>
								<li>
									<a href="#"><?php echo $key; ?></a>
									<ul class="book-chapter clearfix">
									<?php 
									for($i=1; $i<=$val_num; $i++) { 
									?>									
										<li><a href="<?php echo get_permalink(get_page_by_path('bible-study-resources/books-of-the-bible')->ID) . '?val=' . $val_num . '&book='.$key.'&chapter='. $i; ?>"><?php echo $i; ?></a></li>
									<?php
									}
									?>	
									</ul>
								</li>
							<?php
							}
							?>
							
							<li class="Label">New Testament</li>
							<?php 
							foreach($new_testament_books as $key => $val_num ) { 
							?>
								<li>
									<a href="#"><?php echo $key; ?></a>
									<ul class="book-chapter clearfix">
									<?php 
									for($i=1; $i<=$val_num; $i++) { 
									?>									
										<li><a href="<?php echo get_permalink(get_page_by_path('bible-study-resources/books-of-the-bible')->ID) . '?val=' . $val_num . '&book='.$key.'&chapter='. $i; ?>"><?php echo $i; ?></a></li>
									<?php
									}
									?>	
									</ul>
								</li>
							<?php
							}
							?>
							
							
						</ul>
					</li>
					
					<li>
						<a href="#">Browse by Topics</a>
						<ul>
						<?php
						// no default values. using these as examples
						$taxonomies = array(
							'topic',
						);

						$args = array(
							'orderby'           => 'name', 
							'order'             => 'ASC',
							'hide_empty'        => false, 
							'exclude'           => array(), 
							'exclude_tree'      => array(), 
							'include'           => array(),
							'number'            => '', 
							'fields'            => 'all', 
							'slug'              => '',
							'parent'            => '',
							'hierarchical'      => true, 
							'child_of'          => 0, 
							'get'               => '', 
							'name__like'        => '',
							'description__like' => '',
							'pad_counts'        => false, 
							'offset'            => '', 
							'search'            => '', 
							'cache_domain'      => 'core'
						); 

						$terms = get_terms($taxonomies, $args);
						if ( !empty($terms) ) {
							foreach ($terms as $key => $term) {
								?>							
								
								<li><a href="<?php echo get_permalink(get_page_by_path('bible-study-resources/by-topics')) . '/?t=' . $term->slug ?>"><?php echo $term->name; ?></a></li>
								
								<?php
							}
						}						
						?>	
						</ul>
					</li>
					
					<li>
						<a href="#">Browse by Series</a>
						<?php 
						 $bsr_page = get_page_by_path('bible-study-resources')->ID; ?>
						
						<ul>
							
						<?php
						if(have_rows('by_series_content', $bsr_page)) {
							while(have_rows('by_series_content', $bsr_page)) {
								the_row();
								?>
								<li><a href="<?php echo get_sub_field('series_link'); ?>"><?php echo get_sub_field('series_name'); ?>
								<?php 
								if(get_sub_field('series_overview')) {
								?>
								<br/>
								<small><?php echo get_sub_field('series_overview'); ?></small>
								<?php
								} ?> </a></li>	
							<?php
							}
						}
						?>							
						</ul>
					</li>
				</ul>
			</div><!--#end of Browse Bible Study Resources -->			
		</div>
		
		<div>
			<form action="<?php echo get_permalink(get_page_by_path('bible-study-resources/bsr-search-result')->ID); ?>" method="GET" id="bible_study_form">
				<fieldset>
					<div class="field-wrp field-search">
						<input name="bsr_search" type="text" placeholder="What do you want to read?" class="full-width" value="<?php echo $_GET['bsr_search']?>" />
						<input name="from_month" type="hidden" value="" />
						<input name="from_year" type="hidden" value="" />
						<input name="to_month" type="hidden" value="" />
						<input name="to_year" type="hidden" value="" />
						<input name="bible_book" type="hidden" value="" />
						<input name="bsr_series" type="hidden" value="" />
						<button id="submit_search" class="i-search ion-ios-search-strong"></button>
					</div>
				</fieldset>
			</form>
		</div>
	</div>	
</div>

<?php
function search_bsr_msg_javascript() { 
?>
	<script type="text/javascript">
	(function($){
		$(document).ready(function(){
			// removed default click event
			
			var search_series = new Array();
			
			$( ".search-series a" ).toggle(function() {			
				search_series.push($(this).data('value'));
				console.log(search_series);
				
			}, function() {
				var itemtoRemove = $(this).data('value'); //.attr('data-value')
				search_series.splice($.inArray(itemtoRemove, search_series),1);				
				console.log(search_series);
			});
			
			$('#bsr_apply').click(function(){
			
				search_series.toString();
				
				var bsr_search = $('#bible_study_form input[name="bsr_search"]').val();	
				var from_month 	= $('#from_month').val();
				var from_year 	= $('#from_year').val();				
				var to_month 	= $('#to_month').val();
				var to_year 	= $('#to_year').val();
				var bible_book 	= $('#bible_book').val();
					
				$('#bible_study_form input[name=from_month]').val(from_month);
				$('#bible_study_form input[name=from_year]').val(from_year);
				$('#bible_study_form input[name=to_month]').val(to_month);
				$('#bible_study_form input[name=to_year]').val(to_year);
				$('#bible_study_form input[name=bible_book]').val(bible_book);
				$('#bible_study_form input[name=bsr_series]').val(search_series);
				
				if(search_series != '') {
					if(from_month !='' || from_year !='' || to_month !='' || to_year !='' || bible_book !='' || bsr_search.length > 4 ) {
						$('#bible_study_form').submit();
					} else {					
						location.href = '<?php echo get_permalink(get_page_by_path('bible-study-resources')->ID); ?>';
					}
				} else {
					
					if(from_month !='' || from_year !='' || to_month !='' || to_year !='' || bible_book !='' || bsr_search.length > 4 ) { 
						$('#bible_study_form').submit();
					} else {
						return false;
					}					
				}				
			});		
		
			$('#submit_search').click(function(){
				var bsr_search = $.trim($('#bible_study_form input[name="bsr_search"]').val());
				if(bsr_search.length < 3 || bsr_search == '' ) {
					//console.log('bsr_search: ' + bsr_search);
					$('#bible_study_form input[name="bsr_search"]').attr("placeholder", "Enter minimum 3 characters");
					return false;
				} else {
					
					$('#bible_study_form').submit();
				}
			});
		});
	
	})(jQuery);
	</script>
<?php
}
add_action('wp_footer', 'search_bsr_msg_javascript');
?>