<?php

namespace CategoriaServicio\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CategoriaServicioController extends AbstractActionController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * CategoriaServicio manager.
     * @var User\Service\CategoriaServicioManager 
     */
    protected $categoriaServicioManager;

    private $servicioManager;

    public function __construct($entityManager, $categoriaServicioManager, $servicioManager) {
        $this->entityManager = $entityManager;
        $this->categoriaServicioManager = $categoriaServicioManager;
        $this->servicioManager= $servicioManager;
    }

    public function indexAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarIndexAction() {
        $paginator = $this->categoriaServicioManager->getTabla();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(3);

        $categoriaServicios = $this->categoriaServicioManager->getCategoriaServicios();
        return new ViewModel([
            'categorias_pag' => $paginator
        ]);
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        $form = $this->categoriaServicioManager->createForm();
        $paginator = $this->categoriaServicioManager->getTabla();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(4);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            // $this->categoriaServicioManager->addCategoriaServicio($data);
            $categoriaServicio = $this->categoriaServicioManager->getCategoriaServicioFromForm($form, $data);
            return $this->redirect()->toRoute('gestionEmpresa/gestionServicios/categorias');
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
        $categoriaServicio = $this->categoriaServicioManager->getCategoriaServicioId($id);
        $form = $this->categoriaServicioManager->getFormForCategoriaServicio($categoriaServicio);
        if ($form == null) {
            $this->reportarError();
        } else {
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                if ($this->categoriaServicioManager->formValid($form, $data)) {
                    $this->categoriaServicioManager->updateCategoriaServicio($categoriaServicio, $form);
                    return $this->redirect()->toRoute('categoriaServicio');
                }
            } else {
                $this->categoriaServicioManager->getFormEdited($form, $categoriaServicio);
            }
            return new ViewModel(array(
                'categoriaServicio' => $categoriaServicio,
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
        $categoriaServicio = $this->categoriaServicioManager->getCategoriaServicio($id);
        if ($categoriaServicio == null) {
            $this->reportarError();
        } else {
            $this->servicioManager->eliminarCategoriaServicio($id);
            $this->categoriaServicioManager->removeCategoriaServicio($id);
            return $this->redirect()->toRoute('categoriaServicio');
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
