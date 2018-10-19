<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Clientes\Controller;

use Zend\View\Model\ViewModel;
use DBAL\Entity\Usuario;
use DBAL\Entity\Evento;
use DBAL\Entity\CategoriaCliente;

class ClientesInactivosController extends ClientesController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $clientesInactivosManager;

    public function __construct($clientesInactivosManager) {
        $this->clientesInactivosManager = $clientesInactivosManager;
    }

    public function indexAction() {
       $request = $this->getRequest();
        if ($request->isPost()) {
            $parametros = $this->params()->fromPost();
        } 
        $parametros = $this->params()->fromPost();
        $paginator = $this->clientesInactivosManager->getFiltrados($parametros);
        $total_inactivos = $this->clientesInactivosManager->getTotal();
        $pag = $this->getPaginator($paginator);
        return new ViewModel([
            'clientes' => $pag,
            'total_inactivos'=>$total_inactivos,
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


    public function processIndex(){
        $request = $this->getRequest();
        if ($request->isPost()) {
            $parametros = $this->params()->fromPost();
        } 
        $parametros = $this->params()->fromPost();
        $paginator = $this->clientesInactivosManager->getFiltrados($parametros);
        $pag = $this->getPaginator($paginator);
        return new ViewModel([
            'clientes' => $pag,
        ]);
    }
}