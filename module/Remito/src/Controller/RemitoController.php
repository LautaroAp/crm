<?php

/**
 * Clase actualmente sin uso
 */

namespace Remito\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class RemitoController extends HuellaController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Remito manager.
     * @var User\Service\RemitoManager 
     */
    protected $remitoManager;
    private $transaccionManager;
    private $monedaManager;

    public function __construct($entityManager, $remitoManager, $monedaManager,$transaccionManager) {
        $this->entityManager = $entityManager;
        $this->remitoManager = $remitoManager;
        $this->monedaManager= $monedaManager;
        $this->transaccionManager = $transaccionManager;
    }

    public function indexAction() {
        return $this->procesarIndexAction();
    }

    private function procesarIndexAction() {
        $paginator = $this->remitoManager->getTabla();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage($this->getElemsPag());
        $volver = $this->getUltimaUrl();
        return new ViewModel([
            'remitos' => $paginator,
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
            $this->remitoManager->addRemito($data);
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
        $remito = $this->remitoManager->getRemitoId($id);
        $tipo = $this->params()->fromRoute('tipo');
        $transacciones = $this->transaccionesManager->getIvas();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->remitoManager->updateRemito($remito, $data);
            return $this->redirect()->toRoute('gestionProductosServicios/gestionServicios/listado');
        }
        $volver = $this->getUltimaUrl();
        return new ViewModel([
            'remito' => $remito,
            'categorias'=>$categorias,
            'transacciones'=>$transacciones,
            'proveedores'=>$proveedores,
            'tipo'=>"remito",
            'volver' => $volver,
        ]);
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $remito = $this->remitoManager->getRemitoId($id);
        if ($remito == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        } else {
            $this->remitoManager->removeRemito($remito);
            return $this->redirect()->toRoute('gestionProductosRemitos/gestionRemitos/listado');
        }
    }

    public function viewAction() {
        return new ViewModel();
    }

    public function backupAction(){
        $this->layout()->setTemplate('layout/nulo');
        $resultado = $this->remitoManager->getRemitos();
        return new ViewModel([
            'resultado' => $resultado
        ]);
    }


}
