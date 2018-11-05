<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Territorios Controller
 *
 * @property \App\Model\Table\TerritoriosTable $Territorios
 *
 * @method \App\Model\Entity\Territorio[] paginate($object = null, array $settings = [])
 */
class TerritoriosController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $territorios = $this->paginate($this->Territorios);

        $this->set(compact('territorios'));
        $this->set('_serialize', ['territorios']);
    }

    /**
     * View method
     *
     * @param string|null $id Territorio id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $territorio = $this->Territorios->get($id, [
            'contain' => []
        ]);

        $this->set('territorio', $territorio);
        $this->set('_serialize', ['territorio']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function agregar()
    {
        $territorio = $this->Territorios->newEntity();
        if ($this->request->is('post')) {
          if(!empty($this->data['territorios']['upload']['name']))
          {
              $file = $this->data['territorios']['upload']; //put the data into a var for easy use

              $ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
              $arr_ext = array('jpg', 'jpeg', 'png'); //set allowed extensions

              //only process if the extension is valid
              if(in_array($ext, $arr_ext))
              {
                  //do the actual uploading of the file. First arg is the tmp name, second arg is
                  //where we are putting it
                  move_uploaded_file($file['tmp_name'], IMG_ROOT . $file['name']);

                  //prepare the filename for database entry
                  // TODO $this->data['territorios']['product_image'] = $file['name'];
              }
          }
            $territorio = $this->Territorios->patchEntity($territorio, $this->request->getData());
            if ($this->Territorios->save($territorio)) {
                $this->Flash->success(__('The territorio has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The territorio could not be saved. Please, try again.'));
        }
        $this->set(compact('territorio'));
        $this->set('_serialize', ['territorio']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Territorio id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $territorio = $this->Territorios->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $territorio = $this->Territorios->patchEntity($territorio, $this->request->getData());
            if ($this->Territorios->save($territorio)) {
                $this->Flash->success(__('The territorio has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The territorio could not be saved. Please, try again.'));
        }
        $this->set(compact('territorio'));
        $this->set('_serialize', ['territorio']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Territorio id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $territorio = $this->Territorios->get($id);
        if ($this->Territorios->delete($territorio)) {
            $this->Flash->success(__('The territorio has been deleted.'));
        } else {
            $this->Flash->error(__('The territorio could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
