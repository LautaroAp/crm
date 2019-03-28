<?php

namespace Clientes\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class ClientesController extends HuellaController
{

    /**
     * @var DoctrineORMEntityManager
     */
    protected $clientesManager;

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
        $clientesManager,
        $tipoEventosManager,
        $eventoManager,
        $personaManager
    ) {
        $this->clientesManager = $clientesManager;
        $this->tipoEventosManager = $tipoEventosManager;
        $this->eventoManager = $eventoManager;
        $this->personaManager = $personaManager;
    }

    public function indexAction(){
        $this->reiniciarParametros("TRANSACCIONES");
        $this->prepararBreadcrumbs("Listado", "/listado", "Clientes");
        $request = $this->getRequest();
        $tipo= $this->params()->fromRoute('tipo');
        $pais = $this->clientesManager->getPais();
        $provincia = $this->clientesManager->getProvincia();
        $categorias = $this->clientesManager->getCategoriasCliente($tipo);
        $condiciones_iva = $this->clientesManager->getCondicionIva('iva');
        if ($request->isPost()) {
            $parametros = $this->params()->fromPost();
            $_SESSION['PARAMETROS_CLIENTE'] = $parametros;
        }
        if (!is_null($_SESSION['PARAMETROS_CLIENTE'])) {
            $parametros = $_SESSION['PARAMETROS_CLIENTE'];
        } else {
            $parametros = array();
        }
        $paginator = $this->clientesManager->getTablaFiltrado($parametros, "S");
        $total_clientes = $this->clientesManager->getTotal();
        $pag = $this->getPaginator($paginator);
        $volver = $this->getUltimaUrl();
        return new ViewModel([
            'personas' => $pag,
            'paises' => $pais,
            'provincias' => $provincia,
            'categorias' => $categorias,
            'condiciones_iva' => $condiciones_iva,
            'parametros' => $parametros,
            'total_clientes' => $total_clientes,
            'tipo' => $tipo,
            'volver'=>$volver,
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
        $this->prepararBreadcrumbs("Agregar Cliente", "/add/cliente", "Clientes");
        $request = $this->getRequest();
        $tipo= $this->params()->fromRoute('tipo');
        $categorias = $this->clientesManager->getCategoriasCliente($tipo);
        $condiciones_iva = $this->clientesManager->getCondicionIva('iva');
        $profesion = $this->clientesManager->getProfesion();
        $pais = $this->clientesManager->getPais();
        $provincia = $this->clientesManager->getProvincia();
        $licencia = $this->clientesManager->getLicencia();
        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            $cliente = $this->clientesManager->addCliente($data);
            $id_persona = $cliente->getPersona()->getId();
            $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $id_persona]);
        }
        $volver = $this->getUltimaUrl();
        return new ViewModel([
            'categorias' => $categorias,
            'condiciones_iva' => $condiciones_iva,
            'profesiones' => $profesion,
            'paises' => $pais,
            'provincias' => $provincia,
            'licencias' => $licencia,
            'tipo' => $tipo,
            'volver'=>$volver,
        ]);
    }

    public function editAction(){
        $view = $this->processEdit();
        return $view;
    }

    private function processEdit(){
        $request = $this->getRequest();
        //obtener cliente y persona desde la ruta
        $id_persona = $this->params()->fromRoute('id');
        $persona = $this->personaManager->getPersona($id_persona);
        $cliente = $this->clientesManager->getClienteIdPersona($id_persona);
        $tipo= $this->params()->fromRoute('tipo');
        //preparar breadcrum con el id de la persona
        $this->prepararBreadcrumbs("Editar Cliente", "/edit/".$tipo."/".$id_persona, "Ficha Cliente");
        //obtener opciones para los clientes
        $categorias = $this->clientesManager->getCategoriasCliente($tipo);
        $condiciones_iva = $this->clientesManager->getCondicionIva('iva');
        $profesion = $this->clientesManager->getProfesion();
        $pais = $this->clientesManager->getPais();
        $provincia = $this->clientesManager->getProvincia();
        $licencia = $this->clientesManager->getLicencia(); 
        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            $this->clientesManager->updateCliente($cliente, $data);
            $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $id_persona]);
        } 
        $volver = $this->getUltimaUrl();
        return new ViewModel([
            'cliente' => $cliente,
            'persona' => $persona,
            'categorias' => $categorias,
            'condiciones_iva' => $condiciones_iva,
            'profesiones' => $profesion,
            'paises' => $pais,
            'provincias' => $provincia,
            'licencias' => $licencia,
            'tipo' => $tipo,
            'volver'=>$volver,
        ]);
    }

    public function deleteAction() {
        $view = $this->processDelete();
        return $view;
    }

    private function processDelete(){
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $id = $this->params()->fromRoute('id');
            $this->clientesManager->deleteCliente($id);
            $this->redirect()->toRoute('clientes/listado');
        } else {
            return new ViewModel();
        }
    }

    public function modificarEstadoAction(){
        $view = $this->processModificarEstado();
        return $view;
    }

    private function processModificarEstado(){
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $id = $this->params()->fromRoute('id');
            $this->clientesManager->modificarEstado($id);
            $this->redirect()->toRoute('gestionClientes/listado');
        } else {
            return new ViewModel();
        }
    }

    public function fichaAction(){
        $this->reiniciarParametros("TRANSACCIONES");
        $id_persona = (int)$this->params()->fromRoute('id', -1);
        $persona = $this->personaManager->getPersona($id_persona);
        $limite = $this->getAnterior();
        $this->prepararBreadcrumbs("Ficha Cliente", "/ficha/".$id_persona, $limite);
        $data = $this->clientesManager->getDataFicha($id_persona);
        $_SESSION['TIPOEVENTO']['TIPO']=$persona->getTipo();
        $volver = $this->getUltimaUrl();
        return new ViewModel([
            'cliente' => $data['cliente'],
            'usuarios' => $data['usuarios'],
            'eventos' => $data['eventos'],
            'tipo_eventos' => $this->tipoEventosManager->getTipoEventos($persona->getTipo()),
            'persona' => $data['persona'],
            'volver'=>$volver,
        ]);
    }

    public function eliminaEventosAction(){
        $this->layout()->setTemplate('layout/nulo');
        $id = $this->params()->fromRoute('id');
        $this->eventoManager->removeEvento($id);
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setTemplate('clientes/clientes/json.phtml');
        return $view;
    }

    public function getProvinciasAction() {
        $this->layout()->setTemplate('layout/nulo');
        $id_pais = $this->params()->fromRoute('id');
        $provs = $this->clientesManager->getProvincias($id_pais);
        $view = new ViewModel(['provincias' => $provs]);
        return $view;
    }

    public function mostrarTransaccionesAction(){
        $this->layout()->setTemplate('layout/nulo');
        $id_persona = $this->params()->fromRoute('id');
        $transacciones = $this->clientesManager->getTransacciones($id_persona);
        $view = new ViewModel(['transacciones' => $transacciones]);
        return $view;
    }

    public function mostrarAccionesComercialesAction(){
        $this->layout()->setTemplate('layout/nulo');
        $id_persona = $this->params()->fromRoute('id');
        $cliente = $this->clientesManager->getClienteIdPersona($id_persona);
        $eventos = $cliente->getPersona()->getEventos();
        $view = new ViewModel(['eventos' => $eventos]);
        return $view;
    }

    public function backupAction(){
        $this->layout()->setTemplate('layout/nulo');
        $resultado = $this->clientesManager->getListaClientes();
        return new ViewModel([
            'resultado' => $resultado
        ]);
    }


}
