<?php

/**
 * Esta clase es el controlador de la entidad FormaPago.  
 * Se encarga de direccionar los datos entre las vistas y el manager
 * @author SoftHuella 
 */

namespace FormaPago\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class FormaPagoController extends HuellaController {



    protected $formaPagoManager;
    private $transaccionManager;



    public function __construct($formaPagoManager, $transaccionManager) {
        $this->formaPagoManager = $formaPagoManager;
        $this->transaccionManager = $transaccionManager;
    }

    public function indexAction() {
        $view = $this->procesarAddAction();
        return $view;
    }


    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        $formasPago = $this->formaPagoManager->getFormasPago();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->limpiarParametros($data);
            $this->formaPagoManager->addFormaPago($data);
            return $this->redirect()->toRoute("herramientas/formaspago");
            
        }
        return new ViewModel([
            'formasPago' => $formasPago,
        ]);
    }

    
    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = $this->params()->fromRoute('id');
        $formaPago = $this->formaPagoManager->getFormaPagoId($id);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->formaPagoManager->edit($formaPago, $data);
        }
        return new ViewModel(array(
            'formaPago' => $formaPago,
        ));  
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $formaPago = $this->formaPagoManager->getFormaPagoId($id);
        if ($formaPago == null) {
            $this->reportarError();
        } else {
            $this->transaccionManager->eliminarFormasPago($formaPago->getId());
            $this->formaPagoManager->removeFormaPago($formaPago);
            return $this->redirect()->toRoute('herramientas/formaspago');

        }
    }

    public function viewAction() {
        return new ViewModel();
    }

    private function reportarError() {
        $this->getResponse()->setStatusCode(404);
        return;
    }


}
