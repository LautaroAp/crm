<?php

namespace Ejecutivo\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;
use Ejecutivo\Form\EjecutivoForm;

class EjecutivoController extends HuellaController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;
    protected $ejecutivoManager;
    protected $personaManager;

    public function __construct($entityManager, $ejecutivoManager, $personaManager) {
        $this->entityManager = $entityManager;
        $this->ejecutivoManager = $ejecutivoManager;
        $this->personaManager = $personaManager;
    }

 
   
    public function getEntityManager() {
        if (null === $this->entityManager) {
            $this->entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->entityManager;
    }

    public function indexAction() {
        $view = $this->procesarIndexAction();
        return $view;
    }
    public function procesarIndexAction(){
        $this->prepararBreadcrumbs("Ejecutivos", "/ejecutivos", "Herramientas");
        $paginator = $this->ejecutivoManager->getTabla();
        $pag = $this->getPaginator($paginator);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form = new EjecutivoForm('create', $this->entityManager);
            $this->ejecutivoManager->getEjecutivoFromForm($form, $data);
            return $this->redirect()->toRoute('ejecutivos');
        }
        return new ViewModel([
            'ejecutivos' => $pag,
            ]);
    }

    protected function getPaginator($paginator) {
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);
        return $paginator;
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        $form = new EjecutivoForm('create', $this->entityManager);
        $paginator = $this->ejecutivoManager->getTabla();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->ejecutivoManager->getEjecutivoFromForm($form, $data);
            return $this->redirect()->toRoute('ejecutivos');
        }
        return new ViewModel([
            'form' => $form,
            'ejecutivos' => $paginator
        ]);
    }
    
    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }
    
    private function procesarEditAction(){
        $id = (int)$this->params()->fromRoute('id', -1);  
        $ejecutivo = $this->ejecutivoManager->recuperarEjecutivo($id);
        if ($ejecutivo == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $limite = "";
        if ($ejecutivo->isActivo()){
            $limite= "Ejecutivos";
        }
        else{
            $limite = "Inactivos";
        }
        $this->prepararBreadcrumbs("Editar Ejecutivo", "/edit/".$id, $limite);

        $form = new EjecutivoForm('update', $this->entityManager, $ejecutivo);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();
                $this->ejecutivoManager->updateEjecutivo($ejecutivo, $data);
                return $this->redirect()->toRoute('ejecutivos');
            }
        } else {
            $data = $this->ejecutivoManager->getData($id);
            $form->setData(array(
                    'id_ejecutivo'=>$id,
                    'nombre'=>$data['nombre'],
                    'mail'=>$data['mail'],
                    'usuario'=>$data['usuario'],
                    'clave'=>$data['clave'],               
                ));
        }
        return new ViewModel(array(
            'ejecutivo' => $ejecutivo,
            'persona'=>$ejecutivo->getPersona(),
            'form' => $form
        ));
    }

    private function procesarDeleteAction() {
        if (!$this->getRequest()->isPost()) {
            $id = $this->params()->fromRoute('id');
            $ejecutivo = $this->ejecutivoManager->recuperarEjecutivo($id);
            if ($ejecutivo == null) {
                $this->getResponse()->setStatusCode(404);
                return;
            }
            $this->ejecutivoManager->removeEjecutivo($ejecutivo);
            $this->redirect()->toRoute('ejecutivos');
        } else {
            $view = new ViewModel();
            return $view;
        }
    }

    public function deleteAction() {
        $view = $this->procesarDeleteAction();
        return $view;
    }

    public function backupAction() {
        $this->layout()->setTemplate('layout/nulo');
        $resultado = $this->ejecutivoManager->getEjecutivos();
        return new ViewModel([
            'resultado' => $resultado
        ]);
    }
}
