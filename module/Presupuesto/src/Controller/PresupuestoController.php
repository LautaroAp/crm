<?php

/**
 * Clase actualmente sin uso
 */

namespace Presupuesto\Controller;

use Transaccion\Controller\TransaccionController;
use Presupuesto\Service\PresupuestoManager;

use Zend\View\Model\ViewModel;

class PresupuestoController extends TransaccionController{

    /**
     * Presupuesto manager.
     * @var User\Service\PresupuestoManager 
     */
    protected $presupuestoManager;
    private $monedaManager;
    private $clientesManager;
    private $proveedorManager;
    private $tipo;
    private $bienesTransaccionesManager;


    public function __construct($presupuestoManager, $monedaManager, $personaManager, $clientesManager,
    $proveedorManager,$bienesTransaccionesManager) {
        parent::__construct($presupuestoManager, $personaManager);
        $this->clientesManager=$clientesManager;
        $this->proveedorManager= $proveedorManager;
        $this->presupuestoManager = $presupuestoManager;
        $this->monedaManager= $monedaManager;
        $this->bienesTransaccionesManager= $bienesTransaccionesManager;
        
    }

    public function indexAction() {
    }

   
    private function getTipo(){
        return "presupuesto";
    }

    public function addAction() {
        $items = array();
        if (isset($_SESSION['TRANSACCIONES']['PRESUPUESTO'])){
            $items = $_SESSION['TRANSACCIONES']['PRESUPUESTO'];
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
            $this->presupuestoManager->addPresupuesto($data, $items);
            if($persona->getTipo()=="CLIENTE"){
                $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            }
            else{
                $this->redirect()->toRoute('proveedor/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            }
        
        }
        $numTransacciones= $this->presupuestoManager->getTotalTransacciones()+1;
        $numPresupuesto = $this->presupuestoManager->getTotalPresupuestos()+1;
        $formasPago = $this->presupuestoManager->getFormasPago();
        $this->reiniciarParams();
        return new ViewModel([
            'items' => $items,
            'persona' => $persona,
            'tipoPersona'=>$tipoPersona,
            'numTransacciones'=>$numTransacciones,
            'numPresupuesto'=>$numPresupuesto,
            'json' => $json,
            'formasPago' => $formasPago,
        ]);
    }
    public function editAction() {
        $id_transaccion= $this->params()->fromRoute('id');
        $presupuesto = $this->presupuestoManager->getPresupuestoFromTransaccionId($id_transaccion);
        $items= array();
        if (!is_null($presupuesto)){
            $items = $presupuesto->getTransaccion()->getBienesTransacciones();
        }
        $items = $this->getItemsArray($items);
        if (!isset($_SESSION['TRANSACCIONES']['PRESUPUESTO'])){
            $_SESSION['TRANSACCIONES']['PRESUPUESTO']= $items;
        }
        $items = $_SESSION['TRANSACCIONES']['PRESUPUESTO'];
        $json = "";
        foreach ($items as $array){
            $item = $this->bienesTransaccionesManager->bienTransaccionFromArray($array);
            $json .= $item->getJson(). ',';
        }
        $json = substr($json, 0, -1);
        $json = '['.$json.']';
        $persona = $presupuesto->getTransaccion()->getPersona();
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
            $data['items'] = $_SESSION['TRANSACCIONES']['PRESUPUESTO'];
            $this->presupuestoManager->edit($presupuesto, $data);
            $url = $data['url'];
            if($persona->getTipo()=="CLIENTE"){
                $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            }
            else{
                $this->redirect()->toRoute('proveedor/ficha', ['action' => 'ficha', 'id' => $persona->getId()]);
            }
        }
        $numTransacciones= $presupuesto->getTransaccion()->getNumero(); 
        $numPresupuesto = $presupuesto->getNumero();
        $formasPago= $this->presupuestoManager->getFormasPago();
        $this->reiniciarParams();
        return new ViewModel([
            'items' => $items,
            'persona' => $persona,
            'tipoPersona'=>$tipoPersona,
            'numTransacciones'=>$numTransacciones,
            'numPresupuesto'=>$numPresupuesto,
            'json' => $json,
            'formasPago' => $formasPago,

        ]);
    }

    public function eliminarItemAction(){
        $this->layout()->setTemplate('layout/nulo');
        $pos = $this->params()->fromRoute('id');
        $id = $this->params()->fromRoute('id2');
        array_splice($_SESSION['TRANSACCIONES']['PRESUPUESTO'], $pos,1);
        $view = new ViewModel();
        $view->setTerminal(true);
        return $view;
    }

}
