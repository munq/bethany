<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: BSR Search
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */
//$start = microtime(true);
if(isset($_GET) && !empty($_GET)) {		
	$search 		= $_GET['bsr_search'];
	$bible_book 	= $_GET['bible_book'];
	$from_yearmonth = $_GET['from_year'] . $_GET['from_month'];
	$to_yearmonth 	= $_GET['to_year'] . $_GET['to_month'];
	$bsr_series		= $_GET['bsr_series'];

	// Use MYSQL CASE to order the values by columns 
	$querystr = "SELECT ID, 
		CASE
		WHEN wp_posts.post_title LIKE '%{$search}%' THEN '1'
		WHEN mt1.meta_key = 'article_title' THEN '2' 
		WHEN mt1.meta_key = 'text_refrence' THEN '3' 
		WHEN mt1.meta_key = 'beth_search_keywords' THEN '4' 
		WHEN mt2.meta_key = '_wp_page_template' THEN '5' 
		ELSE NULL
		END AS 'meta_weight'
		FROM $wpdb->posts
		LEFT JOIN $wpdb->postmeta mt1 ON(wp_posts.ID = mt1.post_id)
		LEFT JOIN $wpdb->postmeta mt2 ON(wp_posts.ID = mt2.post_id) ";

    if (!empty($from_yearmonth) || !empty($to_yearmonth)) {		
		$querystr .= "LEFT JOIN $wpdb->postmeta mt3 ON(wp_posts.ID = mt3.post_id) ";
	}
	if (!empty($bible_book)){
		$querystr .= "LEFT JOIN $wpdb->postmeta mt4 ON(wp_posts.ID = mt4.post_id) ";
	}
	$querystr .= " WHERE 1=1 ";
	
	$bsr_series_array = array();					
	
	if(!empty($to_yearmonth)) {
		$to_yearmonth 	= $_GET['to_year'] . '-' . $_GET['to_month'];
		$to_yearmonth 	= strtotime(date("Y-m", strtotime($to_yearmonth)) . " +1 month");
		$to_yearmonth 	= date("Ym",$to_yearmonth);
	}
		
	if(!empty($search)) {
		$querystr .= " AND (
			$wpdb->posts.post_title LIKE '%" . $search ."%' 
			OR 
			mt1.meta_key = 'article_title' AND mt1.meta_value LIKE '%" . $search ."%'
			OR
			mt1.meta_key = 'text_refrence' AND ( CAST(mt1.meta_value AS CHAR) LIKE '%{$search}%' OR CAST(mt1.meta_value AS CHAR) LIKE '%{$search}:%' OR CAST(mt1.meta_value AS CHAR) LIKE '%{$search};%' )
			OR
			mt1.meta_key = 'beth_search_keywords' AND mt1.meta_value LIKE '%" . $search ."%'
		)";
	}						
	
	if(!empty($from_yearmonth) && !empty($to_yearmonth)) {				
		$querystr .= " AND (mt3.meta_key = 'article_date' AND mt3.meta_value BETWEEN '" . $from_yearmonth . "' AND '". $to_yearmonth ."')";
	}
	
	if(!empty($from_yearmonth) && empty($to_yearmonth)) {						
		$time_today = current_time( 'timestamp' );
		$to_yearmonth = date('Ym', $time_today);
		
		$querystr .= " AND (mt3.meta_key = 'article_date' AND mt3.meta_value BETWEEN '" . $from_yearmonth . "' AND '". $to_yearmonth ."')";							
	}
	
	if(!empty($bible_book)) {	
		$querystr .= " AND (mt4.meta_key LIKE 'botb_tags_%_botb_tag_book' AND mt4.meta_value LIKE '%" .$bible_book."%')";
	}

	$querystr_other = $querystr;

	if(!empty($bsr_series)) {
		$common_query = array();
		$bsr_series_tags = explode(',', $bsr_series);
		foreach($bsr_series_tags as $bsr_series_tag) {
			$common_query[] = " mt2.meta_key = 'search_tag' AND mt2.meta_value = '$bsr_series_tag'";
		}

		if(!empty($common_query)) {
			$querystr_search_tag .= " AND (
				" .	implode(' OR ', $common_query)
				.")";
			$querystr .= $querystr_search_tag;
			$querystr .= " 
				AND (
					mt3.meta_key = '_wp_page_template' AND mt3.meta_value = 'templates/daily-devotions-detail.php'
				) 
				AND $wpdb->posts.post_type = 'page'
				AND $wpdb->posts.post_status = 'publish'
			";
			$querystr_other .= $querystr_search_tag;
		}

	} else {
		$querystr_tpl .= " AND (
				mt2.meta_key = '_wp_page_template' AND mt2.meta_value = 'templates/daily-devotions-detail.php'
			)
			AND $wpdb->posts.post_type = 'page'
			AND $wpdb->posts.post_status = 'publish'
		";
		$querystr .= $querystr_tpl;
		// $querystr_other .= $querystr_tpl;
	}

	$querystr .= " GROUP BY wp_posts.ID, meta_weight
				   HAVING MIN(meta_weight)
				   ORDER BY meta_weight ASC, wp_posts.post_date DESC";

	$querystr_other = $querystr_other . " AND (
			mt2.meta_key = '_wp_page_template' 
				AND (
					mt2.meta_value IN (
						'templates/bsr-series-detail.php',
						'templates/meditation-detail.php',
						'templates-yag/yag-article-detail.php',
						'templates-ypg/ypg-article-detail.php'
					)
				)
			)
			AND $wpdb->posts.post_type = 'page'
			AND $wpdb->posts.post_status = 'publish'
			GROUP BY wp_posts.ID, meta_weight
			HAVING MIN(meta_weight)
			ORDER BY meta_weight ASC, wp_posts.post_date DESC";

			
	$pageposts = $wpdb->get_results($querystr);
	
	$template = array('templates/daily-devotions-detail.php');
	$querystr_content = where_content_like($search, $template);
	$pageposts_content = $wpdb->get_results($querystr_content);
	
	$pageposts_other = $wpdb->get_results($querystr_other);
	$template = array(
		'templates/bsr-series-detail.php', 
		'templates/meditation-detail.php', 
		'templates-yag/yag-article-detail.php', 
		'templates-ypg/ypg-article-detail.php'
	);
	$querystr_other_content = where_content_like($search, $template);
	$pageposts_other_content = $wpdb->get_results($querystr_other_content);

	//Print the query
	// echo "Last SQL-Query: {$querystr}";
	// echo "<br /><br /><br /><br />";
	// echo "Last SQL-Query: {$querystr_content}"; 
	// echo "<br /><br /><br /><br />";
	// echo "Last SQL-Query: {$querystr_other}";
	// echo "<br /><br /><br /><br />";
	// echo "Last SQL-Query: {$querystr_other_content}"; 
	// echo "<br /><br /><br /><br />";
	// exit;
	
	//merge all search for daily devotionals
	$pageposts = array_merge($pageposts, $pageposts_content);
	$pagepost_final  = array();
	foreach ($pageposts as $current) {
		if (!isset($pagepost_final[$current->ID])) {
            $pagepost_final[$current->ID] = $current;
        }
	}
	$pageposts = array_merge($pagepost_final);
	//LIMIT the results to 500, to save time
	//$pageposts = array_slice($pagepost_final,0,1000);

	// PRINT THE RESULTS
	// echo "<br /><br /><br />";
	// echo "\n pageposts \n";
	// foreach($pageposts as $id) {
	// 	print_r($id->ID);
	// 	echo "\n";
	// } exit;
	
	$pageposts_other = array_merge($pageposts_other, $pageposts_other_content);
	$pagepost_other_final  = array();
	foreach ($pageposts_other as $current) {
		if (!isset($pagepost_other_final[$current->ID])) {
            $pagepost_other_final[$current->ID] = $current;
        }
	}
	$pageposts_other = array_merge($pagepost_other_final);
	
	//remove duplicates from devotionals
	$others_final = array();
	foreach($pageposts_other as $id) {
		foreach($pageposts as $pid){
			if(!isset($pageposts[$id->ID])){
			  $others_final[$id->ID] = $id;
			}
		}
	}
	$pageposts_other = array_merge($others_final);
	//LIMIT the results to 500, to save time
	//$pageposts_other = array_slice($pagepost_final,0,500);

	//ALter this array for testing porpuse
	// $pageposts = [1016806,1016737];
	// $pageposts_other = [];


	// echo "\n pageposts_other \n";
	// foreach($pageposts_other as $id) {
	// 	print_r($id->ID);
	// 	echo "\n";
	// }
	// exit;

} else {
	echo wp_redirect(site_url());
}

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
		<?php the_content(); ?>
		
		<!-- Bible Study Resources -->
		<div id="bible-study-resources">
			<?php include get_template_directory() . '/content/bsr/bsr-search-top.php'; ?>
			
			<h2>Search Results</h2>
			
			<div class="bsr-filter box-gray">
				<?php include get_template_directory() . '/content/bsr/bsr-search-filter.php'; ?>
			</div>
			
			<!-- Full-width Column -->
			<section class="col-full wrapper clearfix">
				<?php 
				//if ( 0 == $pageposts->found_posts && 0 == $pageposts_other->found_posts ) :
				if ( count($pageposts) == 0 && count($pageposts_other) == 0 ) :
					echo "<p>No articles found on this search.</p>";
				else: ?>
					<div class="list-articles with-line" id="listing">
						<?php	
						if($pageposts) { ?>
							<h3 class="label-daily">Daily Devotionals</h3>
							<?php
							global $post;
							foreach($pageposts as $post) {
								setup_postdata($post);
								$notes_url = '';
								$series_name = "";
								$series_path = "";
								$series_value = get_field('search_tag', $post->ID);

								if ( isset($series_value) && !empty($series_value) ) {
									$series_field = get_field_object('search_tag', $post->ID);
									if (empty($series_field)) {
										//$series_field = get_field_object('search_tag', $post->ID);
										//field_558a8d891ecb7 is the field_key of Search Tag
										$series_field = get_field_object('field_558a8d891ecb7', $post->ID);
									}
									$series_name = $series_field['choices'][$series_value];

									if ($series_name == "Daily Devotions") {
										if( have_rows('field_54ec334d5a348',$post->ID) ):
											while( have_rows('field_54ec334d5a348',$post->ID) ): the_row();
												//var_dump('have_rows');
												$sub_field = get_sub_field('field_54ec336d5a349');
												//var_dump('$sub_field = '.$sub_field);
												if ( "" != $sub_field) {
													$series_name = $sub_field;
													break;
												}
											endwhile;
										endif;
									}

									$series_page = get_page_by_title($series_name)->ID;
									$series_path = get_permalink($series_page);
								}

								if(get_field('message', $post->ID)) {
									$messages		= get_field('message', $post->ID);
									$article_date	= get_field('article_date', $messages->ID);
									$article_title	= get_field('article_title', $messages->ID);
									$text_refrence	= get_field('text_refrence', $messages->ID);
								
									if(get_field('speaker', $messages->ID)) {
										$speaker = get_field('speaker', $messages->ID);
									} else {
										$speaker = get_field('author', $messages->ID);
									}
								
									$video_url		= get_field('video_url', $messages->ID);
									$audio_url		= get_field('audio_url', $messages->ID);
									$author			= get_field('author', $messages->ID);
									
									$bulletin			= get_field('bulletin', $messages->ID);
									$bulletin_url 		= ($bulletin) ? wp_get_attachment_url( $bulletin['id'] ) : '';
								
									if(get_field('notes', $messages->ID)) {
										$notes		= get_field('notes', $messages->ID);
										$notes_url	= ($notes) ? wp_get_attachment_url( $notes['id'] ) : '';
									} else {
										$notes_url	= get_field('notes_url', $messages->ID);
									}
								
								} else {
									$messages = '';
									$article_date 	= get_field('article_date', $post->ID);
									$article_title 	= get_field('article_title', $post->ID);
									$text_refrence 	= get_field('text_refrence', $post->ID);
								
									if(get_field('speaker', $post->ID)) {
										$speaker = get_field('speaker', $post->ID);
									} else {
										$speaker = get_field('author', $post->ID);
									}								
								
									$video_url 		= '';
									$audio_url 		= '';
									$author 		= get_field('author', $post->ID);
									$bulletin		= get_field('bulletin', $post->ID);
									$bulletin_url 	= ($bulletin) ? wp_get_attachment_url( $bulletin['id'] ) : '';
								
									if(get_field('notes', $post->ID)) {
										$notes		= get_field('notes', $post->ID);
										$notes_url	= ($notes) ? wp_get_attachment_url( $notes['id'] ) : '';
									} else {
										$notes_url	= get_field('notes_url', $post->ID);
									}
								}
								
								if (empty($article_title)) {
									$article_title =  get_post_field( 'post_title', $post->ID );
								}	
								
								// if (empty($speaker)) {
								// 	$speaker =  the_author();
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
										<strong><a href="<?php echo get_permalink($post->ID); ?>"><?php echo $article_title; ?></a></strong><br/>
										<?php echo ($text_refrence) ? 'Text: ' . $text_refrence : ''; ?>
									</p>
									<p><?php echo ($speaker) ? 'by ' . $speaker : ''; ?> <?php echo ($speaker && $series_name)  ? ' | ' : ''; ?> <?php echo ($series_name) ? '<a href="'.$series_path.'">' . $series_name . '</a>' : '';?></p>

									<!-- Resource Download -->
									<?php 
									generate_print_email_save(
										array(								
											'url'			=> get_permalink($post->ID),
											'bulletin_url'  => $bulletin_url,
											'notes_url'		=> $notes_url,
											'video_url' 	=> $video_url,
											'audio_url' 	=> $audio_url,
										)							
									);
									?>
									
								</div>
							<?php
						    }
						}

						if($pageposts_other) { ?>
							<h3 class="label-others">Other Articles</h3>
							<?php
							global $post;
							foreach($pageposts_other as $post) {
								setup_postdata($post);
								$notes_url = '';
								$series_name = "";
								$series_path = "";
								$series_value = get_field('search_tag', $post->ID);
								if ( isset($series_value) && !empty($series_value) ) {
									//$series_field = get_field_object('search_tag', $post->ID);
									//field_558a8d891ecb7 is the field_key of Search Tag
									$series_field = get_field_object('field_558a8d891ecb7', $post->ID);
									$series_name = $series_field['choices'][$series_value];

									if ($series_name == "Daily Devotions") {
										if( have_rows('field_54ec334d5a348',$post->ID) ):
											while( have_rows('field_54ec334d5a348',$post->ID) ): the_row();
												//var_dump('have_rows');
												$sub_field = get_sub_field('field_54ec336d5a349');
												//var_dump('$sub_field = '.$sub_field);
												if ( "" != $sub_field) {
													$series_name = $sub_field;
													break;
												}
											endwhile;
										endif;
									}

									$series_page = get_page_by_title($series_name)->ID;
									$series_path = get_permalink($series_page);
								}

								if(get_field('message', $post->ID)) {
									$messages		= get_field('message', $post->ID);
									$article_date	= get_field('article_date', $messages->ID);
									$article_title	= get_field('article_title', $messages->ID);
									$text_refrence	= get_field('text_refrence', $messages->ID);
								
									if(get_field('speaker', $messages->ID)) {
										$speaker = get_field('speaker', $messages->ID);
									} else {
										$speaker = get_field('author', $messages->ID);
									}
								
									$video_url		= get_field('video_url', $messages->ID);
									$audio_url		= get_field('audio_url', $messages->ID);
									$author			= get_field('author', $messages->ID);
									
									$bulletin			= get_field('bulletin', $messages->ID);
									$bulletin_url 		= ($bulletin) ? wp_get_attachment_url( $bulletin['id'] ) : '';
								
									if(get_field('notes', $messages->ID)) {
										$notes		= get_field('notes', $messages->ID);
										$notes_url	= ($notes) ? wp_get_attachment_url( $notes['id'] ) : '';
									} else {
										$notes_url	= get_field('notes_url', $messages->ID);
									}
								
								} else {
									$messages = '';
									$article_date 	= get_field('article_date', $post->ID);
									$article_title 	= get_field('article_title', $post->ID);
									$text_refrence 	= get_field('text_refrence', $post->ID);
									
									if(get_field('speaker', $post->ID)) {
										$speaker = get_field('speaker', $post->ID);
									} else {
										$speaker = get_field('author', $post->ID);
									}								
								
									$video_url 		= '';
									$audio_url 		= '';
									$author 		= get_field('author', $post->ID);
									$bulletin		= get_field('bulletin', $post->ID);
									$bulletin_url 	= ($bulletin) ? wp_get_attachment_url( $bulletin['id'] ) : '';
									
									if(get_field('notes', $post->ID)) {
										$notes		= get_field('notes', $post->ID);
										$notes_url	= ($notes) ? wp_get_attachment_url( $notes['id'] ) : '';
									} else {
										$notes_url	= get_field('notes_url', $post->ID);
									}
								}
								
								if (empty($article_title)) {
									$article_title =  get_post_field( 'post_title', $post->ID );
								}	
							
								// if (empty($speaker)) {
								// 	$speaker =  the_author();
								// }

								if ( '19700101' == $article_date ) {
									$article_date = '';
								}
								
								if (empty($article_date)) {
									$article_date =  get_post_field( 'post_date', $post->ID );
								}

							?>
							
							<div class="clearfix item-others">
								<p><?php echo !empty($article_date) ? date('d M Y', strtotime($article_date)):''; ?><br/>
									<strong><a href="<?php echo get_permalink($post->ID); ?>"><?php echo $article_title; ?></a></strong><br/>
									<?php echo ($text_refrence) ? 'Text: ' . $text_refrence : ''; ?></p>
								<?php $series = get_post($post->post_parent); ?>	
								<p><?php echo ($speaker) ? 'by ' . $speaker : ''; ?> <?php echo ($speaker && $series_name)  ? ' | ' : ''; ?> <?php echo ($series_name) ? '<a href="'.$series_path.'">' . $series_name . '</a>' : '';?></p>
								
								<!-- Resource Download -->															
								
								<?php 
								generate_print_email_save(
									array(								
										'url'			=> get_permalink($post->ID),
										'bulletin_url'  => $bulletin_url,
										'notes_url'		=> $notes_url,
										'video_url' 	=> $video_url,
										'audio_url' 	=> $audio_url,
									)							
								);
								?>
								
							</div>
						<?php
						}
						?>
						</div>
					<?php
					}	
					?>
					</div>
					<?php
				endif;
				?>		
				
				<!-- Pagination -->
				<div class="pagination-wrp">
					<div class="pagination"></div>
				</div><!--#end of Pagination -->
			</section><!--#end of Full-width Column -->
		</div><!--#end of Bible Study Resources -->
	</section>
	<!--#end of Full-width Column -->
	
</section><!--#end of Content -->

<?php endwhile; endif; ?>

<?php get_footer(); ?>
<?php
//$total = microtime(true) - $start;
//echo 'total = '.$total;
?>
