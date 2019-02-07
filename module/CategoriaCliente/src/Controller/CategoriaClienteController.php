<?php

namespace CategoriaCliente\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class CategoriaClienteController extends HuellaController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * CategoriaCliente manager.
     * @var User\Service\CategoriaClienteManager 
     */
    protected $categoriaclienteManager;

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    protected $clientesManager;

    public function __construct($entityManager, $categoriaclienteManager, $clientesManager) {
        $this->entityManager = $entityManager;
        $this->categoriaclienteManager = $categoriaclienteManager;
        $this->clientesManager = $clientesManager;
    }

    public function indexAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarIndexAction() {
        $paginator = $this->categoriaclienteManager->getTabla();
        $mensaje = "";

        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(3);

        $categoriaclientes = $this->categoriaclienteManager->getCategoriaClientes();
        return new ViewModel([
            'categoriaclientes' => $categoriaclientes,
            'categorias_pag' => $paginator,
            'mensaje' => $mensaje
        ]);
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        $form = $this->categoriaclienteManager->createForm();
        $paginator = $this->categoriaclienteManager->getTabla();
        $mensaje = "";
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $categoriacliente = $this->categoriaclienteManager->getCategoriaClienteFromForm($form, $data);
            return $this->redirect()->toRoute('categoriacliente');
        }
        return new ViewModel([
            'form' => $form,
            'categorias_pag' => $paginator,
            'mensaje' => $mensaje
        ]);
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $categoriacliente = $this->categoriaclienteManager->getCategoriaClienteId($id);
        $form = $this->categoriaclienteManager->getFormForCategoriaCliente($categoriacliente);
        if ($form == null) {
            $this->reportarError();
        } else {
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                if ($this->categoriaclienteManager->formValid($form, $data)) {
                    $this->categoriaclienteManager->updateCategoriaCliente($categoriacliente, $form);
                    return $this->redirect()->toRoute('categoriacliente');
                }
            } else {
                $this->categoriaclienteManager->getFormEdited($form, $categoriacliente);
            }
            return new ViewModel(array(
                'categoriacliente' => $categoriacliente,
                'form' => $form
            ));
        }
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $categoriacliente = $this->categoriaclienteManager->getCategoriaClienteId($id);
        if ($categoriacliente == null) {
            $this->reportarError();
        } else {
            $this->clientesManager->eliminarCategoriaClientes($id);
            $this->categoriaclienteManager->removeCategoriaCliente($id);
            return $this->redirect()->toRoute('categoriacliente');
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
