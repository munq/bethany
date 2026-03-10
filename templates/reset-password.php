<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Reset Password

 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */

if(is_user_logged_in()) {
    wp_redirect(site_url());
}

get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <!-- Main Banner -->
    <?php get_template_part('content/common', 'banner'); ?>
    <!--#end of Main Banner -->

    <!-- include breadcrumbs -->
    <?php get_template_part('content/common', 'breadcrumb'); ?>

    <!-- Content -->
    <section id="content" class="clearfix">
        <!-- Full-width Column -->
        <section class="col-full wrapper clearfix">
            <h1>Reset Password</h1>
            <?php echo the_content(); ?>
        </section> <!--#end of Full-width Column -->
    </section><!--#end of Content -->

<?php endwhile; endif; ?>

<?php get_footer(); ?>
