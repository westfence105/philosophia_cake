<h2><?= __('Register') ?></h2>
<?php
	echo $this->Form->create( $entities, [ 'type' => 'Post' ]), "\n";
	echo $this->Form->input( 'username', [ 'type' => 'text',     'label' => __('Username'), 'required' => true, 'style' => 'width: 24em;' ] ), "\n";
	echo $this->Form->input( 'password', [ 'type' => 'password', 'label' => __('Password'), 'required' => true, 'style' => 'width: 24em;' ] ), "\n";
	echo $this->Form->submit(__x('text of register form submit button','Register') ), "\n";
?>