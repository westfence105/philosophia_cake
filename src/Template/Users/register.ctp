<h2><?= isset($title) ? h($title) : 'Register' ?></h2>
<?php
	echo $this->Form->create( $entities, ['type' => 'Post']), "\n";
	echo $this->Form->input( 'email', 	 ['type' => 'text', 'label' => __('Email'), 'required' => true ] ), "\n";
	echo $this->Form->input( 'username', ['type' => 'text', 'label' => __('Username'), 'required' => true ] ), "\n";
	echo $this->Form->input( 'password', ['type' => 'password', 'label' => __('Password'), 'required' => true ] ), "\n";
	echo $this->Form->input( 'password_confirm', ['type' => 'password', 'label' => __('Password Confirm'), 'required' => true ] ), "\n";
	echo $this->Form->input( 'language', ['type' => 'text',	 	'label' => __('Language (ISO 639 Code)'), 'value' => $language ] ), "\n";
	echo $this->Form->submit(__x('text of register form submit button','Register') ), "\n";
	echo $this->Form->end();
?>