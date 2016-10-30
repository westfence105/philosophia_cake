<div class="name_preset">
	<div class="preset_name" data-preset-name="<?= @$preset ?>">
		<span name="preset_name">
			<?= (!empty($preset)) ? \Locale::getDisplayName( $preset, $preset ) : 'default', "\n" ?>:
		</span>
		<span class="description"><?= __x('description of name preset','Name Preset (Language)') ?></span>
	</div>
	<div class="names">
		<?php foreach ( $names as $i => $name ): ?> <?= "\n" ?>
			<div class="name" 
				 data-name="<?= $name['name'] ?>" 
				 data-name-type="<?= $name['type'] ?>"
				 data-name-display="<?= $name['display'] ?>"
				 data-name-short="<?= $name['short'] ?>" >
			<?php
				if( $name['display'] == 'display' ){
					echo $name['name'], "\n";
				}
				else if( $name['display'] == 'short' ){
					echo $name['short'], '<span class="full">(', $name['name'], ")</span>\n";
				}
				else if( $name['display'] == 'omit' ){
					echo '<span class="omit">"', $name['name'], '"', "</span>\n";
				}
				else {
					echo '<span class="private">[', $name['name'], "]</span>\n";
				}
			?>
				<span class="description">
					<?php 
						echo @\App\Model\Table\NamesTable::types()[ $name['type'] ],
							 ' (',
							 @\App\Model\Table\NamesTable::display()[ $name['display'] ],
							 ')'
							;
					?>
				</span>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="edit_preset">
		<input type="button" class="edit_name" value="<?= __x('edit name preset','Edit') ?>" />
	</div>
</div>