<?php

namespace Clientes\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ClientesController extends AbstractActionController {

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

    public function __construct($clientesManager, $tipoEventosManager, $eventoManager,
     $personaManager) {
        $this->clientesManager = $clientesManager;
        $this->tipoEventosManager = $tipoEventosManager;
        $this->eventoManager = $eventoManager;
        $this->personaManager = $personaManager;
    }

    public function indexAction() {
        $request = $this->getRequest();
        $pais = $this->clientesManager->getPais();
        $provincia = $this->clientesManager->getProvincia();
        $CategoriaCliente = $this->clientesManager->getCategoriaCliente();
        if ($request->isPost()) {
            $parametros = $this->params()->fromPost();
            $_SESSION['PARAMETROS_CLIENTE'] = $parametros;
        }
        if (!is_null($_SESSION['PARAMETROS_CLIENTE'])) {
            $parametros = $_SESSION['PARAMETROS_CLIENTE'];
        } else {
            $parametros = array();
        }
        $paginator = $this->clientesManager->getTablaFiltrado($parametros);
        $total_clientes = $this->clientesManager->getTotal();
        $pag = $this->getPaginator($paginator);
        return new ViewModel([
            'clientes' => $pag,
            'paises' => $pais,
            'provincias' => $provincia,
            'categorias' => $CategoriaCliente,
            'parametros' => $parametros,
            'total_clientes' => $total_clientes,
        ]);
    }

    private function getPaginator($paginator) {
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);
        return $paginator;
    }

    public function addAction() {
        $view = $this->processAdd();
        return $view;
    }

    private function processAdd() {
        $request = $this->getRequest();
        $mensaje = "";
        $CategoriaCliente = $this->clientesManager->getCategoriaCliente();
        $ProfesionCliente = $this->clientesManager->getProfesionCliente();
        $pais = $this->clientesManager->getPais();
        $provincia = $this->clientesManager->getProvincia();
        $licencia = $this->clientesManager->getLicencia();
        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            $this->clientesManager->addCliente($data);
            $this->redirect()->toRoute('clientes');
        }
        return new ViewModel([
            'mensaje' => $mensaje,
            'categorias' => $CategoriaCliente,
            'profesiones' => $ProfesionCliente,
            'paises' => $pais,
            'provincias' => $provincia,
            'licencias' => $licencia,
        ]);
    }

    public function editAction() {
        $view = $this->processEdit();
        return $view;
    }

    private function processEdit() {
        $request = $this->getRequest();
        $mensaje = "";
        $CategoriaCliente = $this->clientesManager->getCategoriaCliente();
        $ProfesionCliente = $this->clientesManager->getProfesionCliente();
        $pais = $this->clientesManager->getPais();
        $provincia = $this->clientesManager->getProvincia();
        $licencia = $this->clientesManager->getLicencia();
        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            $cliente = $this->clientesManager->updateCliente($data);
            $id_persona = $this->params()->fromRoute('id');
            $cliente_ficha = $this->clientesManager->getClienteIdPersona($id_persona);
            $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $cliente_ficha->getId()]);
        } else {
            $id_persona = $this->params()->fromRoute('id');
            $persona = $this->personaManager->getPersona($id_persona);
            $cliente = $this->clientesManager->getClienteIdPersona($id_persona);
        }
        return new ViewModel([
            'cliente' => $cliente,
            'persona'=>$persona,
            'mensaje' => $mensaje,
            'categorias' => $CategoriaCliente,
            'profesiones' => $ProfesionCliente,
            'paises' => $pais,
            'provincias' => $provincia,
            'licencias' => $licencia,
        ]);
    }

    public function deleteAction() {
        $view = $this->processDelete();
        return $view;
    }

    private function processDelete() {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $id = $this->params()->fromRoute('id');
            $this->clientesManager->deleteCliente($id);
            $this->redirect()->toRoute('clientes');
        } else {
            return new ViewModel();
        }
    }

    public function modificarEstadoAction() {
        $view = $this->processModificarEstado();
        return $view;
    }

    private function processModificarEstado() {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $id = $this->params()->fromRoute('id');
            $this->clientesManager->modificarEstado($id);
            $this->redirect()->toRoute('clientes');
        } else {
            return new ViewModel();
        }
    }

    public function fichaAction() {
        $id_persona = (int) $this->params()->fromRoute('id', -1);
        $data = $this->clientesManager->getDataFicha($id_persona);
        return new ViewModel([
            'cliente' => $data['cliente'],
            'usuarios' => $data['usuarios'],
            'eventos' => $data['eventos'],
            'tipo_eventos' => $this->tipoEventosManager->getTipoEventos(),
            'persona' =>$data['persona']
        ]);
    }

    public function eliminaEventosAction() {
        $this->layout()->setTemplate('layout/nulo');
        $id = $this->params()->fromRoute('id');
        $this->eventoManager->removeEvento($id);
        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setTemplate('clientes/clientes/json.phtml');
        return $view;
    }

    function getProvinciasAction() {
        $this->layout()->setTemplate('layout/nulo');
        $id_pais = $this->params()->fromRoute('id');
        $provs = $this->clientesManager->getProvincias($id_pais);
        $view = new ViewModel([
            'provincias' => $provs]);
        return $view;
    }

}
