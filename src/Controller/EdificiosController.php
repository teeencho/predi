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
use App\Model\Entity\Visita;
use App\Model\Entity\Llamada;
use Cake\Datasource\ConnectionManager;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class EdificiosController extends AppController
{
    public function isAuthorized($user){
        if(in_array($this->request->action, ['visitar', 'buscar', 'tocar_timbre', 'traer_timbre', 'traer_timbres', 'tocar_timbre'])){
            return true;
        }

        return parent::isAuthorized($user);
    }

    public function index()
    {
        $this->paginate =
            [
                'limit' => 10,
                'order' => [
                    'Edificios.territorio' => 'ASC'
                ]
            ];
        $q = $this->request->query('q');
        $query = $this->Edificios->find('all');
        $this->set('edificios_count', $query->count());

        if($q){
            $query = $this->Edificios->find('all');
            $query = $this->_busqueda($q, $query);
            $this->set('edificios', $this->paginate($query));
            return;
        }

        $this->set('edificios', $this->paginate());
    }

    public function editar($id)
    {
        $edificio = $this->Edificios->get($id, ['contain' => ['Timbres']]);
        if ($this->request->is(['post', 'put'])) {
            $this->Edificios->patchEntity($edificio, $this->request->data);
            if ($this->Edificios->save($edificio)) {
                $this->Flash->success(__('El edificio ha sido guardado.'));
            }else{
                $this->Flash->error(__('Error al guardar el edificio.'));
            }
        }

        $this->Flash->default(__('IMPORTANTE: Para editar los timbres por favor use el editor de timbres o cree el edificio nuevamente. Los timbres agregados manualmente no se mostraran en el diagrama'));

        $pb = filter_var($edificio['planta_baja'], FILTER_VALIDATE_BOOLEAN);

        $matrix = array_fill(!$pb, $edificio['pisos'] +$pb, array_fill(1, $edificio['deptos'], 0));

        $i = $pb ? 0:1;
        $excepciones = null;

        foreach ($matrix as $piso) {
            $j=1;
            foreach($piso as $depto){
                $found = false;
                foreach($edificio['timbres'] as $t){
                    if($t['row'] == $i && $t['col'] == $j){
                        $found = true;
                    }
                }
                if(!$found){
                    $excepciones[] = $i.';'.$j;
                }
                $j++;
            }
            $i++;
        }

        $notocar = [];
        foreach($edificio['timbres'] as $timbre){
            if($timbre['notocar'] == true){
                $notocar[] = $timbre['row'].';'.$timbre['col'];
            }
        }

        $this->set('excepciones', json_encode($excepciones));
        $this->set('notocar', json_encode($notocar));
        $this->set('edificio', $edificio);
        $this->set('alphabet', range('A', 'Z'));
        $this->set('timbre', new Timbre());
        $this->render('editar');
    }

    public function agregar(){
        $edificio = new Edificio();
        if ($this->request->is('post')) {
            $ediTable = TableRegistry::get('Edificios');
            $timTable = TableRegistry::get('Timbres');

            $data = $this->request->data;
            $excepciones = json_decode($data['excepciones']);
            $notocar = json_decode($data['notocar']);
            $rows = intval($data['pisos']);
            $cols = intval($data['deptos']);
            $planta_baja = $data['planta_baja'];
            $deptos_numerados = intval($data['deptos_numerados']);
            $inicio_numeracion = intval($data['inicio_numeracion']);

            if(count($excepciones) > 0){
                foreach ($excepciones as $e) {
                    $arrExcepciones[] = explode(';', $e);
                }
            }

            if(count($notocar) > 0){
                foreach ($notocar as $n) {
                    $arrNoTocar[] = explode(';', $n);
                }
            }

            $start = $planta_baja ? 0 : 1;
            for ($i = $start; $i<=$rows; $i++) {
                for ($j=1; $j<=$cols; $j++) {
                    $skip = false;
                    $isNoTocar = 0;
                    if(isset($arrExcepciones)){
                        foreach ($arrExcepciones as $arr) {
                            if($arr[0] == $i && $arr[1] == $j){
                                $skip = true;
                                break;
                            }
                        }
                    }

                    if($skip){
                        continue;
                    }

                    if(isset($arrNoTocar)){
                        foreach ($arrNoTocar as $arr) {
                            if($arr[0] == $i && $arr[1] == $j){
                                $isNoTocar = 1;
                                break;
                            }
                        }
                    }

                    if($deptos_numerados){
                        //$num_print = $numeracion - ($cols - $j);
                        $timbres[] = new Timbre([
                            'row' => $i,
                            'col' => $j,
                            'nombre' => $inicio_numeracion,
                            'notocar' => $isNoTocar
                        ]);
                        $inicio_numeracion++;
                    }else{
                        $timbres[] = new Timbre([
                            'row' => $i,
                            'col' => $j,
                            'notocar' => $isNoTocar
                        ]);
                    }
                }
                //$numeracion = $numeracion - $cols;
            }

            $edificio = new Edificio($data);
            $edificio->timbres = $timbres;

            if ($ediTable->save($edificio)) {
                $this->Flash->success(__('El edificio ha sido guardado.'));
                return $this->redirect(['action' => 'agregar']);
            }
            $this->Flash->error(__('No ha sido posible guardar el edificio.'));
        }

        $this->set('notocar', null);
        $this->set('excepciones', null);
        $this->set('edificio', $edificio);
    }

    public function visitar($id = null){
        if($id){
            $edi = $this->Edificios->get($id);
            $this->set('edificioId', $id);
            $this->set('header', $edi->calle . ' ' . $edi->altura);
        }else{
            $this->set('edificioId', 'null');
            $this->set('header', 'Visitar');
        }
    }

    public function llamar($id = null){
        if($id){
            $edi = $this->Edificios->get($id);
            $this->set('edificioId', $id);
            $this->set('header', $edi->calle . ' ' . $edi->altura);
        }else{
            $this->set('edificioId', 'null');
            $this->set('header', 'Llamar');
        }
    }

    public function buscar(){
        if($this->request->is('ajax')){
            $this->autoRender = false;
            if(!$this->request->data['q']){return;}
            $q = $this->request->data['q'];

            $query = $this->Edificios->find('all');
            $query = $this->_busqueda($q, $query);
            $query->limit(5);

            $this->response->type('json');
            $json = json_encode($query->toArray());
            $this->response->body($json);
        }
    }

    private function _busqueda($q, $query){
        $arr = preg_split('/\s+/i',$q);
            $count = 1;
            foreach($arr as $a){
                if(is_numeric($a)){
                    if($count == 1){
                        $query->where(['CAST(Edificios.altura as CHAR) LIKE' => '%'. $a .'%']);
                    }else{
                        $query->andWhere(['CAST(Edificios.altura as CHAR) LIKE' => '%'. $a .'%']);
                    }
                }else{
                    if($count == 1){
                        $query->where(['Edificios.calle LIKE' => '%'.$a.'%']);
                    }else{
                        $query->andWhere(['Edificios.calle LIKE' => '%'.$a.'%']);
                    }
                }
                $count++;
            }
        return $query;
    }

    public function agregar_timbre(){
        if($this->request->is('ajax')){
            $this->autoRender = false;
            $edificio = $this->request->data['edificio'];
            $piso = $this->request->data['piso'];
            $depto = $this->request->data['depto'];
            $nombre = $this->request->data['nombre'];

            $timTable = TableRegistry::get('Timbres');

            $existe = $timTable->find('all',[
                'conditions' => [
                    'edificioId' => $edificio,
                    'row' => $piso,
                    'col' => $depto,
                    'nombre' => $nombre
                    ]
                ]);

            if($existe->count()){
                $this->response->type('json');
                $json = json_encode(['error' => true, 'msg' => 'Ya existe un timbre para esa ubicación']);
                $this->response->body($json);
            }else{

                $timbre =
                    new Timbre([
                        'edificioid' => $edificio,
                        'row' => $piso,
                        'col' => $depto,
                        'notocar' => false,
                        'nombre' => $nombre
                        ]
                    );
                }

                if($timTable->save($timbre)){
                    $this->response->type('json');
                    $json = json_encode(['error' => false, 'msg' => 'Timbre creado']);
                    $this->response->body($json);
                }
        }
    }

    public function editar_timbre(){
      $this->autoRender = false;
        if($this->request->is('ajax')){
            $timbre = $this->request->data['timbre'];
            $nombre = $this->request->data['nombre'];

            $timTable = TableRegistry::get('Timbres');
            $record = $timTable->get($timbre);
            $record->nombre = $nombre;
            if($timTable->save($record)){
              $this->response->type('json');
              $json = json_encode(['error' => false, 'msg' => 'Timbre creado']);
              $this->response->body($json);
            }
        }
    }

    public function no_tocar($id = null){
        $this->autoRender = false;
        $timbres = TableRegistry::get('Timbres');

        $timbre = $timbres->get($id);
        $timbre->notocar = ($timbre->notocar == 1) ? 0 : 1;
        $timbre->notocar_fecha = date('Y-m-d', strtotime('now'));
        if($timbres->save($timbre)){
            $this->Flash->success(__('El timbre ahora es No Tocar.'));
            return $this->redirect($this->referer());
        }
        $this->Flash->error(__('Error al modificar el timbre.'));
        return $this->redirect($this->referer());
    }

    public function traer_timbre(){
        if($this->request->is('ajax')){
            $this->autoRender = false;
            if(!$this->request->data['edificio']){return;}
            $id = $this->request->data['edificio'];
            //$offset = intval($this->request->data['offset']);
            $edificios = TableRegistry::get('Edificios');
            $query = $edificios->find('all',[
                'conditions' => [ 'id' => $id ]
                ]);
            $today = new \DateTime('now');
            $query->contain([
                'Timbres' => [
                    'strategy' => 'select',
                    'queryBuilder' => function ($q) use($today) {
                        return $q
                            ->where([
                                'OR' => [
                                    'ultima_visita not LIKE' => '%'.$today->format('Y-m-d').'%',
                                    'ultima_visita IS' => null,
                                ],
                                'AND' => [
                                    'OR' =>[
                                        'notocar' => false,
                                        'notocar IS' => null
                                    ]
                                ]
                            ])
                            ->order([
                                'Timbres.visitas_count' =>'ASC'
                                ])->limit(1);
                    }
                ],
                'Timbres.Visitas'
            ]);

            $results = $query->toArray();
            $this->response->type('json');
            $json = json_encode($results);
            $this->response->body($json);
        }
    }

    public function traer_timbres(){
        if($this->request->is('ajax')){
            $this->autoRender = false;
            if(!$this->request->data['edificio']){return;}
            $id = $this->request->data['edificio'];

            $query = $this->Edificios->get($id,[
                'contain' => [
                  'Timbres',
                  'Timbres.Visitas' => [
                      'sort' => ['Visitas.fecha' => 'DESC']
                  ]]
                ]);

            $total = 0;
            $results = $query->toArray();
            foreach ($results['timbres'] as $key => $value) {
                $total += count($value['visitas']);
            }

            $results['visitas'] = $total;
            $today = new \DateTime('now');

            foreach ($results['timbres'] as $key => $timbre) {
                $visitas = count($timbre['visitas']);
                $results['timbres'][$key]['atendio'] = false;

                if(!$visitas){
                    continue;
                }

                $percent = intval(($visitas * 100) / $total);
                $results['timbres'][$key]['porcentaje'] = $percent;

                foreach ($timbre['visitas'] as $k => $v) {
                    $tocadoHoy = strpos($v['fecha'], $today->format('Y-m-d'));
                    if($tocadoHoy >= 0){
                        $results['timbres'][$key]['atendio'] = $v['atendio'];
                        break;
                    }
                }
            }

            $this->response->type('json');
            $json = json_encode($results);
            $this->response->body($json);
        }
    }

    public function tocar_timbre(){
        if($this->request->is('ajax')){
            $this->autoRender = false;
            if(!$this->request->data['timbre']){return;}
            $timbreId = $this->request->data['timbre'];
            $atendio = $this->request->data['atendio'];
            $now = new \DateTime('now');
            $table = TableRegistry::get('Visitas');
            $timTable = TableRegistry::get('Timbres');

            $query = $table->find('all')
                        ->where([
                            'timbreid' => $timbreId,
                            'fecha LIKE' => '%'.$now->format('Y-m-d').'%'
                            ]);
            $fecha = new \DateTime('now');

            if($query->count()){
                $visita = $table->get($query->first()->id);
                $visita->atendio = $atendio;
            }else{
                $visita = new Visita([
                        'fecha' => $fecha,
                        'timbreid' => $timbreId,
                        'atendio' => $atendio,
                        'userid' => $this->Auth->user('id')
                    ]);
            }

            $timbre = $timTable->get($timbreId);
            $timbre['ultima_visita'] = $fecha;
            $timTable->save($timbre);

            $table->save($visita);
            $this->response->type('json');
            $this->response->body(json_encode($visita));
        }
    }

    public function llamar_timbre(){
        if($this->request->is('ajax')){
            $this->autoRender = false;
            if(!$this->request->data['timbre']){return;}
            $timbreId = $this->request->data['timbre'];
            $atendio = $this->request->data['atendio'];
            $now = new \DateTime('now');
            $table = TableRegistry::get('Llamadas');
            $timTable = TableRegistry::get('Timbres');

            $query = $table->find('all')
                ->where([
                    'timbreid' => $timbreId,
                    'fecha LIKE' => '%'.$now->format('Y-m-d').'%'
                ]);

            if($query->count()){
                $llamada = $table->get($query->first()->id);
                $llamada->atendio = $atendio;
            }else{
                $fecha = new \DateTime('now');
                $llamada = new Llamada([
                    'fecha' => $fecha,
                    'timbreid' => $timbreId,
                    'atendio' => $atendio,
                    'userid' => $this->Auth->user('id')
                ]);
            }

            $timbre = $timTable->get($timbreId);
            $timbre['ultima_llamada']= $fecha;
            $timTable->save($timbre);

            $table->save($llamada);
            $this->response->type('json');
            $this->response->body(json_encode($llamada));
        }
    }

    public function eliminar_timbre($id = null){
        $this->autoRender = false;
        $timbres = TableRegistry::get('Timbres');

        $timbre = $timbres->get($id);
        if($timbres->delete($timbre)){
            $this->Flash->success(__('El timbre ha sido eliminado.'));
            return $this->redirect($this->referer());
        }
        $this->Flash->error(__('Error al eliminar el timbre.'));
        return $this->redirect($this->referer());
    }

    public function eliminar($id = null){
        $this->autoRender = false;

        $edificio = $this->Edificios->get($id);
        if($this->Edificios->delete($edificio)){
            $this->Flash->success(__('El edificio ha sido eliminado.'));
            return $this->redirect($this->referer());
        }
        $this->Flash->error(__('Error al eliminar el edificio.'));
        return $this->redirect($this->referer());
    }

    public function estadisticas(){
        /*$edificios = TableRegistry::get('Edificios');
        $timbres = TableRegistry::get('Timbres');
        $visitas = TableRegistry::get('Visitas');

        $query = $edificios->find();
        $query->

        $territoriosMasVisitados = $query->all();
        $this->response->type('json');
        $this->response->body(json_encode($territoriosMasVisitados));*/
    }

    #SELECT e.id, e.territorio, SUMFORMAT( SUM(
    #CASE WHEN v.id IS NOT NULL
    #THEN 1
    #ELSE 0
    #END ) / COUNT( * ) *100, 0 )) AS pct_complete
    #FROM timbres t
    #JOIN edificios e ON t.edificioid = e.id
    #LEFT JOIN visitas v ON t.id = v.timbreid
    #where e.territorio = 1
    #group by e.id

    public function completados(){
        $this->autoRender = false;
        if($this->request->is('ajax')){
            $from = new \DateTime('-60 days');
            $to = new \DateTime('now');
            $connection = ConnectionManager::get('default');
            $query = "select edificios.territorio, FORMAT(SUM(".
                     "CASE WHEN visitas.id IS NOT NULL ".
                     "THEN 1 ".
                     "ELSE 0 ".
                     "END) / COUNT(*) *100, 0) AS perc ".
                     "FROM timbres ".
                     "JOIN edificios ON timbres.edificioid = edificios.id ".
                     "LEFT JOIN visitas ON timbres.id = visitas.timbreid ".
                     "WHERE visitas.fecha BETWEEN '".$from->format('Y-m-d')."' AND '".$to->format('Y-m-d')."' ".
                     "or visitas.fecha IS NULL ".
                     "GROUP BY edificios.territorio";
            $results = $connection
                ->execute($query)
                ->fetchAll('assoc');
            $this->response->type('json');
            $json = json_encode($results);
            $this->response->body($json);
          }
    }

    public function masVisitados(){
        $this->autoRender = false;
        if($this->request->is('ajax')){
            $edificios = TableRegistry::get('Edificios');
            $query = $edificios->find();
            $query->select([
                    'territorio',
                    'count' => $query->func()->count('t.visitas_count')
                ])
                ->join([
                    'table' => 'timbres',
                    'alias' => 't',
                    'type' => 'left',
                    'conditions' => 't.edificioid = Edificios.id'
                    ])
                ->group('territorio');

            $results = $query->toArray();
            $this->response->type('json');
            $json = json_encode($results);
            $this->response->body($json);
        }
    }

    public function crearEncargados(){
      $this->autoRender = false;
      $edificios = TableRegistry::get('Edificios');
      $timbres = TableRegistry::get('Timbres');

      $all = $edificios->find('all')->contain('Timbres');
      foreach ($all as $edi) {
        if($edi->encargado){
          print_r($edi->calle . $edi->altura. " deberia tener encargado -");
          $has = false;
          foreach ($edi->timbres as $timbre) {
            if($timbre->nombre == 'Encargado'){
              $has = true;
              print_r(' tiene <br>');
              break;
            }
          }
          if(!$has){
            print_r(' no tiene ');
            $encargado = new Timbre([
              'edificioid' => $edi->id,
              'row' => 0,
              'col' => 0,
              'notocar' => 0,
              'nombre' => 'Encargado'
            ]);

            if($timbres->save($encargado)){
              print_r(' <b>creado!</b> <br>');
            }
          }
        }else{
          print_r($edi->calle . $edi->altura. "no deberia tener encargado <br>");
        }
      }

    }
    public function cobertura(){
        $this->autoRender = false;
        if($this->request->is('ajax')){
            $timbres = TableRegistry::get('Timbres');
            $visitas = TableRegistry::get('Visitas');
            $fecha = date('Y-m-d', strtotime("-30 days"));

            $timbresCount = $timbres->find('all')->count();
            $visitasAtendieronCount = $visitas->find('all', [
                'conditions' => [
                    'fecha >=' => $fecha,
                    'atendio' => true
                    ]
                ])->count();

            $visitasNoAtendieronCount = $visitas->find('all', [
                'conditions' => [
                    'fecha >=' => $fecha,
                    'atendio' => false
                    ]
                ])->count();

            $results = [
                'timbresCount' => $timbresCount,
                'visitasAtendieronCount' => $visitasAtendieronCount,
                'visitasNoAtendieronCount' => $visitasNoAtendieronCount
            ];

            $this->response->type('json');
            $json = json_encode($results);
            $this->response->body($json);
        }
    }
}
