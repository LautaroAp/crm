<?php

/**
 * Esta clase es el controlador de la entidad Categoria.  
 * Se encarga de direccionar los datos entre las vistas y el manager
 * @author SoftHuella 
 */

namespace Categoria\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;
use Categoria\Service\CategoriaManager;


class CategoriaController extends HuellaController {

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
    private $proveedorManager;
    private $productoManager;
    private $servicioManager;
    private $licenciaManager;


    public function __construct($entityManager, $categoriaManager, $tipoEventoManager,  $clientesManager
    , $productoManager, $servicioManager, $licenciaManager, $proveedorManager) {
        $this->entityManager = $entityManager;
        $this->categoriaManager = $categoriaManager;
        $this->tipoEventoManager = $tipoEventoManager;
        $this->clientesManager= $clientesManager;
        $this->productoManager = $productoManager;
        $this->servicioManager= $servicioManager;
        $this->licenciaManager=$licenciaManager;
        $this->proveedorManager=$proveedorManager;
    }

    public function indexAction() {
        $view = $this->procesarAdd();
        return $view;
    }

    public function addAction() {
        $view = $this->procesarAdd();
        return $view;
    }

    private function getRuta($tipo){
        if ($tipo=="cliente"){
            return "/categorias/cliente";
        }
        elseif($tipo=="proveedor"){
            return "/categorias/proveedor";
        }
        elseif($tipo=="licencia"){
            return "/categorias/licencia";
        }
        elseif($tipo=="producto"){
            return "/categorias/producto";
        }
        elseif($tipo=="servicio"){
            return "/categorias/servicio";
        }
        elseif($tipo=="evento"){
            return "/categorias/evento";
        }
        elseif($tipo=="iva"){
            return "/categorias/iva";
        }
    }


    private function procesarAdd() {
        $tipo= $this->params()->fromRoute('tipo');
        $url = $this->getRuta($tipo);
        $this->prepararBreadcrumbs("Categorias", $url);
        $id=$this->params()->fromRoute('id');
        $form = $this->categoriaManager->createForm();
        $paginator = $this->categoriaManager->getTabla($tipo);
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(3);
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

    public function editAction() {
        $view = $this->procesarEdit();
        return $view;
    }

    private function procesarEdit(){
        $request = $this->getRequest();
        $tipo= $this->params()->fromRoute('tipo');
        $id = (int) $this->params()->fromRoute('id', -1);
        $url = $this->getRuta($tipo);
        $this->prepararBreadcrumbs("Editar","/edit/".$id);
        $categoriaevento = $this->categoriaManager->getCategoriaId($id);
        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            $this->categoriaManager->updateCategoria($categoriaevento, $data);
            return $this->redireccionar($tipo);
        }
        $_SESSION['CATEGORIA']['TIPO'] = $tipo;
        return new ViewModel([
            'categoriaevento' => $categoriaevento,
            'tipo'=>$tipo,
        ]);
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    private function removerDependencias($tipo, $id){
        if (strtoupper($tipo)==strtoupper("cliente")){
            $this->clientesManager->eliminarCategoriaClientes($id);
        }elseif (strtoupper($tipo)==strtoupper("proveedor")){
            $this->proveedorManager-> eliminarCategoriaProveedor($id);
        }elseif (strtoupper($tipo)==strtoupper("producto")){
            $this->productoManager-> eliminarCategoriaProductos($id);
        }elseif (strtoupper($tipo)==strtoupper("licencia")){
            $this->licenciaManager->eliminarCategoriaLicencia($id);
        }elseif (strtoupper($tipo)==strtoupper("servicio")){
            $this->servicioManager->eliminarCategoriaServicios($id);
        }elseif (strtoupper($tipo)==strtoupper("evento")){
            $this->tipoEventoManager->eliminarCategoriaEventos($id);
        }elseif (strtoupper($tipo)==strtoupper("iva")){
            $this->clientesManager->eliminarCondicionIva($id);
        }
    }

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

    private function redireccionar($tipo){
        if (strtoupper($tipo)==strtoupper("cliente")){
            return $this->redirect()->toRoute('gestionClientes/categorias', ['tipo'=>'cliente']);
        }elseif (strtoupper($tipo)==strtoupper("proveedor")){
            return $this->redirect()->toRoute('gestionProveedores/categorias', ['tipo'=>'proveedor']);
        }elseif (strtoupper($tipo)==strtoupper("producto")){
            return $this->redirect()->toRoute('gestionProductosServicios/gestionProductos/categoriaproducto', ['tipo'=>'producto']);
        }elseif (strtoupper($tipo)==strtoupper("licencia")){
            return $this->redirect()->toRoute('gestionProductosServicios/gestionLicencias/categorialicencia', ['tipo'=>'licencia']);
        }elseif (strtoupper($tipo)==strtoupper("servicio")){
            return $this->redirect()->toRoute('gestionProductosServicios/gestionServicios/categoriaservicio', ['tipo'=>'servicio']);
        }elseif (strtoupper($tipo)==strtoupper("evento")){
            return $this->redirect()->toRoute('gestionClientes/gestionEventosClientes/categorias', ['tipo'=>'evento']);
        }elseif (strtoupper($tipo)==strtoupper("iva")){
            return $this->redirect()->toRoute('herramientas/condicioniva', ['tipo'=>'iva']);
        }else{
            return $this->redirect()->toRoute('home');
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
