<?php

namespace TipoFactura\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class TipoFacturaController extends HuellaController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * TipoFactura manager.
     * @var User\Service\TipoFacturaManager 
     */
    protected $TipoFacturaManager;

    public function __construct($entityManager, $tipoFacturaManager) {
        $this->entityManager = $entityManager;
        $this->tipoFacturaManager = $tipoFacturaManager;
    }

    public function indexAction() {
        // $view = $this->procesarIndexAction();
        $view = $this->procesarIndexAction();
        return $view;
    }

    private function procesarIndexAction() {
        $tipoFacturas = $this->tipoFacturaManager->getTipoFacturas();
        return new ViewModel([
            'tipoFacturas' => $tipoFacturas,
        ]);
    }

    

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        // $this->prepararBreadcrumbs("Editar", "/edit/".$id, "Tipo de factura");
        $tipoFactura = $this->tipoFacuraManager->getTipoFactura($id);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->tipoFacuraManager->updateTipoFactura($tipoFactura, $data);
            return $this->redirect()->toRoute('herramientas/tipofactura');
        }
        return new ViewModel(array(
            'factura' => $tipoFactura,
        ));
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function viewAction() {
        return new ViewModel();
    }

    private function reportarError() {
        $this->getResponse()->setStatusCode(404);
        return;
    }

}
