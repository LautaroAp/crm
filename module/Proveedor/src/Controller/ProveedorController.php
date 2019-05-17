<?php

namespace Proveedor\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class ProveedorController extends HuellaController
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
    protected $tipoFacturaManager;
    protected $cuentaCorrienteManager;

    public function __construct(
        $proveedorManager,
        $tipoEventosManager,
        $eventoManager,
        $personaManager,
        $tipoFacturaManager,
        $cuentaCorrienteManager
    ) {
        $this->proveedorManager = $proveedorManager;
        $this->tipoEventosManager = $tipoEventosManager;
        $this->eventoManager = $eventoManager;
        $this->personaManager = $personaManager;
        $this->tipoFacturaManager = $tipoFacturaManager;
        $this->cuentaCorrienteManager = $cuentaCorrienteManager;

    }

    public function indexAction(){
        $request = $this->getRequest();
        $this->prepararBreadcrumbs("Listado", "/listado", "Proveedores");
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
        $volver = $this->getUltimaUrl();
        $_SESSION['CATEGORIA']['TIPO'] = "PROVEEDOR";
        return new ViewModel([
            'personas' => $pag,
            'paises' => $pais,
            'provincias' => $provincia,
            'categorias' => $categorias,
            'condiciones_iva' => $condiciones_iva,
            'parametros' => $parametros,
            'total_proveedor' => $total_proveedor,
            'tipo' => $tipo,
            'volver' => $volver,
        ]);
    }

    private function getPaginator($paginator){
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int)$page)
            ->setItemCountPerPage($this->getElemsPag());
        return $paginator;
    }

    public function addAction(){
        $view = $this->processAdd();
        return $view;
    }

    private function processAdd(){
        $this->prepararBreadcrumbs("Agregar Proveedor", "/add/proveedor", "Proveedores");
        $request = $this->getRequest();
        $tipo= $this->params()->fromRoute('tipo');
        $categorias = $this->proveedorManager->getCategoriasProveedor($tipo);
        $condiciones_iva = $this->proveedorManager->getCondicionIva('iva');
        $tiposFactura = $this->tipoFacturaManager->getTipoFacturas();
        $pais = $this->proveedorManager->getPais();
        $provincia = $this->proveedorManager->getProvincia();
        // $licencia = $this->proveedorManager->getLicencia();
        $_SESSION['TIPOEVENTO']['TIPO']=$tipo;
        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            $proveedor = $this->proveedorManager->addProveedor($data);
            $id_persona = $proveedor->getPersona()->getId();
            // Evento Automatico ALTA
            $persona = $proveedor->getPersona();
            $data_alta = $this->datosAlta();
            $this->eventoManager->addEvento($data_alta, $persona); 
            $this->redirect()->toRoute('proveedores/ficha', ['action' => 'ficha', 'id' => $id_persona]);
        }
        $volver = $this->getUltimaUrl();
        return new ViewModel([
            'categorias' => $categorias,
            'condiciones_iva' => $condiciones_iva,
            'paises' => $pais,
            'provincias' => $provincia,
            'tiposFactura' => $tiposFactura,
            'tipo' => $tipo,
            'volver' => $volver,
        ]);
    }

    public function editAction(){
        $view = $this->processEdit();
        return $view;
    }

    private function processEdit(){
        $request = $this->getRequest();
        //obtener proveedor y persona desde la ruta
        $id_persona = $this->params()->fromRoute('id');
        $persona = $this->personaManager->getPersona($id_persona);
        $proveedor = $this->proveedorManager->getProveedorIdPersona($id_persona);
        $tipo= $this->params()->fromRoute('tipo');
        //preparar breadcrum con el id de la persona
        $this->prepararBreadcrumbs("Editar Proveedor", "/edit/".$tipo."/".$id_persona, "Ficha Proveedor");
        //obtener opciones para los clientes
        $categorias = $this->proveedorManager->getCategoriasProveedor($tipo);
        $condiciones_iva = $this->proveedorManager->getCondicionIva('iva');
        $pais = $this->proveedorManager->getPais();
        $provincia = $this->proveedorManager->getProvincia();
        $tiposFactura = $this->tipoFacturaManager->getTipoFacturas();
        $_SESSION['TIPOEVENTO']['TIPO']=$tipo;
        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            $this->proveedorManager->updateProveedor($proveedor, $data);
            $this->redirect()->toRoute('proveedores/ficha', ['action' => 'ficha', 'id' => $id_persona]);
        }
        $volver = $this->getUltimaUrl();
        return new ViewModel([
            'proveedor' => $proveedor,
            'persona' => $persona,
            'categorias' => $categorias,
            'condiciones_iva' => $condiciones_iva,
            'paises' => $pais,
            'provincias' => $provincia,
            'tiposFactura' => $tiposFactura,
            'tipo' => $tipo,
            'volver' => $volver,
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
        $limite = $this->getAnterior();
        $this->prepararBreadcrumbs("Ficha Proveedor", "/ficha/".$id_persona, $limite);
        $data = $this->proveedorManager->getDataFicha($id_persona);
        $_SESSION['TIPOEVENTO']['TIPO']=$persona->getTipo();
        $volver = $this->getUltimaUrl();
        return new ViewModel([
            'proveedor' => $data['proveedor'],
            'eventos' => $data['eventos'],
            'datos_adicionales' => $data['datos_adicionales'],
            'tipo_eventos' => $this->tipoEventosManager->getTipoEventos($persona->getTipo()),
            'persona' => $data['persona'],
            'volver' => $volver, 
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

    public function mostrarTransaccionesAction(){
        $this->layout()->setTemplate('layout/nulo');
        $id_persona = $this->params()->fromRoute('id');
        $persona = $this->personaManager->getPersona($id_persona);
        $transacciones = $persona->getTransacciones();
        $view = new ViewModel(['transacciones' => $transacciones, 'id_persona'=>$id_persona]);
        return $view;
    }

    public function mostrarAccionesComercialesAction(){
        $this->layout()->setTemplate('layout/nulo');
        $id_persona = $this->params()->fromRoute('id');
        $persona = $this->personaManager->getPersona($id_persona);
        $eventos = $persona->getEventos();
        $view = new ViewModel(['eventos' => $eventos]);
        return $view;
    }

    public function mostrarCuentaCorrienteAction(){
        $this->layout()->setTemplate('layout/nulo');
        $id_persona = $this->params()->fromRoute('id');
        $ventas = $this->cuentaCorrienteManager->getVentas($id_persona);
        $cobros = $this->cuentaCorrienteManager->getCobros($id_persona);
        $view = new ViewModel(['cobros' => $cobros, 'ventas'=>$ventas ,'id_persona'=>$id_persona]);
        return $view;
    }

    public function datosAlta(){
        $data = [];
        $data['id_cliente'] = '';
        $data['ejecutivo'] = $_SESSION['EJECUTIVO'];
        $data['fecha_evento'] = date('d/m/Y');;
        $data['accion_comercial'] = '40';
        $data['detalle'] = 'Registro de Alta';
        return $data;
    }
}
