<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: BSR Books
 
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */

$book = $_GET['book'];
$chapter = $_GET['chapter'];
$val = $_GET['val'];
$verse = $_GET['verse'];

if (empty($book) && empty($chapter))
	wp_redirect(get_permalink($post->post_parent));

add_filter('posts_where', 'where_botb_tags_like');

if(isset($_GET) && !empty($_GET)) {
	$book = $_GET['book'];
	$chapter = $_GET['chapter'];
	
	// START Getting devotional articles using text_refrence and botb_tags
	$meta_query_args = array(
		'relation' => 'AND',
		array(
			'key' => '_wp_page_template',
			'value' => 'templates/daily-devotions-detail.php',
			'compare' 	=> '=',
		),						
	);

    $meta_query_txt_ref_args = array(
        'relation' => 'AND',
        array(
            'key' => '_wp_page_template',
            'value' => 'templates/daily-devotions-detail.php',
            'compare' 	=> '=',
        ),
    );

    $meta_query_txt_ref_args[] = array(
        'key' 		=> 'text_refrence',
        'value' 	=> $book. ' '.$chapter,
        'compare' 	=> 'LIKE'
    );

	if(!empty($book)) {
        $meta_query_args[] = array(
			'key' 		=> 'botb_tags_%_botb_tag_book',
			'value' 	=> $book,
			'compare' 	=> '='
		);
	}
	
	if(!empty($chapter)) {
        $meta_query_args[] = array(
            'key' 		=> 'botb_tags_%_botb_tag_chapter',
            'value' 	=> $chapter,
            'compare' 	=> '='
        );
	}
	
	if(!empty($verse)) {
        $meta_query_args[] = array(
            'key' 		=> 'botb_tags_%_botb_tag_verse',
            'value' 	=> $verse,
            'compare' 	=> 'LIKE'
        );
	}

	$args_daily_devotion_txt_ref = array(
		'post_type' 	=> 'page',
		'post_status'	=> 'publish',
		'orderby' 		=> 'date',
		//'meta_key' 		=> 'article_date',
		'posts_per_page' => -1,
		'meta_query'	=> $meta_query_txt_ref_args
	);

    $args_daily_devotion = array(
        'post_type' 	=> 'page',
        'post_status'	=> 'publish',
        'orderby' 		=> 'date',
        'meta_key' 		=> 'article_date',
		'posts_per_page' => -1,
        'meta_query'	=> $meta_query_args
    );

    add_filter('posts_where', 'where_text_refrence');
    add_filter('posts_join','join_text_refrence');
    $query_txt_ref =  new stdClass();//2023 declaration
	$query_txt_ref->posts = array();
    $query_txt_ref = new WP_Query($args_daily_devotion_txt_ref);
    remove_filter('posts_where', 'where_text_refrence');
    remove_filter('posts_join', 'join_text_refrence');
    $query_book = new stdClass();//2023 declaration
    $query_book->posts = array();
    add_filter('posts_where', 'where_botb_tags');
    $query_book = new WP_Query($args_daily_devotion);
    remove_filter('posts_where', 'where_botb_tags');

	//Search in Posts Content
	$search = $book . ' ' . $chapter;
	if (!empty(($verse))) {
		$search .= ':' . $verse; 	
	}

	$template = array('templates/daily-devotions-detail.php');
	$query_content = where_content_like($search, $template);
    $query_content_result = $wpdb->get_results($query_content);
	
	// echo "{$query_txt_ref->request}";
    // echo "<br /><br /><br />";  //exit;
	// echo "{$query_book->request}"; 
	// echo "<br /><br /><br />";  //exit;
    // echo "{$query_content}"; 
	// echo "<br /><br /><br />";  exit;

	
    $post_ids_txt_refs = array_merge($query_txt_ref->posts, $query_book->posts);
	$final  = array();
    foreach ($post_ids_txt_refs as $current) {
        if (!isset($final[$current->ID])) {
            $final[$current->ID] = $current;
        }
    }
    $post_ids_txt_refs = array_merge($final);
	

	$post_ids = array_merge($post_ids_txt_refs, $query_content_result);
	
	$final  = array();
    foreach ($post_ids as $current) {
		if (!isset($final[$current->ID])) {
            $final[$current->ID] = $current;
        }
    }

    $post_ids = array_merge($final);

	// Assign All Daily Devotional Results
	$query_devotion = new WP_Query($args_daily_devotion);
	$query_devotion->found_posts = count($post_ids);
	$query_devotion->posts = $post_ids;
	// exit;

    $query_devotion->post_count = count($post_ids);
    // END Getting devotional articles using text_refrence and botb_tags

	// START Getting other articles
	$meta_query_others_args = array(
		'relation' => 'AND',
    );
	
	$meta_query_others_args[] = array(
		'relation' => 'OR',
		array(
			'key' => '_wp_page_template',
			'value' => 'templates/bsr-series-detail.php',
			'compare' 	=> '=',
		)
	);
	
	$meta_query_txt_ref_others_args = array(
        'relation' => 'AND',
        array(
            'key' => '_wp_page_template',
            'value' => 'templates/bsr-series-detail.php',
            'compare' 	=> '=',
        ),
    );
	$meta_query_txt_ref_others_args[] = array(
        'key' 		=> 'text_refrence',
        'value' 	=> $book. ' '.$chapter,
        'compare' 	=> 'LIKE'
    );

	if(!empty($book)) {
		$meta_query_others_args[] = array(
			'key' 		=> 'botb_tags_%_botb_tag_book',
			'value' 	=> $book,
			'compare' 	=> '='
		);
	}
	
	if(!empty($chapter)) {
		$meta_query_others_args[] = array(
			'key' 		=> 'botb_tags_%_botb_tag_chapter',
			'value' 	=> $chapter,
			'compare' 	=> '='
		);
	}
	
	if(!empty($verse)) {
		$meta_query_others_args[] = array(
			'key' 		=> 'botb_tags_%_botb_tag_verse',
			'value' 	=> $verse,
			'compare' 	=> 'LIKE'
		);
	}
	
	$args_others_txt_ref = array(
		'post_type' 	=> 'page',
		'post_status'	=> 'publish',
		'orderby' 		=> 'date',
		//'meta_key' 		=> 'article_date',
		'posts_per_page' => -1,
		'meta_query'	=> $meta_query_txt_ref_others_args
	);

	$args_others = array(
		'post_type' 	=> 'page',
		'post_status'	=> 'publish',
		//'orderby' 		=> 'meta_value',
		'meta_key' 		=> 'article_date',
		'posts_per_page' => -1,
		'meta_query'	=> $meta_query_others_args
	);

	// Others Text Reference Search
	add_filter('posts_where', 'where_text_refrence_others');
    add_filter('posts_join','join_text_refrence');
    $query_txt_ref_others = new stdClass(); //2023 declaration
	$query_txt_ref_others->posts = array();
    $query_txt_ref_others = new WP_Query($args_others_txt_ref);
    remove_filter('posts_where', 'where_text_refrence_others');
    remove_filter('posts_join','join_text_refrence');
	// echo "{$query_txt_ref_others->request}"; //exit;
    // echo "<br /><br /><br />";
	
	// Others BOTB search
	$query_others = new stdClass();//2023 declaration
	$query_others->posts = array();
    add_filter('posts_where', 'where_botb_tags_others');
	$query_others = new WP_Query($args_others);
    remove_filter('posts_where', 'where_botb_tags_others');
	// echo "{$query_others->request}"; //exit;
    // echo "<br /><br /><br />";
	
    remove_filter('posts_where', 'where_botb_tags_like');
    
    //remove_filter('posts_fields', 'select_refrence');
	$template = array(
		'templates/bsr-series-detail.php', 
		'templates/meditation-detail.php', 
		'templates-yag/yag-article-detail.php', 
		'templates-ypg/ypg-article-detail.php'
	);
	$query_others_content = where_content_like($search, $template);
	$query_others_content_result = $wpdb->get_results($query_others_content);
	// echo "{$query_others_content}"; 
    // echo "<br /><br /><br />"; exit;
	
	$post_ids_txt_refs_others = array_merge($query_txt_ref_others->posts, $query_others->posts); 
	$final_others_txt_refs = array();
	foreach ($post_ids_txt_refs_others as $current) {
        if (!isset($final_others_txt_refs[$current->ID])) {
            $final_others_txt_refs[$current->ID] = $current;
        }
    }
    $post_ids_txt_refs_others = $final_others_txt_refs;
	$post_ids_others = array_merge($post_ids_txt_refs_others, $query_others_content_result);
     
	$final_others  = array();
    foreach ($post_ids_others as $current) {
        if (!isset($final_others[$current->ID])) {
            $final_others[$current->ID] = $current;
        }
    }
	
	//remove duplicates from devotionals
	$others_final = array();
	foreach($post_ids_others as $id) {
		foreach($post_ids as $pid){
			if(!isset($post_ids[$id->ID])){
			  $others_final[$id->ID] = $id;
			}
		}
	}
	$post_ids_others = array_merge($others_final);

	// print all other ids
	// foreach($post_ids_others as $id) {
	// 	print_r($id->ID);
	// 	echo "\n";
	// }
	// exit;

    $query_others = new WP_Query($args_daily_devotion);
    $query_others->posts = $post_ids_others;
	// $query_others->posts = [1028631,1016806,1016737];
    $query_others->post_count = count($post_ids_others);
	// END Getting other articles
}

