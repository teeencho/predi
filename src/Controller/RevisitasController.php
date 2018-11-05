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
use App\Model\Entity\Revisita;
use App\Model\Entity\Marker;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class RevisitasController extends AppController
{
    public function isAuthorized($user){
        if(isset($user['role']) && $user['role'] === 'revisitas'){
            return true;
        }

        return parent::isAuthorized($user);
    }

    public function index()
    {
        $this->paginate =
            [
                'contain' => 'Markers',
                'limit' => 10,
                'order' => [
                    'Revisitas.fecha' => 'ASC'
                ]
            ];
        $q = $this->request->query('q');
        $query = $this->Revisitas->find('all')->contain('Markers');
        $this->set('revisitas_count', $query->count());

        if($q){
            $query = $this->Revisitas->find('all')->contain('Markers');
            $query = $this->_busqueda($q, $query);
            $this->set('revisitas', $this->paginate($query));
            return;
        }

        $this->set('revisitas', $this->paginate());
    }

    public function editar($id)
    {
        $revisita = $this->Revisitas->get($id);
        if ($this->request->is(['post', 'put'])) {
            $this->Revisitas->patchEntity($revisita, $this->request->data);
            if ($this->Revisitas->save($revisita)) {
                $this->Flash->success(
                  __('El registro ha sido guardado.'));
            }else{
                $this->Flash->error(__('Error al guardar el registro.'));
            }
        }

        $this->set('revisita', $revisita);
        $this->render('editar');
    }

    public function agregar(){
        $revisita = new Revisita();
        if ($this->request->is('post')) {
            $revTable = TableRegistry::get('Revisitas');
            $data = $this->request->data;
            $revisita = new Revisita($data);

            if ($revTable->save($revisita)) {
                $this->Flash->success(__('El registro ha sido guardado.'));
                return $this->redirect(['action' => 'editar', $revisita['id']]);
            }
            $this->Flash->error(__('No ha sido posible guardar el registro. Por intente nuevamente o contacte al administrador.'));
        }

        $this->set('revisita', $revisita);
    }

    public function buscar(){
        if($this->request->is('ajax')){
            $this->autoRender = false;
            if(!$this->request->data['q']){return;}
            $q = $this->request->data['q'];

            $query = $this->Revisitas->find('all');
            $query = $this->_busqueda($q, $query);
            $query->limit(5);

            $this->response->type('json');
            $json = json_encode($query->toArray());
            $this->response->body($json);
        }
    }

    private function _busqueda($q, $query){
        $query->where(['Revisitas.nombre LIKE' => '%'. $q .'%']);
        return $query;
    }

    public function marcador(){
      $this->autoRender = false;
      if($this->request->is('get')){
        $markTable = TableRegistry::get('Markers');
        $data = $this->request->query('revisita');
        $existing = $markTable->find('all')
            ->where(['revisita' => $data]);
        if($existing->first()){
          $marker = $markTable->get($existing->first()->id);
          $this->response->type('json');
          $this->response->body(json_encode($marker));
        }
      }
      if ($this->request->is('post')) {
        $markTable = TableRegistry::get('Markers');
        $data = $this->request->data;
        $existing = $markTable->find('all')
            ->where(['revisita' => $data['revisita']]);

        if($existing->first()){
          $marker = $markTable->get($existing->first()->id);
          $marker->lat = floatval($data['lat']);
          $marker->lng = floatval($data['lng']);
          if($markTable->save($marker)){
            $this->response->body('Ubicacion guardada con éxito.');
          }else{
            $this->response->body('Ha ocurrido un error al guardar la ubicación.');
          }
        }else{
          $marker = new Marker([
            'lat' => floatval($data['lat']),
            'lng' => floatval($data['lng']),
            'revisita' => intval($data['revisita'])
          ]);

          if ($markTable->save($marker)) {
            $this->response->body('Ubicacion guardada con éxito.');
          }else{
            $this->Flash->error(__('No ha sido posible guardar el registro. Por intente nuevamente o contacte al administrador.'));
          }
        }
      }
    }

    public function eliminar($id = null){
        $this->autoRender = false;

        $revisita = $this->Revisitas->get($id);
        if($this->Revisitas->delete($revisita)){
            $this->Flash->success(__('El registro ha sido eliminado.'));
            return $this->redirect($this->referer());
        }
        $this->Flash->error(__('Error al eliminar el registro.'));
        return $this->redirect($this->referer());
    }
}
