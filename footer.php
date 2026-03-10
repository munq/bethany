<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */
?>
<!-- Footer -->
	<footer id="footer">
		<div class="wrapper">
		
			<?php if ( is_active_sidebar( 'bethany_footer_top' ) ) : ?>
				<ul id="footer-top" class="clearfix">
					<?php dynamic_sidebar( 'bethany_footer_top' ); ?>
				</ul>
			<?php endif; ?>
			
			<?php if ( is_active_sidebar( 'bethany_footer_bottom' ) ) : ?>
				<ul id="footer-links" class="clearfix">
					<?php dynamic_sidebar( 'bethany_footer_bottom' ); ?>
				</ul>
			<?php endif; ?>
			
		</div>
		<div id="footer-copyright">			
			<div class="wrapper">&copy; <?php echo date('Y'); ?> &nbsp;Bethany Independent-Presbyterian Church</div>
		</div>
	</footer><!--#end of Footer -->

</div><!--#end of Container -->

<div id="back-top" class="mobile-only">
	<a href="#"><i class="ion-arrow-up-a"></i></a>
</div>

<script type="text/javascript" data-cfasync="false" src="<?php echo get_template_directory_uri(); ?>/assets/js/vendor/jquery-2.1.3.min.js"></script>
<script type="text/javascript" data-cfasync="false" src="<?php echo get_template_directory_uri(); ?>/assets/js/vendor/jquery-ui-1.11.2.min.js"></script>
<script type="text/javascript" data-cfasync="false" src="<?php echo get_template_directory_uri(); ?>/assets/js/plugins.js?t=<?php echo filemtime(get_template_directory() . '/assets/js/plugins.js'); ?>"></script>
<script type="text/javascript" data-cfasync="false" src="<?php echo get_template_directory_uri(); ?>/assets/js/main.js?t=<?php echo filemtime(get_template_directory() . '/assets/js/plugins.js'); ?>"></script>
<script type="text/javascript" data-cfasync="false" src="<?php echo get_template_directory_uri(); ?>/assets/js/custom.js?t=<?php echo filemtime(get_template_directory() . '/assets/js/custom.js'); ?>"></script>
<script type="text/javascript" src="http://www.youtube.com/iframe_api"></script>

<?php wp_footer(); ?>	

</body>
</html>	
