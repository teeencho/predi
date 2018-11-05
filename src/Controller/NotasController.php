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
use App\Model\Entity\Nota;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class NotasController extends AppController
{
    public $helpers = ['Markdown.Markdown'];
    public function initialize(){
      parent::initialize();
    }

    public function instrucciones(){
        $query = $this->Notas->find('all', ['nombre' => 'instrucciones']);
        $nota = $query->first();
        if(!$nota){
          $nota = new Nota();
        }

        if($this->request->is(['post', 'put'])){
          $edicion = $this->request->data['texto'];
          $nota['texto'] = $edicion;
          $nota['fecha_modificacion'] = date('Y-m-d', strtotime('today'));
          $this->Notas->save($nota);
        }

        if($nota){
          $this->set('nota', $nota);
        }else{
          $this->set('nota', null);
        }
    }
}
