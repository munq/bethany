<?php
$current_user = wp_get_current_user();
$current_user_name = $current_user->data->display_name;
// var_dump($current_user->data);

$menu_name = "";

if ( in_array( 'sst', (array) $current_user->roles ) ) {
    $menu_name = "Top Menu Sunday School";
}
if ( in_array( 'login_member', (array) $current_user->roles ) ) {
    $menu_name = "Top Menu Login Member";
}
if ( in_array( 'pastor', (array) $current_user->roles ) ) {
    $menu_name = "Top Menu Pastor";
}
if ( in_array( 'ipcindia', (array) $current_user->roles ) ) {
    $menu_name = "Top Menu IPC India";
}
if ( in_array( 'testrole', (array) $current_user->roles ) ) {
    $menu_name = "Top Menu Testrole";
}
if ( in_array( 'ymg', (array) $current_user->roles ) ) {
    $menu_name = "Top Menu YMG";
}

$items_postlogin = !empty($menu_name) ? wp_get_nav_menu_items($menu_name) : array();
?>

<!-- Top Bar -->
<nav id="top-bar" class="desktop-only">

	<div class="wrapper clearfix">
		<ul class="nav-top clearfix">
			<?php
			$term_top = get_term_by('slug', 'top-menu', 'nav_menu');
			$menu_top = $term_top->term_id;
			$items_top = wp_get_nav_menu_items($menu_top);
			$allowed_roles = array('administrator','login_member','sst','pastor','ipcindia','testrole');
			?>

			<?php if(array_intersect($allowed_roles, $current_user->roles)) { ?>
				<?php foreach ($items_postlogin as $items) { ?>
					<li class="mobileNavi"><a href="<?php echo $items->url; ?>"><?php echo $items->title; ?></a></li>
				<?php } ?>
			<?php } ?>

			<?php foreach($items_top as $item_top) { ?>
				<li class="desktopNavi"><a href="<?php echo $item_top->url; ?>"><?php echo $item_top->title; ?></a></li>
			<?php } ?>

			<?php if(array_intersect($allowed_roles, $current_user->roles)) { ?>
				<li class="desktopLogin"><a class="loggedOn" href="#">Hi, <?php echo $current_user_name; ?></a>
				<li class="mobileNavi"><a href="<?php echo wp_logout_url(get_permalink(get_page_by_path('bethany-login')->ID)); ?>">Logout</a></li>
			<?php } else { ?>
				<li><a href="<?php echo get_permalink(get_page_by_path('bethany-login')->ID); ?>">Login</a></li>
			<?php } ?>
		</ul>

		<?php if(array_intersect($allowed_roles, $current_user->roles)) { ?>
			<div class="postlogSubs">
				<ul>
				<?php foreach ($items_postlogin as $items) { ?>
					<li><a href="<?php echo $items->url; ?>"><?php echo $items->title; ?></a></li>
				<?php } ?>
					<li><a href="<?php echo wp_logout_url(get_permalink(get_page_by_path('bethany-login')->ID)); ?>">Logout</a></li>
				</ul>
			</div>
		<?php } ?>

	</div>

</nav>
<!--#end of Top Bar -->
