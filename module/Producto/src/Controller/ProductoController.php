<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Producto\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Application\Entity\Post;
use DBAL\Entity\Producto;
use Producto\Form\ProductoForm;


class ProductoController extends AbstractActionController
{
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
    
   
    public function indexAction()
    {
        return $this->procesarIndexAction();
             
    }
    
    private function procesarIndexAction(){
        $productos = $this->productoManager->getProductos();  
        
        return new ViewModel([
            'productos' => $productos
        ]);
    }
    
    public function addAction()
    {
        $view = $this->procesarAddAction();
        return $view;
       
    }
    
    private function procesarAddAction(){
       $form = $this->productoManager->createForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $producto = $this->productoManager->getProductoFromForm($form, $data);
          
            return $this->redirect()->toRoute('producto', ['action' => 'index']);
        }
        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editAction() 
    {
        $view = $this->procesarEditAction();
        return $view;
       
    }
  
    public function procesarEditAction(){
        $id = (int)$this->params()->fromRoute('id', -1);
        $producto = $this->productoManager->getProductoId($id);
 
        $form =$this->productoManager->getFormForProducto($producto);
        if ($form == null){
            $this->reportarError();
        }
        else{
            if ($this->getRequest()->isPost()) {

               $data = $this->params()->fromPost();

               if ($this->productoManager->formValid($form,$data)){
                  
                    $this->productoManager->updateProducto($producto,$form);
                    return $this->redirect()->toRoute('producto', ['action' => 'index']);
               }
            }               
         else {
                       
            $this->productoManager->getFormEdited($form, $producto);
         }
        return new ViewModel(array(
            'producto' => $producto,
            'form' => $form
        ));
        }
            
    }

    public function removeAction() 
    {
       
       $view = $this->procesarRemoveAction();
       return $view;
    }
    
    public function procesarRemoveAction(){
        $id = (int)$this->params()->fromRoute('id', -1);
        $producto = $this->productoManager->getProductoId($id);
         
        if ($producto == null) {
            $this->reportarError();
        }
        else{
            $this->productoManager->removeProducto($producto);
            return $this->redirect()->toRoute('producto', ['action' => 'index']);   
        }
    }
  
    
    
      public function viewAction() 
    {         
          return new ViewModel();
    }
    
    private function reportarError(){
        $this->getResponse()->setStatusCode(404);
        return;
    }
}
