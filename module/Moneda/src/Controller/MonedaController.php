<?php

namespace Moneda\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MonedaController extends AbstractActionController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Moneda manager.
     * @var User\Service\MonedaManager 
     */
    protected $monedaManager;

    public function __construct($entityManager, $monedaManager, $servicioManager) {
        $this->entityManager = $entityManager;
        $this->monedaManager = $monedaManager;
    }

    public function indexAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarIndexAction() {
        $paginator = $this->monedaManager->getTabla();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(3);

        $monedas = $this->monedaManager->getMonedas();
        return new ViewModel([
            'monedas' => $monedas,
            'categorias_pag' => $paginator
        ]);
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        $form = $this->monedaManager->createForm();
        $paginator = $this->monedaManager->getTabla();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            // $this->monedaManager->addMoneda($data);
            $moneda = $this->monedaManager->getMonedaFromForm($form, $data);
            return $this->redirect()->toRoute('moneda');
        }
        return new ViewModel([
            'form' => $form,
            'categorias_pag' => $paginator
        ]);
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $moneda = $this->monedaManager->getMonedaId($id);
        $form = $this->monedaManager->getFormForMoneda($moneda);
        if ($form == null) {
            $this->reportarError();
        } else {
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                if ($this->monedaManager->formValid($form, $data)) {
                    $this->monedaManager->updateMoneda($moneda, $form);
                    return $this->redirect()->toRoute('moneda');
                }
            } else {
                $this->monedaManager->getFormEdited($form, $moneda);
            }
            return new ViewModel(array(
                'moneda' => $moneda,
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
        $moneda = $this->monedaManager->getMonedaId($id);
        if ($moneda == null) {
            $this->reportarError();
        } else {
            $this->servicioManager->eliminarMonedas($id);
            $this->monedaManager->removeMoneda($id);
            return $this->redirect()->toRoute('moneda');
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
