<?php

namespace CategoriaProducto\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CategoriaProductoController extends AbstractActionController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * CategoriaProducto manager.
     * @var User\Service\CategoriaProductoManager 
     */
    protected $categoriaProductoManager;

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    protected $productoManager;

    public function __construct($entityManager, $categoriaProductoManager, $productoManager) {
        $this->entityManager = $entityManager;
        $this->categoriaProductoManager = $categoriaProductoManager;
        $this->productoManager = $productoManager;
    }

    public function indexAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarIndexAction() {
        $paginator = $this->categoriaProductoManager->getTabla();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(3);

        $categoriaProductos = $this->categoriaProductoManager->getCategoriaProductos();
        return new ViewModel([
            'categoriaProductos' => $categoriaProductos,
            'categorias_pag' => $paginator
        ]);
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        $form = $this->categoriaProductoManager->createForm();
        $paginator = $this->categoriaProductoManager->getTabla();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(4);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            // $this->categoriaProductoManager->addCategoriaProducto($data);
            $categoriaProducto = $this->categoriaProductoManager->getCategoriaProductoFromForm($form, $data);
            return $this->redirect()->toRoute('gestionEmpresa/gestionProductos/categorias');
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
        $categoriaProducto = $this->categoriaProductoManager->getCategoriaProductoId($id);
        $form = $this->categoriaProductoManager->getFormForCategoriaProducto($categoriaProducto);
        if ($form == null) {
            $this->reportarError();
        } else {
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                if ($this->categoriaProductoManager->formValid($form, $data)) {
                    $this->categoriaProductoManager->updateCategoriaProducto($categoriaProducto, $form);
                    return $this->redirect()->toRoute('gestionEmpresa/gestionProductos/categorias');
                }
            } else {
                $this->categoriaProductoManager->getFormEdited($form, $categoriaProducto);
            }
            return new ViewModel(array(
                'categoriaProducto' => $categoriaProducto,
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
        $categoriaProducto = $this->categoriaProductoManager->getCategoriaProductoId($id);
        if ($categoriaProducto == null) {
            $this->reportarError();
        } else {
            $this->productoManager->eliminarCategoriaProductos($id);
            $this->categoriaProductoManager->removeCategoriaProducto($id);
            return $this->redirect()->toRoute('categoriaProducto');
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
