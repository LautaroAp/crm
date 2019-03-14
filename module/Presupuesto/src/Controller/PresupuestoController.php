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
    protected $pedidoManager;
    private $monedaManager;
    private $tipo;

    public function __construct($pedidoManager, $monedaManager, $personaManager) {
        parent::__construct($pedidoManager, $personaManager);

        $this->pedidoManager = $pedidoManager;
        $this->monedaManager= $monedaManager;
    }

    public function indexAction() {
        return $this->procesarIndexAction();
    }

   
    private function getTipo(){
        return "presupuesto";
    }

    public function addAction() {
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
        $id = $this->params()->fromRoute('id', -1);
        $presupuesto = $this->pedidoManager->getPresupuestoId($id);
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
