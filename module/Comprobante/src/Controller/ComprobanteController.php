<?php

namespace Comprobante\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class ComprobanteController extends HuellaController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Comprobante manager.
     * @var User\Service\ComprobanteManager 
     */
    protected $ComprobanteManager;
    private $personaManager;

    public function __construct($entityManager, $comprobanteManager, $personaManager) {
        $this->entityManager = $entityManager;
        $this->comprobanteManager = $comprobanteManager;
        $this->personaManager = $personaManager;
    }

    public function indexAction() {
        // $view = $this->procesarIndexAction();
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarIndexAction() {
        $comprobantes = $this->comprobanteManager->getComprobantes();
        return new ViewModel([
            'comprobantes' => $comprobantes,
        ]);
    }

    private function procesarAddAction() {
        $comprobantes = $this->comprobanteManager->getComprobantes();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->limpiarParametros($data);
            $this->comprobanteManager->addComprobante($data);
            return $this->redirect()->toRoute("herramientas/comprobante");
            
        }
        return new ViewModel([
            'comprobantes' => $comprobantes,
        ]);
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        // $this->prepararBreadcrumbs("Editar", "/edit/".$id, "Tipo de comprobante");
        $comprobante = $this->comprobanteManager->getComprobante($id);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->comprobanteManager->updateComprobante($comprobante, $data);
            return $this->redirect()->toRoute('herramientas/comprobante');
        }
        return new ViewModel(array(
            'comprobante' => $comprobante,
        ));
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $comprobante = $this->comprobanteManager->getComprobante($id);
        $this->personaManager->eliminarComprobante($id);
        $this->comprobanteManager->removeComprobante($comprobante);
        return $this->redirect()->toRoute('herramientas/comprobante');
    }

    public function viewAction() {
        return new ViewModel();
    }

    private function reportarError() {
        $this->getResponse()->setStatusCode(404);
        return;
    }

}
