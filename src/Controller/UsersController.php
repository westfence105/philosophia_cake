<?php
namespace App\Controller;

use App\Controller\AppController;

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\I18n;
use Cake\Error\Debugger;

use App\Model\Table\NamesTable;

use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Datasource\Exception\RecordNotFoundException;

class UsersController extends ApiController
{

    public function initialize(){
        parent::initialize();

        $this->Auth->allow(['view','register']);
    }

    public function view( $id ){
        try {
            $user = $this->Users->get($id);
            $ret['username'] = $user->username;
            return $this->serialize($ret);

        }
        catch( RecordNotFoundException $e ){
            throw new NotFoundException();
        }
    }

    public function edit( $id ){

    }
}

?>