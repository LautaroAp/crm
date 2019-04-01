<?php

/**
 * Clase actualmente sin uso
 */

namespace Remito\Controller;

use Transaccion\Controller\TransaccionController;
use Remito\Service\RemitoManager;

use Zend\View\Model\ViewModel;
use DBAL\Entity\BienesTransacciones;

class RemitoController extends TransaccionController{

    /**
     * Remito manager.
     * @var User\Service\RemitoManager 
     */
    protected $remitoManager;
    private $monedaManager;
    private $clientesManager;
    private $proveedorManager;
    private $tipo;
    private $bienesTransaccionesManager;
    private $items;

    public function __construct($remitoManager, $monedaManager, $personaManager, $clientesManager, $proveedorManager,
    $bienesTransaccionesManager) {
        parent::__construct($remitoManager, $personaManager);
        $this->clientesManager=$clientesManager;
        $this->proveedorManager= $proveedorManager;
        $this->remitoManager = $remitoManager;
        $this->monedaManager= $monedaManager;
        $this->bienesTransaccionesManager= $bienesTransaccionesManager;
        

    }

    public function indexAction() {

    }

   
    private function getTipo(){
        return "remito";
    }

    public function addAction() {
        $items = array();
        if (isset($_SESSION['TRANSACCIONES']['REMITO'])){
            $items = $_SESSION['TRANSACCIONES']['REMITO'];
        }
        $json = "";
        foreach ($items as $array){
            $item = $this->bienesTransaccionesManager->bienTransaccionFromArray($array);
            $json .= $item->getJson(). ',';
        }
        $json = substr($json, 0, -1);
        $json = '['.$json.']';
        $id_persona = $this->params()->fromRoute('id');
        $persona = $this->personaManager->getPersona($id_persona);
        $tipoPersona = null;

        if($persona->getTipo()=="CLIENTE"){
            $tipoPersona= $this->clientesManager->getClienteIdPersona($id_persona);
        }
        elseif ($persona->getTipo()=="PROVEEDOR"){
            $tipoPersona= $this->proveedorManager->getProveedorIdPersona($id_persona);
        }
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data['tipo'] = $this->getTipo();
            $data['persona'] = $persona;
            $this->remitoManager->addRemito($data, $items);
            if($persona->getTipo()=="CLIENTE"){
                $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            }
            else{
                $this->redirect()->toRoute('proveedor/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            }
        
        }
        $numTransacciones= $this->remitoManager->getTotalTransacciones()+1;
        $numRemito = $this->remitoManager->getTotalRemitos()+1;
        $formasPago = $this->remitoManager->getFormasPago();
        $this->reiniciarParams();
        return new ViewModel([
            'items' => $items,
            'persona' => $persona,
            'tipoPersona'=>$tipoPersona,
            'numTransacciones'=>$numTransacciones,
            'numRemito'=>$numRemito,
            'json' => $json,
            'formasPago' => $formasPago,
        ]);
    }

    public function addItemAction() {
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data['tipo'] = $this->getTipo();
            $this->procesarAddAction($data);
            $this->redirect()->toRoute('home');
        }
        return new ViewModel([
        ]);
    }


    public function editAction() {
        $id_transaccion= $this->params()->fromRoute('id');
        $remito = $this->remitoManager->getRemitoFromTransaccionId($id_transaccion);
        $items= array();
        if (!is_null($remito)){
            $items = $remito->getTransaccion()->getBienesTransacciones();
        }
        $items = $this->getItemsArray($items);
        if (!isset($_SESSION['TRANSACCIONES']['REMITO'])){
            $_SESSION['TRANSACCIONES']['REMITO']= $items;
        }
        
        $items = $_SESSION['TRANSACCIONES']['REMITO'];
        $json = "";
        foreach ($items as $array){
            $item = $this->bienesTransaccionesManager->bienTransaccionFromArray($array);
            $json .= $item->getJson(). ',';
        }
        $json = substr($json, 0, -1);
        $json = '['.$json.']';
        $persona = $remito->getTransaccion()->getPersona();
        $tipoPersona = null;
        if($persona->getTipo()=="CLIENTE"){
            $tipoPersona= $this->clientesManager->getClienteIdPersona($persona->getId());
        }
        elseif ($persona->getTipo()=="PROVEEDOR"){
            $tipoPersona= $this->proveedorManager->getProveedorIdPersona($persona->getId());
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data['tipo'] = $this->getTipo();
            $data['persona'] = $persona;
            $data['items'] = $_SESSION['TRANSACCIONES']['REMITO'];
            $this->remitoManager->edit($remito, $data);
            $url = $data['url'];
            if($persona->getTipo()=="CLIENTE"){
                $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            }
            else{
                $this->redirect()->toRoute('proveedor/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            }
        
        }
        $numTransacciones= $remito->getTransaccion()->getNumero(); 
        $numRemito = $remito->getNumero();
        $formasPago= $this->remitoManager->getFormasPago();
        $this->reiniciarParams();
        return new ViewModel([
            'items' => $items,
            'persona' => $persona,
            'tipoPersona'=>$tipoPersona,
            'numTransacciones'=>$numTransacciones,
            'numRemito'=>$numRemito,
            'json' => $json,
            'formasPago' => $formasPago,

        ]);
    }

    public function eliminarItemAction(){
        $this->layout()->setTemplate('layout/nulo');
        $pos = $this->params()->fromRoute('id');
        $id = $this->params()->fromRoute('id2');
        array_splice($_SESSION['TRANSACCIONES']['REMITO'], $pos,1);

        // return $this->redirect()->toRoute('remito/add/'.$id);
        $view = new ViewModel();
        $view->setTerminal(true);
        return $view;
    }


}
