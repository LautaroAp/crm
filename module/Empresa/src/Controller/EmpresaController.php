<?php

namespace Empresa\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class EmpresaController extends AbstractActionController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Empresa manager.
     * @var User\Service\EmpresaManager 
     */
    protected $empresaManager;
    private $vencimientos;

    public function __construct($entityManager, $empresaManager) {
        $this->entityManager = $entityManager;
        $this->empresaManager = $empresaManager;
    }

    public function indexAction() {
        return $this->procesarIndexAction();
    }

    private function procesarIndexAction() {
        $empresas = $this->empresaManager->getEmpresas();
        $empresa = $empresas[0];
        return new ViewModel([
            'empresa' => $empresa
        ]);
    }

    public function editAction() {
        return $this->procesarEditAction();
    }

    public function procesarEditAction() {
        $empresas = $this->empresaManager->getEmpresas();
        $empresa = $empresas[0];
        $form = $this->empresaManager->getFormForEmpresa($empresa);
        if ($form == null) {
            $this->getResponse()->setStatusCode(404);
        } else {
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                if ($this->empresaManager->formValid($form, $data)) {
                    $this->empresaManager->updateEmpresa($empresa, $form);
                    return $this->redirect()->toRoute('empresa', ['action' => 'index']);
                }
            } else {
                $this->empresaManager->getFormEdited($form, $empresa);
            }
            return new ViewModel(array(
                'empresa' => $empresa,
                'form' => $form,
            ));
        }
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $empresa = $this->empresaManager->getEmpresaId($id);

        if ($empresa == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        } else {
            $this->empresaManager->removeEmpresa($empresa);
            return $this->redirect()->toRoute('application', ['action' => 'view']);
        }
    }

    public function viewAction() {
        return new ViewModel();
    }
}
