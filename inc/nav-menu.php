<?php

// add hook
add_filter( 'wp_nav_menu_objects', 'pb_nav_menu_objects_sub_menu', 10, 2 );
// filter_hook function to react on sub_menu flag
function pb_nav_menu_objects_sub_menu( $sorted_menu_items, $args ) {
    if ( isset( $args->sub_menu ) ) {
        $root_id = 0;
        // find the current menu item
        if(!$args->root_id) {
            foreach ($sorted_menu_items as $menu_item) {
                if ($menu_item->current) {
                    // set the root id based on whether the current menu item has a parent or not
                    $root_id = ($menu_item->menu_item_parent) ? $menu_item->menu_item_parent : $menu_item->ID;
                    break;
                }
            }
        }else{
            foreach ($sorted_menu_items as $menu_item) {
                if ($menu_item->object_id == $args->root_id) {
                    // set the root id based on whether the current menu item has a parent or not
                    $root_id = $menu_item->ID;
                    break;
                }
            }
        }

        // find the top level parent
        if ( ! isset( $args->direct_parent ) ) {
            $prev_root_id = $root_id;
            while ( $prev_root_id != 0 ) {
                foreach ( $sorted_menu_items as $menu_item ) {
                    if ( $menu_item->ID == $prev_root_id ) {
                        $prev_root_id = $menu_item->menu_item_parent;
                        // don't set the root_id to 0 if we've reached the top of the menu
                        if ( $prev_root_id != 0 ) $root_id = $menu_item->menu_item_parent;
                        break;
                    }
                }
            }
        }

        $menu_item_parents = array();
        foreach ( $sorted_menu_items as $key => $item ) {
            // init menu_item_parents
            if ( $item->ID == $root_id ) $menu_item_parents[] = $item->ID;

            if ( in_array( $item->menu_item_parent, $menu_item_parents ) ) {
                // part of sub-tree: keep!
                $menu_item_parents[] = $item->ID;
            } else if ( ! ( isset( $args->show_parent ) && in_array( $item->ID, $menu_item_parents ) ) ) {
                // not part of sub-tree: away with it!
                unset( $sorted_menu_items[$key] );
            }
        }

        return $sorted_menu_items;
    } else {
        return $sorted_menu_items;
    }
}


// Add HTML to the end of menu items which have dropdowns
class PB_Walker extends Walker_Nav_Menu {

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

        $classes   = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        if(is_array($classes) && (in_array('current-menu-ancestor',$classes) || in_array('current-menu-item',$classes))){
            $classes[] = 'active';
        }

        /**
         * Filters the arguments for a single nav menu item.
         *
         * @since 4.4.0
         *
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param WP_Post  $item  Menu item data object.
         * @param int      $depth Depth of menu item. Used for padding.
         */
        $args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

        /**
         * Filters the CSS classes applied to a menu item's list item element.
         *
         * @since 3.0.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
         * @param WP_Post  $item    The current menu item.
         * @param stdClass $args    An object of wp_nav_menu() arguments.
         * @param int      $depth   Depth of menu item. Used for padding.
         */
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        /**
         * Filters the ID applied to a menu item's list item element.
         *
         * @since 3.0.1
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param WP_Post  $item    The current menu item.
         * @param stdClass $args    An object of wp_nav_menu() arguments.
         * @param int      $depth   Depth of menu item. Used for padding.
         */



        $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
        $output .= $indent . '<li' . $id . $class_names . ' data-page-id="'.$item->object_id.'">';

