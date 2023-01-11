<?php

add_action('acf/init', 'pb_acf_init_block_types');
function pb_acf_init_block_types() {

    add_filter( 'should_load_remote_block_patterns', '__return_false' );

    pb_register_block('banner');
    pb_register_block('banner-contact');
    pb_register_block('cards');
    pb_register_block('column-text');
    pb_register_block('comparison-table');
    pb_register_block('counter', true);
    pb_register_block('donation-box');
    pb_register_block('employees');
    pb_register_block('fact-slider', true);
    pb_register_block('facts');
    pb_register_block('featured-employees');
    pb_register_block('featured-post');
    pb_register_block('featured-post-alt');
    pb_register_block('featured-post-slider', true);
    pb_register_block('featured-posts');
    pb_register_block('gallery', true);
    pb_register_block('hero');
    pb_register_block('hero-frontpage');
    pb_register_block('link-boxes');
    pb_register_block('media-text');
    pb_register_block('media-text-alt');
    pb_register_block('media-text-fullwidth');
    pb_register_block('meet-a-seller');
    pb_register_block('post-slider', true);
    pb_register_block('sponsors', true);
    pb_register_block('text');
    pb_register_block('text-image-icons');
    pb_register_block('usps');
    //pb_register_block('find-retailer', true, false, ['jquery', 'wpsl-js']);

}

function pb_register_block($block_name, $script = false, $style = false, $dep = ['jquery','slick']){

    $args = [];
    if($script && $style){
        $args['enqueue_assets'] = function() use ($block_name, $dep){
            if(!is_admin()) {
                wp_enqueue_script('pb-'.$block_name, get_template_directory_uri() . '/templates/blocks/'.$block_name.'/'.$block_name.'.min.js', $dep, PB_ASSET_VERSION, true);
                wp_enqueue_style('pb-'.$block_name, get_template_directory_uri() . '/templates/blocks/'.$block_name.'/'.$block_name.'.css', [], PB_ASSET_VERSION);
            }
        };
    }else if($script){
        $args['enqueue_assets'] = function() use ($block_name, $dep){
            if(!is_admin()) {
                wp_enqueue_script('pb-'.$block_name, get_template_directory_uri() . '/templates/blocks/'.$block_name.'/'.$block_name.'.min.js', $dep, PB_ASSET_VERSION, true);
            }
        };
    }else if($style){
        $args['enqueue_assets'] = function() use ($block_name){
            if(!is_admin()) {
                wp_enqueue_style('pb-'.$block_name, get_template_directory_uri() . '/templates/blocks/'.$block_name.'/'.$block_name.'.css', [], PB_ASSET_VERSION);
            }
        };
    }

    register_block_type(get_template_directory().'/templates/blocks/'.$block_name,$args);
}

function pb_block_categories( $categories ) {
    global $post;

    if($post && $post->post_type == 'post'){
        array_unshift(
            $categories,

            [
                'slug'  => 'pb-post',
                'title' => __( 'Indlæg', 'pb' ),
            ]

        );
    }else{

        array_unshift(
            $categories,

            [
                'slug'  => 'pb-sliders',
                'title' => __( 'Sliders', 'pb' ),
            ]

        );
        array_unshift(
            $categories,

            [
                'slug'  => 'pb-lists',
                'title' => __( 'Indholdsoversigt', 'pb' ),
            ]

        );
        array_unshift(
            $categories,

            [
                'slug'  => 'pb',
                'title' => __( 'Primær indhold', 'pb' ),
            ]

        );
        array_unshift(
            $categories,

            [
                'slug'  => 'pb-hero',
                'title' => __( 'Heroes', 'pb' ),
            ]

        );

    }

    return $categories;
}
add_action( 'block_categories_all', 'pb_block_categories', 10, 2 );

