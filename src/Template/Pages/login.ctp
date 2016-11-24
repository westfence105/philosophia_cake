<h2><?= isset($title) ? h($title) : 'Login' ?></h2>
<?php
	echo $this->Form->create('Post'), "\n";
	echo $this->Form->input( 'username', ['type' => 'text', 'label' => __('Username'), 'maxlength' => 16 ] ), "\n";
	echo $this->Form->input( 'password', ['type' => 'password', 'label' => __('Password') ] ), "\n";
	echo $this->Form->submit(__('Login') ), "\n";
	echo $this->Form->end(), "\n";
	echo $this->Html->link(__('Register'), '/register' )."\n";
?>