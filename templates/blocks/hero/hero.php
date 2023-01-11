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
    <section class="section section-hero <?= $section_class ?> <?= !empty($block['className']) ? $block['className'] : '' ?>" <?= $section_attributes ?> <?= $section_style ?>>
        <div class="background-container">
            <?php if($fields['video']){ ?>
                <video playsinline autoplay muted loop><source src="<?= $fields['video']['url'] ?>" type="<?= $fields['video']['mime_type'] ?>"></video>
            <?php }else if($fields['image']){ ?>
                <?= wp_get_attachment_image($fields['image'], 'hero_max'); ?>
            <?php } ?>
        </div>
        <div class="content">
            <?php if($fields['subheading']){ ?>
                <div class="h5"><?= $fields['subheading'] ?></div>
            <?php } ?>
            <div class="text">
                <?= $fields['text'] ?>
            </div>
            <?php if(!empty($fields['knapper'])){ ?>
                <div class="buttons">
                    <?= pb_get_buttons_from_array($fields['knapper'], 'btn-primary'); ?>
                </div>
            <?php } ?>
        </div>
    </section>
    <?php
}
?>