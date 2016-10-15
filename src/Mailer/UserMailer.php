<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;
use Cake\Routing\Router;

class UserMailer extends Mailer 
{
	public function verify_email( $user ){
		$url = Router::url([ 'controller' => 'Users', 'action' => 'register'], true );
		$this->profile('default')
			 ->transport('default')
			 ->to($user->email)
			 ->subject(__('Verify email address'))
			 ->viewVars([ 'link' => $url.'?token='.$user->token ])
			;
	}
}

?>