add_filter( 'render_block', 'my_wrap_quote_block_fitler', 10, 3);
function my_wrap_quote_block_fitler( $block_content, $block ) {

    if( is_admin() ) return $block_content;

    if(empty($block['attrs']['data']['responsive_settings_hide_on_device']) || !is_array($block['attrs']['data']['responsive_settings_hide_on_device'])) return $block_content;

    global $isTablet;
    global $isMobile;

    $hide_on_devices = $block['attrs']['data']['responsive_settings_hide_on_device'];

    if(in_array('desktop', $hide_on_devices) && !$isMobile && !$isTablet){
        return '';
    }

    if(in_array('tablet', $hide_on_devices) && $isTablet){
        return '';
    }

    if(in_array('mobile', $hide_on_devices) && $isMobile){
        return '';
    }

    return $block_content;
}

function pb_generate_column_classes($block, $multiplier = 0){
    $classes = [];

    if($multiplier){
        if(!empty($block['columns']['columns_desktop'])){
            $block['columns']['columns_desktop'] = ceil($block['columns']['columns_desktop']/$multiplier);
            if($block['columns']['columns_desktop'] > 12){
                $block['columns']['columns_desktop'] = 12;
            }
        }
        if(!empty($block['columns']['columns_laptop'])){
        $block['columns']['columns_laptop'] = ceil($block['columns']['columns_laptop']/$multiplier);
            if($block['columns']['columns_laptop'] > 12){
                $block['columns']['columns_laptop'] = 12;
            }
        }
        if(!empty($block['columns']['columns_tablet'])){
            $block['columns']['columns_tablet'] = ceil($block['columns']['columns_tablet']/$multiplier);
            if($block['columns']['columns_tablet'] > 12){
                $block['columns']['columns_tablet'] = 12;
            }
        }
        if(!empty($block['columns']['columns_mobile'])) {
            $block['columns']['columns_mobile'] = ceil($block['columns']['columns_mobile'] / $multiplier);
            if ($block['columns']['columns_mobile'] > 12) {
                $block['columns']['columns_mobile'] = 12;
            }
        }
    }

    if(!empty($block['columns'])){

        if(!empty($block['columns']['columns_laptop'])){
            if(!empty($block['columns']['columns_desktop'])){
                $classes[] = 'col-xxl-'.($block['columns']['columns_desktop'] == 5 ? 'fifth' : (12/$block['columns']['columns_desktop']));
            }
            $classes[] = 'col-lg-'.($block['columns']['columns_laptop'] == 5 ? 'fifth' : (12/$block['columns']['columns_laptop']));
        }else{
            if(!empty($block['columns']['columns_desktop'])){
                $classes[] = 'col-xl-'.($block['columns']['columns_desktop'] == 5 ? 'fifth' : (12/$block['columns']['columns_desktop']));
            }
        }
        if(!empty($block['columns']['columns_tablet'])){
            $classes[] = 'col-md-'.($block['columns']['columns_tablet'] == 5 ? 'fifth' :(12/$block['columns']['columns_tablet']));
        }
        if(!empty($block['columns']['columns_mobile'])){
            $classes[] = 'col-'.($block['columns']['columns_mobile'] == 5 ? 'fifth' :(12/$block['columns']['columns_mobile']));
        }
    }

    return implode(' ', $classes);
}

function pb_generate_section_classes($block){
    $classes = [];

    if(!empty($block['responsive_settings']) && !empty($block['responsive_settings']['hide_on_device'])){
        foreach ($block['responsive_settings']['hide_on_device'] as $h){
            $classes[] = 'hide-on-'.$h;
        }
    }

    if(!empty($block['farvetema']) && $block['farvetema'] !== 'default'){
        $classes[] = $block['farvetema'];
    }

    if(!empty($block['forskyd_element_op_i_elementet_over']) && $block['forskyd_element_op_i_elementet_over'] == true){
        $classes[] = 'offset-top';
    }

    if(!empty($block['baggrundsfarve']) && $block['baggrundsfarve'] !== 'default'){
        $classes[] = ($block['baggrundsfarve'] !== 'default') ? $block['baggrundsfarve'] : 'background-default';
    }

    if(!empty($block['fill_half']) && $block['fill_half']){
        $classes[] = 'bg-fill-half';
    }

    if(!empty($block['tekstfarve'])){
        $classes[] = ($block['tekstfarve'] !== 'default') ? $block['tekstfarve'] : 'color-default';
    }

    if(!empty($block['heading_color'])){
        $classes[] = ($block['heading_color'] !== 'default') ? 'heading-'.$block['heading_color'] : '';
    }

    if(!empty($block['justify'])){
        $classes[] = ($block['justify'] !== 'default') ? $block['justify'] : '';
    }


    if(!empty($block['space_settings'])){
        foreach ($block['space_settings'] as $s){
            if($s != 'default'){
                $classes[] = $s;
            }
        }
    }


    if(!empty($block['autoplay']) && $block['autoplay']){
        $classes[] = 'autoplay';
    }

    return (!empty($classes) ? implode(' ',$classes) : '');

}


