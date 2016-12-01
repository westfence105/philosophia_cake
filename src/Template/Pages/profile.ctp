<h2><?= h($profile['username']) ?></h2>
<?php
	//load CSS
	echo $this->Html->css('user_profile',['block' => true ]);

	//load javascript
	echo $this->Html->script('config_name',['block' => true ]);

	echo '<div class="user_profile">',"\n";

	echo '<div id="profile_names" data-element-name="',__('Name'),'">',"\n";
	echo '</div>', "\n";

	echo '</div>',"\n"; //class="user_config"
?>