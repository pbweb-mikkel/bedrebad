<?php

add_filter('pre_set_site_transient_update_themes', 'pb_automatic_updates', 100, 1);
function pb_automatic_updates($data) {
    // Theme information
    $theme   = get_stylesheet(); // Folder name of the current theme
    $current = wp_get_theme()->get('Version'); // Get the version of the current theme
    $slug   = 'bedrebad'; // Folder name of the current theme
    // GitHub information
    $user = 'pbweb-mikkel'; // The GitHub username hosting the repository
    $repo = 'bedrebad'; // Repository name as it appears in the URL
    // Get the latest release tag from the repository. The User-Agent header must be sent, as per
    // GitHub's API documentation: https://developer.github.com/v3/#user-agent-required
    /*$file = json_decode(file_get_contents('https://api.github.com/repos/'.$user.'/'.$repo.'/releases/latest', false,
        stream_context_create(['http' => ['header' => "User-Agent: ".$user."\r\n"]])
    ));*/
    $response = wp_remote_get('https://api.github.com/repos/'.$user.'/'.$repo.'/releases/latest',['headers' => "User-Agent: ".$user]);

    $file = false;
    if(!is_wp_error($response)){
        $file = json_decode($response['body']);
    }

    if($file) {
        $update = filter_var($file->tag_name, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        // Only return a response if the new version number is higher than the current version

        if($update > $current) {
            $item = array(
                'new_version' => $update,
                'url'         => 'https://github.com/'.$user.'/'.$repo,
                'package'     => $file->assets[0]->browser_download_url,
                'theme'            => $theme,
            );

            $data->response[$theme] = $item;

        }else{

            $item = array(
                'new_version' => $current,
                'url'         => '',
                'package'     => '',
                'theme'            => $theme,
                'icons'         => array(),
                'banners'       => array(),
                'banners_rtl'   => array(),
                'tested'        => '',
                'requires_php'  => '',
                'compatibility' => new stdClass(),
            );

            $data->no_update[$theme] = $item;
        }
    }
    return $data;
}