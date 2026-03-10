<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Series Template
 * This Template is used for Series Eg: Morning Worship -> Series -> Articles for that Series
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */

// If the 'view' parameter is SET, display all messages of the category
// Else, Display 'Messages' subpages for the series 

$yag_retreats = get_page_by_title("YAG Retreats");

if ($yag_retreats) {
    $yag_retreats_id = $yag_retreats->ID;
    $yag_retreats_args = get_posts(array(
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'child_of'       => $yag_retreats_id,
        'post_parent'    => $yag_retreats_id,
        'depth'          => 1,
        'orderby'        => 'meta_value',
        'meta_key'       => 'start_date',
        'order'          => 'ASC',
        'posts_per_page' => -1
    ));

    $yag_retreats_page_ids = wp_list_pluck($yag_retreats_args, 'ID');
} else {
    $yag_retreats_page_ids = array();
}

// PREV and NEXT pagination
$args_nav = get_posts(array(
    'post_type'      => 'page',
    'post_status'    => 'publish',
    'child_of'       => $post->post_parent,
    'post_parent'    => $post->post_parent,
    'depth'          => 1,
    'orderby'        => 'meta_value',
    'meta_key'       => 'start_date',
    'order'          => 'ASC',
    'posts_per_page' => -1
));

$page_ids = wp_list_pluck($args_nav, 'ID');
$current_index = array_search($post->ID, $page_ids);
$prevID = $page_ids[$current_index - 1] ?? null;
$nextID = $page_ids[$current_index + 1] ?? null;

get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <!-- Main Banner -->
    <?php get_template_part('content/common', 'banner'); ?>    
    <!--#end of Main Banner -->

    <!-- include breadcrumbs -->
    <?php get_template_part('content/common', 'breadcrumb'); ?>    

    <!-- Content -->
    <section id="content" class="clearfix">
        <?php
        $start_date = get_field('start_date');
        if ('19700101' == $start_date) $start_date = '';

        $end_date = get_field('end_date');
        if ('19700101' == $end_date) $end_date = '';
        ?>

        <!-- Full-width Column -->
        <section class="col-full wrapper clearfix">        
            <h2 class="h2-lead">
                <span><?php echo get_the_title($post->post_parent); ?> Messages</span> &nbsp;|&nbsp;
                <span>
                <?php 
                if (!empty($start_date)) {
                    echo date('M Y', strtotime($start_date));
                }

                if (!empty($end_date)) {
                    echo ' - ' . date('M Y', strtotime($end_date));
                } ?>    
                </span>
            </h2>
            <h1><?php the_title(); ?></h1>        
            <?php the_content(); ?>
                    
            <!-- Main Column -->
            <section class="col-main">
                <ul class="list-messages" id="listing">
                <?php
                $args = array(
                    'post_type'      => 'page',
                    'post_status'    => 'publish',
                    'child_of'       => get_the_ID(),
                    'post_parent'    => get_the_ID(),
                    'depth'          => 1,
                    'orderby'        => 'meta_value',
                    'meta_key'       => 'article_date',
                    'order'          => 'DESC',
                    'posts_per_page' => -1
                );

                $diff_ids = array_diff($yag_retreats_page_ids, $page_ids);
                if (empty($diff_ids)) {
                    $args['orderby'] = 'meta_value date';
                    $args['order'] = 'ASC';
                }

                $query = new WP_Query($args);

                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                        $post_articles = $query->post;

                        $messages = get_field('message', $post_articles->ID);
                        $article_date = get_field('article_date', $post_articles->ID);

                        $article_title = get_field('article_title', $messages->ID);
                        $quote = get_field('quote', $messages->ID);
                        $text_refrence = get_field('text_refrence', $messages->ID);
                        $speaker = get_field('speaker', $messages->ID);

                        $bulletin = get_field('bulletin', $messages->ID);
                        $bulletin_url = ($bulletin) ? wp_get_attachment_url($bulletin['id']) : '';

                        if (!get_field('notes_url', $messages->ID)) {
                            $notes = get_field('notes', $messages->ID);
                            $notes_url = ($notes) ? wp_get_attachment_url($notes['id']) : '';
                        } else {
                            $notes_url = get_field('notes_url', $messages->ID);
                        }            

                        $video_url = get_field('video_url', $messages->ID);
                        $video_cover_image = get_field('video_cover_image', $messages->ID);
                        $cover_image_url = ($video_cover_image) ? $video_cover_image['url'] : '';        

                        $audio_url = get_field('audio_url', $messages->ID);
                    ?>
                    <li class="clearfix">
                        <p>                        
                        <strong><?php echo date('d M Y', strtotime($article_date)); ?></strong><br/>
                        <strong><a href="<?php the_permalink(); ?>"><?php echo esc_html($article_title); ?></a></strong><br/>
                        <?php echo ($text_refrence) ? 'Text: ' . esc_html($text_refrence) : ''; ?> </p>
                        <?php echo ($speaker) ? '<p>Speaker: '. esc_html($speaker) . '</p>' : ''; ?>
                        <a class="media-bar clearfix" href="<?php the_permalink(); ?>">
                            <span class="media-bar-icon"><i class="ion-ios-play"></i></span>
                            <span class="media-bar-title">Watch video or listen to audio</span>
                        </a><!--#end of Media Bar -->
                        <!-- Resource Download -->
                        <?php
                        generate_download_media(array(
                            'class'         => 'tooltip',
                            'url'           => get_permalink(get_the_ID()),
                            'bulletin_url'  => $bulletin_url,
                            'notes_url'     => $notes_url,
                            'video_url'     => $video_url,
                            'audio_url'     => $audio_url,
                        ));
                        ?>
                        <!--#end of Resource Download -->
                    </li>                
                    <?php
                    }
                    wp_reset_postdata();
                }
                ?>                                
                </ul>            
                <!-- Pagination -->
                <div class="pagination-wrp">
                    <div class="pagination"></div>
                </div><!--#end of Pagination -->

                <div class="theme-prev-next">
                <?php 
                if (!empty($prevID)) { ?>
                    <a class="btn-secondary btn-gray" href="<?php echo get_permalink($prevID); ?>"><i class="ion-ios-arrow-left"></i> &nbsp; Previous Theme</a>
                <?php 
                } 
                if (!empty($nextID)) { ?>                
                    <a class="btn-secondary btn-gray" href="<?php echo get_permalink($nextID); ?>">Next Theme &nbsp; <i class="ion-ios-arrow-right"></i></a>
                <?php
                }
                ?>
                </div><!--#end of Previous & Next Theme -->

                <?php
                $parent_id = wp_get_post_parent_id($post->ID);
                $series_name = get_the_title($parent_id);
                $series_path = get_permalink($parent_id);
                ?>
                <div class="view-all-section">
                    <a class="view-all-message-series" href="<?php echo esc_url($series_path); ?>">View all <?php echo esc_html($series_name); ?> messages</a>
                </div>
            </section><!--#end of Main Column -->

            <!-- Side Column -->
            <section class="col-side">
                <?php get_template_part('content/sidebar/right', 'find-a-message'); ?>
            </section><!--#end of Side Column -->
        </section> <!--#end of Full-width Column -->
    </section><!--#end of Content -->
<?php endwhile; endif; ?>

<?php get_footer(); ?>
