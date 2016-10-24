<h2><?= isset($title) ? $title : 'Config' ?></h2>
<?php
	//load CSS
	echo $this->Html->css('user_config',['block' => true ]);

	//set data for javascript
	@$data = [
			'data-name-types' 	=> json_encode( $types ), 
			'data-name-display' => json_encode( $display ),
			'data-name-display-description' => json_encode( $display_description ),
			'data-name-short' 	=> json_encode( __x('description of name.short','Short') ),
		];
	if( ! empty($names) ){
		$data += [ 'data-names' => json_encode($names) ];
	}
	echo $this->Html->script('user_config', $data + ['block' => true]), "\n";

	//div user_config -start-
	echo '<div class="user_config">'."\n";
	
	//names form
	echo '<div class="config_name">'."\n";
	echo $this->Form->create('Post'), "\n";
	echo '<div id="name_inputs"></div>', "\n";
	echo '<div class="add_name">', "\n",
		 '<a href="javascript:void(0);" onClick="addName();">'.__('Add Name-Element').'</a>',
		 '</div>', "\n";
	echo $this->Form->submit(__('Save'));
	echo $this->Form->end(), "\n";
	echo '</div>'."\n";

	echo '</div>'."\n";
	//div user_config -end-

	if( isset($onload) ){
		echo $this->Html->scriptBlock(
				'window.onload = function(){'."\n".implode("\n",$onload)."\n".'};',
				['block' => true ]
			);
	}
?>