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

<?= 'types = ', 		json_encode( \App\Model\Table\NamesTable::types(), JSON_PRETTY_PRINT ), ";\n" ?>
<?= 'display = ', 		json_encode( \App\Model\Table\NamesTable::display(), JSON_PRETTY_PRINT ), ";\n" ?>
<?= 'display_descs = ', json_encode( \App\Model\Table\NamesTable::displayDescriptions(), JSON_PRETTY_PRINT ), ";\n" ?>
<?php $this->Html->scriptEnd(); ?>

<?php
	$this->Html->css('user_settings', ['block' => true ]);
	$this->Html->script('user_settings', ['block' => true ]);
?>

<?= $this->Form->create('Post') ?>
<div id="user_settings">
	<div id="setting_name" class="setting_group">
		<div class="group_title"><h3><?= __('Name') ?></h3></div>
		<div id="name_presets"><!-- ajax --></div>
		<div class="add_preset">
			<input type="button" id="add_preset" value="<?= __x('add name preset','Add Preset') ?>" />
		</div>
	</div><!-- id="setting_name" -->
</div><!-- id="user_settings" -->
<?= $this->Form->end() ?>

<script id="template_name_preset" type="text/template">
	<div class="name_preset" data-preset-name="<%- preset %>">
	<div class="preset_name">
		<span name="preset_name">
			<%- preset %>:
		</span>
		<span class="description"><?= __('Language') ?></span>
	</div>
	<div class="names">
		<% _.each( names, function( name, i ){ %>
			<div class="name" 
				 data-name="<%- name['name'] %>" 
				 data-name-type="<%- name['type'] %>"
				 data-name-display="<%- name['display'] %>"
				 data-name-short="<%- name['short'] %>" >
				<%	if( name['display'] == 'display' ){ %>
						<%- name['name'] %>
				<%	} else if( name['display'] == 'short' ){ %>
						<%- name['short'] %><span class="full">(<%- name['name'] %>)</span>
				<%	} else if( name['display'] == 'omit' ){ %>
						<span class="omit">(<%- name['name'] %>)</span>
				<%	} else { %>
						<span class="private">[<%- name['name'] %>]</span>
				<%	} %>
				<span class="description">
					<%- types[name['type']] %> (<%- display[name['display']] %>)
				</span>
			</div>
		<% }); %>
	</div>

	<!-- editor -->
	<div class="name_editor_padding" style="display: none;"></div>
	<div class="name_editor" style="display: none;">
		<div class="name_input_preset_name">
			<label><?= __('Language (ISO 639 Code)') ?></label>
			<input class="preset_name" type="text" maxlength="6" />
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
</script>

<script id="template_name_input" type="text/tamplate">
	<div class="name_input">
		<div class="cell input_name"><input type="text" name="name" /></div>
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
			<span class="description"></span>
		</div>
		<div class="cell label_name_short"><label><?= h(__x('description of name.short','Short')) ?></label></div>
		<div class="cell input_name_short">
			<input type="text" name="short" value="" maxlength="16" />
		</div>
		<div class="cell button_remove_name" >
			<input type="button" class="remove_name" value="<?= __x('remove name element','Remove') ?>" />
		</div>
	</div>
</script>