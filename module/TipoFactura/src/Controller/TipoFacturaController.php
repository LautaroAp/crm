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
    private $personaManager;

    public function __construct($entityManager, $tipoFacturaManager, $personaManager) {
        $this->entityManager = $entityManager;
        $this->tipoFacturaManager = $tipoFacturaManager;
        $this->personaManager = $personaManager;
    }

    public function indexAction() {
        // $view = $this->procesarIndexAction();
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarIndexAction() {
        $tipoFacturas = $this->tipoFacturaManager->getTipoFacturas();
        return new ViewModel([
            'tipoFacturas' => $tipoFacturas,
        ]);
    }

    private function procesarAddAction() {
        $tipoFacturas = $this->tipoFacturaManager->getTipoFacturas();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->limpiarParametros($data);
            $this->tipoFacturaManager->addTipoFactura($data);
            return $this->redirect()->toRoute("herramientas/tipofactura");
            
        }
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
        $tipoFactura = $this->tipoFacturaManager->getTipoFactura($id);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->tipoFacturaManager->updateTipoFactura($tipoFactura, $data);
            return $this->redirect()->toRoute('herramientas/tipofactura');
        }
        return new ViewModel(array(
            'tipoFactura' => $tipoFactura,
        ));
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $tipoFactura = $this->tipoFacturaManager->getTipoFactura($id);
        $this->personaManager->eliminarTipoFactura($id);
        $this->tipoFacturaManager->removeTipoFactura($tipoFactura);
        return $this->redirect()->toRoute('herramientas/tipofactura');
    }

    public function viewAction() {
        return new ViewModel();
    }

    private function reportarError() {
        $this->getResponse()->setStatusCode(404);
        return;
    }

}
