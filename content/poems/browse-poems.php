<div class="bsr-filter box-generic">
	<div class="bsr-filter-top filter-top clearfix">
		<div>
			<p class="nav-browse-bsr"></p>
			<!-- Browse Bible Study Resources -->
			<div class="nav-bsr-listing" id="mm-nav-browse-bsr">				
			</div><!--#end of Browse Bible Study Resources -->			
		</div>
		
		<div>
			<form action="<?php echo get_permalink(get_page_by_path('poems-search-result')->ID); ?>" method="GET" id="poems_form">
				<fieldset>
					<div class="field-wrp field-search">
						<input name="poem_search" type="text" placeholder="What do you want to read?" class="full-width" />
						<button id="submit_search" class="i-search ion-ios-search-strong"></button>
					</div>
				</fieldset>
			</form>
		</div>
	</div>	
</div>

<?php
function search_poems_javascript() { 
?>
	<script type="text/javascript">
	(function($){
		$(document).ready(function(){
		
			$('#submit_search').click(function(){
				var poem_search = $.trim($('#poems_form input[name="poem_search"]').val());
				if(poem_search.length < 3 || poem_search == '' ) {
					//console.log('poem_search: ' + poem_search);
					$('#poems_form input[name="poem_search"]').attr("placeholder", "Enter minimum 3 characters");
					return false;
				} else {
					
					$('#poems_form').submit();
				}
			});
		});
	
	})(jQuery);
	</script>
<?php
}
add_action('wp_footer', 'search_poems_javascript');
?>