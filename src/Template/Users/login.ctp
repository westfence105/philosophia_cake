<h2><?= isset($title) ? h($title) : 'Login' ?></h2>
<?php
	echo $this->Form->create('Post'), "\n";
	echo $this->Form->input( 'username', ['type' => 'text', 'label' => __('Username') ] ), "\n";
	echo $this->Form->input( 'password', ['type' => 'password', 'label' => __('Password') ] ), "\n";
	echo $this->Form->submit(__('Login') ), "\n";
	echo $this->Form->end(), "\n";
	echo $this->Html->link(__('Register'), ['controller' => 'Users', 'action' => 'register'])."\n";
?>