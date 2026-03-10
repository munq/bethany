<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * The Header for our theme
 *
 * Displays all of the <head> section
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */
?><!DOCTYPE html>

<html class="no-js" lang="">
<!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta charset="<?php bloginfo( 'charset' ); ?>">	
    <!--<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php bloginfo('name'); ?><?php wp_title( ' |', true, 'left' ); ?></title>

	<link rel="shortcut icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/favicon.png">
	<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/favicon.png">

	<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_template_directory_uri(); ?>/favicon-76x76.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_template_directory_uri(); ?>/favicon-120x120.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_template_directory_uri(); ?>/favicon-152x152.png">

	<!-- Custom Styles -->
	<link href="<?php echo get_template_directory_uri(); ?>/assets/css/normalize.min.css?t=<?php echo filemtime(get_template_directory() . '/assets/css/normalize.min.css'); ?>" rel="stylesheet" type="text/css">
	
	<link href="<?php echo get_template_directory_uri(); ?>/assets/css/main.css?t=<?php echo filemtime(get_template_directory() . '/assets/css/main.css'); ?>" rel="stylesheet" type="text/css">
	
	<link href="<?php echo get_template_directory_uri(); ?>/assets/css/typeface.css?t=<?php echo filemtime(get_template_directory() . '/assets/css/typeface.css'); ?>" rel="stylesheet">	
	
	<link href="<?php echo get_template_directory_uri(); ?>/assets/css/media.css?t=<?php echo filemtime(get_template_directory() . '/assets/css/media.css'); ?>" rel="stylesheet">
	
	<link href="<?php echo get_template_directory_uri(); ?>/assets/css/bethany_custom.css?t=<?php echo filemtime(get_template_directory() . '/assets/css/bethany_custom.css'); ?>" rel="stylesheet">
	
	<!-- Javascript -->
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/vendor/modernizr-2.8.3.min.js"></script>
	<script type="text/javascript" src="//wurfl.io/wurfl.js"></script>
	
	<?php 
	/*
	 * Force to show page in print version if they have print_version=1 in the URL
	 * Else, just use print css when they want to print the page.
	 */
	?>
	<?php if ( isset($_GET['print_version']) && !empty($_GET['print_version']) ) : ?>
	<link href="<?php echo get_template_directory_uri(); ?>/assets/css/print.css?t=<?php echo filemtime(get_template_directory() . '/assets/css/print.css'); ?>" rel="stylesheet">
	<?php else :  ?>
	<link href="<?php echo get_template_directory_uri(); ?>/assets/css/print.css?t=<?php echo filemtime(get_template_directory() . '/assets/css/print.css'); ?>" rel="stylesheet" media="print">
	<?php endif;  ?>
	<?php //END ?>
	
	<?php wp_head(); ?>
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	 
	  ga('create', 'UA-50746733-1', 'auto');
	  ga('send', 'pageview');
	</script>

<!-- Global site tag (gtag.js) - Google Analytics - Webmaster Account -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-129103696-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-129103696-1');
</script>
	
</head>

<body <?php body_class(); ?>>

	<!-- <div id="preloader"></div> -->

	<div id="container">
	
		<!-- Top -->
		<header id="top">
		<?php include get_template_directory() . '/content/menus/top-menu.php';?>
		<!-- Main Nav -->
		<?php include get_template_directory() . '/content/menus/main-menu.php';?>
			
		</header><!--#end of Top -->
