<h2><?= __x('header of login page','Login') ?></h2>
<?php
	echo $this->Form->create('Post'), "\n";
	echo $this->Form->input('username',[ 'label' => __('Username'), 'style' => 'width: 24em;' ]), "\n";
	echo $this->Form->input('password',[ 'label' => __('Password'), 'style' => 'width: 24em;' ]), "\n";
	echo $this->Form->submit(__x('submit button of login','Login')), "\n";
?>