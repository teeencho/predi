<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use App\Model\Entity\Timbre;
use App\Model\Entity\Edificio;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class VisitasController extends AppController
{
    public function isAuthorized($user){
        return parent::isAuthorized($user);
    }
    
    public $paginate = [
        'conditions' => [
            'deleted' => 0
        ],
        'limit' => 10,
        'order' => [
            'Visitas.fecha' => 'DESC'
        ],
        'contain' => ['Timbres', 'Timbres.Edificios', 'Users']
    ];

    public function initialize(){
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Paginator');
    }

    public function index()
    {
        $this->set('visitas', $this->paginate());
        $this->set('alphabet', range('A', 'Z'));
    }

    public function eliminar($id = null){
        $this->autoRender = false;

        $visita = $this->Visitas->get($id);
        if($this->Visitas->delete($visita)){
            $this->Flash->success(__('La visita ha sido eliminada.'));
            return $this->redirect($this->referer());
        }
        $this->Flash->error(__('Error al eliminar la visita.'));
        return $this->redirect($this->referer());
    }
}
