<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Ecards Items
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */ 
 
//$remote_ip = get_the_user_ip();
//var_dump($remote_ip);
//var_dump($_POST);
//$privatekey = "6LeE6w4UAAAAAGnAFoO-psNz1m_kSneSTYJLr2ym";
//var_dump(gglcptch_get_response($privatekey,$remote_ip));
//var_dump(gglcptch_check());
get_header(); 
?>
<?php if (have_posts()) : while (have_posts()) : the_post();?>

<!-- Main Banner -->
<?php get_template_part('content/common', 'banner'); ?>	
<!-- End Main Banner -->

<!-- include breadcrumbs -->
<?php get_template_part('content/common', 'breadcrumb'); ?>

<!-- Content -->
<section id="content" class="clearfix">
	<!-- Full-width Column -->
	<section class="col-full wrapper clearfix">
		<h2 class="h2-lead">Poems</h2>
		<h2>eCards</h2>
		
		<!-- Full-width Column -->
		<section id="ecard" class="col-full clearfix">
			<?php
			$ecard_image = get_field('ecard_image');
			?>
			
			<p><a class="link-arrow-back" href="<?php echo get_permalink($post->post_parent); ?>">Back to eCards gallery</a></p>
			<div id="gallery_content">
			<h1><?php echo get_the_title(); ?></h1>
			<div class="box-img">
				<img src="<?php echo ($ecard_image) ? $ecard_image : wp_get_attachment_url(get_post_thumbnail_id($post_ecards->ID)); ?>" />
			</div>
			</div>


			<textarea name="ecard_editor" class="full-width" id="ecard-editor"><p>Type your message here</p></textarea>
			
			<div id="bethany_ecard_item_preview" class="hidden"> 				
				<h3>Preview</h3>
				<div class="ecard-preview-box box-generic"></div>
				<br/>
				<p><a id="back_to_ecard" class="link-arrow-back" href="javascript:;">Back to eCard</a> 
				<a class="btn-primary btn-submit hidden" id="ecard_submit_prev" href="javascript:;">Send</a></p>				
			</div>
			
			<?php
			$post_categories = get_the_category( get_the_ID() );
			
			$cats = array();
			foreach( $post_categories as $cat) {
				$cats[] = $cat->term_id ;
			}
			
			?>
			<!-- Main Column -->
			<section class="col-main" style="">
			<form id="bethany_ecard_item" method="POST" action="<?php echo get_permalink(get_page_by_path('poems/ecards/ecards-sent/')->ID); ?>">		
			<!-- <form id="bethany_ecard_item" method="POST" action="<?php echo get_permalink(get_page_by_path('poems/ecards/ecards-items/')->ID); ?>">	-->		
				<input type="text" class="hidden" name="bgcolor" value=""/>
				<input type="text" class="hidden" name="category_id" value="<?php echo $cats[0]; ?>"/>
				<input type="text" class="hidden" name="exclude_post" value="<?php echo get_the_ID(); ?>"/>
				<input type="text" class="hidden" name="image_src" value="" />				
				<textarea class="hidden" name="ecard_html"></textarea>
				
				<fieldset>
					<h3>From</h3>
					<div class="field-wrp">
						<div class="show-error">
							<input type="text" class="required" name="from_name" placeholder="Name" />
							<p class="msg-error">Please fill in your name</p>
						</div>					
					</div>
					
					<div class="field-wrp">
						<div class="show-error">
							<input type="text" class="email required" name="from_email" placeholder="Email" />
							<p class="msg-error">Please fill in your email</p>
						</div>
					</div>
				</fieldset>
				
				<fieldset>
					<h3>
						To<br/>
						<small class="font-normal">Enter recipients’ name and email.</small>
					</h3>
					
					<div class="field-wrp">						
						<div class="show-error">
							<input type="text" class="required" name="recipient_name[]" placeholder="Name" />
							<p class="msg-error">Please fill in recipient's name</p>
						</div><br/>
						<div class="show-error">	
							<input type="text" class="email required" name="recipient_email[]" placeholder="Email" />
							<p class="msg-error">Please fill in recipient's email</p>
						</div>
					</div>
					
					<div class="field-wrp add-line hidden">
						<div class="show-error">
						<input type="text" class="required" name="recipient_name[]" placeholder="Name" />
						<p class="msg-error">Please fill in recipient's name</p>
						</div><br/>
						<div class="show-error">
							<input type="text" class="email required" name="recipient_email[]"  placeholder="Email" />
							<p class="msg-error">Please fill in recipient's email</p>
						</div>
					</div>
					
					<div class="field-wrp add-line hidden">
						<div class="show-error">
							<input type="text" class="required" name="recipient_name[]" placeholder="Name" />
							<p class="msg-error">Please fill in recipient's name</p>
						</div><br/>
						<div class="show-error">
							<input type="text" class="email required" name="recipient_email[]"  placeholder="Email" />
							<p class="msg-error">Please fill in recipient's email</p>
						</div>
					</div>
					
					<div class="field-wrp add-line hidden">
						<div class="show-error">
							<input type="text" class="required" name="recipient_name[]" placeholder="Name" />
							<p class="msg-error">Please fill in recipient's name</p>
						</div><br/>					
						<div class="show-error">
							<input type="text" class="email required" name="recipient_email[]"  placeholder="Email" />
							<p class="msg-error">Please fill in recipient's email</p>
						</div>
					</div>
					
					<div class="field-wrp add-line hidden">
						<div class="show-error">
						<input type="text" class="required" name="recipient_name[]" placeholder="Name" />
						<p class="msg-error">Please fill in recipient's name</p>
						</div><br/>
						<div class="show-error">
							<input type="text" class="email required" name="recipient_email[]"  placeholder="Email" />
							<p class="msg-error">Please fill in recipient's email</p>
						</div>
					</div>
					
					<div class="field-wrp add-line last hidden">
						<div class="show-error">
							<input type="text" class="required" name="recipient_name[]" placeholder="Name" />
							<p class="msg-error">Please fill in recipient's name</p>
						</div><br/>
						<div class="show-error">
							<input type="text" class="email required" name="recipient_email[]"  placeholder="Email" />
							<p class="msg-error">Please fill in recipient's email</p>
						</div>
					</div>
					

				</fieldset>
			    <div style="display:non--e">
				<?php  //if( function_exists( 'gglcptch_display' ) ) { echo gglcptch_display(); }; ?>
				<?php //echo do_shortcode('[bws_google_captcha]'); ?>
			    </div>

				<fieldset>
					<div class="field-wrp">
						<div class="g-recaptcha-show-error">
							<div class="g-recaptcha" data-sitekey="6LeE6w4UAAAAAD1hX0kZDhIPP_MZMPCJa1abqBjI"></div>
							<p class="msg-error">Please verify captcha</p>
						</div><br/>
					</div>
				</fieldset>
				<div class="buttons-wrp">
					<a class="btn-primary btn-gray btn-ecard-preview" href="javascript:;">Preview</a>
					<a class="btn-primary btn-submit" id="ecard_submit" href="javascript:;">Send</a>
				</div>
			</form>
		</section>
		</section><!--end#Main Column -->
	</section> <!--#end of Full-width Column -->
