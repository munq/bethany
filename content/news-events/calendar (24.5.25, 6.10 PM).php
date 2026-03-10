<?php
$month_count = 0;
$current_timestamp = current_time('timestamp');
$current_month = date('n');
$current_year = date('Y');

while ($month_count < 15) { ?>
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

                        $day_format = 'd';
                        $month_format = 'M Y';
                        $year_format = 'd M Y';

                        $formatted_date = get_format_date($start_date, $end_date, $day_format, $month_format, $year_format);
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

                            <div
                                class="cal-date-event <?php echo strlen($event_date[0]) == 5 ? "event-with-end-date" : ""; ?>

<?php echo count($event_date) > 3 ? "event-two-dates" : ""; ?>">

                                <?php if (count($event_date) == 3) : ?>
                                    <div class="date">
                                        <span><?php echo $event_date[0]; ?></span>
                                        <span><?php echo $event_date[1] . '<br />' . $event_date[2]; ?></span>
                                    </div>

                                <?php elseif (count($event_date == 7)) : ?>
                                    <div class="date">
                                        <span><?php echo $event_date[0]; ?></span>
                                        <span><?php echo $event_date[1] . '<br />' . $event_date[2]; ?></span>
                                    </div>
                                    <div class="date-separator">
                                        <span> - </span>
                                    </div>
                                    <div class="date date-end">
                                        <span><?php echo $event_date[4]; ?></span>
                                        <span><?php echo $event_date[5] . '<br />' . $event_date[6]; ?></span>
                                    </div>

                                <?php else : ?>
                                    <div class="date">
                                        <span></span>
                                        <span></span>
                                    </div>
                                <?php endif; ?>

                            </div>
                            <div
                                class="events-detail <?php echo count($event_date) > 3 || strlen($event_date[0]) == 5 ? "events-detail-narrow" : ""; ?>">
                                <h3><?php echo $title; ?></h3>
                                <?php if (!empty($event_time[0]) || !empty($location)) : ?>
                                    <ul class="info-list clearfix">
                                        <?php if (!empty($event_time[0])) : ?>
                                            <li class="time"><?php echo $event_time[0] . $event_time[1]; ?></li>
                                        <?php endif; ?>
                                        <?php if (!empty($location)) : ?>
                                            <li class="location"><?php echo $location; ?></li>
                                        <?php endif; ?>
                                    </ul>
                                <?php endif; ?>
                                <p><?php echo $event_summary; ?></p>
                                <div class="buttons-wrp">
                                    <?php
                                    if ($form_fields) {
                                        ?>
                                        <a class="btn-primary btn-submit lightbox-styled"
                                           href="#register_event_<?php echo $post_event->ID; ?>">Register &nbsp;<i
                                                class="ion-ios-arrow-right"></i></a>
                                        <?php
                                    }

                                    ?>
                                    <a class="btn-primary btn-gray btn-add-to-calendar" id=""
                                       href="<?php echo get_permalink(get_page_by_path('download-ics')->ID) . '?event_id=' . $post_event->ID; ?>"
                                       target="_blank"><i class="ion-calendar"></i>&nbsp; Add to Calendar</a>

                                </div>
                            </div>
                        </li>

                        <?php
                        //echo $from_date."<br>".$title."<br>".$event_summary."<br>";
                    }
                    wp_reset_postdata();
                } else {
                    echo "There are no Events to show.";
                }
                //exit;
                ?>
            </ul>
        </div><!--#end of List View -->
        <!-- Calendar View -->
        <div id="load" class="view-calendar hidden">
            <ul class="calendar-days c-seven clearfix">
                <li>SUN</li>
                <li>MON</li>
                <li>TUE</li>
                <li>WED</li>
                <li>THU</li>
                <li>FRI</li>
                <li>SAT</li>
            </ul>

            <?php
            $class = '';
            $timestamp = mktime(0, 0, 0, $current_month, 1, $current_year);
            $maxday = date("t", $timestamp);
            $thismonth = getdate($timestamp);
            $first_week_day = $thismonth['wday'];
            ?>
            <ul class="grid-expand calendar-dates c-seven clearfix">
                <?php
                for ($i = 0; $i < ($maxday + $first_week_day); $i++) {
                    if ($i < $first_week_day) {
                        ?>
                        <li><a href="javascript:;"></a></li>
                        <?php
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
                        if ($query->found_posts > 0) {
                            $class = 'class = "grid-click"';
                        }

                        ?>
                        <li><a <?php echo $class; ?> href="javascript:;">
                                <span class="date"><?php echo $current_day; ?></span>
                                <span class="events-number"></span>
                            </a>
                            <div class="grid-expanded">
                                <ul class="list-events with-padding">
                                    <?php
                                    if ($query->have_posts()) {

                                        while ($query->have_posts()) {

                                            $query->the_post();
                                            setup_postdata($post);
                                            $post_event = $post;

                                            $class = '';
                                            $title = get_the_title($post_event->ID);
                                            $start_date = get_field('start_date', $post_event->ID);
                                            $event_time = get_field('event_time', $post_event->ID);
                                            $event_time = explode(' ', $event_time);
                                            $location = get_field('location', $post_event->ID);
                                            $event_summary = get_field('event_summary', $post_event->ID);
                                            $form_fields = get_field('event_form_fields', $post_event->ID);
                                            $register = get_field('event_form_fields', $post_event->ID);

                                            $post_categories = get_the_category($post_event->ID);
                                            $cats = array();
                                            foreach ($post_categories as $cat) {
                                                $cats[] = $cat->slug;
                                            }
                                            ?>
                                            <li class="clearfix <?php echo implode(' ', $cats) ?>">
                                                <div class="events-detail">
                                                    <h3><?php echo $title; ?></h3>
                                                    <ul class="info-list clearfix">
                                                        <li class="time"><?php echo $event_time[0] . $event_time[1]; ?></li>
                                                        <li class="location"><?php echo $location; ?></li>
                                                    </ul>
                                                    <p><?php echo $event_summary; ?></p>
                                                    <div class="buttons-wrp">
                                                        <?php
                                                        if ($form_fields) {
                                                            ?>
                                                            <a class="btn-primary btn-submit lightbox-styled"
                                                               href="#register_event_<?php echo $post_event->ID; ?>">Register
                                                                &nbsp;<i class="ion-ios-arrow-right"></i></a>
                                                        <?php }
                                                        ?>
                                                        <a class="btn-primary btn-gray btn-add-to-calendar" id=""
                                                           href="<?php echo get_permalink(get_page_by_path('download-ics')->ID) . '?event_id=' . $post_event->ID; ?>"
                                                           target="_blank"><i class="ion-calendar"></i>&nbsp; Add to
                                                            Calendar</a>
                                                    </div>
                                                </div>
                                            </li>
                                            <?php
                                        }
                                        wp_reset_postdata();
                                    }
                                    ?>
                                </ul>
                            </div>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>

            <?php foreach ($lightboxes as $lightbox) {
                $event_lighbox = get_post($lightbox);
                $title = get_the_title($event_lighbox->ID);
                $start_date = get_field('start_date', $event_lighbox->ID);
                $event_time = get_field('event_time', $event_lighbox->ID);
                $event_time = explode(' ', $event_time);
                $location = get_field('location', $event_lighbox->ID);
                $event_summary = get_field('event_summary', $event_lighbox->ID); ?>

                <!-- Lightbox - Register for Event -->
                <div class="lightbox-content" id="register_event_<?php echo $event_lighbox->ID; ?>">
                    <div class="wrapper">
                        <h2>Register for <?php echo $title; ?></h2>

                        <!-- Full-width Column -->
                        <section class="col-full clearfix">
                            <!-- Side Column -->
                            <section class="col-side">
                                <div class="box-side">
                                    <div class="box-side-top schedule clearfix">
									<span>
										<b><?php echo date('d', strtotime($start_date)); ?></b><br/>
										<i><?php echo date('M', strtotime($start_date)); ?></i>
									</span>
                                        <span class="time"><?php echo $event_time[0] . $event_time[1]; ?></span>
                                    </div>
                                    <div class="box-side-content">
                                        <h3><?php echo get_the_title($event_lighbox->ID); ?></h3>
                                        <p><?php echo $event_summary; ?></p>
                                    </div>
                                </div>
                            </section><!--#end of Side Column -->

                            <!-- Main Column -->
                            <section class="col-main">
                                <div id="form_fields_<?php echo $event_lighbox->ID; ?>"
                                     class="hidden bethany_form_fields">
                                    <fieldset>
                                        <h3></h3>
                                        <?php echo apply_filters('the_content', $form_fields); ?>
                                        <a class="grid-close ion-android-close" href="javascript:;"
                                           onclick="remove_fieldset(this);" id="remove_fieldset"></a>
                                    </fieldset>
                                </div>

                                <form id="bethany_eventform_<?php echo $event_lighbox->ID; ?>"
                                      class="bethany_event_form" data-event-id="<?php echo $event_lighbox->ID; ?>" style="position: relative;">

                                    <!-- /* Insert before #add_attendee_container the #bethany_generated_form_fields html here when they click the + Add attendee and then chamge the %att_index% with the counter  */ -->

                                    <div id="add_attendee_container" class="field-wrp">
                                        <a class="btn-secondary btn-gray" href="javascript:;" id="fieldset_field">+ Add
                                            attendee</a>
                                    </div>
                                    <div class="buttons-wrp">
                                        <a class="btn-primary btn-submit" href="#thx-register-event">Register</a>
                                        <a class="btn-primary btn-gray" href="javascript:;"
                                           onClick="$.fancybox.close();">Cancel</a>
                                    </div>
                                </form>
                            </section><!--#end of Main Column -->
                        </section><!--#end of Full-width Column -->
                    </div>
                </div>
            <?php } ?>

            <!-- Thank You Message -->
            <div class="lightbox-content" id="thx-register-event">
                <div class="wrapper">
                    <h2>Thank you for signing up for <span class="event-title"></span></h2>
                    <p>A notification email will be sent to you.</p>
                    <p>&nbsp;</p>
                    <div class="buttons-wrp">
                        <a class="btn-primary btn-submit" href="javascript:;" onClick="$.fancybox.close();">Close</a>
                    </div>
                </div>
            </div><!--#end of Lightbox - Register for Event -->
        </div><!--#end of Calendar View -->
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
