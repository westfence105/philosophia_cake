<h2><?= isset($title) ? $title : 'Settings' ?></h2>

<?php $this->Html->scriptStart(['block' => true ]); ?>
	messages = {
        'save':  '<?= __x('save user setting(s)','Save') ?>',
        'short': '<?= __x('description of name.short','Short') ?>',
        'internal error occurred': '<?= __('Internal error occurred.') ?>',
	};

	function message( str ){
<?php if( @\Cake\Core\Configure::read('debug') ): ?>
		if( !( str in messages ) ){
			debug(str);
		}
<?php endif; ?>
		return ( str in messages ) ? messages[str] : str;
	}

	name_types = {
<?php foreach( \App\Model\Table\NamesTable::types() as $key => $value ): ?>
		'<?= $key ?>' :	'<?= $value ?>',
<?php endforeach; ?>
	};

	name_display = {
<?php foreach( \App\Model\Table\NamesTable::display() as $key => $value ): ?>
		'<?= $key ?>' :	'<?= $value ?>',
<?php endforeach; ?>
	};

	display_descs = {
<?php foreach( \App\Model\Table\NamesTable::displayDescriptions() as $key => $value ): ?>
		'<?= $key ?>' :	'<?= $value ?>',
<?php endforeach; ?>
	};
<?php $this->Html->scriptEnd(); ?>

<?php
	$this->Html->css('user_settings', ['block' => true ]);
	$this->Html->script('user_settings', ['block' => true ]);
?>

<?= $this->Form->create('Post') ?>
<div id="user_settings">
	<div id="setting_name" class="setting_group">
		<div class="group_title"><h3><?= __('Name') ?></h3></div>
			<div id="name_presets">
		<?php if( isset($data) && array_key_exists('names', $data ) ): ?>
			<?php foreach ( $data['names'] as $preset => $names ): ?>
				<?= $this->element('Users/settings/name_preset', ['preset' => $preset, 'names' => $names ]); ?>
			<?php endforeach; ?>
		<?php endif; ?>
		</div><!-- id="name_presets" -->
		<div class="add_preset">
			<input type="button" id="add_preset" value="<?= __x('add name preset','Add Preset') ?>" />
		</div>
	</div><!-- id="setting_name" -->
</div><!-- id="user_settings" -->
<?= $this->Form->end() ?>

<div id="templates" style="display: none;">
	<div class="name_input">
		<div class="cell input_name"><input type="text" name="name" value=""/></div>
		<div class="cell input_name_type">
			<select name="type">
		<?php foreach ( @\App\Model\Table\NamesTable::types() as $key => $value): ?>
				<option value="<?= $key ?>"><?= h($value) ?></option>
		<?php endforeach; ?>
			</select>
		</div>
		<div class="cell input_name_display">
			<select name="display">
		<?php foreach ( @\App\Model\Table\NamesTable::display() as $key => $value): ?>
				<option value="<?= $key ?>"><?= h($value) ?></option>
		<?php endforeach; ?>
			</select>
		</div>
		<div class="cell label_name_short"><label><?= h(__x('description of name.short','Short')) ?></label></div>
		<div class="cell input_name_short">
			<input type="text" name="short" value="" maxlength="16" />
		</div>
		<div class="cell button_remove_name" >
			<input type="button" class="remove_name" value="<?= __x('remove name element','Remove') ?>" />
		</div>
	</div>
</div>