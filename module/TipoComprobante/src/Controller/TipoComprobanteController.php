<?php

namespace TipoComprobante\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class TipoComprobanteController extends HuellaController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * TipoComprobante manager.
     * @var User\Service\TipoComprobanteManager 
     */
    protected $tipoComprobanteManager;
    protected $comprobanteManager;

    public function __construct($entityManager, $tipoComprobanteManager, $comprobanteManager) {
        $this->entityManager = $entityManager;
        $this->tipoComprobanteManager = $tipoComprobanteManager;
        $this->comprobanteManager = $comprobanteManager;
    }

    public function indexAction() {
        // $view = $this->procesarIndexAction();
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarIndexAction() {
        $tipoComprobantes = $this->tipoComprobanteManager->getTipoComprobantes();
        return new ViewModel([
            'tipoComprobantes' => $tipoComprobantes,
        ]);
    }

    private function procesarAddAction() {
        $tipoComprobantes = $this->tipoComprobanteManager->getTipoComprobantes();
        $comprobantes = $this->comprobanteManager->getComprobantes();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->limpiarParametros($data);
            $this->tipoComprobanteManager->addTipoComprobante($data);
            return $this->redirect()->toRoute("herramientas/tipocomprobante");
            
        }
        return new ViewModel([
            'tipoComprobantes' => $tipoComprobantes,
            'comprobantes'=> $comprobantes
        ]);
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        // $this->prepararBreadcrumbs("Editar", "/edit/".$id, "Tipo de comprobante");
        $tipoComprobante = $this->tipoComprobanteManager->getTipoComprobante($id);
        $comprobantes = $this->comprobanteManager->getComprobantes();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->tipoComprobanteManager->updateTipoComprobante($tipoComprobante, $data);
            return $this->redirect()->toRoute('herramientas/tipocomprobante');
        }
        return new ViewModel(array(
            'tipoComprobante' => $tipoComprobante,
            'comprobantes'=> $comprobantes

        ));
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $tipoComprobante = $this->tipoComprobanteManager->getTipoComprobante($id);
        $this->tipoComprobanteManager->removeTipoComprobante($tipoComprobante);
        return $this->redirect()->toRoute('herramientas/tipocomprobante');
    }

    public function viewAction() {
        return new ViewModel();
    }

    private function reportarError() {
        $this->getResponse()->setStatusCode(404);
        return;
    }

}
