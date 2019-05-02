<?php

namespace Empresa\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class EmpresaController extends HuellaController
{

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

    public function indexAction()
    {
        return $this->procesarIndexAction();
    }

    private function procesarIndexAction()
    {
        $this->prepararBreadcrumbs("Configuracion", "/configuracion", "Empresa");
        $empresas = $this->empresaManager->getEmpresas();
        $empresa = $empresas[0];
        $volver = $this->getUltimaUrl();
        return new ViewModel([
            'empresa' => $empresa,
            'volver'=>$volver,
        ]);
    }

    public function editAction()
    {
        return $this->procesarEditAction();
    }

    public function procesarEditAction()
    {
        $empresa = $this->empresaManager->getEmpresa();
        $this->prepararBreadcrumbs("Editar Empresa", "/editar/" . $empresa->getId(), "Configuracion");
        $form = $this->empresaManager->getFormForEmpresa($empresa);
        $monedas = $this->monedaManager->getMonedas();
        $condiciones_iva = $this->empresaManager->getCondicionIva('iva');
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->empresaManager->updateEmpresa($empresa, $data);
            return $this->redirect()->toRoute('gestionEmpresa/configuracion');
        }
        $volver = $this->getUltimaUrl();
        return new ViewModel(array(
            'empresa' => $empresa,
            'form' => $form,
            'monedas' => $monedas,
            'condiciones_iva' => $condiciones_iva,
            'volver' => $volver,
        ));
    }

    public function removeAction()
    {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        $empresa = $this->empresaManager->getEmpresaId($id);

        if ($empresa == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        } else {
            $this->empresaManager->removeEmpresa($empresa);
            return $this->redirect()->toRoute('application', ['action' => 'view']);
        }
    }

    public function viewAction()
    {
        return new ViewModel();
    }

    
    public function backupAction()
    {
        $this->layout()->setTemplate('layout/nulo');
        $empresa = $this->empresaManager->getEmpresa();
        return new ViewModel([
            'empresa' => $empresa
        ]);
    }
}
