<h2><?= __('Login') ?></h2>
<?php
	echo $this->Form->create('Post'), "\n";
	echo $this->Form->input('username',[ 'label' => __('Username'), 'style' => 'width: 24em;' ]), "\n";
	echo $this->Form->input('password',[ 'label' => __('Password'), 'style' => 'width: 24em;' ]), "\n";
	echo $this->Form->submit(__('Login')), "\n";
?>