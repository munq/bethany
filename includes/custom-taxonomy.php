<?php

// hook into the init action and call create_topic_taxonomies when it fires

$post_id = isset($_GET['post']) ? $_GET['post'] : '0';

$restricted_to = array(
		'templates/bsr-series-detail.php', 
		'templates/daily-devotions-detail.php',
		'templates/meditation-detail.php',
	);
	
add_action( 'init', 'create_topic_taxonomies', 0 );

if ( isset($post_id) && !in_array(get_page_template_slug( $post_id ), $restricted_to) ) {
	add_action( 'admin_head', 'remove_topic_metabox' );
}

function remove_topic_metabox() {
	remove_meta_box('tagsdiv-topic', 'page', 'side');
}

// create two taxonomies, genres and Topics for the post type "book"
function create_topic_taxonomies() {
	
	// Add new taxonomy, NOT hierarchical (like tags)
	$labels = array(
		'name'                       => _x( 'Topics', 'taxonomy general name' ),
		'singular_name'              => _x( 'Topic', 'taxonomy singular name' ),
		'search_items'               => __( 'Search Topics' ),
		'popular_items'              => __( 'Popular Topics' ),
		'all_items'                  => __( 'All Topics' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Topic' ),
		'update_item'                => __( 'Update Topic' ),
		'add_new_item'               => __( 'Add New Topic' ),
		'new_item_name'              => __( 'New Topic' ),
		'separate_items_with_commas' => __( 'Separate Topics with commas' ),
		'add_or_remove_items'        => __( 'Add or remove Topics' ),
		'choose_from_most_used'      => __( 'Choose from the most used Topics' ),
		'not_found'                  => __( 'No Topics found.' ),
		'menu_name'                  => __( 'Topics' ),
	);

	$args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'public'				=> false,
		'rewrite' 				=> false,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'show_tagcloud'			=> true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'topic' ),
	);

	register_taxonomy( 'topic', 'page', $args );
	
	if( !get_option('predefine_topics_inserted') ) {
		$pre_insert_terms = array(
				'Baptism',
				'Christ',
				'Covenants',
				'Fellowship',
				'Grace',
				'Holy Spirit',
				'Love',
				'Marriage',
				'Offering',
				'Parenting',
				'Salvation',
				'Sin',
				'Spiritual Gifts',
				'Suffering',
				'Thanksgiving',
				'The Church',
				'Worship'
			);
		
		foreach ( $pre_insert_terms as $key => $term ) {
			wp_insert_term( $term, 'topic' );
		}		
		update_option('predefine_topics_inserted', True);
	}
}