<?php

function where_title_like( $where, &$wp_query ) {
	global $wpdb;
	
	$post_title_like = $wp_query->get( 'post_title_like' );
	
	$where .= " AND post_title LIKE '%" . esc_sql( $wpdb->esc_like( $post_title_like ) ) . "%'";

	return $where;
}

function where_search_keyword_title_like( $where, &$wp_query ) {
    global $wpdb;
    $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
    $poem_search = filter_input(INPUT_GET, 'poem_search', FILTER_SANITIZE_STRING);

    $search = $search ? $search : $poem_search;

    $post_title_like = $wp_query->get( 'post_title_like' );

    $where .= " AND ( ";
    $where .= " ( post_title LIKE '%" . esc_sql( $wpdb->esc_like( $post_title_like ) ) . "%' ) ";
    $where .= " OR ";
    $where .= " ( mt1.meta_key = 'poems_ecards_search_keywords' AND mt1.meta_value LIKE '%{$search}%' ) ";
    $where .= " ) ";

    return $where;
}

function join_text_refrence ($join) {
   $text_ref_join = " INNER JOIN wp_postmeta AS mt2 ON (wp_posts.ID = mt2.post_id) ";
   $join .= $text_ref_join;
   return $join;
}

function join_search_keyword ($join) {
    $join_search_keyword = " INNER JOIN wp_postmeta AS mt1 ON ( wp_posts.ID = mt1.post_id ) ";
    $join .= $join_search_keyword;
    return $join;
}

// custom filter for text_refrence using daily-devotions template
function where_text_refrence( $where ){
    $text_refrence = "";
    $book = filter_input(INPUT_GET, 'book', FILTER_SANITIZE_STRING);
    $chapter = filter_input(INPUT_GET, 'chapter', FILTER_SANITIZE_NUMBER_INT);
    $verse = filter_input(INPUT_GET, 'verse', FILTER_SANITIZE_NUMBER_INT);
    
    if ( (isset($book) && !empty($book)) && (isset($chapter) && !empty($chapter)) ){
        $text_refrence = $book . " " .$chapter;
        if (isset($book) && !empty($book)) {
            $text_refrence = $book . " " . $chapter . ":" .$verse;
        }
        $where = "
        AND (
          (
          mt1.meta_key = 'text_refrence'
             AND (
                CAST(mt1.meta_value AS CHAR) LIKE '%{$text_refrence}%' 
                OR 
                CAST(mt1.meta_value AS CHAR) LIKE '%{$text_refrence}:%' 
                OR
                CAST(mt1.meta_value AS CHAR) LIKE '%{$text_refrence};%'
             )
          )
          AND ( mt2.meta_key = '_wp_page_template' AND CAST(mt2.meta_value AS CHAR) = 'templates/daily-devotions-detail.php' )  
        )
        AND wp_posts.post_type = 'page' AND ((wp_posts.post_status = 'publish'))
        ";
    }
    return $where;
}


// add_filter('posts_fields', 'select_refrence');
// for custom query must select all needed fields
function select_refrence($fields){
    return 'ID,post_title';
}

// custom filter for text_refrence using others template
function where_text_refrence_others( $where ){
    $text_refrence = "";
    $book = filter_input(INPUT_GET, 'book', FILTER_SANITIZE_STRING);
    $chapter = filter_input(INPUT_GET, 'chapter', FILTER_SANITIZE_NUMBER_INT);
    if ( (isset($book) && !empty($book)) && (isset($chapter) && !empty($chapter)) ){
        $text_refrence = $book . " " .$chapter;
        $where = "
        
        AND ( 
            mt1.meta_key = '_wp_page_template' 
            AND ( 
                mt1.meta_value IN (
                    'templates/bsr-series-detail.php',
                    'templates/meditation-detail.php',
                    'templates-yag/yag-article-detail.php',
                    'templates-ypg/ypg-article-detail.php'
                )     
            ) 
            AND (
                mt2.meta_key = 'text_refrence'
                AND (
                    CAST(mt2.meta_value AS CHAR) LIKE '%{$text_refrence}%' 
                    OR 
                    CAST(mt2.meta_value AS CHAR) LIKE '%{$text_refrence}:%' 
                    OR
                    CAST(mt2.meta_value AS CHAR) LIKE '%{$text_refrence};%'
                )
            )
        )
        AND wp_posts.post_type = 'page' AND ((wp_posts.post_status = 'publish'))
        ";
    }
    return $where;
}

