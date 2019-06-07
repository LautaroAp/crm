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
    protected $TipoComprobanteManager;
    private $personaManager;

    public function __construct($entityManager, $tipoComprobanteManager, $personaManager) {
        $this->entityManager = $entityManager;
        $this->tipoComprobanteManager = $tipoComprobanteManager;
        $this->personaManager = $personaManager;
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
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->limpiarParametros($data);
            $this->tipoComprobanteManager->addTipoComprobante($data);
            return $this->redirect()->toRoute("herramientas/tipocomprobante");
            
        }
        return new ViewModel([
            'tipoComprobantes' => $tipoComprobantes,
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
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->tipoComprobanteManager->updateTipoComprobante($tipoComprobante, $data);
            return $this->redirect()->toRoute('herramientas/tipocomprobante');
        }
        return new ViewModel(array(
            'tipoComprobante' => $tipoComprobante,
        ));
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $tipoComprobante = $this->tipoComprobanteManager->getTipoComprobante($id);
        $this->personaManager->eliminarTipoComprobante($id);
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
