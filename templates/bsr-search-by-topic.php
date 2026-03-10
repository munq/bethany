<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: BSR Search by Topic
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */

get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php
$book = isset($_GET['book']) ? sanitize_text_field($_GET['book']) : '';
$chapter = isset($_GET['chapter']) ? sanitize_text_field($_GET['chapter']) : '';
$val = $chapter;

// Fallback for banner image
$banner_image_url = get_field('banner_image') ?: get_template_directory_uri() . '/assets/images/default-banner.jpg';
?>

<!-- Main Banner -->
<section id="banner-main" class="banner-hidden" style="background-image:url(<?php echo esc_url($banner_image_url); ?>);">
    <div class="wrapper">
        <div class="title"><?php echo get_field('custom_page_title') ? get_field('custom_page_title') : get_the_title(); ?></div>
    </div>
</section><!--#end of Main Banner -->

<!-- include breadcrumbs -->
<?php get_template_part('content/common', 'breadcrumb'); ?>

<!-- Content -->
<section id="content" class="clearfix">
    <!-- Full-width Column -->
    <section class="col-full wrapper clearfix">
        <?php the_content(); ?>

        <?php
        $topic_slug = isset($_GET['t']) ? sanitize_title($_GET['t']) : '';
        $topic = get_term_by('slug', $topic_slug, 'topic');
        ?>
        <h1>Topic: <?php echo esc_html($topic ? $topic->name : 'Unknown'); ?></h1>

        <!-- Bible Study Resources -->
        <div id="bible-study-resources">

            <?php include get_template_directory() . '/content/bsr/browse-bsr.php'; ?>

            <section class="col-full wrapper clearfix">
                <div class="list-articles with-line" id="listing">
                    <?php
                    $show_pagination = false;

                    if (!empty($topic_slug)) {

                        $allowed_templates = array(
                            'templates/bsr-series-detail.php',
                            'templates/daily-devotions-detail.php',
                            'templates/meditation-detail.php',
                        );

                        $args_others = array(
                            'post_type' => 'page',
                            'post_status' => 'publish',
                            'orderby' => 'meta_value',
                            'meta_key' => 'article_date',
                            'meta_query' => array(
                                array(
                                    'key' => '_wp_page_template',
                                    'value' => $allowed_templates,
                                    'compare' => 'IN',
                                ),
                            ),
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'topic',
                                    'field' => 'slug',
                                    'terms' => $topic_slug,
                                ),
                            ),
                            'posts_per_page' => -1,
                        );

                        $query_others = new WP_Query($args_others);

                        if ($query_others->have_posts()) {
                            while ($query_others->have_posts()) {
                                $query_others->the_post();
                                $post_others = $query_others->post;

                                if (get_field('message', $post_others->ID)) {
                                    $messages = get_field('message', $post_others->ID);
                                    $article_date = get_field('article_date', $messages->ID);
                                    $article_title = get_field('article_title', $messages->ID);
                                    $text_refrence = get_field('text_refrence', $messages->ID);
                                    $speaker = get_field('speaker', $messages->ID);
                                    $video_url = get_field('video_url', $messages->ID);
                                    $audio_url = get_field('audio_url', $messages->ID);
                                    $author = get_field('author', $messages->ID);
                                    $post_permalink = get_permalink($post_others->ID);
                                } else {
                                    $article_title = get_field('article_title', $post_others->ID);
                                    $article_date = get_field('article_date', $post_others->ID);
                                    $text_refrence = get_field('text_refrence', $post_others->ID);
                                    $author = get_field('author', $post_others->ID);
                                    $video_url = get_field('video_url', $post_others->ID);
                                    $audio_url = get_field('audio_url', $post_others->ID);
                                    $post_permalink = get_permalink($post_others->ID);
                                }
                                ?>

                                <div class="<?php echo ($video_url ? 'has-video' : ($audio_url ? 'has-audio' : '')); ?> clearfix">
                                    <p><?php echo date('d M Y', strtotime($article_date)); ?><br />
                                        <strong><a href="<?php echo esc_url($post_permalink); ?>"><?php echo esc_html($article_title); ?></a></strong><br />
                                        <?php echo ($text_refrence) ? 'Text: ' . esc_html($text_refrence) : ''; ?>
                                    </p>

                                    <?php if ($author): ?>
                                        <p><?php echo 'by ' . esc_html($author); ?></p>
                                    <?php endif; ?>

                                    <?php
                                    generate_print_email_save(array(
                                        'url' => $post_permalink,
                                        'video_url' => $video_url,
                                        'audio_url' => $audio_url,
                                    ));
                                    ?>
                                </div>
                                <?php
                            }
                            wp_reset_postdata();
                        } else {
                            echo '<p>There are no articles found for this topic.</p>';
                        }
                    }
                    ?>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrp">
                    <?php if (isset($query_others) && $query_others->post_count > 20): ?>
                        <div class="pagination"></div>
                    <?php else: ?>
                        <div class="inline-block"></div>
                    <?php endif; ?>
                </div><!--#end of Pagination -->

            </section><!--#end of Full-width Column -->
        </div><!--#end of Bible Study Resources -->
    </section><!--#end of Full-width Column -->
</section><!--#end of Content -->

<?php endwhile; endif; ?>
<?php get_footer(); ?>