// custom filter for botb_tags:book,chapter and verse
function where_botb_tags( $where ){
    $book = filter_input(INPUT_GET, 'book', FILTER_SANITIZE_STRING);
    $chapter = filter_input(INPUT_GET, 'chapter', FILTER_SANITIZE_NUMBER_INT);
    if ( (isset($book) && !empty($book)) && (isset($chapter) && !empty($chapter)) ){
        $verse = filter_input(INPUT_GET, 'verse', FILTER_SANITIZE_NUMBER_INT);
        if (isset($verse) && !empty($verse)) {
            $mt_verse = " AND 
                          ( mt4.meta_key LIKE 'botb_tags_%_botb_tag_verse' AND CAST(mt4.meta_value AS CHAR) = '{$verse}' )
                          AND 
                          ( mt4.meta_key = REPLACE(mt3.meta_key, '_botb_tag_chapter', '_botb_tag_verse') )";
        }
        $where = "
        AND (
                ( mt1.meta_key = '_wp_page_template' AND CAST(mt1.meta_value AS CHAR) = 'templates/daily-devotions-detail.php' )
                AND 
                ( mt2.meta_key LIKE 'botb_tags_%_botb_tag_book' AND CAST(mt2.meta_value AS CHAR) = '{$book}' )
                AND 
                ( mt3.meta_key LIKE 'botb_tags_%_botb_tag_chapter' AND CAST(mt3.meta_value AS CHAR) = '{$chapter}' )
                AND 
                ( mt3.meta_key = REPLACE(mt2.meta_key, '_botb_tag_book', '_botb_tag_chapter') )";
        $where .= $mt_verse;
        $where .= ")
        AND wp_posts.post_type = 'page' 
        AND wp_posts.post_status = 'publish'
        ";
    }
    return $where;
}

// custom filter for botb_tags:book,chapter and verse
function where_botb_tags_others( $where ){
    $book = filter_input(INPUT_GET, 'book', FILTER_SANITIZE_STRING);
    $chapter = filter_input(INPUT_GET, 'chapter', FILTER_SANITIZE_NUMBER_INT);
    if ( (isset($book) && !empty($book)) && (isset($chapter) && !empty($chapter)) ){
        $verse = filter_input(INPUT_GET, 'verse', FILTER_SANITIZE_NUMBER_INT);
        if (isset($verse) && !empty($verse)) {
            $mt_verse = " AND 
                          ( mt4.meta_key LIKE 'botb_tags_%_botb_tag_verse' AND CAST(mt4.meta_value AS CHAR) = '{$verse}' )
                          AND 
                          ( mt4.meta_key = REPLACE(mt3.meta_key, '_botb_tag_chapter', '_botb_tag_verse') )";
        }
        $where = "
        AND ( mt1.meta_key = '_wp_page_template' 
            AND ( 
                mt1.meta_value IN (
                    'templates/bsr-series-detail.php',
                    'templates/meditation-detail.php',
                    'templates-yag/yag-article-detail.php',
                    'templates-ypg/ypg-article-detail.php'
                )     
            ) 
            AND 
                ( mt2.meta_key LIKE 'botb_tags_%_botb_tag_book' AND CAST(mt2.meta_value AS CHAR) = '{$book}' )
            AND 
                ( mt3.meta_key LIKE 'botb_tags_%_botb_tag_chapter' AND CAST(mt3.meta_value AS CHAR) = '{$chapter}' )
            AND 
                ( mt3.meta_key = REPLACE(mt2.meta_key, '_botb_tag_book', '_botb_tag_chapter') )";
        $where .= $mt_verse;
        $where .= ") 
        AND wp_posts.post_type = 'page' 
        AND wp_posts.post_status = 'publish'
        ";
    }
    return $where;
}

// custom filter to replace '=' with 'LIKE'
function where_content_like( $search, $template ) {
    //$template = explode(","$template);
    $tpl_size = count($template);
    $i = 1;
    $cat = " , ";
    $tpl_query = ' mt1.meta_value IN ( ';
    foreach($template as $tpl){
        $tpl_query .= " '{$tpl}' ";
        if($tpl_size != $i){
            $tpl_query .= $cat;    
        }
        $i++;
    }
    $tpl_query .= " ) "; 
    $query = "SELECT ID FROM wp_posts
			LEFT JOIN wp_postmeta mt1 ON(wp_posts.ID = mt1.post_id) 
			WHERE 1=1 
			AND ( mt1.meta_key = '_wp_page_template' AND ({$tpl_query}) ) 
			AND wp_posts.post_content LIKE '%{$search}%'
			AND wp_posts.post_type = 'page' 
			AND wp_posts.post_status = 'publish' 
			GROUP BY wp_posts.ID 
			ORDER BY wp_posts.post_date DESC";		
    return $query;
}    

// custom filter to replace '=' with 'LIKE'
function where_botb_tags_like( $where ) {
    $where = str_replace("meta_key = 'botb_tags_%_botb_tag_book'", "meta_key LIKE 'botb_tags_%_botb_tag_book'", $where);
    $where = str_replace("meta_key = 'botb_tags_%_botb_tag_chapter'", "meta_key LIKE 'botb_tags_%_botb_tag_chapter'", $where);
    $where = str_replace("meta_key = 'botb_tags_%_botb_tag_verse'", "meta_key LIKE 'botb_tags_%_botb_tag_verse'", $where);
    return $where;
}