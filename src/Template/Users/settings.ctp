<h2><?= isset($title) ? $title : 'Settings' ?></h2>
<?php
	$this->Html->css('user_settings', ['block' => true ]);
	$this->Html->script('user_settings', ['block' => true ]);

	echo \App\Utils\AppUtility::genHtmlTag('div', [
			'close' => true,
			'attrs' => ['id' => 'resources', 'style' => 'display:none'] + $resources,
		]);
	echo "\n";

	echo '<div id="user_settings">', "\n";
	echo $this->Form->create('Post'), "\n";

	echo '<div id="setting_name" class="setting_group">', "\n";
	echo '<div class="group_title"><h3>', __('Name'), '</h3></div>', "\n";
	echo '<div id="name_presets">', "\n";
	if( isset($data) && array_key_exists('names', $data ) ){
		foreach ( $data['names'] as $preset => $names ) {
			echo $this->element('Users/settings/name_preset', 
					[ 'resources' => $resources, 'preset' => $preset, 'names' => $names ]);
		}
	}
	echo '</ul>', "\n"; //id="name_presets"
	echo '</div>', "\n"; //id="setting_name"

	echo $this->Form->end(), "\n";
	echo '</div>', "\n"; //id="user_settings"
?>