<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Ecards Sent
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */ 

//$remote_ip = "";
$remote_ip = get_the_user_ip();
$privatekey = "6LeE6w4UAAAAAGnAFoO-psNz1m_kSneSTYJLr2ym";
$gglcptch_get_response = gglcptch_get_response($privatekey,$remote_ip);

if ( $gglcptch_get_response['success'] !== true ) {
  wp_die("Sorry Can not access directly");
}

if ( ! empty( $_POST ) ) { 
	get_header(); ?>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<!-- Main Banner -->
	<?php get_template_part('content/common', 'banner'); ?>	
	<!-- End Main Banner -->

	<!-- include breadcrumbs -->
	<?php get_template_part('content/common', 'breadcrumb'); ?>

	<?php
	$category_id 	= isset( $_POST['category_id'] ) ? absint( $_POST['category_id'] ) : 0;
	$cat_slug 		= $category_id ? get_category( $category_id ) : null;
	$cat_name 		= ( $cat_slug && ! is_wp_error( $cat_slug ) ) ? $cat_slug->slug : '';
	$exclude_post 	= isset( $_POST['exclude_post'] ) ? absint( $_POST['exclude_post'] ) : 0;

	add_filter( 'wp_mail_content_type', function( $content_type ) {
		return 'text/html';
	});

	$from_name 			= isset( $_POST['from_name'] ) ? sanitize_text_field( wp_unslash( $_POST['from_name'] ) ) : '';
	$from_email 		= isset( $_POST['from_email'] ) ? sanitize_email( wp_unslash( $_POST['from_email'] ) ) : '';
	$recipient_email 	= isset( $_POST['recipient_email'] ) && is_array( $_POST['recipient_email'] ) ? array_filter( array_map( 'sanitize_email', wp_unslash( $_POST['recipient_email'] ) ) ) : array();
	$recipient_name 	= isset( $_POST['recipient_name'] ) && is_array( $_POST['recipient_name'] ) ? array_filter( array_map( 'sanitize_text_field', wp_unslash( $_POST['recipient_name'] ) ) ) : array();
	$bgcolor	 		= isset( $_POST['bgcolor'] ) ? sanitize_text_field( wp_unslash( $_POST['bgcolor'] ) ) : '';
	$font_color	 		= isset( $_POST['font_color'] ) ? sanitize_text_field( wp_unslash( $_POST['font_color'] ) ) : '';
	$image_src	 		= isset( $_POST['image_src'] ) ? esc_url_raw( wp_unslash( $_POST['image_src'] ) ) : '';
	$message	 		= isset( $_POST['ecard_html'] ) ? wp_unslash( $_POST['ecard_html'] ) : '';
	$email_sent_conf = array();
	$email_notsent_conf = array();

	//$headers  = "MIME-Version: 1.0" . "\r\n";
	//$headers .= "Content-type: text/html; charset=".get_bloginfo('charset')."" . "\r\n";
	$headers .= "From: Bethany eCard <webmaster@bethanyipc.sg>\r\n";
	$headers .= 'Reply-To: "'.$from_name.'" <'.$from_email.'>';

	for($i=0; $i<count($recipient_email); $i++) {
		$recipient_name_value = isset( $recipient_name[$i] ) ? $recipient_name[$i] : '';

		$email_template = file_get_contents(get_template_directory() . '/content/ecards/email-template.php');
		$email_template = str_replace('%bgcolor%', $bgcolor, $email_template);
		$email_template = str_replace('%font_color%', $font_color, $email_template);
		$email_template = str_replace('%image_src%', $image_src, $email_template);

		if ( empty($message) ) {
			$email_template = str_replace('%recipient_name%', "", $email_template);
		} else {
			$email_template = str_replace('%recipient_name%', $recipient_name_value . ",", $email_template);
		}

		$email_template = str_replace('%message%', $message, $email_template);
		$body = $email_template;

		if(wp_mail( $recipient_email[$i], $from_name.' has sent you an eCard', $body, $headers )) {
			$email_sent_conf[] = $recipient_name_value;
		}
		else {
			$email_notsent_conf[] = $recipient_name_value;
		}
	}
	?>

	<!-- Content -->
	<section id="content" class="clearfix">
		<!-- Full-width Column -->
		<section class="col-full wrapper clearfix">
			<h2 class="h2-lead">Poems</h2>
			<h2>eCards</h2>

			<div id="bethany_ecard_item_preview" class="hidden">
				<h3>Preview</h3>
				<div class="ecard-preview-box box-generic"></div>
				<br />
				<p><a href="<?php echo get_permalink($post->post_parent); ?>">View more eCards</a></p>
			</div>

			<div id="ecard-sent">
			<?php if(!empty($email_notsent_conf)) { ?>
					<!-- Full-width Column -->
					<section class="col-full clearfix">
						<div class="box-generic txt-center">
							<h3>Your eCard was not sent to</h3>
							<p><?php echo implode(', ', $email_notsent_conf); ?></p>
							<!--<p><a class="btn-primary btn-ecard-preview" href="javascript:;">View eCard</a></p>-->
							<p><a href="<?php echo get_permalink($post->post_parent); ?>">View more eCards</a></p>
						</div>
						<hr/>
					</section><!--#end of Full-width Column -->
			<?php } else { ?>
					<!-- Full-width Column -->
					<section class="col-full clearfix">
						<div class="box-generic txt-center">
							<h3>Your eCard has been sent!</h3><br/>
							<p><a class="btn-primary btn-ecard-preview" href="javascript:;">View eCard</a></p>
							<p><a href="<?php echo get_permalink($post->post_parent); ?>">View more eCards</a></p>
						</div>
						<hr/>
					</section><!--#end of Full-width Column -->
			<?php }
			remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
			?>

			<!-- Full-width Column -->
			<section class="col-full clearfix">
				<!-- Wall Display -->
				<ul class="grid-gallery wall-display g-four match-height clearfix">
					<li class="box-gray">
						<div class="txt-center">
							<h3>Related eCards</h3>
							<p><a class="link-txt" href="<?php echo get_permalink($post->post_parent).'?ecard_category='.$cat_name; ?>">View all</a></p>
						</div>
					</li>
					<?php
					if(isset($_POST) && !empty($_POST)) {
						
						if ( $category_id ) {						
							$args = array(
								'post_type' 		=> 'page',
								'post_status'		=> 'publish',
								'category__in'		=> $category_id,
								'orderby' 			=> 'menu_order',
								'posts_per_page'	=> 6,
								'post__not_in' 		=> array($exclude_post),
								'meta_query' => array(
									'relation' => 'AND',
									array(
									'key' 		=> '_wp_page_template',
									'value' 	=> 'templates-poems/ecards-items.php',
									'compare' 	=> '=',
								  ),
								)						
							);
							
							$query = new WP_Query($args);
							
							if ($query->have_posts()) {
								
								while ($query->have_posts()) {
									$query->the_post();
									$post_ecards = $post;
									
									$featured_image_url = wp_get_attachment_url(get_post_thumbnail_id($post_ecards->ID));
								?>
									<li>
										<figure class="box-img">
											<img src="<?php echo $featured_image_url; ?>" alt="" />
											<figcaption>
												<div><?php echo get_the_title($post_ecards->ID); ?></div>
												<a href="<?php echo get_permalink($post_ecards->ID); ?>"></a>
											</figcaption>
										</figure>
									</li>
								<?php
								}						
							} wp_reset_query();	
						}
						else {
							echo 'No Related eCards';
						}
					} else {
						echo 'No Related eCards';
					}				
					?>				
				</ul><!--#end of Wall Display -->
			</section><!--#end of Full-width Column -->		
			</div>

		</section><!--#end of Full-width Column -->
	</section><!--#end of Content -->

	<?php endwhile; endif; ?>


	<?php function ecards_sent_javascript() { ?>
		<script type="text/javascript" >
			(function($) {
				$(document).ready(function() {
					console.log('ecards sent js');
					$('.btn-ecard-preview').unbind('click');
					$('.btn-ecard-preview').click(function(){
						$('#bethany_ecard_item_preview').removeClass('hidden');
						$('#ecard-sent').addClass('hidden');
						var image = '<image src="<?php echo esc_js( $image_src ); ?>" />';
						var ecard_html = '<?php echo esc_js( $message ); ?>';
						$('.ecard-preview-box').css('background-color', '#fff').html(ecard_html + '<br /><br />' + image);
						$(window).scrollTop(0);
					});

				});
			})(jQuery);
		</script>
		<?php
	}
	add_action( 'wp_footer', 'ecards_sent_javascript' );
	?>

	<?php
	get_footer();
	//print_r($_POST);
} else {
	wp_redirect(site_url());
}
?>
