<?php

namespace Empresa\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class EmpresaController extends HuellaController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Empresa manager.
     * @var User\Service\EmpresaManager 
     */
    protected $empresaManager;
    private $monedaManager;

    public function __construct($entityManager, $empresaManager, $monedaManager)
    {
        $this->entityManager = $entityManager;
        $this->empresaManager = $empresaManager;
        $this->monedaManager = $monedaManager;
    }

    public function indexAction() {
        return $this->procesarIndexAction();
    }

    private function procesarIndexAction() {
        $this->prepararBreadcrumbs("Configuracion", "/configuracion", "Empresa");
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
        $empresa = $this->getEmpresa();
        $this->prepararBreadcrumbs("Editar Empresa", "/editar/".$empresa->getId(), "Configuracion");

        $form = $this->empresaManager->getFormForEmpresa($empresa);
        $monedas=$this->monedaManager->getMonedas();
        if ($form == null) {
            $this->getResponse()->setStatusCode(404);
        } else {
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                if ($this->empresaManager->formValid($form, $data)) {
                    $this->empresaManager->updateEmpresa($empresa, $form);
                    return $this->redirect()->toRoute('gestionEmpresa/configuracion');
                }
            } else {
                $this->empresaManager->getFormEdited($form, $empresa);
            }
        return new ViewModel(array(
            'empresa' => $empresa,
            'form' => $form,
            'monedas'=>$monedas
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

    private function getEmpresa(){
        $empresas = $this->empresaManager->getEmpresas();
        $empresa = $empresas[0];
        return $empresa;
    }

    public function backupAction() {
        $this->layout()->setTemplate('layout/nulo');
        $empresa= $this->getEmpresa();
        return new ViewModel([
            'empresa' => $empresa
        ]);
    }
}