        $atts           = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target ) ? $item->target : '';
        if ( '_blank' === $item->target && empty( $item->xfn ) ) {
            $atts['rel'] = 'noopener noreferrer';
        } else {
            $atts['rel'] = $item->xfn;
        }
        $atts['href']         = ! empty( $item->url ) ? $item->url : '';
        $atts['aria-current'] = $item->current ? 'page' : '';

        /**
         * Filters the HTML attributes applied to a menu item's anchor element.
         *
         * @since 3.6.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
         *
         *     @type string $title        Title attribute.
         *     @type string $target       Target attribute.
         *     @type string $rel          The rel attribute.
         *     @type string $href         The href attribute.
         *     @type string $aria_current The aria-current attribute.
         * }
         * @param WP_Post  $item  The current menu item.
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param int      $depth Depth of menu item. Used for padding.
         */
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
                $value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        /** This filter is documented in wp-includes/post-template.php */
        $title = apply_filters( 'the_title', $item->title, $item->ID );

        /**
         * Filters a menu item's title.
         *
         * @since 4.4.0
         *
         * @param string   $title The menu item's title.
         * @param WP_Post  $item  The current menu item.
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param int      $depth Depth of menu item. Used for padding.
         */
        $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

        $item_output  = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . $title . $args->link_after;

        if(is_array($item->classes) && in_array('menu-item-has-children',$item->classes)){
            $item_output .= '<span class="dropdown-trigger"><svg width="7" height="5" viewBox="0 0 7 5" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M5.82746 1.32373L5.2958 2.0154C5.12148 2.25761 4.92964 2.48869 4.72161 2.70707C4.51897 2.92796 4.30239 3.13763 4.07299 3.33497C3.99324 3.40856 3.87627 3.52138 3.75399 3.61949C3.69521 3.67603 3.62469 3.72108 3.54664 3.75194C3.54664 3.75194 3.46158 3.69798 3.41905 3.66364C3.37651 3.6293 3.2436 3.48704 3.1798 3.42818L1.77622 2.16257C1.20735 1.60825 1.33495 1.59844 1.18076 1.59844C1.13282 1.59844 1.08684 1.61601 1.05295 1.64729C1.01905 1.67857 1 1.72099 1 1.76523C1.00233 1.84372 1.02808 1.92011 1.07443 1.98597C1.18496 2.14457 1.30568 2.29691 1.43596 2.44218L2.67472 3.83533C2.81548 4.00951 2.9819 4.16471 3.16917 4.29644C3.31024 4.3826 3.47942 4.42076 3.64766 4.40437C3.7894 4.37574 3.92329 4.32065 4.04109 4.24248C4.23276 4.10722 4.41071 3.95617 4.57274 3.79118C4.80423 3.56115 5.02081 3.31873 5.22137 3.06517C5.41898 2.81236 5.59666 2.54679 5.75303 2.27049C6.35912 1.22072 6.50267 1.30902 6.35912 1.15204C6.32277 1.11189 6.27123 1.086 6.21495 1.07962C6.15867 1.07324 6.10187 1.08686 6.05607 1.11771C5.96691 1.17297 5.88944 1.24279 5.82746 1.32373Z" fill="currentColor" stroke="currentColor"/>
</svg>
</span>';
        }



        $item_output .= '</a>';
        $item_output .= $args->after;

        /**
         * Filters a menu item's starting output.
         *
         * The menu item's starting output only includes `$args->before`, the opening `<a>`,
         * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
         * no filter for modifying the opening and closing `<li>` for a menu item.
         *
         * @since 3.0.0
         *
         * @param string   $item_output The menu item's starting HTML output.
         * @param WP_Post  $item        Menu item data object.
         * @param int      $depth       Depth of menu item. Used for padding.
         * @param stdClass $args        An object of wp_nav_menu() arguments.
         */
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

}

// Add HTML to the end of menu items which have dropdowns
class PB_Walker_Logo_Middle extends Walker_Nav_Menu {

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

        $classes   = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        if(is_array($classes) && (in_array('current-menu-ancestor',$classes) || in_array('current-menu-item',$classes))){
            $classes[] = 'active';
        }

        /**
         * Filters the arguments for a single nav menu item.
         *
         * @since 4.4.0
         *
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param WP_Post  $item  Menu item data object.
         * @param int      $depth Depth of menu item. Used for padding.
         */
        $args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

