<?php

namespace CategoriaLicencia\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class CategoriaLicenciaController extends HuellaController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * CategoriaLicencia manager.
     * @var User\Service\CategoriaLicenciaManager 
     */
    protected $categoriaLicenciaManager;

    private $licenciaManager;

    public function __construct($entityManager, $categoriaLicenciaManager, $licenciaManager) {
        $this->entityManager = $entityManager;
        $this->categoriaLicenciaManager = $categoriaLicenciaManager;
        $this->licenciaManager= $licenciaManager;
    }

    public function indexAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarIndexAction() {
        $paginator = $this->categoriaLicenciaManager->getTabla();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(3);

        $categoriaLicencias = $this->categoriaLicenciaManager->getCategoriaLicencias();
        return new ViewModel([
            'categorias_pag' => $paginator
        ]);
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        $form = $this->categoriaLicenciaManager->createForm();
        $paginator = $this->categoriaLicenciaManager->getTabla();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(4);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            // $this->categoriaLicenciaManager->addCategoriaLicencia($data);
            $categoriaLicencia = $this->categoriaLicenciaManager->getCategoriaLicenciaFromForm($form, $data);
            return $this->redirect()->toRoute('gestionProductosServicios/gestionLicencias/categorias');
        }
        return new ViewModel([
            'form' => $form,
            'categorias_pag' => $paginator
        ]);
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $categoriaLicencia = $this->categoriaLicenciaManager->getCategoriaLicencia($id);
        $form = $this->categoriaLicenciaManager->getFormForCategoriaLicencia($categoriaLicencia);
        if ($form == null) {
            $this->reportarError();
        } else {
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                if ($this->categoriaLicenciaManager->formValid($form, $data)) {
                    $this->categoriaLicenciaManager->updateCategoriaLicencia($categoriaLicencia, $form);
                    return $this->redirect()->toRoute('categoriaLicencia');
                }
            } else {
                $this->categoriaLicenciaManager->getFormEdited($form, $categoriaLicencia);
            }
            return new ViewModel(array(
                'categoriaLicencia' => $categoriaLicencia,
                'form' => $form
            ));
        }
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $categoriaLicencia = $this->categoriaLicenciaManager->getCategoriaLicencia($id);
        if ($categoriaLicencia == null) {
            $this->reportarError();
        } else {
            $this->licenciaManager->eliminarCategoriaLicencia($id);
            $this->categoriaLicenciaManager->removeCategoriaLicencia($id);
            return $this->redirect()->toRoute('categoriaLicencia');
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