function pb_generate_color_classes($block){
    $classes = [];

    if(!empty($block['backgroundColor'])){
        $classes[] = 'has-'.$block['backgroundColor'].'-background-color';
    }

    if(!empty($block['textColor'])){
        $classes[] = 'has-'.$block['textColor'].'-color';
    }

    return implode(' ',$classes);
}

function pb_generate_section_attributes($block){

    $attributes = [];

    if(!empty($block['anchor'])){
        $attributes[] = 'id="'.$block['anchor'].'"';
    }

    if($style = pb_generate_section_style($block)){
        $attributes[] = $style;
    }

    return implode(' ', $attributes);
}

function pb_generate_section_style($block){

    $fields = $block['data'];
    $block = !empty($block['style']) ? $block['style'] : [];
    $styles = [];

    if(!empty($block['typography'])){

        if(!empty($block['typography']['textTransform'])){
            $styles[] = 'text-transform:'.$block['typography']['textTransform'];
        }

        if(!empty($block['typography']['fontWeight'])){
            $styles[] = 'font-weight:'.$block['typography']['fontWeight'];
        }

        if(!empty($block['typography']['letterSpacing'])){
            $styles[] = 'letterspacing:'.$block['typography']['letterSpacing'];
        }

        if(!empty($block['typography']['fontSize'])){
            $styles[] = 'font-size:'.$block['typography']['fontSize'];
        }
    }

    if(!empty($fields['max_height'])){
        $styles[] = 'max-height:'.$fields['max_height'].'px';
    }

    if(!empty($fields['baggrundsbillede'])){
        $styles[] = 'background-image:url('.wp_get_attachment_image_url($fields['baggrundsbillede'], 'max').')';

        if(!empty($fields['background-position'])){
            $styles[] = 'background-position:'.$fields['background-position'];
        }

        if(!empty($fields['background-size'])){
            $styles[] = 'background-size:'.$fields['background-size'];
        }

        if(!empty($fields['background_attachment_fixed'])){
            $styles[] = 'background-attachment:fixed';
        }

    }

    if($styles){
        return 'style="'.implode(';',$styles).'"';
    }else{
        return '';
    }

}

function pb_get_fields_from_block(){
     $fields = get_fields();

     if(!empty($fields['tekstfarve']) && !empty($fields['tekstfarve']['tekstfarve'])){
         $fields['tekstfarve'] = $fields['tekstfarve']['tekstfarve'];
     }

    if(!empty($fields['farvetema']) && !empty($fields['farvetema']['farvetema'])){
        $fields['farvetema'] = $fields['farvetema']['farvetema'];
    }

    if(!empty($fields['baggrundsfarve']) && !empty($fields['baggrundsfarve']['baggrundsfarve'])){
        $fields['baggrundsfarve'] = $fields['baggrundsfarve']['baggrundsfarve'];
    }

    if(!empty($fields['space_settings']) && !empty($fields['space_settings']['space_settings'])){
        $fields['space_settings'] = $fields['space_settings']['space_settings'];
    }

    if(!empty($fields['responsive_settings']) && !empty($fields['responsive_settings']['responsive_settings'])){
        $fields['responsive_settings'] = $fields['responsive_settings']['responsive_settings'];
    }

    return $fields;

}

