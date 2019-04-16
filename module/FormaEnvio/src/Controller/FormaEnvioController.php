<?php

/**
 * Esta clase es el controlador de la entidad FormaEnvio.  
 * Se encarga de direccionar los datos entre las vistas y el manager
 * @author SoftHuella 
 */

namespace FormaEnvio\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class FormaEnvioController extends HuellaController {

    protected $formaEnvioManager;
    private $transaccionManager;

    public function __construct($formaEnvioManager, $transaccionManager) {
        $this->formaEnvioManager = $formaEnvioManager;
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
        $formasEnvio = $this->formaEnvioManager->getFormasEnvio();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->limpiarParametros($data);
            $this->formaEnvioManager->addFormaEnvio($data);
            return $this->redirect()->toRoute("herramientas/formasenvio");
            
        }
        return new ViewModel([
            'formasEnvio' => $formasEnvio,
        ]);
    }

    
    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = $this->params()->fromRoute('id');
        $formaEnvio = $this->formaEnvioManager->getFormaEnvioId($id);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->formaEnvioManager->updateFormaEnvio($formaEnvio, $data);
            return $this->redirect()->toRoute("herramientas/formasenvio");
        }
        return new ViewModel(array(
            'formaEnvio' => $formaEnvio,
        ));  
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $formaEnvio = $this->formaEnvioManager->getFormaEnvioId($id);
        if ($formaEnvio == null) {
            $this->reportarError();
        } else {
            $this->transaccionManager->eliminarFormasEnvio($formaEnvio->getId());
            $this->formaEnvioManager->removeFormaEnvio($formaEnvio);
            return $this->redirect()->toRoute('herramientas/formasenvio');

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
