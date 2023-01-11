<?php
if(!pb_is_preview($block)){
    $fields = get_fields();
    $section_class = pb_generate_section_classes($fields);
    $section_class .= ' '.pb_generate_color_classes($block);
    $section_style = pb_generate_section_style($block);
    $section_attributes = pb_generate_section_attributes($block);
    $column_class = pb_generate_column_classes($fields);
    $delay = 0;
    ?>
    <section class="section section-default <?= $section_class ?> <?= !empty($block['className']) ? $block['className'] : '' ?>" <?= $section_attributes ?> <?= $section_style ?>>

    </section>
    <?php
}
?>