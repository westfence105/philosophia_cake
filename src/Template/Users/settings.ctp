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
			echo '<div class="name_preset">', "\n";
			echo '<div class="preset_name" data-preset-name="', $preset,'">', "\n";
			echo '<span class="preset_name">',
					(!empty($preset)) ? \Locale::getDisplayName( $preset, $preset ) : 'default',
				 '</span>', "\n";
			echo '<span class="description">', __x('description of name preset','Name Preset'), '</span>';
			echo '</div>', "\n"; //class="preset_name"

			echo '<div class="names">',"\n";
			foreach ( $names as $i => $name ) {
				if( array_key_exists( 'errors', $name ) ){
					$attrs['data-name-errors'] = $name['errors'];
				}
				echo @\App\Utils\AppUtility::genHtmlTag('div', 
						[
							'attrs' => [
								'class' => 'name',
								'data-name' => $name['name'],
								'data-name-type' => $name['type'],
								'data-name-display' => $name['display'],
								'data-name-short' => $name['short'],
							],
						]), "\n";
				echo @h( $name['display'] == 'short' ? $name['short'] : $name['name'] );
				echo '<span class="description">', @$resources['data-name-types'][$name['type']], '</span>', "\n";
				echo '</div>', "\n";
				unset($attrs);
				unset($name);
			}
			echo '</div>', "\n"; //class="names"
			echo '<div class="edit_preset">',
					'<input type="button" class="edit_name" value="', __x('edit name preset','Edit'), '" />',
				'</div>';
			echo '</div>', "\n"; //class="name_preset"
		}
	}
	echo '</ul>', "\n"; //id="name_presets"
	echo '</div>', "\n"; //id="setting_name"

	echo $this->Form->end(), "\n";
	echo '</div>', "\n"; //id="user_settings"
?>