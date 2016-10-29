<div class="name_preset">
	<div class="preset_name" data-preset-name="<?= @$preset ?>">
		<span class="preset_name">
			<?= (!empty($preset)) ? \Locale::getDisplayName( $preset, $preset ) : 'default', "\n" ?>
		</span>
		<span class="description"><?= __x('description of name preset','Name Preset (Language)') ?></span>
	</div>
	<div class="names">
		<?php foreach ( $names as $i => $name ): ?> <?= "\n" ?>
			<div class="name" 
				 data-name="<?= $name['name'] ?>" 
				 data-name-type="<?= $name['type'] ?>"
				 data-name-display="<?= $name['display'] ?>"
				 data-name-short="<?= $name['short'] ?>" >
				<?= $name['display'] == 'short' ? $name['short'] : $name['name'] ?> <?= "\n"?>
				<span class="description"><?= @$resources['data-name-types'][$name['type']] ?></span>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="edit_preset">
		<input type="button" class="edit_name" value="<?= __x('edit name preset','Edit') ?>" />
	</div>
</div>