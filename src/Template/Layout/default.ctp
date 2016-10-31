<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?= isset($title) ? h($title) : 'Φιλοσοφια' ?> - philosophia Speech Networking Service </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>
    <?= $this->Html->css('my.css') ?>

    <?= $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js') ?>
    <?= $this->Html->script('https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js') ?>
    <?= $this->Html->script('my.js') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <?= @\Cake\Core\Configure::read('debug') ? '<input type="hidden" id="debug" />' : '' ?>
    <nav class="top-bar expanded" data-topbar role="navigation">
        <ul class="title-area large-3 medium-4 columns">
            <li class="name">
                <h1><?= $this->Html->link( 'Φιλοσοφια', '/' ) ?></a></h1>
            </li>
        </ul>
        <div class="top-bar-section">
            <ul class="right">
                <li class="username menu_parent">
                <?php if( isset($username) ): ?>
                    <div><?= h($username) ?></div>
                    <ul id="user_menu" class="menu_child">
                        <li class="menu_item">
                            <?= $this->Html->link( __('Profile'), 
                                    ['controller' => 'Users', 'action' => 'profile', 'username' => $username ]
                                ); ?>
                        </li>
                        <li class="menu_item">
                            <?= $this->Html->link( __('Settings'), 
                                    ['controller' => 'Users', 'action' => 'settings'] 
                                ); ?>        
                        </li>
                    </ul>
                <?php else: ?>
                    <?= $this->Html->link( __('Login'), ['controller' => 'Users', 'action' => 'login'] ) ?>
                <?php endif; ?>
                </li>
            </ul>
        </div>
    </nav>
    <?= $this->Flash->render() ?>
    <div class="container clearfix">
        <?= $this->fetch('content') ?>
    </div>
    <footer>
    </footer>
</body>
</html>
