<?php

namespace Clientes\Controller;

use Zend\View\Model\ViewModel;

class ClientesInactivosController extends ClientesController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $clientesManager;

    public function __construct($clientesManager) {
        $this->clientesManager = $clientesManager;
    }

    public function indexAction() {
        $this->prepararBreadcrumbs("Inactivos", "/inactivos", "Clientes");
        $request = $this->getRequest();
        $pais = $this->clientesManager->getPais();
        $provincia = $this->clientesManager->getProvincia();
        $CategoriaCliente = $this->clientesManager->getCategoriaCliente();
        if ($request->isPost()) {
            $parametros = $this->params()->fromPost();
            $_SESSION['PARAMETROS_CLIENTE_INACTIVO'] = $parametros;
        }
        if (!is_null($_SESSION['PARAMETROS_CLIENTE_INACTIVO'])) {
            $parametros = $_SESSION['PARAMETROS_CLIENTE_INACTIVO'];
        } else {
            $parametros = array();
        }
        $paginator = $this->clientesManager->getTablaFiltrado($parametros, "N");
        $total_inactivos = $this->clientesManager->getTotal();
        $pag = $this->getPaginator($paginator);
        return new ViewModel([
            'clientes' => $pag,
            'parametros' => $parametros,
            'total_inactivos' => $total_inactivos,
            'paises' => $pais,
            'provincias' => $provincia,
            'categorias' => $CategoriaCliente,
        ]);
    }

    public function getPaginator($paginator) {
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);
        return $paginator;
    }

    public function processIndex() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $parametros = $this->params()->fromPost();
        }
        $parametros = $this->params()->fromPost();
        $paginator = $this->clientesManager->getTablaFiltrado($parametros);
        $pag = $this->getPaginator($paginator);
        return new ViewModel([
            'clientes' => $pag,
        ]);
    }

}
