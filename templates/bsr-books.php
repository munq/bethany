<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: BSR Books
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */

$book = isset($_GET['book']) ? sanitize_text_field($_GET['book']) : '';
$chapter = isset($_GET['chapter']) ? sanitize_text_field($_GET['chapter']) : '';
$val = isset($_GET['val']) ? intval($_GET['val']) : '';
$verse = isset($_GET['verse']) ? sanitize_text_field($_GET['verse']) : '';

if (empty($book) && empty($chapter)) {
	wp_redirect(get_permalink($post->post_parent));
	exit;
}

add_filter('posts_where', 'where_botb_tags_like');

if(isset($_GET) && !empty($_GET)) {
	// START Getting devotional articles using text_refrence and botb_tags
	$meta_query_args = array(
		'relation' => 'AND',
		array(
			'key' => '_wp_page_template',
			'value' => 'templates/daily-devotions-detail.php',
			'compare' => '=',
		),
	);

	$meta_query_txt_ref_args = array(
		'relation' => 'AND',
		array(
			'key' => '_wp_page_template',
			'value' => 'templates/daily-devotions-detail.php',
			'compare' => '=',
		),
		array(
			'key' => 'text_refrence',
			'value' => $book . ' ' . $chapter,
			'compare' => 'LIKE',
		),
	);

	if (!empty($book)) {
		$meta_query_args[] = array(
			'key' => 'botb_tags_%_botb_tag_book',
			'value' => $book,
			'compare' => '='
		);
	}

	if (!empty($chapter)) {
		$meta_query_args[] = array(
			'key' => 'botb_tags_%_botb_tag_chapter',
			'value' => $chapter,
			'compare' => '='
		);
	}

	if (!empty($verse)) {
		$meta_query_args[] = array(
			'key' => 'botb_tags_%_botb_tag_verse',
			'value' => $verse,
			'compare' => 'LIKE'
		);
	}

	$args_daily_devotion_txt_ref = array(
		'post_type' => 'page',
		'post_status' => 'publish',
		'orderby' => 'date',
		'posts_per_page' => -1,
		'meta_query' => $meta_query_txt_ref_args
	);

	$args_daily_devotion = array(
		'post_type' => 'page',
		'post_status' => 'publish',
		'orderby' => 'date',
		'meta_key' => 'article_date',
		'posts_per_page' => -1,
		'meta_query' => $meta_query_args
	);

	add_filter('posts_where', 'where_text_refrence');
	add_filter('posts_join', 'join_text_refrence');
	$query_txt_ref = new WP_Query($args_daily_devotion_txt_ref);
	remove_filter('posts_where', 'where_text_refrence');
	remove_filter('posts_join', 'join_text_refrence');

	add_filter('posts_where', 'where_botb_tags');
	$query_book = new WP_Query($args_daily_devotion);
	remove_filter('posts_where', 'where_botb_tags');

	$search = $book . ' ' . $chapter;
	if (!empty($verse)) {
		$search .= ':' . $verse;
	}

	$template = array('templates/daily-devotions-detail.php');
	global $wpdb;
	$query_content = where_content_like($search, $template);
	$query_content_result = $wpdb->get_results($query_content);

	$post_ids_txt_refs = array_merge($query_txt_ref->posts, $query_book->posts);
	$final = array();
	foreach ($post_ids_txt_refs as $current) {
		if (!isset($final[$current->ID])) {
			$final[$current->ID] = $current;
		}
	}
	$post_ids_txt_refs = array_values($final);

	$post_ids = array_merge($post_ids_txt_refs, $query_content_result);
	$final = array();
	foreach ($post_ids as $current) {
		if (!isset($final[$current->ID])) {
			$final[$current->ID] = $current;
		}
	}
	$post_ids = array_values($final);

	$query_devotion = new WP_Query($args_daily_devotion);
	$query_devotion->found_posts = count($post_ids);
	$query_devotion->posts = $post_ids;
	$query_devotion->post_count = count($post_ids);

	// START Getting other articles
	$meta_query_others_args = array('relation' => 'AND');
	$meta_query_others_args[] = array(
		'relation' => 'OR',
		array(
			'key' => '_wp_page_template',
			'value' => 'templates/bsr-series-detail.php',
			'compare' => '=',
		)
	);

	$meta_query_txt_ref_others_args = array(
		'relation' => 'AND',
		array(
			'key' => '_wp_page_template',
			'value' => 'templates/bsr-series-detail.php',
			'compare' => '=',
		),
		array(
			'key' => 'text_refrence',
			'value' => $book . ' ' . $chapter,
			'compare' => 'LIKE'
		),
	);

	if (!empty($book)) {
		$meta_query_others_args[] = array(
			'key' => 'botb_tags_%_botb_tag_book',
			'value' => $book,
			'compare' => '='
		);
	}
	if (!empty($chapter)) {
		$meta_query_others_args[] = array(
			'key' => 'botb_tags_%_botb_tag_chapter',
			'value' => $chapter,
			'compare' => '='
		);
	}
	if (!empty($verse)) {
		$meta_query_others_args[] = array(
			'key' => 'botb_tags_%_botb_tag_verse',
			'value' => $verse,
			'compare' => 'LIKE'
		);
	}

	$args_others_txt_ref = array(
		'post_type' => 'page',
		'post_status' => 'publish',
		'orderby' => 'date',
		'posts_per_page' => -1,
		'meta_query' => $meta_query_txt_ref_others_args
	);

	$args_others = array(
		'post_type' => 'page',
		'post_status' => 'publish',
		'meta_key' => 'article_date',
		'posts_per_page' => -1,
		'meta_query' => $meta_query_others_args
	);

	add_filter('posts_where', 'where_text_refrence_others');
	add_filter('posts_join', 'join_text_refrence');
	$query_txt_ref_others = new WP_Query($args_others_txt_ref);
	remove_filter('posts_where', 'where_text_refrence_others');
	remove_filter('posts_join', 'join_text_refrence');

	add_filter('posts_where', 'where_botb_tags_others');
	$query_others = new WP_Query($args_others);
	remove_filter('posts_where', 'where_botb_tags_others');

	remove_filter('posts_where', 'where_botb_tags_like');

	$template = array(
		'templates/bsr-series-detail.php',
		'templates/meditation-detail.php',
		'templates-yag/yag-article-detail.php',
		'templates-ypg/ypg-article-detail.php'
	);

	$query_others_content = where_content_like($search, $template);
	$query_others_content_result = $wpdb->get_results($query_others_content);

		$post_ids_txt_refs_others = array_merge($query_txt_ref_others->posts, $query_others->posts);
	$final_others_txt_refs = array();
	foreach ($post_ids_txt_refs_others as $current) {
		if (!isset($final_others_txt_refs[$current->ID])) {
			$final_others_txt_refs[$current->ID] = $current;
		}
	}
	$post_ids_txt_refs_others = array_values($final_others_txt_refs);

	$post_ids_others = array_merge($post_ids_txt_refs_others, $query_others_content_result);
	$final_others = array();
	foreach ($post_ids_others as $current) {
		if (!isset($final_others[$current->ID])) {
			$final_others[$current->ID] = $current;
		}
	}
	$post_ids_others = array_values($final_others);

	// Remove duplicates from devotionals
	$others_final = array();
	foreach($post_ids_others as $id) {
		if (!isset($final[$id->ID])) {
			$others_final[$id->ID] = $id;
		}
	}
	$post_ids_others = array_values($others_final);

	$query_others = new WP_Query($args_daily_devotion); // This probably should use $args_others
	$query_others->posts = $post_ids_others;
	$query_others->post_count = count($post_ids_others);
}
?>

