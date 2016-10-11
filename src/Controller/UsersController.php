<?php
namespace App\Controller;

use App\Controller\AppController;

class UsersController extends AppController
{
    public function initialize(){
        parent::initialize();

        $this->Auth->allow([ 'register' ]);
    }

    public function index(){
        
    }

    public function home(){

    }

    public function login()
    {

    }
}

?>