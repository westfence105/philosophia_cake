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
<h2 id="user_names">
	<?php if( array_key_exists('names', $profile ) ): ?>
		<?php foreach( $profile['names'] as $i => $name ): ?>
			<span class="user_name"><?= @$name['name'] ?></span>
		<?php endforeach; ?>
		<?php if( array_key_exists('username', $profile ) ): ?>
			<span class="user_id">(<?= $profile['username'] ?>)</span>
		<?php endif; ?>
	<?php else: ?>
		<span class="user_name">
			<?= array_key_exists('username', $profile ) ? $profile['username'] : ' ' ?>
		</span>
	<?php endif; ?>
</h2>
<div id="user_profile">
	<?php if( array_key_exists('all_names', $profile ) ): ?>
		<div id="profile_names" class="profile_component">
			<h4 class="component_name"><?= __x('title of names','Name') ?>:</h4>
			<div class="profile_content">
				<div id="profile_name">
				<?php foreach( $profile['all_names'] as $preset => $names ): ?>
					<div class="name_preset">
						<span class="preset_name">
							<?= empty( $preset ) ? __('default') : \Locale::getDisplayLanguage( $preset ) ?>:
						</span>
						<div class="names">
						<?php foreach( $names as $i => $name ): ?>
							<div class="name">
								<?= $name['name'] ?>
								<span class="description"><?= $name['type'] ?></span>
							</div>
						<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>