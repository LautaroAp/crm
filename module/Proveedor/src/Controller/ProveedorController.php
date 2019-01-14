<?php

namespace Proveedor\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ProveedorController extends AbstractActionController
{

    /**
     * @var DoctrineORMEntityManager
     */
    protected $proveedorManager;

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $eventoManager;

    /**
     * @var DoctrineORMEntityManager
     */
    protected $tipoEventosManager;
    protected $personaManager;

    public function __construct(
        $proveedorManager,
        $tipoEventosManager,
        $eventoManager,
        $personaManager
    ) {
        $this->proveedorManager = $proveedorManager;
        $this->tipoEventosManager = $tipoEventosManager;
        $this->eventoManager = $eventoManager;
        $this->personaManager = $personaManager;
    }

    public function indexAction(){
        $request = $this->getRequest();
        $tipo= $this->params()->fromRoute('tipo');
        $pais = $this->proveedorManager->getPais();
        $provincia = $this->proveedorManager->getProvincia();
        $categorias = $this->proveedorManager->getCategoriasProveedor($tipo);
        $condiciones_iva = $this->proveedorManager->getCondicionIva('iva');
        $_SESSION['TIPOEVENTO']['TIPO']=$tipo;
        if ($request->isPost()) {
            $parametros = $this->params()->fromPost();
            $_SESSION['PARAMETROS_PROVEEDOR'] = $parametros;
        }
        if (!is_null($_SESSION['PARAMETROS_PROVEEDOR'])) {
            $parametros = $_SESSION['PARAMETROS_PROVEEDOR'];
        } else {
            $parametros = array();
        }
        $paginator = $this->proveedorManager->getTablaFiltrado($parametros, "S");
        $total_proveedor = $this->proveedorManager->getTotal();
        $pag = $this->getPaginator($paginator);
        return new ViewModel([
            'personas' => $pag,
            'paises' => $pais,
            'provincias' => $provincia,
            'categorias' => $categorias,
            'condiciones_iva' => $condiciones_iva,
            'parametros' => $parametros,
            'total_proveedor' => $total_proveedor,
            'tipo' => $tipo,
        ]);
    }

    private function getPaginator($paginator){
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int)$page)
            ->setItemCountPerPage(10);
        return $paginator;
    }

    public function addAction(){
        $view = $this->processAdd();
        return $view;
    }

    private function processAdd(){
        $request = $this->getRequest();
        $tipo= $this->params()->fromRoute('tipo');
        $categorias = $this->proveedorManager->getCategoriasProveedor($tipo);
        $condiciones_iva = $this->proveedorManager->getCondicionIva('iva');
        $pais = $this->proveedorManager->getPais();
        $provincia = $this->proveedorManager->getProvincia();
        $licencia = $this->proveedorManager->getLicencia();
        $_SESSION['TIPOEVENTO']['TIPO']=$tipo;
        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            $proveedor = $this->proveedorManager->addProveedor($data);
            $id_persona = $proveedor->getPersona()->getId();
            $this->redirect()->toRoute('gestionProveedores/listado', ['id' => $id_persona]);
        }
        return new ViewModel([
            'categorias' => $categorias,
            'condiciones_iva' => $condiciones_iva,
            'profesiones' => $profesion,
            'paises' => $pais,
            'provincias' => $provincia,
            'licencias' => $licencia,
            'tipo' => $tipo,
        ]);
    }

    public function editAction(){
        $view = $this->processEdit();
        return $view;
    }

    private function processEdit(){
        $request = $this->getRequest();
        $tipo= $this->params()->fromRoute('tipo');
        $categorias = $this->proveedorManager->getCategoriasProveedor($tipo);
        $condiciones_iva = $this->proveedorManager->getCondicionIva('iva');
        $pais = $this->proveedorManager->getPais();
        $provincia = $this->proveedorManager->getProvincia();
        $licencia = $this->proveedorManager->getLicencia();
        $_SESSION['TIPOEVENTO']['TIPO']=$tipo;
        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            $id_persona = $this->params()->fromRoute('id');
            $persona = $this->personaManager->getPersona($id_persona);
            $proveedor = $this->proveedorManager->getProveedorIdPersona($id_persona);
            $this->proveedorManager->updateProveedor($proveedor, $data);
            $this->redirect()->toRoute('proveedores/ficha', ['action' => 'ficha', 'id' => $id_persona]);
        } else {
            $id_persona = $this->params()->fromRoute('id');
            $persona = $this->personaManager->getPersona($id_persona);
            $proveedor = $this->proveedorManager->getProveedorIdPersona($id_persona);
        }
        return new ViewModel([
            'proveedor' => $proveedor,
            'persona' => $persona,
            'mensaje' => $mensaje,
            'categorias' => $categorias,
            'condiciones_iva' => $condiciones_iva,
            'profesiones' => $profesion,
            'paises' => $pais,
            'provincias' => $provincia,
            'licencias' => $licencia,
            'tipo' => $tipo,
        ]);
    }

    
    public function modificarEstadoAction(){
        $view = $this->processModificarEstado();
        return $view;
    }

    private function processModificarEstado(){
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $id = $this->params()->fromRoute('id');
            $this->proveedorManager->modificarEstado($id);
            $this->redirect()->toRoute('gestionProveedores/listado');
        } else {
            return new ViewModel();
        }
    }

    public function fichaAction(){
        $id_persona = (int)$this->params()->fromRoute('id', -1);
        $persona = $this->personaManager->getPersona($id_persona);
        $data = $this->proveedorManager->getDataFicha($id_persona);
        $_SESSION['TIPOEVENTO']['TIPO']=$persona->getTipo();
        return new ViewModel([
            'proveedor' => $data['proveedor'],
            'eventos' => $data['eventos'],
            'tipo_eventos' => $this->tipoEventosManager->getTipoEventos($persona->getTipo()),
            'persona' => $data['persona']
        ]);
    }

    public function eliminaEventosAction(){
        $this->layout()->setTemplate('layout/nulo');
        $id = $this->params()->fromRoute('id');
        $this->eventoManager->removeEvento($id);
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setTemplate('proveedor/proveedor/json.phtml');
        return $view;
    }

    public function getProvinciasAction() {
        $this->layout()->setTemplate('layout/nulo');
        $id_pais = $this->params()->fromRoute('id');
        $provs = $this->proveedorManager->getProvincias($id_pais);
        $view = new ViewModel(['provincias' => $provs]);
        return $view;
    }

    public function backupAction(){
        $this->layout()->setTemplate('layout/nulo');
        $resultado = $this->proveedorManager->getListaProveedores();
        return new ViewModel([
            'resultado' => $resultado
        ]);
    }


}
