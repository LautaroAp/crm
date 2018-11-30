<?php

namespace Ejecutivo\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Ejecutivo\Form\EjecutivoForm;

class EjecutivoController extends AbstractActionController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    //private $ejecutivoManager;

    public function __construct($entityManager, $ejecutivoManager) {
        $this->entityManager = $entityManager;
        $this->ejecutivoManager = $ejecutivoManager;
    }

    public function getEntityManager() {
        if (null === $this->entityManager) {
            $this->entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->entityManager;
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
        $form = new EjecutivoForm('create', $this->entityManager);
        $paginator = $this->ejecutivoManager->getTabla();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            $ejecutivo = $this->ejecutivoManager->getEjecutivoFromForm($form, $data);
            return $this->redirect()->toRoute('ejecutivos');
            
        }
        return new ViewModel([
            'form' => $form,
            'ejecutivos' => $paginator
        ]);
    }

    public function viewAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        // Find a user with such ID.
        $ejecutivo = $this->ejecutivoManager->recuperarEjecutivo($id);
        if ($ejecutivo == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        return new ViewModel([
            'ejecutivo' => $ejecutivo
        ]);
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }
    
    private function procesarEditAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);  
        $ejecutivo = $this->ejecutivoManager->recuperarEjecutivo($id);

       if ($ejecutivo == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
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
                    'apellido'=>$data['apellido'],
                    'nombre'=>$data['nombre'],
                    'mail'=>$data['mail'],
                    'usuario'=>$data['usuario'],
                    'clave'=>$data['clave'],               
                ));
        }
        return new ViewModel(array(
            'ejecutivo' => $ejecutivo,
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

}
