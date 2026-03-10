<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Family Camp Series
 * This Template is used For Series Eg: Morning Worship -> Series -> Articles for that Series
 
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */
 
 // If the 'view' parameter is SET, display all messages of the category
 // Else, Display 'Messages' subpages for the series 

 
$camp_sessions = array(
	'morning' => 'Morning',
	'afternoon' => 'Afternoon',
	'evening' => 'Evening',
);
 
get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php
// get days count
$count_days = count(get_pages( array( 'child_of' => $post->ID, 'parent' => $post->ID ) ));
?>

<!-- Main Banner -->
<?php get_template_part('content/common', 'banner'); ?>	
<!--#end of Main Banner -->
	
<!-- include breadcrumbs -->
<?php get_template_part('content/common', 'breadcrumb'); ?>	

<!-- Content -->
<section id="content" class="clearfix">
	<!-- Full-width Column -->
	<section class="col-full wrapper clearfix">
		<?php 
		
		$start_date = get_field('start_date');
		if ( '19700101' == $start_date )
			$start_date = '';
			
		$end_date 	= get_field('end_date');
		if ( '19700101' == $end_date )
			$end_date = '';	
		?>		
		
		<?php 
		$grand_parent = $post->post_parent;
		$grand_parent_id = wp_get_post_parent_id($grand_parent);
		?>
		<h2 class="h2-lead">			
		<span><?php echo get_the_title($grand_parent_id); ?> Messages</span> &nbsp;|&nbsp;
		<?php 
		if(!empty($start_date)) {
			echo date('M Y', strtotime($start_date));
		}
		
		if(!empty($end_date)) {
			echo ' - ' . date('M Y', strtotime($end_date));
		}?>
		</h2>
		
		<h1><span class="numeric"><?php echo date('Y', strtotime($start_date)); ?></span>: <?php echo get_the_title(); ?></h1>
		<?php echo the_content(); ?>
		
		<!-- Main Column -->
		<section class="col-main">				
			<ul class="list-messages" id="listing">
			<?php					
			$args = array(
				'post_type' 	=> 'page',
				'post_status'	=> 'publish',
				'child_of'		=> get_the_ID(), 
				'post_parent' 	=> get_the_ID(), 
				'depth' 		=> 1, 
				'orderby' 		=> 'meta_value', 
				'meta_key' 		=> 'message_date', 
				'order' 		=> 'ASC',
				'posts_per_page'	=> -1
			);
			
			$day = isset( $_GET['camp_day'] ) ? absint( $_GET['camp_day'] ) : 0;
			
			if(!empty($day)) {
				$args['post_title_like'] = "Day $day";
				
				add_filter('posts_where', 'where_title_like', 10, 2);
			}
			
			$query	= new WP_Query($args);
			
			// remove the attached filter for WP_Query
			if (!empty($day))
				remove_filter('posts_where', 'where_title_like');
			
			if($query->have_posts()) :
			
				while($query->have_posts()) :
					$query->the_post();
					$post_day = $query->post;
					$message_date = get_field('message_date', $post_day->ID);
				?>
				<li class="clearfix">
					<h3><?php echo $post_day->post_title; ?> | <?php echo date('d M Y', strtotime($message_date)); ?></h3>
					<?php
					
					$args_articles = array(
						'post_type' 	=> 'page',
						'post_status'	=> 'publish',
						'child_of'		=> $post_day->ID,
						'post_parent' 	=> $post_day->ID,
						'depth' 		=> 1, 
						'orderby' 		=> 'date',						
						'order' 		=> 'ASC',
						'posts_per_page'	=> -1
					);
					
					$session = isset( $_GET['camp_session'] ) ? sanitize_text_field( wp_unslash( $_GET['camp_session'] ) ) : '';
			
					if(!empty($session)) {
						$meta_query = array(
								'relation' => 'AND',
							);
						
						$meta_query[] = array(
								'key' 		=> 'camp_session',
								'value' 	=> $session,
								'compare' 	=> '='
							);
					}
					
					if ( isset($meta_query) && !empty( $meta_query ) ) {
						$args_articles['meta_query'] = $meta_query;
					}
					
					$query_articles	= new WP_Query($args_articles);
			
					if($query_articles->have_posts()) :
					
						while($query_articles->have_posts()) :
							$query_articles->the_post();
							$post_articles = $query_articles->post;
							
							$messages = get_field( 'message', $post_articles->ID );
							$message_id = ( is_object( $messages ) && isset( $messages->ID ) ) ? $messages->ID : 0;
							
							$article_title		= $message_id ? get_field( 'article_title', $message_id ) : '';							
							$text_refrence		= $message_id ? get_field( 'text_refrence', $message_id ) : '';
							$speaker			= $message_id ? get_field( 'speaker', $message_id ) : '';
							
							$bulletin			= $message_id ? get_field( 'bulletin', $message_id ) : '';
							$bulletin_url 		= ( is_array( $bulletin ) && ! empty( $bulletin['id'] ) ) ? wp_get_attachment_url( $bulletin['id'] ) : '';
							
							if ( $message_id && get_field( 'notes', $message_id ) ) {
								$notes				= get_field( 'notes', $message_id );
								$notes_url			= ( is_array( $notes ) && ! empty( $notes['id'] ) ) ? wp_get_attachment_url( $notes['id'] ) : '';
							} else {
								$notes_url			= $message_id ? get_field( 'notes_url', $message_id ) : '';
							}
							
							$video_url			= $message_id ? get_field( 'video_url', $message_id ) : '';
							$audio_url			= $message_id ? get_field( 'audio_url', $message_id ) : '';							
						?>
							<p><strong><a href="<?php echo get_permalink($post_articles->ID); ?>"><?php echo $post_articles->post_title; ?></a></strong><br/>
							<?php echo ($text_refrence) ? 'Text: ' . $text_refrence : '' ; ?></p>
							<p><?php echo ($speaker) ? 'Speaker: ' . $speaker : '' ; ?></p>
							<!-- Media Bar -->
							<a class="media-bar clearfix" href="<?php echo get_permalink($post_articles->ID); ?>">
								<span class="media-bar-title">Watch video or listen to audio</span>
							</a><!--#end of Media Bar -->
							<!-- Resource Download -->
							
							<?php
												
							generate_download_media(
								array(
									'class'			=> 'tooltip',
									'url'			=> get_permalink(get_the_ID()),
									'bulletin_url'  => $bulletin_url,
									'notes_url'		=> $notes_url,
									'video_url' 	=> $video_url,
									'audio_url' 	=> $audio_url,
								)
							
							);
							
							?>
							
							<!--#end of Resource Download -->
						<?php endwhile; wp_reset_postdata(); // end of while($query_articles->have_posts()) ?>
					<?php else : ?>
						<p>No <?php echo $session ?> session found on this day.</p>
					<?php endif; wp_reset_query(); // end of if($query_articles->have_posts()) ?>
				</li>
				<?php endwhile; wp_reset_postdata(); // end of while($query->have_posts()) ?>
			<?php endif; wp_reset_query();// end of if ($query->have_posts()) ?>
									
			</ul>	

			<!-- Pagination -->
			<div class="pagination-wrp">
				<div class="pagination"></div>
			</div><!--#end of Pagination -->
			
			<!-- Previous & Next Theme -->
			<?php
			// PREV and NEXT pagination
			// Don't print empty markup if there's nowhere to navigate.			
			
			$args_nav = array(
				'post_type' 	=> 'page',
				'post_status'	=> 'publish',
				'child_of'		=> $post->post_parent,
				'post_parent' 	=> $post->post_parent,
				'depth' 		=> 1, 
				'orderby' 		=> 'meta_value', 
				'meta_key' 		=> 'start_date', 
				'order' 		=> 'ASC'
			);
			
			$query_nav	= new WP_Query($args_nav);
			
			$page_ids = array();
			
			if($query_nav->have_posts()) {	
				while($query_nav->have_posts()) {
					
					$query_nav->the_post();
					setup_postdata($post);					
					$post_nav = $post;				
					$page_ids[] = $post_nav->ID;				
				} wp_reset_postdata();
			} wp_reset_query();
			
			$current = array_search($post->ID, $page_ids);
			$prevID = $page_ids[$current-1];
			$nextID = $page_ids[$current+1];			
			?>
			
			<div class="theme-prev-next">
			<?php 
			if (!empty($prevID)) { ?>
				
				<a class="btn-secondary btn-gray" href="<?php echo get_permalink($prevID); ?>"><i class="ion-ios-arrow-left"></i> &nbsp; Previous Theme</a>
				
			<?php 
			} 
			if(!empty($nextID)) { ?>				
				<a class="btn-secondary btn-gray" href="<?php echo get_permalink($nextID); ?>">Next Theme &nbsp; <i class="ion-ios-arrow-right"></i></a>
			<?php
			}
			?>
			</div><!--#end of Previous & Next Theme -->
			
		</section><!--#end of Main Column -->
		<!-- Side Column -->
		<section class="col-side">
			<div class="box-side sticky-box">
				<div class="box-side-top clearfix">
					<h3>Find message from this camp</h3>
				</div>
				<div class="box-side-content">
					<form action="" method="GET">
						<fieldset>
							<!--<div class="field-wrp field-search">
								<input type="text" placeholder="Search" class="full-width" />
								<a class="i-search ion-ios-search-strong" href="javascript:;"></a>
							</div>
							<h4>Date</h4>-->
							<div class="field-wrp field-half clearfix">
								<select name="camp_session" class="select-session">
									<?php foreach($camp_sessions as $key => $session) : ?>
									<option value="<?php echo $key;  ?>" <?php echo (isset($_GET['camp_session']) && $key == $_GET['camp_session']) ? 'selected':'' ?>><?php echo $session; ?></option>
									<?php endforeach; ?>
								</select>
								<select name="camp_day" class="select-day">
									<?php for ( $i = 1; $i <= $count_days; $i++ ) : ?>
									<option value="<?php echo $i; ?>" <?php echo (isset($_GET['camp_day']) && $i == $_GET['camp_day']) ? 'selected':'' ?>><?php echo "Day $i"; ?></option>
									<?php endfor; ?>
								</select>
								<p><button type="submit" class="btn-primary btn-gray">Search Messages</button></p>
							</div>
							<div class="field-wrp">
								<?php $parent_post = get_post( $post->post_parent ); $messages_url = $parent_post ? get_permalink( $parent_post->post_parent ) : ''; ?>
								<p><a href="<?php echo $messages_url; ?>">View all messages</a></p>
							</div>
						</fieldset>
						
					</form>
				</div>
			</div>
		</section><!--#end of Side Column -->
		
	</section> <!--#end of Full-width Column -->
	</section><!--#end of Content -->

<?php endwhile; endif; ?>

<?php get_footer(); ?>
