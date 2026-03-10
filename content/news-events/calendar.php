<?php
$month_count = 0;
$current_timestamp = current_time('timestamp');
$current_month = date('n');
$current_year = date('Y');
$lightboxes = array(); // Initialize to avoid undefined variable warning

while ($month_count < 15) {
?>
    <div>
        <!-- List View -->
        <div class="view-list">
            <ul class="list-events with-padding">
                <?php
                $args = array(
                    'post_type' => 'event',
                    'post_status' => 'publish',
                    'orderby' => 'start_date',
                    'order' => 'ASC',
                    'meta_query' => array(
                        'relation' => 'AND',
                        array(
                            'key' => 'start_date',
                            'value' => date('Y-m-d', mktime(0, 0, 0, $current_month, 1, $current_year)),
                            'compare' => '>=',
                            'type' => 'DATE'
                        ),
                        array(
                            'key' => 'start_date',
                            'value' => date('Y-m-d', mktime(0, 0, 0, $current_month, 31, $current_year)),
                            'compare' => '<=',
                            'type' => 'DATE'
                        ),
                        array(
                            'key' => 'start_date',
                            'value' => date('Y-m-d', $current_timestamp),
                            'compare' => '>=',
                            'type' => 'DATE'
                        )
                    )
                );

                $query = new WP_Query($args);
                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                        setup_postdata($post);
                        $post_event = $post;

                        $lightboxes[] = $post_event->ID;
                        $title = get_the_title($post_event->ID);
                        $start_date = get_field('start_date', $post_event->ID);
                        $end_date = get_field('end_date', $post_event->ID);

                        $formatted_date = get_format_date($start_date, $end_date, 'd', 'M Y', 'd M Y');
                        $event_date = explode(" ", $formatted_date);

                        $event_time = get_field('event_time', $post_event->ID);
                        $event_time = explode(' ', $event_time);
                        $location = get_field('location', $post_event->ID);
                        $event_summary = get_field('event_summary', $post_event->ID);
                        $form_fields = get_field('event_form_fields', $post_event->ID);

                        $post_categories = get_the_category($post_event->ID);
                        $cats = array();
                        foreach ($post_categories as $cat) {
                            $cats[] = $cat->slug;
                        }
                ?>
                        <li class="clearfix <?php echo implode(' ', $cats); ?>">
                            <!-- Event HTML here -->
                        </li>
                <?php
                    }
                    wp_reset_postdata();
                } else {
                    echo "There are no Events to show.";
                }
                ?>
            </ul>
        </div>

        <!-- Calendar View -->
        <div id="load" class="view-calendar hidden">
            <ul class="calendar-days c-seven clearfix">
                <li>SUN</li><li>MON</li><li>TUE</li><li>WED</li><li>THU</li><li>FRI</li><li>SAT</li>
            </ul>

            <?php
            $timestamp = mktime(0, 0, 0, $current_month, 1, $current_year);
            $maxday = date("t", $timestamp);
            $first_week_day = date("w", $timestamp);
            ?>

            <ul class="grid-expand calendar-dates c-seven clearfix">
                <?php
                for ($i = 0; $i < ($maxday + $first_week_day); $i++) {
                    if ($i < $first_week_day) {
                        echo '<li><a href="javascript:;"></a></li>';
                    } else {
                        $current_day = $i - $first_week_day + 1;
                        $args = array(
                            'post_type' => 'event',
                            'post_status' => 'publish',
                            'orderby' => 'start_date',
                            'order' => 'ASC',
                            'meta_query' => array(
                                'relation' => 'AND',
                                array(
                                    'key' => 'start_date',
                                    'value' => date('Y-m-d', mktime(0, 0, 0, $current_month, $current_day, $current_year)),
                                    'compare' => '==',
                                    'type' => 'DATE'
                                ),
                                array(
                                    'key' => 'start_date',
                                    'value' => date('Y-m-d', $current_timestamp),
                                    'compare' => '>=',
                                    'type' => 'DATE'
                                )
                            )
                        );

                        $query = new WP_Query($args);
                        $class = ($query->found_posts > 0) ? 'class="grid-click"' : '';

                        echo '<li><a ' . $class . ' href="javascript:;">
                                <span class="date">' . $current_day . '</span>
                                <span class="events-number"></span>
                              </a></li>';
                    }
                }
                ?>
            </ul>

            <?php foreach ($lightboxes as $lightbox) {
                $event_lighbox = get_post($lightbox);
                $title = get_the_title($event_lighbox->ID);
                $start_date = get_field('start_date', $event_lighbox->ID);
                $event_time = explode(' ', get_field('event_time', $event_lighbox->ID));
                $location = get_field('location', $event_lighbox->ID);
                $event_summary = get_field('event_summary', $event_lighbox->ID);
                $form_fields = get_field('event_form_fields', $event_lighbox->ID);
                ?>
                <!-- Lightbox content for each event -->
                <div class="lightbox-content" id="register_event_<?php echo $event_lighbox->ID; ?>">
                    <!-- Lightbox HTML here -->
                </div>
            <?php } ?>

            <div class="lightbox-content" id="thx-register-event">
                <div class="wrapper">
                    <h2>Thank you for signing up for <span class="event-title"></span></h2>
                    <p>A notification email will be sent to you.</p>
                    <div class="buttons-wrp">
                        <a class="btn-primary btn-submit" href="javascript:;" onClick="$.fancybox.close();">Close</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    $month_count++;
    $current_month++;
    if ($current_month > 12) {
        $current_month = 1;
        $current_year++;
    }
}
?>
