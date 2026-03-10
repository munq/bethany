<?php
/**
 * This function will generate print, email, and save as pdf links
 *
 * @return String
 * @author Tami
 * @param $atts Array
 */
function shortcode_download_media( $atts ){
	extract(shortcode_atts(array(
			'class'			=> '', // extra class
			'url' 			=> '',
			'bulletin_url'	=> '',
			'notes_url'		=> '',
			'video_url'		=> '',
			'audio_url'		=> '',			
		), $atts));
		
	ob_start();
	?>
	
	<ul class="nav-download clearfix">
		<li class="bulletin <?php echo ($bulletin_url) ? '' : 'disabled'; ?>"><a class="<?php echo !empty($class) ? $class:''; ?>" target="_blank" href="<?php echo $bulletin_url;?>"><span class="<?php echo !empty($class) ? 'tooltip-content' :''; ?>" >Bulletin</span></a></li>	
		
		<li class="notes <?php echo ($notes_url) ? '' : 'disabled'; ?>"><a class="<?php echo !empty($class) ? $class:''; ?>" target="_blank" href="<?php echo $notes_url;?>"><span class="<?php echo !empty($class) ? 'tooltip-content' :''; ?>" >Notes</span></a></li>
		
		<li class="video <?php echo ($video_url) ? '' : 'disabled'; ?>"><a class="<?php echo !empty($class) ? $class:''; ?>" target="_blank" href="<?php echo $video_url;?>"><span class="<?php echo !empty($class) ? 'tooltip-content' :''; ?>" >Download Video</span></a></li>
		
		<li class="audio <?php echo ($audio_url) ? '' : 'disabled'; ?>"><a class="<?php echo !empty($class) ? $class:''; ?>" target="_blank" href="<?php echo $audio_url;?>"><span class="<?php echo !empty($class) ? 'tooltip-content' :''; ?>" >Download Audio</span></a></li>
	</ul>
	
	<?php
	
	$return = ob_get_clean();
	
	return $return;
}


function generate_download_media( $args ) {
	
	// check if $args is integer
	// if integer, then it means it is a post_id. Then you need to get the links first then do shortcode
	// else if it is array then you don't need to grab the links, then do the shortcode directly
	
	if (is_int($args))
		$atts = get_download_media_links( $args );
	
	//$atts = array();
	
	foreach( $args as $key => $value ) {
		$atts[] = $key . '="' . $value . '"';
	}
	
	if (shortcode_exists('download_media'))
		echo do_shortcode('[download_media ' . implode(' ', $atts) . ']');
}

function get_download_media_links( $post_id ) {
	
	
	
	// return array
}

add_shortcode( 'download_media', 'shortcode_download_media' );
