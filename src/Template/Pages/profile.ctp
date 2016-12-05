<?php
	//load CSS
	echo $this->Html->css('user_profile',['block' => true ]);

	//load javascript
	echo $this->Html->script('config_name',['block' => true ]);

//	echo '<div class="user_profile">',"\n";
//
//	echo '<div id="profile_names" data-element-name="',__('Name'),'">',"\n";
//	echo '</div>', "\n";
//
//	echo '</div>',"\n"; //class="user_config"
?>
<div id="box_profile">
	<div id="name">
	<?php if( array_key_exists('names', $profile ) ): ?>
		<?php foreach( $profile['names'] as $i => $name ): ?>
		<div class="name">
			<?= @$name['name'] ?>
			<span class="description"><?= @$name['type'] ?></span>
		</div>
		<?php endforeach; ?>
	<?php else: ?>
		<h2><?= array_key_exists('username', $profile ) ? $profile['username'] : ' ' ?></h2>
	<?php endif; ?>
	</div>
</div>