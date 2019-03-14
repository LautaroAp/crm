<?php

/**
 * Clase actualmente sin uso
 */

namespace Presupuesto\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class PresupuestoController extends HuellaController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Presupuesto manager.
     * @var User\Service\PresupuestoManager 
     */
    protected $presupuestoManager;
    private $monedaManager;

    public function __construct($entityManager, $presupuestoManager, $monedaManager) {
        $this->entityManager = $entityManager;
        $this->presupuestoManager = $presupuestoManager;
        $this->monedaManager= $monedaManager;
    }

    public function indexAction() {
        return $this->procesarIndexAction();
    }

    private function procesarIndexAction() {
        $paginator = $this->presupuestoManager->getTabla();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage($this->getElemsPag());
        $volver = $this->getUltimaUrl();
        return new ViewModel([
            'presupuestos' => $paginator,
            'volver' => $volver,
        ]);        
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->presupuestoManager->addPresupuesto($data);
            $this->redirect()->toRoute('gestionProductosServicios/gestionServicios/listado');
        }
        $transacciones = $this->transaccionesManager->getIvas();
        $tipo= $this->params()->fromRoute('tipo');
        $volver = $this->getUltimaUrl();
        return new ViewModel([
            'tipo'=>$tipo,
            'volver' => $volver,
        ]);

    }

    public function editAction() {
        return $this->procesarEditAction();
    }

    public function procesarEditAction() {
        $id = $this->params()->fromRoute('id', -1);
        $presupuesto = $this->presupuestoManager->getPresupuestoId($id);
        $tipo = $this->params()->fromRoute('tipo');
        $transacciones = $this->transaccionesManager->getIvas();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->presupuestoManager->updatePresupuesto($presupuesto, $data);
            return $this->redirect()->toRoute('gestionProductosServicios/gestionServicios/listado');
        }
        $volver = $this->getUltimaUrl();
        return new ViewModel([
            'presupuesto' => $presupuesto,
            'categorias'=>$categorias,
            'transacciones'=>$transacciones,
            'proveedores'=>$proveedores,
            'tipo'=>"presupuesto",
            'volver' => $volver,
        ]);
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $presupuesto = $this->presupuestoManager->getPresupuestoId($id);
        if ($presupuesto == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        } else {
            $this->presupuestoManager->removePresupuesto($presupuesto);
            return $this->redirect()->toRoute('gestionProductosPresupuestos/gestionPresupuestos/listado');
        }
    }

    public function viewAction() {
        return new ViewModel();
    }

    public function backupAction(){
        $this->layout()->setTemplate('layout/nulo');
        $resultado = $this->presupuestoManager->getPresupuestos();
        return new ViewModel([
            'resultado' => $resultado
        ]);
    }


}
