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

    public function __construct($entityManager, $ivaManager, $servicioManager) {
        $this->entityManager = $entityManager;
        $this->ivaManager = $ivaManager;
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
                ->setItemCountPerPage(3);

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
                ->setItemCountPerPage(3);
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
