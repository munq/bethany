<?php
global $sub_page;

// Start
// Get all the posts of type 'News'
// Display


?>
<!-- Tab #2 -->
<div id="news">
    <!-- Full-width Column -->
    <section class="col-full clearfix">
        <h2>News</h2>
        <!-- Pagination -->
        <div class="pagination-wrp">
            <div class="pagination-legend"></div>

        </div><!--#end of Pagination -->
        <!-- News Listing -->
        <ul class="list-lesson" id="listing">
            <?php

            $args = array(
                'post_type' => 'news',
                'post_status' => 'publish',
                'meta_key' => 'date',                    //(string) - Custom field key.
                'orderby' => 'meta_value',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => 'date',
                        'value' => date("Y-m-d"),
                        'compare' => '>=',
                        'type' => 'DATE'
                    )
                )
            );

            $posts = get_posts($args);
            if ($posts) {
                foreach ($posts as $post) {

                    $headline = get_the_title($post->ID);
                    $news = $post->post_content;
                    $date = get_field('date', $post->ID);
                    ?>

                    <li class="clearfix">
                        <div class="cal-date-event">
                            <div class="date">
                                <span><?php echo date('d', strtotime($date)); ?></span>
                                <span><?php echo date('M', strtotime($date)) . '<br />' . date('Y', strtotime($date)); ?></span>
                            </div>
                            <div class="event">
                                <h3><?php echo $headline; ?></h3>
                            </div>
                        </div>
                        <div id="news_last_child">
                            <p><?php echo $news; ?></p>
                        </div>
                    </li>
                    <?php
                }
            }
            ?>
        </ul><!--#end of News Listing -->

        <!-- Pagination -->
        <div class="pagination-wrp">
            <div class="pagination"></div>
        </div><!--#end of Pagination -->
    </section><!--#end of Full-width Column -->
</div><!--#end of Tab #2 -->