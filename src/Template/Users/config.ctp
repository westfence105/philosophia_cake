<h2><?= isset($title) ? $title : 'Config' ?></h2>
<?php
	@$data_name = [
			'data-name-types' 	=> json_encode( $types ), 
			'data-name-display' => json_encode( $display ),
			'data-name-display-description' => json_encode( $display_description ),
			'data-name-short' 	=> json_encode( __x('description of name.short','Short') ),
		];
	if( ! empty($names) ){
		$data_name += [ 'data-names' => json_encode($names) ];
	}
	echo $this->Html->script('user_config', $data_name + ['block' => true]), "\n";
/*
	echo $this->Html->scriptBlock('
			$( function() {
			  $( "#sortable" ).sortable();
			  $( "#sortable" ).disableSelection();
			} );', ['block' => true ]
		);
*/
	echo '<div class="user_config">'."\n";
	
	echo $this->Form->create( 'Post',['class' => 'config_name']), "\n";
	echo '<div></div>';
	echo '<table id="name_inputs"><tbody class="sortable"></tbody></table>', "\n";
	echo '<div class="add_name">', "\n",
		 '<a href="javascript:void(0);" onClick="addName();">'.__('Add Name-Element').'</a>',
		 '</div>', "\n";
	echo $this->Form->submit(__('Save'));
	echo $this->Form->end(), "\n";

	echo '</div>'."\n";

	if( isset($onload) ){
		echo $this->Html->scriptBlock(
				'window.onload = function(){'."\n".implode("\n",$onload)."\n".'};',
				['block' => true ]
			);
	}
?>