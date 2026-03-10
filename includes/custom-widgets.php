<?php

/* Widgets */

function footer_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Bethany Footer Top', 'bethany' ),
		'id' => 'bethany_footer_top',
		'description' => __( 'This widget appears on the top section of footer', 'bethany' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	) );
	
	register_sidebar( array(
		'name' => __( 'Bethany Footer Bottom', 'bethany' ),
		'id' => 'bethany_footer_bottom',
		'description' => __( 'This widget appears on the bottom section of footer', 'bethany' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	) );
}

add_action( 'widgets_init', 'footer_widgets_init' ); 

class Bethany_Widget_Footer_Top extends WP_Widget {

    // Create Widget
    function __construct() {
        parent::WP_Widget(false, $name = 'Bethany Widget - Footer Top', array('description' => 'For managing footer top texts or links'));
    }

    // Widget Content
    function widget($args, $instance) { 
        extract( $args );
		
		$title = strip_tags($instance['title']);
		$classes = strip_tags($instance['classes']);
        $free_html = ($instance['free_html']);
		
        ?>
		<li<?php echo !empty($classes) ? ' class="' . sanitize_html_class($classes) . '"':'' ?>>
			<?php echo $free_html; ?>
		</li>
        <?php
    }

    // Update and save the widget
    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    // If widget content needs a form
    function form($instance) {
        //widgetform in backend
		
        $title = strip_tags($instance['title']);
		$classes = strip_tags($instance['classes']);
        $free_html = ($instance['free_html']);
		
        ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title: </label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('classes'); ?>">Classes: </label>
			<input class="widefat" id="<?php echo $this->get_field_id('classes'); ?>" name="<?php echo $this->get_field_name('classes'); ?>" type="text" value="<?php echo attribute_escape($classes); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('free_html'); ?>">HTML: </label>
			<textarea class="widefat" id="<?php echo $this->get_field_id('free_html'); ?>" name="<?php echo $this->get_field_name('free_html'); ?>" cols="20" rows="10"><?php echo attribute_escape($free_html); ?></textarea>
		</p>
        <?php 
    }
}

class Bethany_Widget_Footer_Bottom extends WP_Widget {

    // Create Widget
    function __construct() {
        parent::WP_Widget(false, $name = 'Bethany Widget - Footer Bottom', array('description' => 'For managing footer bottom texts or links'));
    }

    // Widget Content
    function widget($args, $instance) { 
        extract( $args );
		
		$title = strip_tags($instance['title']);
		$classes = strip_tags($instance['classes']);
        $free_html = ($instance['free_html']);
		
        ?>
		<li<?php echo !empty($classes) ? ' class="' . sanitize_html_class($classes) . '"':'' ?>>
			<?php if ( ! empty( $title ) ) : ?>
				<?php echo $before_title . $title . $after_title; ?>
			<?php endif; ?>
			<?php echo $free_html; ?>
		</li>
        <?php
    }

    // Update and save the widget
    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    // If widget content needs a form
    function form($instance) {
        //widgetform in backend
		
        $title = strip_tags($instance['title']);
		$classes = strip_tags($instance['classes']);
        $free_html = ($instance['free_html']);
		
        ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title: </label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('classes'); ?>">Classes: </label>
			<input class="widefat" id="<?php echo $this->get_field_id('classes'); ?>" name="<?php echo $this->get_field_name('classes'); ?>" type="text" value="<?php echo attribute_escape($classes); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('free_html'); ?>">HTML: </label>
			<textarea class="widefat" id="<?php echo $this->get_field_id('free_html'); ?>" name="<?php echo $this->get_field_name('free_html'); ?>" cols="20" rows="10"><?php echo attribute_escape($free_html); ?></textarea>
		</p>
        <?php 
    }
}

register_widget('Bethany_Widget_Footer_Top');
register_widget('Bethany_Widget_Footer_Bottom');

$footer_widgets_init = get_option( 'footer_widgets_init' );

$sidebars_widgets = get_option( 'sidebars_widgets' );

