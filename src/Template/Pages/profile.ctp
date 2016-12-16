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
	<?php if( array_key_exists('names', $profile ) && !empty($profile['names']) ): ?>
		<?php foreach( array_values($profile['names'])[0] as $i => $name ): ?>
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
	<?php if( array_key_exists('names', $profile ) ): ?>
		<div id="profile_names" class="profile_component">
			<div class="component_name"><h4><?= __x('title of names','Name') ?></h4></div>
			<div class="profile_content">
				<div id="profile_name">
				<?php foreach( $profile['names'] as $preset => $names ): ?>
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
	<?php if( array_key_exists('languages', $profile ) && !empty($profile['languages']) ): ?>
		<div id="profile_language" class="profile_component">
			<div class="component_name"><h4><?= __('Language') ?></h4></div>
			<div class="profile_content">
				<?php foreach( $profile['languages'] as $i => $language ): ?>
					<span class="language" <?= $i == 0 ? 'id="native_language"' : '' ?>>
						<?= \Locale::getDisplayLanguage( $language ) ?>	
					</span>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
</div>