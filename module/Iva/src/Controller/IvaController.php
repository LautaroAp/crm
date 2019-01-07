<?php

namespace Iva\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IvaController extends AbstractActionController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Iva manager.
     * @var User\Service\IvaManager 
     */
    protected $ivaManager;

    /**
     * Iva manager.
     * @var User\Service\LicenciaManager 
     */
    private $licenciaManager;

    /**
     * Iva manager.
     * @var User\Service\ProductoManager 
     */
    private $productoManager;

    /**
     * Iva manager.
     * @var User\Service\SevicioManager 
     */
    private $servicioManager;

    

    public function __construct($entityManager, $ivaManager, $licenciaManager, $productoManager, $servicioManager) {
        $this->entityManager = $entityManager;
        $this->ivaManager = $ivaManager;
        $this->licenciaManager = $licenciaManager;
        $this->productoManager = $productoManager;
        $this->servicioManager = $servicioManager;
    }

    public function indexAction() {
        // $view = $this->procesarIndexAction();
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarIndexAction() {
        $paginator = $this->ivaManager->getTabla();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);

        $ivas = $this->ivaManager->getIvas();
        return new ViewModel([
            'ivas' => $paginator,
        ]);
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        $request = $this->getRequest();
        $paginator = $this->ivaManager->getTabla();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);
        $ivas = $this->ivaManager->getIvas();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->ivaManager->addIva($data);
            return $this->redirect()->toRoute('herramientas/tipoiva');
        }
        return new ViewModel([
            'ivas' => $paginator,
        ]);
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $iva = $this->ivaManager->getIva($id);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->ivaManager->updateIva($iva, $data);
            return $this->redirect()->toRoute('herramientas/tipoiva');
        }
        return new ViewModel(array(
            'iva' => $iva,
        ));
    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $iva = $this->ivaManager->getIva($id);
        if ($iva == null) {
            $this->reportarError();
        } else {
            // Eliminar de Licencias
            $this->licenciaManager->eliminarIvas($id);
            // Eliminar de Productos
            $this->productoManager->eliminarIvas($id);
            // Eliminar de Servicios
            $this->servicioManager->eliminarIvas($id);
            // Eliminar Tipo IVA
            $this->ivaManager->removeIva($id);
            return $this->redirect()->toRoute('herramientas/tipoiva');
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