</section><!--#end of Content -->

<?php endwhile; endif; ?>

<?php function ecards_javascript() { ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script type="text/javascript" >
	(function($) {
		$(document).ready(function() {



			
			var validateEmail = function(email) {
				var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
				
				var valid = emailReg.test(email);

				if(!valid)
				{
					return false;
				} 
				else
				{
					return true;
				}
			};
			
			$('#ecard_submit, #ecard_submit_prev').click(function(e){
				//console.log('hello');
				e.preventDefault();
				var ecard_form = $(this).closest('#bethany_ecard_item');				
				var form_valid  = new Array() ;
				var i = 0;
				var response = grecaptcha.getResponse();


				ecard_form.find('.required').each(function(i, element) {		
					//console.log($(element));
					
					if(!$(element).closest('.field-wrp').hasClass('hidden')) {
						if($(element).hasClass('email')){
							if(!validateEmail($(element).val())){
								$(element).closest('.show-error').find('.msg-error').css('display', 'block');
								form_valid[i] = 'false';
							}
							else {
								$(element).closest('.show-error').find('.msg-error').hide();
								form_valid[i] = 'true';
							}
						} else if($(element).val() == '') {
							$(element).closest('.show-error').find('.msg-error').css('display', 'block');
							form_valid[i] = 'false';
						}
						else {
							$(element).closest('.show-error').find('.msg-error').hide();
							form_valid[i] = 'true';
						} 


					}
				});
				//console.log(response);
				//console.log(response.length);
				if (response.length == 0) {
					$('.g-recaptcha-show-error .msg-error').show();
					return false;
				} else {
					$('.g-recaptcha-show-error .msg-error').hide();
				}

				if($.inArray("false", form_valid) === - 1){
					
					var image = $('.box-img').html();					
					var ecard_bg = $('.jHtmlArea iframe').contents().find('body').css('background-color');
					//var font_color = $('.jHtmlArea iframe').contents().find('p').css('text-color');
					var ecard_editor = $('#ecard-editor').val();
					if (ecard_editor == '<p style="display: none;">Type your message here</p>' || ((ecard_editor == '<p>Type your message here</p>'))) {
						ecard_editor = "";
					}
					console.log('ecard_editor = ' +ecard_editor);
					$('textarea[name=ecard_html]').html(ecard_editor);
					$('input[name=bgcolor]').val(ecard_bg);
					//$('input[name=font_color]').val(ecard_bg);
					$('input[name=image_src]').val($(image).attr('src'));
					
					$('#bethany_ecard_item').submit();
				}



				return false;
			});
			$('.btn-ecard-preview').unbind('click');
			
			$('.btn-ecard-preview').click(function(){
				
				$('#bethany_ecard_item_preview').removeClass('hidden');
				$('#bethany_ecard_item').addClass('hidden');
				$('#gallery_content').addClass('hidden');
				$('.jHtmlArea').addClass('hidden');
				
				var image = $('.box-img').html();
				var ecard_bg = $('.jHtmlArea iframe').contents().find('body').css('background-color');				
				var ecard_editor = $('#ecard-editor').val();
				//console.log(ecard_editor);
				
				$('.ecard-preview-box').css('background-color', ecard_bg).html(ecard_editor + '<br /><br />' + image);
				
				$('textarea[name=ecard_html]').html(ecard_editor);
				$('input[name=bgcolor]').val(ecard_bg);
				//$('input[name=font_color]').val(ecard_bg);
				$('input[name=image_src]').val($(image).attr('src'));				
				
				var ecard_form = $(this).closest('#bethany_ecard_item');				
				var form_valid  = new Array() ;
				var i = 0;				
				
				ecard_form.find('.required').each(function(i, element) {		
					//console.log($(element));
					
					if(!$(element).closest('.field-wrp').hasClass('hidden')) {
						if($(element).hasClass('email')){
							if(!validateEmail($(element).val())){
								$(element).closest('.show-error').find('.msg-error').css('display', 'block');
								form_valid[i] = 'false';
							}
							else {
								$(element).closest('.show-error').find('.msg-error').hide();
								form_valid[i] = 'true';
							}
						} else if($(element).val() == '') {
							$(element).closest('.show-error').find('.msg-error').css('display', 'block');
							form_valid[i] = 'false';
						}
						else {
							$(element).closest('.show-error').find('.msg-error').hide();
							form_valid[i] = 'true';
						}
					}
				});
				
				if($.inArray("false", form_valid) === - 1){					
					$('#ecard_submit_prev').removeClass('hidden');
				}
				
				$(window).scrollTop(0);
			});
			
			$('#back_to_ecard').click(function(){
				$('#bethany_ecard_item_preview').addClass('hidden');
				$('#bethany_ecard_item').removeClass('hidden');
				$('#gallery_content').removeClass('hidden');
				$('.jHtmlArea').removeClass('hidden');
				$('#ecard_submit_prev').addClass('hidden');
			});
			
			$('.grid-close').click(function(){
				$(this).closest('.field-wrp').find('.msg-error').hide();		
				$(this).closest('.field-wrp').find('input:text').val('');
			});
			
		});
	})(jQuery);	
	</script>
<?php 
}
add_action( 'wp_footer', 'ecards_javascript' );
?>
<?php get_footer(); ?>
