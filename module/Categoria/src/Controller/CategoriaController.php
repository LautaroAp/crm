<?php

/**
 * Esta clase es el controlador de la entidad Categoria.  
 * Se encarga de direccionar los datos entre las vistas y el manager
 * @author SoftHuella 
 */

namespace Categoria\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Categoria\Service\CategoriaManager;


class CategoriaController extends AbstractActionController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Categoria manager.
     * @var User\Service\CategoriaManager 
     */
    protected $categoriaManager;

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $tipoEventoManager;
    private $clientesManager;
    private $productoManager;
    private $servicioManager;
    private $licenciaManager;


    public function __construct($entityManager, $categoriaManager, $tipoEventoManager,  $clientesManager
    , $productoManager, $servicioManager, $licenciaManager) {
        $this->entityManager = $entityManager;
        $this->categoriaManager = $categoriaManager;
        $this->tipoEventoManager = $tipoEventoManager;
        $this->clientesManager= $clientesManager;
        $this->productoManager = $productoManager;
        $this->servicioManager= $servicioManager;
        $this->licenciaManager=$licenciaManager;
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
        $tipo= $this->params()->fromRoute('tipo');
        $id=$this->params()->fromRoute('id');
        $form = $this->categoriaManager->createForm();
        $paginator = $this->categoriaManager->getTabla($tipo);
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->categoriaManager->addCategoria($data, $tipo);
            return $this->redireccionar($tipo);
            
        }
        $_SESSION['CATEGORIA']['TIPO'] = $tipo;
        return new ViewModel([
            'categorias' => $paginator,
            'tipo'=>$tipo,
        ]);
    }

    private function redireccionar($tipo){
        if ($tipo=="cliente"){
            return $this->redirect()->toRoute('gestionClientes/categoriacliente', ['tipo'=>'cliente']);
        }elseif ($tipo=="producto"){
            return $this->redirect()->toRoute('gestionClientes/categoriacliente', ['tipo'=>'cliente']);
        }elseif ($tipo=="licencia"){
            return $this->redirect()->toRoute('gestionClientes/categoriacliente', ['tipo'=>'cliente']);
        }elseif ($tipo=="servicio"){
            return $this->redirect()->toRoute('gestionClientes/categoriacliente', ['tipo'=>'cliente']);
        }elseif ($tipo=="evento"){
            return $this->redirect()->toRoute('gestionClientes/categoriacliente', ['tipo'=>'cliente']);
        }elseif ($tipo=="iva"){
            return $this->redirect()->toRoute('gestionClientes/categoriacliente', ['tipo'=>'cliente']);
        }else{
            return $this->redirect()->toRoute('home');
        }
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $categoriaevento = $this->categoriaManager->getCategoriaId($id);
        $form = $this->categoriaManager->getFormForCategoria($categoriaevento);
        if ($form == null) {
            $this->reportarError();
        } else {
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                if ($this->categoriaManager->formValid($form, $data)) {
                    $this->categoriaManager->updateCategoria($categoriaevento, $data);
                    return $this->redirect()->toRoute('categoriaevento');
                }
            } else {
                $this->categoriaManager->getFormEdited($form, $categoriaevento);
            }
            return new ViewModel(array(
                'categoriaevento' => $categoriaevento,
                'form' => $form
            ));
        }
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    private function removerDependencias($tipo, $id){
        if (strtoupper($tipo)==strtoupper("cliente")){
            $this->clientesManager->eliminarCategoriaClientes($id);
        }elseif (strtoupper($tipo)==strtoupper("producto")){
            $this->productoManager-> eliminarCategoriaProductos($id);
        }elseif (strtoupper($tipo)==strtoupper("licencia")){
            $this->licenciaManager->eliminarCategoriaLicencia($id);
        }elseif (strtoupper($tipo)==strtoupper("servicio")){
            $this->servicioManager->eliminarCategoriaServicios($id);
        }elseif (strtoupper($tipo)==strtoupper("evento")){
            $this->tipoEventoManager->eliminarCategoriaEventos($id);
        //}elseif ($tipo=="iva"){
            //return $this->redirect()->toRoute('gestionClientes/categoriacliente', ['tipo'=>'cliente']);
        //}else{
            //return $this->redirect()->toRoute('home');
        }
    }

    //TERMINAR ESTOOOOO 
    private function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $categoria = $this->categoriaManager->getCategoriaId($id);
        if ($categoria == null) {
            $this->reportarError();
        } else { 
            $this->removerDependencias($categoria->getTipo(), $id);
            $this->categoriaManager->removeCategoria($categoria);
            return $this->redireccionar($categoria->getTipo());
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
