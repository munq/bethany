<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
	
if (have_posts()) : while (have_posts()) : the_post();
?>

<!-- Main Banner -->
<?php get_template_part('content/common', 'banner'); ?>	

<!-- include breadcrumbs -->
<?php get_template_part('content/common', 'breadcrumb'); ?>	

<!-- Content -->
<section id="content" class="clearfix">
	<section class="col-full wrapper clearfix">
	<?php the_content(); ?>
	</section><!--#full wrapper clearfix -->
</section><!--#end of Content -->

<?php endwhile; endif; ?>