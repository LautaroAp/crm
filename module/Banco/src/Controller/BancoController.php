<?php

namespace Banco\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class BancoController extends HuellaController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Banco manager.
     * @var User\Service\BancoManager 
     */
    protected $bancoManager;

    private $servicioManager;
      
    public function __construct($entityManager, $bancoManager, $servicioManager) {
        $this->entityManager = $entityManager;
        $this->bancoManager = $bancoManager;
        $this->servicioManager = $servicioManager;
    }

    public function indexAction() {
        $view = $this->procesarAddAction();
        return $view;
    }
  

    public function getBancos(){
        return $this->entityManager->getRepository(Banco::class)->fetchAll();
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        $bancos = $this->bancoManager->getBancos();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->limpiarParametros($data);
            $this->bancoManager->addBanco($data);
            return $this->redirect()->toRoute("herramientas/banco");
        }
        return new ViewModel([
            'bancos' => $bancos,
        ]);
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $banco = $this->bancoManager->getBancoId($id);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->bancoManager->updateBanco($banco, $data);
            return $this->redirect()->toRoute('herramientas/banco');
        }
        return new ViewModel(array(
            'banco' => $banco,
        ));
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $banco = $this->bancoManager->getBancoId($id);
        if ($banco == null) {
            $this->reportarError();
        } else {
            $this->bancoManager->removeBanco($id);
            return $this->redirect()->toRoute('herramientas/banco');
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