<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php get_template_part('content/common', 'banner'); ?>
<?php get_template_part('content/books', 'of-the-bible-breadcrumb'); ?>

<section id="content" class="clearfix">
	<section class="col-full wrapper clearfix">
		<?php the_content(); ?>
		<h2><?php echo esc_html($book); ?></h2>
		<div id="bible-study-resources">
			<?php include get_template_directory() . '/content/bsr/browse-bsr.php'; ?>
			<div class="bsr-filter box-gray">
				<form id="bethany_bsr_books" method="GET" action="">
					<input type="hidden" name="val" value="<?php echo esc_attr($val); ?>"/>
					<input type="hidden" name="book" value="<?php echo esc_attr($book); ?>"/>
					<fieldset class="bsr-filter-top filter-top clearfix">
						<div class="field-wrp">
							<span><strong><?php echo esc_html($book); ?></strong></span> &nbsp;
							<select class="select" name="chapter">
								<?php for ($i = 1; $i <= $val; $i++) : ?>
									<option value="<?php echo $i; ?>" <?php selected($chapter, $i); ?>><?php echo $i; ?></option>
								<?php endfor; ?>
							</select>
							<span>:</span>
							<input class="xsmall" type="text" name="verse" placeholder="<?php echo esc_attr($verse ?: 'Verse'); ?>"/>
							<a class="btn-primary" id="bsr_book_submit" href="javascript:;">Filter</a>
						</div>
						<div class="pagination-wrp">
							<div class="pagination-legend"></div>
						</div>
					</fieldset>
				</form>
			</div>

			<section class="col-full wrapper clearfix">
				<?php if (0 == $query_txt_ref->found_posts && 0 == $query_devotion->found_posts && 0 == $query_others->found_posts) : ?>
					<p>No articles found on this chapter.</p>
				<?php else: ?>
					<div class="list-articles with-line" id="listing">
						<h3 class="label-daily">Daily Devotionals</h3>
						<?php if ($query_devotion->have_posts()) : while ($query_devotion->have_posts()) : $query_devotion->the_post();
							$post_devotion = $query_devotion->post;
							$article_title = get_field('article_title', $post_devotion->ID) ?: get_the_title($post_devotion->ID);
							$article_date = get_field('article_date', $post_devotion->ID);
							if ($article_date === '19700101' || empty($article_date)) {
								$article_date = get_post_field('post_date', $post_devotion->ID);
							}
							$text_refrence = get_field('text_refrence', $post_devotion->ID);
							$author = get_field('author', $post_devotion->ID);
							$series_value = get_field('search_tag', $post_devotion->ID);
							$series_name = '';
							$series_path = '';
							$series_field = get_field_object('search_tag', $post_devotion->ID);
							if (is_array($series_field) && isset($series_field['choices'][$series_value])) {
								$series_name = $series_field['choices'][$series_value];
								$series_page = get_page_by_title($series_name);
								if ($series_page) $series_path = get_permalink($series_page);
							}
						?>
							<div class="clearfix item-daily">
								<p><?php echo !empty($article_date) ? date('d M Y', strtotime($article_date)) : ''; ?><br/>
									<strong><a href="<?php echo get_permalink($post_devotion->ID); ?>"><?php echo esc_html($article_title); ?></a></strong><br/>
									<?php echo ($text_refrence) ? 'Text: ' . esc_html($text_refrence) : '' ; ?>
								</p>
								<p><?php echo ($author) ? 'by ' . esc_html($author) : ''; ?>
									<?php echo ($author && $series_name)  ? ' | ' : ''; ?>
									<?php echo ($series_name) ? '<a href="'.esc_url($series_path).'">' . esc_html($series_name) . '</a>' : '';?></p>
								<?php generate_print_email_save(['url' => get_permalink($post_devotion->ID)]); ?>
							</div>
						<?php endwhile; endif; ?>

						<?php if ($query_others->have_posts()) : ?>
							<h3 class="label-others">Other Articles</h3>
							<?php while ($query_others->have_posts()) : $query_others->the_post();
								$post_others = $query_others->post;
								$messages = get_field('message', $post_others->ID);
								if ($messages) {
									$article_title = get_field('article_title', $messages->ID);
									$article_date = get_field('article_date', $messages->ID);
									$text_refrence = get_field('text_refrence', $messages->ID);
									$video_url = get_field('video_url', $messages->ID);
									$audio_url = get_field('audio_url', $messages->ID);
									$author = get_field('speaker', $messages->ID) ?: get_field('author', $messages->ID);
								} else {
									$article_title = get_field('article_title', $post_others->ID) ?: get_the_title($post_others->ID);
									$article_date = get_field('article_date', $post_others->ID);
									$text_refrence = get_field('text_refrence', $post_others->ID);
									$video_url = '';
									$audio_url = '';
									$author = get_field('speaker', $post_others->ID) ?: get_field('author', $post_others->ID);
								}
								if (empty($article_date) || $article_date === '19700101') {
									$article_date = get_post_field('post_date', $post_others->ID);
								}
								$series_value = get_field('search_tag', $post_others->ID);
								$series_name = '';
								$series_path = '';
								$series_field = get_field_object('search_tag', $post_others->ID);
								if (is_array($series_field) && isset($series_field['choices'][$series_value])) {
									$series_name = $series_field['choices'][$series_value];
									$series_page = get_page_by_title($series_name);
									if ($series_page) $series_path = get_permalink($series_page);
								}
							?>
							<div class="clearfix item-others <?php echo $video_url ? 'has-video' : ($audio_url ? 'has-audio' : ''); ?>">
								<p><?php echo !empty($article_date) ? date('d M Y', strtotime($article_date)) : ''; ?><br/>
									<strong><a href="<?php echo get_permalink($post_others->ID); ?>"><?php echo esc_html($article_title); ?></a></strong><br/>
									<?php echo ($text_refrence) ? 'Text: ' . esc_html($text_refrence) : ''; ?></p>
								<p><?php echo ($author) ? 'by ' . esc_html($author) : ''; ?>
									<?php echo ($author && $series_name)  ? ' | ' : ''; ?>
									<?php echo ($series_name) ? '<a href="'.esc_url($series_path).'">' . esc_html($series_name) . '</a>' : '';?></p>
								<?php generate_print_email_save([
									'url' => get_permalink($post_others->ID),
									'video_url' => $video_url,
									'audio_url' => $audio_url
								]); ?>
							</div>
							<?php endwhile; ?>
						<?php endif; ?>
					</div>
					<?php
					$total_count = $query_devotion->post_count + $query_others->post_count;
					?>
					<div class="pagination-wrp">
						<?php if ($total_count > 20): ?>
							<div class="pagination"></div>
						<?php else: ?>
							<div class="inline-block"></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</section><!--#end of Full-width Column-->
		</div><!--#end of Bible Study Resources -->
	</section><!--#end of Full-width Column-->
</section><!--#end of Content -->

<?php endwhile; wp_reset_postdata(); endif; ?>

<?php
function bsr_books_javascript() { ?>
	<script type="text/javascript">
	(function($) {
		$(document).ready(function() {
			$('#bsr_book_submit').click(function() {
				$('#bethany_bsr_books').submit();
			});
		});
	})(jQuery);
	</script>
<?php
}
add_action('wp_footer', 'bsr_books_javascript');
?>

<?php get_footer(); ?>


