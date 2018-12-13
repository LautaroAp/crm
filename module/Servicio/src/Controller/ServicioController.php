<?php

/**
 * Clase actualmente sin uso
 */

namespace Servicio\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ServicioController extends AbstractActionController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Servicio manager.
     * @var User\Service\ServicioManager 
     */
    protected $servicioManager;

    public function __construct($entityManager, $servicioManager) {
        $this->entityManager = $entityManager;
        $this->servicioManager = $servicioManager;
    }

    public function indexAction() {
        return $this->procesarIndexAction();
    }

    private function procesarIndexAction() {
        $paginator = $this->servicioManager->getTabla();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);
        return new ViewModel([
            'servicios' => $paginator
        ]);        
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->servicioManager->addServicio($data);
            return $this->redirect()->toRoute('servicio', ['action' => 'index']);
        }
        return new ViewModel();
    }

    public function editAction() {
        return $this->procesarEditAction();
    }

    public function procesarEditAction() {
        $id = $this->params()->fromRoute('id', -1);
        $servicio = $this->servicioManager->getServicioId($id);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->servicioManager->updateServicio($servicio, $data);
            return $this->redirect()->toRoute('servicio', ['action' => 'index']);
        }
        return new ViewModel([
            'servicio' => $servicio]);
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $servicio = $this->servicioManager->getServicioId($id);
        if ($servicio == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        } else {
            $this->servicioManager->removeServicio($servicio);
            return $this->redirect()->toRoute('servicio', ['action' => 'index']);
        }
    }

    public function viewAction() {
        return new ViewModel();
    }

    public function backupAction(){
        $this->layout()->setTemplate('layout/nulo');
        $resultado = $this->servicioManager->getServicios();
        return new ViewModel([
            'resultado' => $resultado
        ]);
    }
}