add_action( 'admin_menu', 'pb_add_reusable_block_to_admin_bar' );
function pb_add_reusable_block_to_admin_bar() {
    add_menu_page( 'linked_url', 'Reusable Blocks', 'read', 'edit.php?post_type=wp_block', '', 'dashicons-editor-table', 22 );
}

// Adds support for editor color palette.
add_theme_support( 'editor-color-palette', array(
    array(
        'name'  => __( 'White', 'pb' ),
        'slug'  => 'white',
        'color'	=> '#fff',
    ),
    array(
        'name'  => __( 'White (faded)', 'pb' ),
        'slug'  => 'white-faded',
        'color'	=> 'rgba(255,255,255,0.6)',
    ),
    array(
        'name'  => __( 'Black', 'pb' ),
        'slug'  => 'black',
        'color'	=> '#282827',
    ),
    array(
        'name'  => __( 'Primary', 'pb' ),
        'slug'  => 'primary',
        'color'	=> '#E85B4D',
    ),
    array(
        'name'  => __( 'Secondary', 'pb' ),
        'slug'  => 'highlight',
        'color'	=> '#50B25D',
    ),
    array(
        'name'  => __( 'Light gray', 'pb' ),
        'slug'  => 'light-gray',
        'color'	=> '#F5F5F5',
    ),
    array(
        'name'  => __( 'Medium gray', 'pb' ),
        'slug'  => 'medium-gray',
        'color' => '#E2E2E2',
    ),
    array(
        'name'  => __( 'Dark gray', 'pb' ),
        'slug'  => 'dark-gray',
        'color' => '#333',
    ),
) );

function pb_get_arrow_icon_from_text_color($color, $default = 'arrow-right-primary'){
    $icon = '';
    switch($color){
        case 'has-primary-2-color':
            $icon = 'arrow-right-primary-2';
            break;
        case 'has-highlight-2-color':
            $icon = 'arrow-right-highlight-2';
            break;
        case 'has-highlight-color':
            $icon = 'arrow-right-highlight';
            break;
        case 'has-primary-color':
            $icon = 'arrow-right-primary';
            break;
        case 'has-white-color':
            $icon = 'arrow-right-w';
            break;
        case 'has-black-color':
            $icon = 'arrow-right-b';
            break;
        case 'primary-2':
            $icon = 'arrow-right-primary-2';
            break;
        case 'color-primary-2':
            $icon = 'arrow-right-primary-2';
            break;
        default:
            $icon = $default;
    }
    return $icon;
}


function pb_get_button_color_from_background_color($color){
    $icon = '';
    switch($color){
        case 'has-primary-background-color':
            $icon = 'btn-white';
            break;
        case 'has-white-background-color':
            $icon = 'btn-primary';
            break;
        default:
            $icon = 'btn-white';
    }
    return $icon;
}


function pb_get_circle_arrow_color_from_text_color($color){
    $icon = '';
    switch($color){
        case 'has-primary-2-color':
            $icon = 'primary-2';
            break;
        case 'color-blue':
            $icon = 'primary-2';
            break;
        case 'has-highlight-2-color':
            $icon = 'highlight-2';
            break;
        case 'has-highlight-color':
            $icon = 'highlight';
            break;
        case 'has-primary-color':
            $icon = 'primary';
            break;
        case 'secondary':
            $icon = 'secondary';
            break;
        case 'has-black-color':
            $icon = 'black';
            break;
        case 'has-white-color':
            $icon = 'white';
            break;
        default:
            $icon = 'primary';
    }
    return $icon;
}

function pb_is_preview($block){

    if(empty($block['data']['preview_image_help'])){
        return false;
    }

    if(file_exists($block['path'].'/block.png')){
        $dir = '/'.str_replace(ABSPATH, '', $block['path']);
        echo '<img src="'.$dir.'/block.png" style="width:100%;height:auto;">';
        return true;
    }

    $name = basename($block['render_template'], '.php');
    $image = get_template_directory_uri().'/img/previews/'.$name.'.png';

    echo '<img src="'.$image.'" style="width:100%;height:auto;">';
    return true;
}