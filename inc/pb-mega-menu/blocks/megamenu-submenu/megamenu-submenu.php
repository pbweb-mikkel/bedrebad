<?php

$connected_page = $block['data']['forbundet_side'];
$heading = $block['data']['heading'];

if($connected_page) {
    echo '<div class="megamenu-submenu">';
    echo '<div class="submenu-title">' . ($heading ?: get_the_title($connected_page)) . '</div>';
    echo wp_nav_menu(array(
        'theme_location'  => 'primary',
        'sub_menu' => true,
        'root_id'  => $connected_page
    ));
    echo '</div>';
}