?>

<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
<!-- Main Banner -->
<?php get_template_part('content/common', 'banner'); ?>	
<!--#end of Main Banner -->
	
<!-- include breadcrumbs -->
<?php get_template_part('content/books', 'of-the-bible-breadcrumb'); ?>

<!-- Content -->
<section id="content" class="clearfix">
	<!-- Full-width Column -->
	<section class="col-full wrapper clearfix">		
		<?php the_content(); ?>
		<h2><?php echo $book; ?></h2>
		
		<!-- Bible Study Resources -->
		<div id="bible-study-resources">
			<?php include get_template_directory() . '/content/bsr/browse-bsr.php'; ?>
			<div class="bsr-filter box-gray">				
				<form id="bethany_bsr_books" method="GET" action="">
					
					<input  type="hidden" name="val" value="<?php echo $val; ?>"/>
					<input  type="hidden" name="book" value="<?php echo $book; ?>"/>
					<fieldset class="bsr-filter-top filter-top clearfix">
						<div class="field-wrp">
						
							<span><strong><?php echo $book; ?></strong></span> &nbsp;							
							<select class="select" name="chapter">
							<?php for ( $i=1; $i <= $val; $i++ ) : ?>
								<option value="<?php echo $i; ?>" <?php echo ($chapter == $i) ? 'selected' : '';?> ><?php echo $i; ?></option>
							<?php endfor; ?>
							</select>
							
							<span>:</span>							
							
							<input  class="xsmall" type="text" name="verse" placeholder="<?php echo ($verse)? $verse : 'Verse'; ?>"/>
							<a class="btn-primary" id="bsr_book_submit" href="javascript:;">Filter</a>
							
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
				<?php
				if( 0 == $query_txt_ref->found_posts &&
                          0 == $query_devotion->found_posts &&
                          0 == $query_others->found_posts ) : ?>
			
				<p>No articles found on this chapter.</p>
			
				<?php else: ?>
				
				<div class="list-articles with-line" id="listing">
				
					<h3 class="label-daily">Daily Devotionals</h3>
		
					<?php
						if($query_devotion->have_posts()) {


							while($query_devotion->have_posts()) {
							
								$query_devotion->the_post();
								$post_devotion = $query_devotion->post;
								$article_title = get_field('article_title', $post_devotion->ID);
								$article_date = get_field('article_date', $post_devotion->ID);
								$text_refrence = get_field('text_refrence', $post_devotion->ID);
								$author = get_field('author', $post_devotion->ID);
								$series_name = "";
								$series_path = "";
								$series_value = get_field('search_tag', $post_devotion->ID);
								if ( isset($series_value) && !empty($series_value) ) {
									$series_field = get_field_object('search_tag', $post_devotion->ID);
									$series_name = $series_field['choices'][$series_value];
									$series_page = get_page_by_title($series_name)->ID;
									$series_path = get_permalink($series_page);
								}
								
								if (empty($article_title)) {
									$article_title =  get_post_field( 'post_title', $post_devotion->ID );
								}	
								
								// if (empty($author)) {
								// 	$author =  get_the_author();
								// }

								if ( '19700101' == $article_date ) {
									$article_date = '';
								}

								if (empty($article_date)) {
									$article_date =  get_post_field( 'post_date', $post->ID );
								}

								?>
								<div class="clearfix item-daily">
									<p><?php echo !empty($article_date) ? date('d M Y', strtotime($article_date)):''; ?><br/>
										<strong><a href="<?php echo get_permalink($post_devotion->ID); ?>"><?php echo $article_title; ?></a></strong><br/>
										<?php echo ($text_refrence) ? 'Text: ' . $text_refrence : '' ; ?>
									</p>
									<p><?php echo ($author) ? 'by ' . $author : ''; ?> <?php echo ($author && $series_name)  ? ' | ' : ''; ?> <?php echo ($series_name) ? '<a href="'.$series_path.'">' . $series_name . '</a>' : '';?></p>
									<?php 
									generate_print_email_save( 
										array(
											'url'	=> get_permalink($post_devotion->ID),
										)
									); 
									?>
								</div>	
							<?php
							}
						}

						if($query_others->have_posts()) { ?>
							<h3 class="label-others">Other Articles</h3>
							<?php
							while($query_others->have_posts()) {

								$query_others->the_post();
								$post_others = $query_others->post;
                                $series_name = "";
                                $series_path = "";
                                $series_value = get_field('search_tag', $post_others->ID);
                                if ( isset($series_value) && !empty($series_value) ) {
                                    $series_field = get_field_object('search_tag', $post_others->ID);
                                    $series_name = $series_field['choices'][$series_value];
                                    $series_page = get_page_by_title($series_name)->ID;
                                    $series_path = get_permalink($series_page);
                                }

								if(get_field('message', $post_others->ID)) {
									$messages			= get_field('message', $post_others->ID);
									$article_date		= get_field('article_date', $messages->ID);
									$article_title		= get_field('article_title', $messages->ID);
									$text_refrence		= get_field('text_refrence', $messages->ID);
									$speaker			= get_field('speaker', $messages->ID);
									$video_url			= get_field('video_url', $messages->ID);
									$audio_url			= get_field('audio_url', $messages->ID);

									if(get_field('speaker', $messages->ID)) {
										$author = get_field('speaker', $messages->ID);
									} else {
										$author	= get_field('author', $messages->ID);
									}

								} else {
									$messages = '';
									$article_date = get_field('article_date', $post_others->ID);
									$article_title = get_field('article_title', $post_others->ID);
									$text_refrence = get_field('text_refrence', $post_others->ID);
									$video_url = '';
									$audio_url = '';

									if(get_field('speaker', $post_others->ID)) {
										$author = get_field('speaker', $post_others->ID);
									} else {
										$author	= get_field('author', $post_others->ID);
									}
								}

								if (empty($article_title)) {
									$article_title =  get_post_field( 'post_title', $post_others->ID );
								}
								if (empty($article_date)) {
									$article_date =  get_post_field( 'post_date', $post_others->ID );
								}
								// if (empty($author)) {
								// 	$author =  get_the_author();
								// }

								if ( '19700101' == $article_date ) {
									$article_date = '';
								}
								?>

								<?php if($video_url) : ?>
									<div class="clearfix item-others has-video">
								<?php elseif($audio_url) : ?>
									<div class="clearfix item-others has-audio">
								<?php else : ?>
									<div class="clearfix item-others">
								<?php endif; ?>
										<p><?php echo !empty($article_date) ? date('d M Y', strtotime($article_date)):''; ?><br/>
											<strong><a href="<?php echo get_permalink($post_others->ID); ?>"><?php echo $article_title; ?></a></strong><br/>
											<?php echo ($text_refrence) ? 'Text: ' . $text_refrence : '' ; ?></p>
										<p><?php echo ($author) ? 'by ' . $author : ''; ?> <?php echo ($author && $series_name)  ? ' | ' : ''; ?> <?php echo ($series_name) ? '<a href="'.$series_path.'">' . $series_name . '</a>' : '';?></p>
										<?php
										generate_print_email_save(
											array(
												'url'		=> get_permalink($post_others->ID),
												'video_url'	=> $video_url,
												'audio_url'	=> $audio_url,
											)
										);
										?>
									</div>
								<?php
							}
						}
                        ?>
				</div>
				<?php
				$total_count = $query_devotion->post_count +  $query_others->post_count;
				?>
				<!-- Pagination -->
				<div class="pagination-wrp">
					<?php if ($total_count > 20): ?>
						<div class="pagination"></div>
					<?php else: ?>
						<div class="inline-block"></div>
					<?php endif; ?>
				</div><!--#end of Pagination -->

				<?php endif; ?>
				
			</section><!--#end of Full-width Column -->
			
		</div><!--#end of Bible Study Resources -->
	</section><!--#end of Full-width Column -->
</section><!--#end of Content -->
	
<?php endwhile; wp_reset_postdata(); endif; ?>

<?php function bsr_books_javascript() { ?>
	<script type="text/javascript">
	(function($) {
		$(document).ready(function($) {
			$('#bsr_book_submit').click(function(){			
				$('#bethany_bsr_books').submit();
			});	
		});
	})(jQuery);
	</script>
<?php 
}
add_action( 'wp_footer', 'bsr_books_javascript' );
?>

<?php get_footer(); ?>
