<?php
$image = get_field('image');
?>
<div id="popup-<?= get_the_ID(); ?>" class="pb-popup <?= ($image) ? '' : 'no-image' ?> <?= (get_field('skjul_pa_mobil')) ? 'hide-mobile' : '' ?> <?= (get_field('open_manually')) ? 'open-manually' : 'open-automatically' ?>" style="display:none" data-id="<?= get_the_ID(); ?>">
	<div class="inner">
		<div class="popup-close"></div>
		<?php if($image){ ?>
			<div class="image">
				<img src="<?= $image['sizes']['large'] ?>" alt="<?= $image['alt'] ?>">
			</div>
		<?php } ?>
		<div class="content">
			<?php the_content(); ?>
			<div class="buttons">
				<?php if($knap = get_field('knap_1')){ ?>
					<a class="btn <?= get_field('knap_1_color') ?>" href="<?= $knap['url'] ?>" target="<?= $knap['target'] ?>"><?= $knap['title'] ?></a>
				<?php } ?>
				<?php if($knap = get_field('knap_2')){ ?>
					<a class="btn <?= get_field('knap_2_color') ?>" href="<?= $knap['url'] ?>" target="<?= $knap['target'] ?>"><?= $knap['title'] ?></a>
				<?php } ?>
			</div>
		</div>
	</div>
</div>