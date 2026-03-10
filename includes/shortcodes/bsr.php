<?php
/**
 * This function will generate print, email, and save as pdf links
 *
 * @return String
 * @author Tami
 * @param $atts Array
 */

function shortcode_bsr_search_tab( $atts ){

	extract(shortcode_atts(array(
			'tab'			=> '',
		), $atts));
	
	if (empty($tab))
		return '';
	
	$return = '';
	
	switch($tab) {
		case 'books-of-the-bible':
			$return = bsr_search_tab_botb();			
			break;
		case 'by-topics':
			$return = bsr_search_tab_topics();
			break;
		case 'by-series':
			$return = bsr_search_tab_series();
			break;
		default:
			$return = '';
	}
	
	return $return;
}

function bsr_search_tab_botb() {
	include get_template_directory() . '/includes/bible-books-chapters.php';
	
	ob_start();
	?>
	<div class="bible-books accordion-bsr clearfix">
							
		<!-- Old Testament -->
		<div>
			<h3>OLD TESTAMENT</h3>
			<div class="list-bsr old-testament">
				<?php 
				foreach($old_testament_books as $key => $val ) {
				?>
					<p><a href="#"><?php echo $key; ?></a></p>
					<div class="list-bsr-chapters">
						<ul class="book-chapter clearfix">
						<?php 
						for($i=1; $i<=$val; $i++) {
						?>										
							<li><a href="<?php echo get_permalink(get_page_by_path('bible-study-resources/books-of-the-bible')->ID) . '?val=' . $val . '&book='.$key.'&chapter='. $i; ?>"><?php echo $i; ?></a></li>
						<?php
						}
						?>
						</ul>
					</div>
				<?php
				}
				?>								
			</div>
		</div>
		
		<!-- New Testament -->
		<div>
			<h3>NEW TESTAMENT</h3>
			<div class="list-bsr new-testament">
			<?php 
			foreach($new_testament_books as $key=>$val ) {
			?>
				<p><a href="#"><?php echo $key; ?></a></p>
				<div class="list-bsr-chapters">
					<ul class="book-chapter clearfix">
					<?php 
					for($i=1; $i<=$val; $i++) {
					?>							
						<li><a href="<?php echo get_permalink(get_page_by_path('bible-study-resources/books-of-the-bible')->ID) . '?val=' . $val . '&book='.$key.'&chapter='. $i; ?>"><?php echo $i; ?></a></li>
					<?php
					}
					?>
					</ul>
				</div>
			<?php
			}
			?>	
				
			</div>
		</div>
	</div>
	<?php
	
	$return = ob_get_clean();
	
	return $return;
}

function bsr_search_tab_topics() {
	
	ob_start();
	?>
	<div class="bible-topics clearfix">
		<div>
			<div class="list-bsr column-count-2">
				
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
					<h3><a href="<?php echo get_permalink(get_page_by_path('bible-study-resources/by-topics')) . '/?t=' . $term->slug ?>"><?php echo $term->name; ?></a></h3>
					<?php
					}
				}				
				?>				
			</div>
		</div>
	</div>	
	<?php
	
	$return = ob_get_clean();
	
	return $return;
}

function bsr_search_tab_series() {
	
	ob_start();
	?>
	<div class="bible-series clearfix">
		<div>
			<div class="list-bsr column-count-2">
				<h3><a href="#">Daily Devotions</a></h3>
				<h3><a href="#">Grace Works<br/>
					<small>Weekly articles by Pastor Mark Tan to the young adults</small></a></h3>
				<h3><a href="#">Meditation<br/>
					<small>Weekly articles on pulpit themes, written by Pastor Charles Tan</small></a></h3>
				<h3><a href="#">Parenting by the Book<br/>
					<small>Special series for parents with wisdom from the Book of Proverbs</small></a></h3>
				<h3><a href="#">Youth Walk<br/>
					<small>Weekly articles by Pastor Mitchell Tan to the young people</small></a></h3>
				<h3><a href="#">Bible Study</a></h3>
				<h3><a href="#">Family Camp Messages</a></h3>
				<h3><a href="#">Evening Worship</a></h3>
				<h3><a href="#">Morning Worship</a></h3>
				<h3><a href="#">Prayer Meeting</a></h3>
				<h3><a href="#">Senior Sunday School 4</a></h3>
				<h3><a href="#">YAG Retreat</a></h3>
				<h3><a href="#">Young Adults’ Group</a></h3>
				<h3><a href="#">Young People’s Group</a></h3>
			</div>
		</div>
	</div>
	<?php
	
	$return = ob_get_clean();
	
	return $return;
}

add_shortcode( 'bsr-search-tab', 'shortcode_bsr_search_tab' );