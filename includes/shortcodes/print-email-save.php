<?php
/**
 * This function will generate print, email, and save as pdf links
 *
 * @return String
 * @author Tami
 * @param $atts Array
 */
function shortcode_print_email_save( $atts ){
	extract(shortcode_atts(array(
			'class'			=> '', // extra class
			'url' 			=> '',
			'notes_url' 		=> '',
			'video_url'		=> '',
			'audio_url'		=> '',
			'email_subject'	=> '',
		), $atts));
		
	ob_start();
	?>
	
	<ul class="nav-download nav-features clearfix <?php echo !empty($class) ? $class:''; ?>">										
		
		<li class="video <?php echo (empty($video_url)) ? 'disabled' : '' ; ?>"><a class="tooltip" target="_blank" href="<?php echo $video_url ?>"><span class="tooltip-content">Download Video</span></a></li>		
		
		<li class="audio <?php echo (empty($audio_url)) ? 'disabled' : '' ; ?>"><a class="tooltip" target="_blank" href="<?php echo $audio_url ?>"><span class="tooltip-content">Download Audio</span></a></li>
				
		<li class="print"><a class="tooltip" href="javascript:;" data-url="<?php echo $url . ((FALSE !== strpos($url, '?')) ? '&':'?') . 'print_version=1'; ?>"><span class="tooltip-content">Print</span></a></li>
		
		<li class="email"><a class="tooltip" target="_blank" href="javascript:;" data-subject="<?php echo $email_subject ? $email_subject:'Share this article'; ?>" data-url="<?php echo esc_url($url); ?>"><span class="tooltip-content">Email</span></a></li>
		
		<li class="save <?php echo (empty($notes_url)) ? 'disabled' : '' ; ?>"><a class="tooltip" target="_blank" href="javascript:;" data-pdf-url="<?php echo $notes_url; ?>"><span class="tooltip-content">Save As PDF</span></a></li>
		
	</ul>
	<?php
	
	$return = ob_get_clean();
	
	return $return;
}

function generate_print_email_save( $args ) {
	
	$atts = array();
	
	foreach( $args as $key => $value ) {
		$atts[] = $key . '="' . $value . '"';
	}
	
	if (shortcode_exists('print_email_save'))
		echo do_shortcode('[print_email_save ' . implode(' ', $atts) . ']');
}

function print_email_save_javascript() {
?>
<script type="text/javascript">
	(function($) {
		$(document).ready(function() {
			$('.nav-download .print a').click(function(e) {
				e.preventDefault();
				
				var data_url = $(this).data('url');
				console.log(data_url);
				var winprint = window.open(data_url, "_blank", "toolbar=no, scrollbars=no, resizable=no, top=0, left=0, width=640, height=480");
				
				winprint.focus();
				winprint.print();
			});
			
			$('.nav-download .email a').click(function(e) {
				var data_subject = $(this).data('subject');
				var data_url = $(this).data('url');
				window.location.href = 'mailto:recipient@domain.com?subject=' + data_subject + '&body=' + data_url;
			});
			
			$('.nav-download .save a').click(function(e) {
				var data_pdf_url = $(this).data('pdf-url');
				if (data_pdf_url)
					window.open(data_pdf_url, '_blank');
			});
		});
	})(jQuery);
</script>
<?php
}

add_shortcode( 'print_email_save', 'shortcode_print_email_save' );
add_action( 'wp_footer', 'print_email_save_javascript' ); // add the js first before the ajax js