<?php
if(!pb_is_preview($block)){
    $fields = get_fields();
    $section_class = pb_generate_section_classes($fields);
    $section_class .= ' '.pb_generate_color_classes($block);
    $section_style = pb_generate_section_style($block);
    $section_attributes = pb_generate_section_attributes($block);
    $column_class = pb_generate_column_classes($fields);
    $delay = 0;
    $width = $fields['width'] ?: 'col-lg-8';
    ?>
    <section class="section section-text <?= $section_class ?> <?= !empty($block['className']) ? $block['className'] : '' ?>" <?= $section_attributes ?> <?= $section_style ?>>
        <div class="container">
            <div class="row justify-content-center">
                <div class="<?= $width ?>">
                    <?php if($fields['image']){ ?>
                        <div class="image">
                            <?= wp_get_attachment_image($fields['image'], 'large'); ?>
                        </div>
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
            </div>
        </div>
    </section>
    <?php
}
?>