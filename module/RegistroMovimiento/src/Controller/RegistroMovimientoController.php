<?php

namespace RegistroMovimiento\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class RegistroMovimientoController extends HuellaController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * RegistroMovimiento manager.
     * @var User\Service\RegistroMovimientoManager 
     */
    protected $registroMovimientoManager;

    private $servicioManager;
      
    public function __construct($entityManager, $registroMovimientoManager, $servicioManager) {
        $this->entityManager = $entityManager;
        $this->registroMovimientoManager = $registroMovimientoManager;
        $this->servicioManager = $servicioManager;
    }

    public function indexAction() {
        $view = $this->procesarAddAction();
        return $view;
    }
  

    public function getRegistroMovimientos(){
        return $this->entityManager->getRepository(RegistroMovimiento::class)->fetchAll();
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        $registroMovimientos = $this->registroMovimientoManager->getRegistroMovimientos();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->limpiarParametros($data);
            $this->registroMovimientoManager->addRegistroMovimiento($data);
            return $this->redirect()->toRoute("gestionProductosServicios");
        }
        return new ViewModel([
            'registroMovimientos' => $registroMovimientos,
        ]);
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $registroMovimiento = $this->registroMovimientoManager->getRegistroMovimientoId($id);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->registroMovimientoManager->updateRegistroMovimiento($registroMovimiento, $data);
            return $this->redirect()->toRoute('gestionProductosServicios');
        }
        return new ViewModel(array(
            'registroMovimiento' => $registroMovimiento,
        ));
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $registroMovimiento = $this->registroMovimientoManager->getRegistroMovimientoId($id);
        if ($registroMovimiento == null) {
            $this->reportarError();
        } else {
            $this->registroMovimientoManager->removeRegistroMovimiento($id);
            return $this->redirect()->toRoute('gestionProductosServicios');
        }
    }

    public function viewAction() {
        return new ViewModel();
    }

    private function reportarError() {
        $this->getResponse()->setStatusCode(404);
        return;
    }

}
