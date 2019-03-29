<?php

/**
 * Clase actualmente sin uso
 */

namespace Servicio\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class ServicioController extends HuellaController {

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
    private $bienesManager;

    public function __construct($entityManager, $servicioManager, $ivaManager,
    $categoriaManager, $bienesManager) {
        $this->entityManager = $entityManager;
        $this->servicioManager = $servicioManager;
        $this->ivaManager= $ivaManager;
        $this->categoriaManager = $categoriaManager;
        $this->bienesManager = $bienesManager;
    }

    public function indexAction() {
        return $this->procesarIndexAction();
    }

    private function procesarIndexAction() {
        $this->prepararBreadcrumbs("Listado", "/listado", "Servicios");
        $servicios = $this->servicioManager->getServicios();

        $volver = $this->getUltimaUrl();
        return new ViewModel([
            'servicios' => $servicios,
            'volver' => $volver,
        ]);        
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        $this->prepararBreadcrumbs("Agregar Servicios", "/add/servicio", "Servicios");
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->servicioManager->addServicio($data);
            $this->redirect()->toRoute('gestionProductosServicios/gestionServicios/listado');
        }
        $ivas = $this->ivaManager->getIvas();
        $tipo= $this->params()->fromRoute('tipo');
        $categorias = $this->servicioManager->getCategoriasServicio($tipo);
        $proveedores = $this->servicioManager->getListaProveedores($tipo);
        $volver = $this->getUltimaUrl();
        return new ViewModel([
            'tipo'=>$tipo,
            'ivas'=>$ivas,
            'proveedores'=>$proveedores,
            'categorias'=>$categorias,
            'volver' => $volver,
        ]);

    }

    public function editAction() {
        return $this->procesarEditAction();
    }

    public function procesarEditAction() {
        $id = $this->params()->fromRoute('id', -1);
        $servicio = $this->servicioManager->getServicioId($id);
        $tipo = $this->params()->fromRoute('tipo');
        $this->prepararBreadcrumbs("Editar Servicio", "/edit/".$tipo."/".$id, "Listado");
        $categorias = $this->servicioManager->getCategoriasServicio($tipo);
        $proveedores = $this->servicioManager->getListaProveedores($tipo);
        $ivas = $this->ivaManager->getIvas();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->servicioManager->updateServicio($servicio, $data);
            return $this->redirect()->toRoute('gestionProductosServicios/gestionServicios/listado');
        }
        $volver = $this->getUltimaUrl();
        return new ViewModel([
            'servicio' => $servicio,
            'categorias'=>$categorias,
            'ivas'=>$ivas,
            'proveedores'=>$proveedores,
            'tipo'=>"servicio",
            'volver' => $volver,
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
