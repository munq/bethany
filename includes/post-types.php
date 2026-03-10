<?php

function create_custom_post()
{
	register_post_type('message',
		array(
		   'labels'=> array(
					'name'=> __( 'Messages' ),
					'singular'=> __( 'Message' )
				),
			'public' => true,
			'has_archive' => true,
			
			)
		);
		
	// register_post_type('teacher',
		// array(
		   // 'labels'=> array(
					// 'name'=> __( 'Teachers' ),
					// 'singular'=> __( 'Teacher' )
				// ),
			// 'public' => true,
			// 'has_archive' => true,
			
			// )
		// );
		
	// register_post_type('class',
		// array(
		   // 'labels'=> array(
					// 'name'=> __( 'Classes' ),
					// 'singular'=> __( 'Class' )
				// ),
			// 'public' => true,
			// 'has_archive' => true
			// )
		// );
		
	// register_post_type('lesson',
		// array(
		   // 'labels'=> array(
				// 'name'=> __( 'Lessons' ),
				// 'singular'=> __( 'Lesson' )
				// ),
			// 'public' => true,
			// 'has_archive' => true
			// )
		// );
		
	register_post_type('testimony',
		array(
		   'labels'=> array(
				'name'=> __( 'Testimonies' ),
				'singular'=> __( 'Testimony' )
				),
			'public' => true,
			'has_archive' => true
			)
		);
		
		
	register_post_type('reflection',
	array(
	   'labels'=> array(
			'name'=> __( 'Reflections' ),
			'singular'=> __( 'Reflection' )
			),
		'public' => true,
		'has_archive' => true
		)
	);
		
		
		register_post_type('event',
		array(
		   'labels'=> array(
				'name'=> __( 'Events' ),
				'singular'=> __( 'Event' )
				),
			'public' => true,
			'has_archive' => true,			
			'taxonomies' => array( 'post_tag', 'category'),
			'supports'	=> array('thumbnail', 'title', 'editor'),
			'capability_type' => 'event',
            		'capabilities' => array(
                		'publish_posts' => 'publish_event',
                		'edit_posts' => 'edit_event',
                		'edit_others_posts' => 'edit_others_event',
                		'delete_posts' => 'delete_event',
                		'delete_others_posts' => 'delete_others_event',
                		'read_private_posts' => 'read_private_event',
                		'edit_post' => 'edit_event',
                		'delete_post' => 'delete_event',
                		'read_post' => 'read_event',
				)
			)
		);	
		
	register_post_type('news',
		array(
		   'labels'=> array(
				'name'=> __( 'News' ),
				'singular'=> __( 'News' )
				),
			'public' => true,
			'has_archive' => true
			)
		);
	
	register_post_type('gallery',
		array(
		   'labels'=> array(
				'name'=> __( 'Galleries' ),
				'singular'=> __( 'Gallery' )
				),
			'public' 		=> true,
			'has_archive' 	=> true,
			'supports'		=> array('thumbnail', 'title')
			)
		);
		
	register_post_type('highlight',
		array(
		   'labels'=> array(
				'name'=> __( 'Highlights' ),
				'singular'=> __( 'Highlight' )
				),
			'public' => true,
			'has_archive' => true
			)
		);
		
	register_post_type('blog',
	array(
	   'labels'=> array(
			'name'=> __( 'Blog' ),
			'singular'=> __( 'Blog' )
			),
		'public' => true,
		'has_archive' => true
		)
	);
		
	// register_post_type('theme',
		// array(
		   // 'labels'=> array(
				// 'name'=> __( 'Themes' ),
				// 'singular'=> __( 'Theme' )
				// ),
			// 'public' => true,
			// 'has_archive' => true,
			// 'taxonomies' => array( 'post_tag', 'category'),
			// )
		// );
		
	register_post_type('memory_verses',
	array(
	   'labels'=> array(
			'name'=> __( 'Memory Verses' ),
			'singular'=> __( 'Memory Verse' )
			),
		'public' => true,
		'has_archive' => true
		)
	);
		
	// drop support to meta_form when editing page
	remove_post_type_support( 'page', 'custom-fields' );
	//remove_post_type_support( 'page', 'page-attributes' );		
 }
 
add_action('init', 'create_custom_post');

// Priority 5 allows the removal of default tabs and insertion of other plugin's tabs 
/* add_filter( 'contextual_help', 'wpse_77308_products_help', 5, 3 );

function wpse_77308_products_help( $old_help, $screen_id, $screen )
{
    // Not our screen, exit earlier
    // Adjust for your correct screen_id, see plugin recommendation bellow
    if( 'edit-schedule' != $screen_id )
        return;

    // Remove default tabs
    $screen->remove_help_tabs();

    // Add one help tab
    // For new ones: duplicate this, change id's and create custom callbacks
    $screen->add_help_tab( array(
        'id'      => 'schedule-help',
        'title'   => 'schedule',
        'content' => '', // left empty on purpose, we use the callback bellow
        'callback' => 'wpse_77308_print_help'
    ));

    // This sets the sidebar, which is common for all tabs of this screen
    get_current_screen()->set_help_sidebar(
        '<p><strong>' . __('For more information:') . '</strong></p>' .
        '<p>' . __('<a href="http://wordpress.stackexchange.com/" title="WordPress StackExchange" target="_blank">WordPress Answers</a>') . '</p>' .
        '<p>' . __('<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>') . '</p>'
    );

    return $old_help;
} */


/* function wpse_77308_print_help()
{
    echo '
        <p>Products show the details of the items that we sell on the website. 
        You can see a list of them on this page in reverse chronological order 
        - the latest one we added is first.</p> 

        <p>You can view/edit the details of each product
        by clicking on its name, or you can perform bulk actions 
        using the dropdown menu and selecting multiple items.</p>
    ';
}
  */
