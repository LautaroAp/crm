<?php

namespace Moneda\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class MonedaController extends HuellaController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Moneda manager.
     * @var User\Service\MonedaManager 
     */
    protected $monedaManager;

    private $servicioManager;
      
    public function __construct($entityManager, $monedaManager, $servicioManager) {
        $this->entityManager = $entityManager;
        $this->monedaManager = $monedaManager;
        $this->servicioManager = $servicioManager;
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
                ->setItemCountPerPage($this->getElemsPag());

        $monedas = $this->monedaManager->getMonedas();
        return new ViewModel([
            'monedas' => $monedas,
            'categorias_pag' => $paginator
        ]);
    }

    public function getMonedas(){
        return $this->entityManager->getRepository(Moneda::class)->fetchAll();
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
                ->setItemCountPerPage($this->getElemsPag());

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
