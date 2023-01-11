<?php
// Callback function to insert 'styleselect' into the $buttons array
/*function pb_mce_buttons_2( $buttons ) {
    array_unshift( $buttons, 'styleselect' );
    return $buttons;
}
// Register our callback to the appropriate filter
add_filter( 'mce_buttons_2', 'pb_mce_buttons_2' );

// Callback function to filter the MCE settings
function pb_mce_before_init_insert_formats( $init_array ) {
    // Define the style_formats array
    $style_formats = array(
        // Each array child is a format with it's own settings
        array(
            'title' => 'Underoverskift',
            'block' => 'div',
            'classes' => 'h5',
            'wrapper' => true,
        ),
        array(
            'title' => 'Underoverskift grøn',
            'block' => 'div',
            'classes' => 'h5 has-secondary-color',
            'wrapper' => true,
        ),

    );
    // Insert the array, JSON ENCODED, into 'style_formats'
    $init_array['style_formats'] = wp_json_encode( $style_formats );

    return $init_array;

}
// Attach callback to 'tiny_mce_before_init'
add_filter( 'tiny_mce_before_init', 'pb_mce_before_init_insert_formats' );
*/
// Customize mce editor font sizes
if ( ! function_exists( 'wpex_mce_text_sizes' ) ) {
    function wpex_mce_text_sizes( $initArray ){
        $initArray['fontsize_formats'] = "9px 10px 12px 13px 14px 16px 18px 21px 24px 28px 32px 36px 40px 44px 48px 52px 56px 60px 64px 68px 72px 96px";
        return $initArray;
    }
}
add_filter( 'tiny_mce_before_init', 'wpex_mce_text_sizes' );