if ( FALSE === $footer_widgets_init ) {
	update_option( 'footer_widgets_init', TRUE );
	
	//if ( isset($sidebars_widgets['bethany_footer_top']) ) {
		$counter = 1;
		$default_items = array();
		$bethany_footer_top_widgets = array();
		
		$sidebars_widgets['bethany_footer_top'] = array();
		$sidebars_widgets['bethany_footer_top'][] = 'bethany_footer_top-' . ($counter+1);
		$sidebars_widgets['bethany_footer_top'][] = 'bethany_footer_top-' . ($counter+2);
		$sidebars_widgets['bethany_footer_top'][] = 'bethany_footer_top-' . ($counter+3);
		
		$bethany_footer_top_widgets[$counter+1] = array(
				'title' => 'Site Name',
				'classes' => '',
				'free_html' => '<span>Bethany</span><br/><span>Independent<br/>Presbyterian Church</span>',
			);
			
		$bethany_footer_top_widgets[$counter+2] = array(
				'title' => 'Address',
				'classes' => '',
				'free_html' => '<p><span class="ion-android-pin"></span><br/>301 Upper Paya Lebar Road, Singapore 534934<br/>Tel: (65) 6287 7713</p>',
			);
			
		$bethany_footer_top_widgets[$counter+3] = array(
				'title' => 'Download Our App',
				'classes' => 'clearfix',
				'free_html' => 'Download Our App<br/><br/><br/><a href="#"><img src="' . get_template_directory_uri() . '/assets/img/app-app-store.png" alt="Available on the App Store" /></a><a href="#"><img src="' . get_template_directory_uri() . '/assets/img/app-google-play.png" alt="Android App on Google Play" /></a>',
			);
			
		update_option( 'widget_bethany_footer_top', $bethany_footer_top_widgets );
	//}

	//if ( isset($sidebars_widgets['bethany_footer_bottom']) ) {
		$counter = 1;
		$default_items = array();
		$bethany_footer_bottom_widgets = array();
		
		$sidebars_widgets['bethany_footer_bottom'] = array();
		$sidebars_widgets['bethany_footer_bottom'][] = 'bethany_footer_bottom-' . ($counter+1);
		$sidebars_widgets['bethany_footer_bottom'][] = 'bethany_footer_bottom-' . ($counter+2);
		$sidebars_widgets['bethany_footer_bottom'][] = 'bethany_footer_bottom-' . ($counter+3);
		$sidebars_widgets['bethany_footer_bottom'][] = 'bethany_footer_bottom-' . ($counter+4);
		
		$bethany_footer_bottom_widgets[$counter+1] = array(
				'title' => 'About Us',
				'classes' => 'desktop-only',
				'free_html' => '<a href="#">Our Faith</a>
<a href="#">Our Heritage</a>
<a href="#">Our Leaders</a>
<a href="#">Missions Work</a>
<a href="#">Our Ministries</a>',
			);
			
		$bethany_footer_bottom_widgets[$counter+2] = array(
				'title' => 'Fellowship Groups',
				'classes' => 'desktop-only',
				'free_html' => '<a href="#">Young People’s Group</a>
<a href="#">Young Adult’s Group</a>',
			);
			
		$bethany_footer_bottom_widgets[$counter+3] = array(
				'title' => 'Join Our Mailing List',
				'classes' => '',
				'free_html' => '<p>Sign up to receive daily devotions, <br/>
iCare or other bible study materials</p>
<p><a class="btn-secondary btn-submit" href="#">Sign up  <i class="ion-ios-arrow-right"></i></a></p>',
			);
			
		$bethany_footer_bottom_widgets[$counter+4] = array(
				'title' => '',
				'classes' => '',
				'free_html' => '<div class="box-footer"><a href="#">
<img src="' . get_template_directory_uri() . '/assets/img/i-magazine-kindergarden.png" />
<span class="link-arrow" href="#">Bethany<br/>Kindergarden</span></a></div>',
			);
		
		update_option( 'widget_bethany_footer_bottom', $bethany_footer_bottom_widgets );
	//}
}

update_option( 'sidebars_widgets', $sidebars_widgets );

/* $sidebars_widgets['bethany_footer_top'] = array();
$sidebars_widgets['bethany_footer_bottom'] = array();

update_option( 'sidebars_widgets', $sidebars_widgets ); */