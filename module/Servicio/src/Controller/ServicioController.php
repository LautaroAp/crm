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
    private $categoriaManager;


    public function __construct($entityManager, $servicioManager, $ivaManager,
    $categoriaManager) {
        $this->entityManager = $entityManager;
        $this->servicioManager = $servicioManager;
        $this->ivaManager= $ivaManager;
        $this->categoriaManager = $categoriaManager;
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
            $this->redirect()->toRoute('gestionProductosServicios/gestionServicios/listado');
        }
        $ivas = $this->ivaManager->getIvas();
        $tipo= $this->params()->fromRoute('tipo');
        $categorias = $this->servicioManager->getCategoriasServicio($tipo);
        return new ViewModel([
            'tipo'=>$tipo,
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
        $tipo = $this->params()->fromRoute('tipo');
        $categorias = $this->servicioManager->getCategoriasServicio($tipo);
        $ivas = $this->ivaManager->getIvas();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->servicioManager->updateServicio($servicio, $data);
            return $this->redirect()->toRoute('gestionProductosServicios/gestionServicios/listado');
        }
        return new ViewModel([
            'servicio' => $servicio,
            'categorias'=>$categorias,
            'ivas'=>$ivas,
            'tipo'=>"servicio"
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
            return $this->redirect()->toRoute('gestionProductosServicios/gestionServicios/listado');
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
