<?php
function pb_ajax_search(){
    ob_start();
    include(get_template_directory().'/templates/partials/search-result.php');
    $c = ob_get_clean();
    wp_send_json_success($c);
}
add_action('wp_ajax_pb_ajax_search', 'pb_ajax_search');
add_action('wp_ajax_nopriv_pb_ajax_search', 'pb_ajax_search');
