<?php

namespace Proveedor\Controller;

use Zend\View\Model\ViewModel;

class ProveedorInactivoController extends ProveedorController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $proveedorManager;

    public function __construct($proveedorManager) {
        $this->proveedorManager = $proveedorManager;
    }

    public function indexAction() {
        $request = $this->getRequest();
        $pais = $this->proveedorManager->getPais();
        $provincia = $this->proveedorManager->getProvincia();
        if ($request->isPost()) {
            $parametros = $this->params()->fromPost();
            $_SESSION['PARAMETROS_PROVEEDOR_INACTIVO'] = $parametros;
        }
        if (!is_null($_SESSION['PARAMETROS_PROVEEDOR_INACTIVO'])) {
            $parametros = $_SESSION['PARAMETROS_PROVEEDOR_INACTIVO'];
        } else {
            $parametros = array();
        }
        $paginator = $this->proveedorManager->getTablaFiltrado($parametros, "N");
        $total_inactivos = $this->proveedorManager->getTotal();
        $pag = $this->getPaginator($paginator);
        return new ViewModel([
            'proveedores' => $pag,
            'parametros' => $parametros,
            'total_inactivos' => $total_inactivos,
            'paises' => $pais,
            'provincias' => $provincia,
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
        $paginator = $this->proveedorManager->getTablaFiltrado($parametros);
        $pag = $this->getPaginator($paginator);
        return new ViewModel([
            'proveedores' => $pag,
        ]);
    }

}
