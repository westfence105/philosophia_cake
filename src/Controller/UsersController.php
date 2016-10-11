<?php
namespace App\Controller;

use App\Controller\AppController;

class UsersController extends AppController
{
    public function initialize(){
        parent::initialize();

        $this->loadComponent('Auth', 
                [
                    'authenticate' => 'Form' ,
                    'loginAction' => [ 'controller' => 'Pages', 'action' => 'introduction' ]
                ] );
        $this->Auth->allow([ 'register' ]);
    }

    public function home(){

    }

    public function login()
    {

    }
}

?>