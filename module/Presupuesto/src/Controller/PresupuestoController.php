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


    public function __construct($presupuestoManager, $monedaManager, $personaManager, $clientesManager, $proveedorManager) {
        parent::__construct($presupuestoManager, $personaManager);
        $this->clientesManager=$clientesManager;
        $this->proveedorManager= $proveedorManager;
        $this->presupuestoManager = $presupuestoManager;
        $this->monedaManager= $monedaManager;
        
    }

    public function indexAction() {
        print_r("hola");
        die();
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
        foreach ($items as $item){
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
            $this->redirect()->toRoute('home');
        }
        $numTransacciones= $this->presupuestoManager->getTotalTransacciones()+1;
        $numPresupuesto = $this->presupuestoManager->getTotalPresupuestos()+1;
        $this->reiniciarParams();
        return new ViewModel([
            'items' => $items,
            'persona' => $persona,
            'tipoPersona'=>$tipoPersona,
            'numTransacciones'=>$numTransacciones,
            'numPresupuesto'=>$numPresupuesto,
            'json' => $json,
        ]);
    }

    public function editAction() {
        $id = $this->params()->fromRoute('id', -1);
        $presupuesto = $this->presupuestoManager->getPresupuestoId($id);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data['tipo'] = $this->getTipo();
            $this->procesarEditAction($presupuesto, $data);
            return $this->redirect()->toRoute('home');
        }       
        return new ViewModel([
            'presupuesto' => $presupuesto,
            'transaccion'=>$presupuesto->getTransaccion(),
            'persona'=>$transaccion->getPersona(),
            'tipo'=>$this->getTipo(),
        ]);    
    }


}
