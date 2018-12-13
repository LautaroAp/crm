<?php

namespace Producto\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ProductoController extends AbstractActionController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Producto manager.
     * @var User\Service\ProductoManager 
     */
    protected $productoManager;

    public function __construct($entityManager, $productoManager)
    {
        $this->entityManager = $entityManager;
        $this->productoManager = $productoManager;
    }

    public function indexAction() {
        return $this->procesarIndexAction();
    }

    private function procesarIndexAction() {
        $paginator = $this->productoManager->getTabla();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(3);

        $productos = $this->productoManager->getProductos();
        return new ViewModel([
            'productos' => $productos,
            'productos_pag' => $paginator
        ]);
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }
    
    private function procesarAddAction() {
        $request = $this->getRequest();
        $categoriaProductos = $this->productoManager->getCategoriaProducto();
        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            $this->productoManager->addProducto($data);
            $this->redirect()->toRoute('gestionEmpresa/gestionProductos/listado');
        }
        return new ViewModel([
            'categorias' => $categoriaProductos
        ]);
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id', -1);
        $producto = $this->productoManager->getProductoId($id);
        $categoriaProductos = $this->productoManager->getCategoriaProducto();

        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            $this->productoManager->updateProducto($producto, $data);
            $this->redirect()->toRoute('gestionEmpresa/gestionProductos/listado');
        }
        return new ViewModel([
            'producto' => $producto,
            'categorias' => $categoriaProductos,
        ]);
    }

    public function removeAction() 
    {
       $view = $this->procesarRemoveAction();
       return $view;
    }

    public function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $producto = $this->productoManager->getProductoId($id);
        if ($producto == null) {
            $this->reportarError();
        } else {
            $this->productoManager->removeProducto($producto);
            return $this->redirect()->toRoute('producto', ['action' => 'index']);
        }
    }
    
      public function viewAction() 
    {         
          return new ViewModel();
    }

    private function reportarError() {
        $this->getResponse()->setStatusCode(404);
        return;
    }

}
