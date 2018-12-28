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

    private $ivaManager;
    public function __construct($entityManager, $productoManager, $ivaManager)
    {
        $this->entityManager = $entityManager;
        $this->productoManager = $productoManager;
        $this->ivaManager = $ivaManager;
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
                ->setItemCountPerPage(10);

        $productos = $this->productoManager->getProductos();
        return new ViewModel([
            'productos' => $productos,
            'productos_pag' => $paginator,
        ]);
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }
    
    private function procesarAddAction() {
        $request = $this->getRequest();
        $tipo= $this->params()->fromRoute('tipo');
        $categoriaProductos = $this->productoManager->getCategoriaProducto(null,$tipo);
        $ivas = $this->ivaManager->getIvas();
        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            $this->productoManager->addProducto($data);
            $this->redirect()->toRoute('gestionEmpresa/gestionProductos/listado');
        }
        return new ViewModel([
            'categorias' => $categoriaProductos,
            'ivas'=>$ivas
        ]);
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id', -1);
        $tipo =  $this->params()->fromRoute('tipo');
        $producto = $this->productoManager->getProductoId($id);
        $categoriaProductos = $this->productoManager->getCategoriaProducto(null, $tipo);
        $ivas = $this->ivaManager->getIvas();

        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            $this->productoManager->updateProducto($producto, $data);
            $this->redirect()->toRoute('gestionEmpresa/gestionProductos/listado');
        }
        return new ViewModel([
            'producto' => $producto,
            'categorias' => $categoriaProductos,
            'ivas'=>$ivas
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
            return $this->redirect()->toRoute('gestionEmpresa/gestionProductos/listado');
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

    public function backupAction(){
        $this->layout()->setTemplate('layout/nulo');
        $resultado = $this->productoManager->getTabla();
        return new ViewModel([
            'resultado' => $resultado
        ]);
    }
}
