<?php
	if( ! isset($preset) ){
		$preset = '';
	}
	if( ! isset($names) ){
		$names = [
			[ 'name' => '', 'type' => 'given', 'display' => 'display', 'short' => '' ]
		];
	}
	$types = @\App\Model\Table\NamesTable::types();
	$display = @\App\Model\Table\NamesTable::display();
	$display_descs = @\App\Model\Table\NamesTable::displayDescriptions();

	const name_required = ['name','type','display','short'];
	function checkName( $name ){
		foreach ( name_required as $i => $key ) {
			if( ! array_key_exists( $key, $name ) ){
				$name[$key] = '';
			}
		}
	}
?>
<div class="name_preset" data-preset-name="<?= @$preset ?>">
	<div class="preset_name">
		<span name="preset_name">
			<?= (!empty($preset)) ? h(\Locale::getDisplayName( $preset, $preset )) : 'default', "\n" ?>:
		</span>
		<span class="description"><?= __x('description of name preset','Name Preset (Language)') ?></span>
	</div>
	<div class="names">
		<?php foreach ( $names as $i => $name ): ?> <?= "\n" ?>
			<?php checkName($name); ?>
			<div class="name" 
				 data-name="<?= $name['name'] ?>" 
				 data-name-type="<?= $name['type'] ?>"
				 data-name-display="<?= $name['display'] ?>"
				 data-name-short="<?= $name['short'] ?>" >
			<?php
				if( $name['display'] == 'display' ){
					echo h($name['name']), "\n";
				}
				else if( $name['display'] == 'short' ){
					echo h($name['short']);
					if( ! empty($name['name'] ) ){
						echo '<span class="full">(', h($name['name']), ")</span>\n";
					}
				}
				else if( $name['display'] == 'omit' ){
					echo '<span class="omit">(', h($name['name']), ')', "</span>\n";
				}
				else {
					echo '<span class="private">[', h($name['name']), "]</span>\n";
				}
			?>
				<span class="description">
					<?php 
						echo h( $types[ $name['type'] ]. ' ('. $display[ $name['display'] ]. ')' );
					?>
				</span>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="name_editor" style="display: none;">
		<div class="name_input_preset_name">
			<label><?= __('Language (ISO 639 Code)') ?></label>
			<input class="preset_name" type="text" value="<?= @$preset ?>" maxlength="6" />
			<span class="preset_name_preview"></span>
		</div>
		<div class="name_preview"></div>
		<div class="name_inputs"></div>
		<div class="name_input_add_name">
			<input type="button" class="add_name" value="<?= __('Add Name Element') ?>" />
		</div>
		<div class="name_input_buttons">
			<input type="button" class="save"   value="<?= __x('save user setting(s)','Save') ?>" />
			<input type="button" class="cancel" value="<?= __x('cancel editing user setting(s)','Cancel') ?>" />
		</div>
	</div>
	<div class="edit_preset">
		<input type="button" class="edit_name" value="<?= __x('edit name preset','Edit') ?>" />
	</div>
	<div class="remove_preset">
		<input type="button" class="remove_preset" value="<?= __x('remove name preset','Remove Preset') ?>" />
	</div>
	<div class="pad"></div>
</div>