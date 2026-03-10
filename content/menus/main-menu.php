<?php
$current_user = wp_get_current_user();
$current_user_name = '';

if (
    is_user_logged_in() &&
    isset($current_user->data) &&
    property_exists($current_user->data, 'display_name')
) {
    $current_user_name = $current_user->data->display_name;
}

$allowed_roles = array('administrator','login_member','sst','pastor','ipcindia','ymg');
?>

<!-- Main Nav -->
<nav id="nav-main">
    <div class="wrapper clearfix">
        <div id="nav-logo">
            <a href="<?php echo site_url(); ?>" alt="Bethany Independent Presbyterian Church">Bethany Independent Presbyterian Church</a>
        </div>
        <a id="nav-main-mobile" class="mobile-only" href="#mm-nav-menu">MENU</a>
        <div id="nav-menu">
            <ul class="nav-main clearfix">
                <li class="desktop-only">
                    <a href="<?php echo site_url(); ?>">
                        <span class="i-home"><span></span><span></span></span>
                    </a>
                </li>

                <?php if(array_intersect($allowed_roles, $current_user->roles)) { ?>
                    <li class="mobileUserID"><span>Hi, <?php echo $current_user_name; ?></span></li>
                <?php } ?>

                <?php
                    $term = get_term_by('slug', 'main-menu', 'nav_menu');
                    $menu_id = $term->term_id;

                    $items = wp_get_nav_menu_items($menu_id);

                    $main_menus = array();
                    $sub_menus = array();

                    foreach ($items as $item) {
                        if (0 == $item->menu_item_parent) {
                            $main_menus[$item->db_id] = $item;
                        }
                    }

                    foreach ($items as $item) {
                        if (0 < $item->menu_item_parent) {
                            $sub_menus[$item->menu_item_parent][$item->db_id] = $item;
                        }
                    }

                    $site_url = get_option('siteurl');
                    $bucket_url = "http://bethanyipc.s3-ap-southeast-1.amazonaws.com";

                    foreach ($main_menus as $db_id => $main_menu) {
                ?>
                    <li>
                        <a href="<?php echo $main_menu->url; ?>" <?php echo (!empty($main_menu->classes) ? ' class="'.implode(' ', $main_menu->classes).'"' : ''); ?>>
                            <?php echo $main_menu->title; ?>
                        </a>
                        <?php if (!empty($sub_menus[$db_id]) || !empty($main_menu->classes[0])) { ?>
                            <div class="nav-mega-wrapper">
                                <?php
                                if (!empty($main_menu->classes[0])) {
                                    echo str_replace($site_url, $bucket_url, $main_menu->description);
                                } else { ?>
                                    <ul class="nav-level-2nd">
                                        <?php foreach ($sub_menus[$db_id] as $sub_db_id => $sub_menu) { ?>
                                            <li>
                                                <a href="<?php echo $sub_menu->url; ?>">
                                                    <?php echo $sub_menu->title . " " . str_replace($site_url, $bucket_url, $sub_menu->description); ?>
                                                </a>
                                                <?php if (isset($sub_menus[$sub_db_id]) && !empty($sub_menus[$sub_db_id])) { ?>
                                                    <ul class="nav-level-3rd">
                                                        <?php foreach ($sub_menus[$sub_db_id] as $subsub_db_id => $subsub_menu) { ?>
                                                            <li>
                                                                <a href="<?php echo $subsub_menu->url; ?>">
                                                                    <?php echo $subsub_menu->title . " " . str_replace($site_url, $bucket_url, $sub_menu->description); ?>
                                                                </a>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                <?php } ?>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <?php echo str_replace($site_url, $bucket_url, $main_menu->description); ?>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav><!--#end of Main Nav -->