        /**
         * Filters the CSS classes applied to a menu item's list item element.
         *
         * @since 3.0.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
         * @param WP_Post  $item    The current menu item.
         * @param stdClass $args    An object of wp_nav_menu() arguments.
         * @param int      $depth   Depth of menu item. Used for padding.
         */
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        /**
         * Filters the ID applied to a menu item's list item element.
         *
         * @since 3.0.1
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param WP_Post  $item    The current menu item.
         * @param stdClass $args    An object of wp_nav_menu() arguments.
         * @param int      $depth   Depth of menu item. Used for padding.
         */



        $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
        $output .= $indent . '<li' . $id . $class_names . ' data-page-id="'.$item->object_id.'">';

        $atts           = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target ) ? $item->target : '';
        if ( '_blank' === $item->target && empty( $item->xfn ) ) {
            $atts['rel'] = 'noopener noreferrer';
        } else {
            $atts['rel'] = $item->xfn;
        }
        $atts['href']         = ! empty( $item->url ) ? $item->url : '';
        $atts['aria-current'] = $item->current ? 'page' : '';

        /**
         * Filters the HTML attributes applied to a menu item's anchor element.
         *
         * @since 3.6.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
         *
         *     @type string $title        Title attribute.
         *     @type string $target       Target attribute.
         *     @type string $rel          The rel attribute.
         *     @type string $href         The href attribute.
         *     @type string $aria_current The aria-current attribute.
         * }
         * @param WP_Post  $item  The current menu item.
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param int      $depth Depth of menu item. Used for padding.
         */
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
                $value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        /** This filter is documented in wp-includes/post-template.php */
        $title = apply_filters( 'the_title', $item->title, $item->ID );

        /**
         * Filters a menu item's title.
         *
         * @since 4.4.0
         *
         * @param string   $title The menu item's title.
         * @param WP_Post  $item  The current menu item.
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param int      $depth Depth of menu item. Used for padding.
         */
        $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

        $item_output  = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . $title . $args->link_after;

        if(is_array($item->classes) && in_array('menu-item-has-children',$item->classes)){
            $item_output .= '<span class="dropdown-trigger"></span>';
        }



        $item_output .= '</a>';
        $item_output .= $args->after;

        /**
         * Filters a menu item's starting output.
         *
         * The menu item's starting output only includes `$args->before`, the opening `<a>`,
         * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
         * no filter for modifying the opening and closing `<li>` for a menu item.
         *
         * @since 3.0.0
         *
         * @param string   $item_output The menu item's starting HTML output.
         * @param WP_Post  $item        Menu item data object.
         * @param int      $depth       Depth of menu item. Used for padding.
         * @param stdClass $args        An object of wp_nav_menu() arguments.
         */
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    function end_el (&$output, $item, $depth = 0, $args = [], $id = 0) {
        parent::end_el($output, $item, $depth, $args, $id);

        // the menu slug
        $theme_location = 'primary';

        // check for top level depth and if correct menu
        if ($depth === 0 && isset($args->menu) && isset($args->menu->slug) && $args->menu->slug === $theme_location) {
            // get current menu items
            $menu_items = wp_get_nav_menu_items($theme_location);

            // will indicate how many top level menu items we have
            $parent_menu_items = [];

            // get top level menu items
            foreach ($menu_items as $menu_item) {
                if ($menu_item->menu_item_parent == 0) $parent_menu_items[] = $menu_item;
            }

            // get total menu items halved
            $half_menu_items = floor(count($parent_menu_items) / 2);

            // the menu item we want to add the logo to
            // before or after, depends if menu items are odd or even
            if (is_float($half_menu_items)) {
                // if you have odd menu items lets say three this will add after the first
                // [1] [custom html] [2] [3]
                // if you want it to add after the second remove the - 1, this will result in
                // [1] [2] [custom html] [3]
                $middle_item = $parent_menu_items[$half_menu_items - 1];
            } elseif (is_int($half_menu_items)) {
                // this handles even menu items
                $middle_item = $parent_menu_items[$half_menu_items - 1];
            }

            // if current menu item is middle item, add our custom html
            if (isset($middle_item) && $middle_item->ID === $item->ID) {
                $output .= '<li class="my-logo">';

                ob_start();
                ?>
                <div class="header-logo">
                    <?php if($logo = get_field('logo', 'option')){ ?>
                        <div id="logo">
                            <a href="<?= get_home_url() ?>">
                                <svg width="204px" height="36px" viewBox="0 0 512 91" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <g fill="#FFFFFF" fill-rule="nonzero">
                                        <path d="M50.3084198,89.6422425 C42.9163972,89.6422425 36.1643618,88.7618367 30.0590861,87.0044113 C23.9538104,85.2469858 18.6341276,82.5278864 14.1068101,78.8572714 C9.63367129,75.1866563 6.15945451,70.5577535 3.69770441,64.9739489 C1.23256814,59.4003029 0,52.8582105 0,45.3646026 C0,37.4714258 1.32399489,30.6583716 3.96182615,24.9255753 C6.59965741,19.1961652 10.2838171,14.4419738 15.0109191,10.6697735 C19.5890293,7.10074377 24.8646918,4.48661574 30.8548375,2.83078574 C36.8382109,1.1715594 43.048458,0.341946231 49.4821928,0.341946231 C55.2623956,0.341946231 60.5990093,0.968388828 65.475103,2.2246602 C70.3511967,3.48092141 74.9022175,5.11644451 79.1247793,7.12783318 L79.1247793,30.282506 L75.2848555,30.282506 C74.2283685,29.378397 72.9585524,28.32191 71.4787934,27.1164313 C69.9956482,25.9109526 68.1772716,24.7291771 66.0202774,23.5711048 C63.9614822,22.4638252 61.7062889,21.5427853 59.2445388,20.8181436 C56.7827887,20.0935018 53.9214698,19.7244086 50.6605821,19.7244086 C43.4277098,19.7244086 37.8676084,22.0270085 33.976892,26.6254357 C30.0827894,31.2272492 28.1391242,37.474812 28.1391242,45.3679887 C28.1391242,53.5151287 30.133582,59.6982864 34.1258837,63.9242343 C38.1181855,68.1501822 43.7798721,70.25977 51.1109436,70.25977 C54.5242092,70.25977 57.595471,69.8703597 60.3382737,69.088153 C63.0743041,68.3093325 65.3430421,67.3882926 67.1546464,66.3385779 C68.857893,65.3328836 70.3681276,64.2763966 71.6751916,63.169117 C72.9754833,62.0584512 74.1843482,60.9816472 75.2882416,59.921774 L79.1281654,59.921774 L79.1281654,83.0764469 C74.8514249,85.0946079 70.395217,86.6861107 65.7392248,87.8712724 C61.0832325,89.0530479 55.943017,89.6422425 50.3084198,89.6422425 Z" id="Path"></path>
                                        <path d="M183.6557,45.066619 C183.6557,58.9973479 179.599061,69.9685588 171.47901,77.9836379 C163.355574,86.0122617 151.954318,90.0147219 137.268472,90.0147219 C122.589398,90.0147219 111.184757,86.0122617 103.064706,77.9836379 C94.9412697,69.9685588 90.8812444,58.9973479 90.8812444,45.066619 C90.8812444,31.034271 94.9649729,20.022426 103.135816,12.0310502 C111.303273,4.03628817 122.684211,0.0372015476 137.265086,0.0372015476 C152.045745,0.0372015476 163.47409,4.0599914 171.543348,12.1021599 C179.62615,20.1511007 183.6557,31.1358563 183.6557,45.066619 Z M150.244299,65.6545703 C152.008497,63.4908038 153.322334,60.8936066 154.206126,57.8426619 C155.089918,54.808648 155.53012,50.5928586 155.53012,45.2156108 C155.53012,40.2345456 155.072987,36.0627765 154.172264,32.6934974 C153.264769,29.324252 152.011883,26.6356281 150.396677,24.6242395 C148.788244,22.5620582 146.847965,21.1026162 144.589385,20.2492998 C142.327419,19.3959834 139.885986,18.965939 137.271858,18.965939 C134.65773,18.965939 132.331427,19.3181014 130.296335,20.022426 C128.257857,20.7267507 126.30742,22.1354 124.448409,24.2449877 C122.789183,26.2055838 121.468574,28.8975938 120.486583,32.321018 C119.511364,35.7377036 119.016982,40.0347612 119.016982,45.218997 C119.016982,49.8445137 119.440254,53.8503601 120.296957,57.250081 C121.150273,60.6430296 122.413317,63.3689014 124.069157,65.4310827 C125.677591,67.3882926 127.600939,68.8240313 129.839202,69.7315265 C132.074078,70.6390217 134.630641,71.085997 137.49196,71.085997 C139.957096,71.085997 142.307102,70.6694973 144.548751,69.8432703 C146.7904,69.0136571 148.683272,67.6185525 150.244299,65.6545703 Z" id="Shape"></path>
                                        <polygon id="Path" points="276.208022 87.3802768 196.636268 87.3802768 196.636268 69.9550141 241.228822 22.5113332 198.444486 22.5113332 198.444486 2.67510967 275.378409 2.67510967 275.378409 19.7990061 231.473249 67.1614188 276.208022 67.1614188"></polygon>
                                        <polygon id="Path" points="368.375336 87.3802768 288.803921 87.3802768 288.803921 69.9550141 333.396475 22.5113332 290.612139 22.5113332 290.612139 2.67510967 367.545723 2.67510967 367.545723 19.7990061 323.640901 67.1614188 368.375336 67.1614188"></polygon>
                                        <path d="M470.881662,50.0409119 L408.731784,50.0409119 C409.134739,56.6812034 411.660827,61.7604677 416.310047,65.2787048 C420.959267,68.7969419 427.812887,70.5577535 436.867522,70.5577535 C442.59016,70.5577535 448.153648,69.5317421 453.534282,67.4695608 C458.914916,65.4073795 463.161181,63.1962064 466.283235,60.8360416 L469.303704,60.8360416 L469.303704,82.6294716 C463.164567,85.0979941 457.380978,86.8757366 451.949551,87.9864024 C446.518125,89.0902958 440.514434,89.6456287 433.924935,89.6456287 C416.926331,89.6456287 403.906483,85.8226358 394.855234,78.1766499 C385.803985,70.5374364 381.276668,59.6441076 381.276668,45.5203667 C381.276668,31.5388113 385.566953,20.4694013 394.140751,12.2951719 C402.711163,4.12432875 414.467967,0.0372015476 429.401004,0.0372015476 C443.175969,0.0372015476 453.537668,3.51820321 460.472557,10.4835677 C467.414218,17.4489321 470.885049,27.4686275 470.885049,40.5426876 L470.885049,50.0409119 L470.881662,50.0409119 Z M443.876907,34.1258837 C443.727915,28.4438462 442.31588,24.173878 439.650959,21.309173 C436.989425,18.4376955 432.841359,17.005343 427.203376,17.005343 C421.978506,17.005343 417.678062,18.3631997 414.312203,21.0755268 C410.939571,23.7946263 409.053471,28.1424765 408.650516,34.1258837 L443.876907,34.1258837 Z" id="Shape"></path>
                                        <path d="M481.805467,9.62012658 C482.631694,7.71032321 483.755904,6.03755216 485.188257,4.61197198 C486.613837,3.17961945 488.27645,2.05540895 490.172708,1.23256814 C492.072353,0.40972732 494.107445,0 496.291529,0 C498.44175,0 500.473456,0.40972732 502.38326,1.22918196 C504.293063,2.05202278 505.965834,3.17623328 507.391414,4.60858581 C508.823767,6.03755216 509.947977,7.70693703 510.767432,9.61674041 C511.590273,11.5265438 512,13.5582495 512,15.7118576 C512,17.892555 511.590273,19.9310331 510.767432,21.8272918 C509.947977,23.7235505 508.823767,25.3895491 507.391414,26.8151293 C505.962448,28.250868 504.293063,29.3750785 502.38326,30.1945332 C500.476842,31.017374 498.44175,31.4271013 496.291529,31.4271013 C494.104059,31.4271013 492.072353,31.017374 490.169322,30.1945332 C488.273063,29.3750785 486.610451,28.250868 485.184871,26.8151293 C483.752518,25.3895491 482.628308,23.7235505 481.802081,21.8272918 C480.982626,19.9310331 480.572899,17.892555 480.572899,15.7118576 C480.576285,13.5616357 480.986012,11.52993 481.805467,9.62012658 Z M486.119455,20.3204434 C486.644313,21.7223203 487.379113,22.9311852 488.327242,23.9504243 C489.275372,24.9730495 490.423285,25.7654147 491.764211,26.3309061 C493.111909,26.8963976 494.618758,27.1808364 496.294915,27.1808364 C497.930438,27.1808364 499.437286,26.8963976 500.791757,26.3309061 C502.149613,25.7654147 503.3043,24.9730495 504.255815,23.9504243 C505.203944,22.9311852 505.938745,21.7223203 506.463602,20.3204434 C506.981687,18.9185664 507.242422,17.3880148 507.242422,15.7152437 C507.242422,14.0763345 506.981687,12.5457828 506.463602,11.1472921 C505.938745,9.7454151 505.203944,8.5365502 504.255815,7.52069734 C503.3043,6.49807213 502.149613,5.69893454 500.791757,5.11989841 C499.437286,4.53408993 497.930438,4.24626496 496.294915,4.24626496 C494.618758,4.24626496 493.115295,4.53408993 491.764211,5.11989841 C490.423285,5.69893454 489.278758,6.49807213 488.327242,7.52069734 C487.379113,8.5365502 486.644313,9.7454151 486.119455,11.1472921 C485.60137,12.5457828 485.333831,14.0729483 485.333831,15.7152437 L485.333831,15.7558779 L485.333831,15.7152437 C485.330476,17.3880148 485.597984,18.9185664 486.119455,20.3204434 Z M490.050806,7.13128708 L496.376183,7.13128708 C498.526405,7.13128708 500.209334,7.50376646 501.428358,8.23518052 C502.647381,8.97675311 503.253507,10.3346098 503.253507,12.3121367 C503.253507,13.9002533 502.84378,15.0346223 502.024325,15.7118576 C501.201484,16.392479 500.155156,16.7852754 498.881953,16.9004054 L502.918275,24.2958142 L498.922588,24.2958142 L495.184249,17.1171207 L493.778986,17.1171207 L493.778986,24.2958142 L490.044034,24.2958142 L490.044034,7.13128708 L490.050806,7.13128708 Z M493.782372,14.3133668 L496.836703,14.3133668 C497.632454,14.3133668 498.275828,14.1745336 498.77021,13.8867086 C499.267977,13.605656 499.511782,12.9690548 499.511782,11.9769052 C499.511782,11.4994544 499.413583,11.1235888 499.196868,10.8526947 C498.986925,10.5818006 498.709258,10.3786301 498.367255,10.2364107 C498.028637,10.0941913 497.666316,10.0095368 497.287064,9.98244744 C496.907813,9.95197185 496.542106,9.94181332 496.203488,9.94181332 L493.782372,9.94181332 L493.782372,14.3133668 Z" id="Shape"></path>
                                    </g>
                                </svg>
                            </a>
                        </div>
                    <?php } ?>
                </div>
                <?php
                $output .= ob_get_clean();

                $output .= '</li>';
            }
        }
    }

}