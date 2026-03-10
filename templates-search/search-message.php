<?php if (!defined('ABSPATH')) die('No direct script access allowed!');
/**
 * Template Name: Search Message
 *
 * @package Bethany
 * @subpackage bethany
 * @since bethany 1.0
 */

get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<!-- Main Banner -->
<?php get_template_part('content/common', 'banner'); ?>    

<!-- include breadcrumbs -->
<?php get_template_part('content/common', 'breadcrumb'); ?>    

<!-- Content -->
<section id="content" class="clearfix">    
    <!-- Full-width Column -->
    <section class="col-full wrapper clearfix">
        <!-- Bible Study Resources -->
        <div id="bible-study-resources">
            <div class="bsr-filter box-generic">
                <div class="bsr-filter-top filter-top clearfix">                        
                    <div>
                        <form action="" method="GET" id="search_form">
                            <fieldset>
                                <div class="field-wrp field-search">
                                    <input type="text" name="msg_search" placeholder="Search" class="full-width" />
                                    <button id="submit_search" class="i-search ion-ios-search-strong"></button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            
            <h2>Search Results</h2>
            
            <?php            
				$search_d = $_GET['msg_search'] ?? '';
				$msg_year_d = $_GET['msg_year'] ?? '';
				$msg_month_d = '';

				if (!empty($_GET['msg_month'])) {
  				  $dateObj = DateTime::createFromFormat('!m', $_GET['msg_month']);
 				  $msg_month_d = $dateObj ? $dateObj->format('M') : '';
}
?>

            
            <div class="bsr-filter box-gray">
                <form>
                    <fieldset class="bsr-filter-top filter-top clearfix">
                        <div class="field-wrp field-search">
                            <span class="pagination-count"><strong></strong></span> <span>
                            <strong>Results for:
                            <?php echo esc_html($search_d); ?>
                            <?php  
                            if($msg_month_d != '' || $msg_year_d != '') { 
                                echo ' Date: ' . esc_html($msg_month_d) . ' ' . esc_html($msg_year_d);
                            }
                            ?>
                            </strong></span>
                        </div>
                        <div>
                            <!-- Pagination -->
                            <div class="pagination-wrp">
                                <div class="pagination-legend"></div>
                            </div><!--#end of Pagination -->                            
                        </div>
                    </fieldset>                    
                </form>
            </div>
            
            <!-- Full-width Column -->
            <section class="col-full wrapper clearfix">
                <?php 
                if (isset($_GET) && (!empty($_GET['msg_search']) || !empty($_GET['msg_month']) || !empty($_GET['msg_year']))) {
                    $args = array(
                        'post_type'      => 'page',
                        'post_status'    => 'publish',                        
                        'depth'          => 1, 
                        'orderby'        => 'meta_value', 
                        'meta_key'       => 'article_date', 
                        'order'          => 'DESC',
                        'posts_per_page' => -1                                          
                    );
                    
                    $search = $_GET['msg_search'] ?? '';
                    $yearmonth = (!empty($_GET['msg_year']) ? $_GET['msg_year'] : '') . (!empty($_GET['msg_month']) ? $_GET['msg_month'] : '');
                    
                    $meta_query = array('relation' => 'AND');
                    
                    if (!empty($search)) {
                        $args['s'] = $search;                    
                    }
                    
                    if (!empty($yearmonth)) {
                        $meta_query[] = array(
                            'key'     => 'article_date',
                            'value'   => $yearmonth,
                            'compare' => 'LIKE'
                        );
                    }
                    
                    $meta_query[] = array(
                        'relation' => 'AND',
                        array(
                            'key'     => '_wp_page_template',
                            'value'   => 'templates/series-article-detail.php',
                            'compare' => '=',
                        ),                    
                    );
                    
                    $args['meta_query'] = $meta_query;                    
                    
                    $query = new WP_Query($args);
                    ?>
                    
                    <div class="list-articles with-line" id="listing">
                    <?php
                    if ($query->have_posts()) {
                        while ($query->have_posts()) {                
                            $query->the_post();
                            $post_articles = $query->post;
                            
                            $messages = get_field('message', $post_articles->ID);
                            $article_date = get_field('article_date', $messages->ID);
                            $article_title = get_field('article_title', $messages->ID);
                            $text_refrence = get_field('text_refrence', $messages->ID);
                            
                            $speaker = get_field('speaker', $messages->ID) ?: get_field('author', $messages->ID);
                            
                            $bulletin = get_field('bulletin', $messages->ID);
                            $bulletin_url = ($bulletin) ? wp_get_attachment_url($bulletin['id']) : '';
                            
                            $notes_url = get_field('notes_url', $messages->ID) ?: (($notes = get_field('notes', $messages->ID)) ? wp_get_attachment_url($notes['id']) : '');
                            
                            $video_url = get_field('video_url', $messages->ID);                                
                            $audio_url = get_field('audio_url', $messages->ID);
                            ?>
                            <div class="clearfix">
                                <p><?php echo !empty($article_date) ? date('d M Y', strtotime($article_date)) : ''; ?><br/>
                                    <strong><a href="<?php the_permalink(); ?>"><?php echo esc_html($article_title); ?></a></strong><br/>
                                    <?php echo ($text_refrence) ? 'Text: ' . esc_html($text_refrence) : ''; ?></p>
                                    
                                    <?php $series = get_post($post_articles->post_parent); ?>
                                    
                                <p><?php echo ($speaker) ? 'by ' . esc_html($speaker) : ''; ?> <?php echo ($series) ? ' | <a href="' . get_permalink($series) .'">' . esc_html(get_the_title($series)) . '</a>' : '';?></p>
                                
                                <!-- Resource Download -->                                                            
                                
                                <?php 
                                generate_print_email_save(array(
                                    'url'          => get_permalink($post_articles->ID),
                                    'bulletin_url' => $bulletin_url,
                                    'notes_url'    => $notes_url,
                                    'video_url'    => $video_url,
                                    'audio_url'    => $audio_url,
                                ));
                                ?>
                                
                            </div>
                        <?php
                        }
                    } else {
                        echo 'Your Search Returned Zero Results.';
                    }
                    ?>                        
                    </div>
                <?php
                } else {
                    wp_redirect(site_url());
                    exit;
                }
                ?>                
                
                <!-- Pagination -->
                <div class="pagination-wrp">
                    <div class="pagination"></div>
                </div><!--#end of Pagination -->
            </section><!--#end of Full-width Column -->
        </div><!--#end of Bible Study Resources -->
    </section>
    <!--#end of Full-width Column -->
    
</section><!--#end of Content -->

<?php endwhile; endif; ?>

<?php function search_msg_javascript() { ?>
    <script type="text/javascript">
    (function($) {
        $(document).ready(function($) {
            $('#submit_search').click(function(){                
                var search_form = $('#search_form input[name="msg_search"]').val();                    
                if(search_form == '') {                
                    return false;
                } else{
                    $('#search_form').submit();                
                }
            });    
        });
    })(jQuery);
    </script>
<?php 
} 
add_action('wp_footer', 'search_msg_javascript');
?>

<?php get_footer(); ?>
