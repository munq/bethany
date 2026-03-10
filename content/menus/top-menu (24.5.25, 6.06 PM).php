<?php
$current_user = wp_get_current_user();
$current_user_name = isset($current_user->data) ? $current_user->data->display_name : 'Guest';

$roles = (array) $current_user->roles;
$menu_name = "";

// Role-to-menu mapping with priority
$role_priority = array(
    'sst'         => 'Top Menu Sunday School',
    'login_member'=> 'Top Menu Login Member',
    'pastor'      => 'Top Menu Pastor',
    'ipcindia'    => 'Top Menu IPC India',
    'testrole'    => 'Top Menu Testrole',
    'ymg'         => 'Top Menu YMG'
);

// Select the first matching menu based on role priority
foreach ($role_priority as $role => $menu) {
    if (in_array($role, $roles)) {
        $menu_name = $menu;
        break;
    }
}

$items_postlogin = !empty($menu_name) ? wp_get_nav_menu_items($menu_name) : array();

// Get top menu safely
$items_top = array();
$term_top = get_term_by('slug', 'top-menu', 'nav_menu');
if ($term_top && !is_wp_error($term_top)) {
    $menu_top = $term_top->term_id;
    $items_top = wp_get_nav_menu_items($menu_top);
}

$allowed_roles = array('administrator','login_member','sst','pastor','ipcindia','testrole');
?>

<!-- Top Bar -->
<nav id="top-bar" class="desktop-only">
    <div class="wrapper clearfix">
        <ul class="nav-top clearfix">
            <?php if (array_intersect($allowed_roles, $roles)) : ?>
                <?php foreach ($items_postlogin as $item) : ?>
                    <li class="mobileNavi"><a href="<?php echo esc_url($item->url); ?>"><?php echo esc_html($item->title); ?></a></li>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php foreach ($items_top as $item_top) : ?>
                <li class="desktopNavi"><a href="<?php echo esc_url($item_top->url); ?>"><?php echo esc_html($item_top->title); ?></a></li>
            <?php endforeach; ?>

            <?php if (array_intersect($allowed_roles, $roles)) : ?>
                <li class="desktopLogin"><a class="loggedOn" href="#">Hi, <?php echo esc_html($current_user_name); ?></a></li>
                <li class="mobileNavi"><a href="<?php echo esc_url(wp_logout_url(get_permalink(get_page_by_path('bethany-login')->ID))); ?>">Logout</a></li>
            <?php else : ?>
                <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('bethany-login')->ID)); ?>">Login</a></li>
            <?php endif; ?>
        </ul>

        <?php if (array_intersect($allowed_roles, $roles)) : ?>
            <div class="postlogSubs">
                <ul>
                    <?php foreach ($items_postlogin as $item) : ?>
                        <li><a href="<?php echo esc_url($item->url); ?>"><?php echo esc_html($item->title); ?></a></li>
                    <?php endforeach; ?>
                    <li><a href="<?php echo esc_url(wp_logout_url(get_permalink(get_page_by_path('bethany-login')->ID))); ?>">Logout</a></li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</nav>
<!--#end of Top Bar -->
