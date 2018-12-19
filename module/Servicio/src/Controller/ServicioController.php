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

    private $ivaManager;
    private $categoriaServicioManager;
    public function __construct($entityManager, $servicioManager, $ivaManager,
    $categoriaServicioManager) {
        $this->entityManager = $entityManager;
        $this->servicioManager = $servicioManager;
        $this->ivaManager= $ivaManager;
        $this->categoriaServicioManager = $categoriaServicioManager;
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
            $this->redirect()->toRoute('gestionEmpresa/gestionServicios/listado');
        }
        $ivas = $this->ivaManager->getIvas();
        $categorias = $this->categoriaServicioManager->getCategoriaServicios();
        return new ViewModel([
            'ivas'=>$ivas,
            'categorias'=>$categorias
        ]);

    }

    public function editAction() {
        return $this->procesarEditAction();
    }

    public function procesarEditAction() {
        $id = $this->params()->fromRoute('id', -1);
        $servicio = $this->servicioManager->getServicioId($id);
        $categorias = $this->categoriaServicioManager->getCategoriaServicios();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->servicioManager->updateServicio($servicio, $data);
            return $this->redirect()->toRoute('gestionEmpresa/gestionServicios/listado');
        }
        return new ViewModel([
            'servicio' => $servicio,
            'categorias'=>$categorias
        ]);
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
            return $this->redirect()->toRoute('gestionEmpresa/gestionServicios/listado');
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
