<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Search Poems
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */
 
get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<!-- Main Banner -->
<?php get_template_part('content/common', 'banner'); ?>	

<!-- include breadcrumbs -->
<?php get_template_part('content/common', 'breadcrumb'); ?>	

<!-- Content -->
<section id="content" class="clearfix">	
	<!-- Full-width Column -->
	<section class="col-full wrapper clearfix">
		<!-- Bible Study Resources -->
		<div id="bible-study-resources">
			<div class="bsr-filter box-generic">
				<div class="bsr-filter-top filter-top clearfix">						
					<div>
						<form action="" method="GET" id="poems_form">
							<fieldset>
								<div class="field-wrp field-search">
									<input type="text" name="poem_search" placeholder="Search" class="full-width" />
									<button id="submit_search" class="i-search ion-ios-search-strong"></button>
								</div>
							</fieldset>
						</form>
					</div>
				<!-- <div class="txt-right hidden">
						<p><a class="link-txt btn-expand-filter" href="javascript:;">Advanced Search &nbsp;<i class="ion-ios-arrow-down"></i></a></p>
					</div> -->
				</div>
			</div>
			
			<h2>Search Results</h2>
			
			<?php			
			$search_d = $_GET['poem_search'];

			if(isset($_GET) && !empty($_GET)) {

				if(!empty($_GET['poem_search'])) {

					$args = array(
						'post_type' 	=> 'page',
						'post_status'	=> 'publish',
						'depth' 		=> 1,
						'orderby' 		=> 'menu_order',
						'order' 		=> 'ASC',
						'posts_per_page'	=> -1,
						'meta_query'	=> array(
							'relation' => 'AND',
							array(
								'key' => '_wp_page_template',
								'value' => 'templates-poems/poem-detail.php',
								'compare' => '='
							)
						)
					);

					$search = $_GET['poem_search'];

					if(!empty($search)) {
						$args['post_title_like'] = $search;
						add_filter('posts_where', 'where_search_keyword_title_like', 10, 2);
						add_filter('posts_join','join_search_keyword');
					}


					$query	= new WP_Query($args);

					//echo "Last SQL-Query: {$query->request}";

					// remove the attached filter for WP_Query
					if (!empty($search)) {
						remove_filter('posts_where', 'where_search_keyword_title_like');
						remove_filter('posts_join', 'join_search_keyword');
					}
				}

			} else { ?>
				<p>Your Search Returned Zero Results</p>
			<?php } ?>

			<?php if($query->have_posts()) { ?>

				<div class="bsr-filter box-gray">
					<form>
						<fieldset class="bsr-filter-top filter-top clearfix">
							<div class="field-wrp field-search">
								<span class="pagination-count"><strong></strong></span> <span>
								<strong>Results for:
								<?php echo $search_d; ?><br/>
								</strong></span>
							</div>
							<div>
								<!-- Pagination -->
								<div class="pagination-wrp">
									<div class="pagination-legend"></div>
								</div><!--#end of Pagination -->
							</div>
						</fieldset>
					</form>
				</div>
			
				<!-- Full-width Column -->
				<section class="col-full wrapper clearfix">

					<div class="list-articles with-line" id="listing">
						<?php
						while($query->have_posts()) {
							$query->the_post();
							$post_articles = $query->post;

							$article_date		= get_field('article_date', $post_articles->ID);
							$article_title		= get_the_title( $post_articles->ID);
							$notes				= get_field('notes', $post_articles->ID);
							$notes_url			= ($notes) ? wp_get_attachment_url( $notes['id'] ) : '';

							$series = get_post($post_articles->post_parent);

							?>
							<div class="clearfix">
								<p><?php echo !empty($article_date) ? date('d M Y', strtotime($article_date)) .'<br/>':''; ?>
									<strong><a href="<?php echo get_permalink($post_articles->ID); ?>"><?php echo $article_title; ?></a></strong>
									</p>

									<p><?php echo ($series) ? '<a href="' . get_permalink($series) .'">' . get_the_title($series) . '</a>' : '';?></p>

								<!-- Resource Download -->

								<?php
								generate_print_email_save(
									array(
										'url'			=> get_permalink($post_articles->ID),
										'notes_url'		=> $notes_url
									)
								);
								?>

							</div>
						<?php
						}
						?>
					</div>

					<!-- Pagination -->
					<div class="pagination-wrp">
						<div class="pagination"></div>
					</div><!--#end of Pagination -->
				</section><!--#end of Full-width Column -->

			<?php } else { ?>
				<p>There were no results found. Please try another search term.</p>
			<?php } ?>

		</div><!--#end of Bible Study Resources -->
	</section>
	<!--#end of Full-width Column -->
	
</section><!--#end of Content -->

<?php endwhile; endif; ?>

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

<?php get_footer(); ?>
