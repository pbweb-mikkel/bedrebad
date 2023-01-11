<?php

function pb_change_language_code($e){

    switch($e){
        case 'da':
            $e = 'dk';
            break;
    }

    return $e;

}

/**** LANGUAGE SWITCHER *********/

function pb_lang_switcher(){
    if(!function_exists('icl_get_languages')){
        return;
    }
    $languages = icl_get_languages('skip_missing=0');

    echo '<ul class="lang-switcher">';
    foreach($languages as $l){
        echo '<li class="lang-'.$l['language_code'].' '.($l['active'] ? 'active' : '').'">';
            if($l['language_code'] != 'en'){
                $l['url'] = "#";
                $l['native_name'] .= ' (Opening soon)';
            }
            echo '<a href="'.$l['url'].'"><span class="flag"><img src="'.get_template_directory_uri().'/img/flags/'.$l['language_code'].'.png" alt="Flag '.$l['language_code'].'" loading="lazy"></span><span class="name">'.$l['native_name'].'</span></a>';
        echo '</li>';
    }
    echo '</ul>';
}
