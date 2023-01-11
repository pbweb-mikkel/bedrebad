<?php

if(!pb_is_preview($block)){
    $fields = get_fields();
    $section_class = pb_generate_section_classes($fields);
    $section_class .= ' '.pb_generate_color_classes($block);
    $section_style = pb_generate_section_style($block);
    $section_attributes = pb_generate_section_attributes($block);
    ?>
    <div class="megamenu-card <?= $section_class ?> <?= !empty($block['className']) ? $block['className'] : '' ?>" <?= $section_attributes ?> <?= $section_style ?>>
        <?php if($knap = $fields['knap']){ ?>
            <a href="<?= $knap['url'] ?>" target="<?= $knap['target'] ?>" title="<?= $knap['title'] ?>">
        <?php } ?>
        <?php if($fields['background_image']){ ?>
            <div class="background-container">
                <?= wp_get_attachment_image($fields['background_image'], 'large') ?>
            </div>
        <?php } ?>
        <div class="content">
            <div class="image">
                <?php if($fields['image']){ ?>
                        <?= wp_get_attachment_image($fields['image'], 'medium_large') ?>
                <?php } ?>
            </div>
            <div class="title">
                <?= $fields['title'] ?>
            </div>
            <?php if($fields['knap']){ ?>
                <div class="btn-primary"><?= $knap['title'] ?></div>
            <?php } ?>
        </div>
        <?php if($fields['knap']){ ?>
        </a>
        <?php } ?>
    </div>
<?php
}
?>