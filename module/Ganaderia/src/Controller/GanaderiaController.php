<?php

namespace Ganaderia\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class GanaderiaController extends AbstractActionController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Ganaderia manager.
     * @var User\Service\GanaderiaManager 
     */
    protected $ganaderiaManager;

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    protected $productoManager;

    public function __construct($entityManager, $ganaderiaManager) {
        $this->entityManager = $entityManager;
        $this->ganaderiaManager = $ganaderiaManager;
    }

    public function indexAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarIndexAction() {
        $paginator = $this->ganaderiaManager->getTabla();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(3);

        $ganaderias = $this->ganaderiaManager->getGanaderias();
        return new ViewModel([
            'ganaderias' => $ganaderias,
            'ganaderias_pag' => $paginator
        ]);
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        $form = $this->ganaderiaManager->createForm();
        $paginator = $this->ganaderiaManager->getTabla();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(4);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            // $this->ganaderiaManager->addGanaderia($data);
            $ganaderia = $this->ganaderiaManager->getGanaderiaFromForm($form, $data);
            return $this->redirect()->toRoute('gestionProductosServicios/gestionProductos/categorias');
        }
        return new ViewModel([
            'form' => $form,
            'ganaderias_pag' => $paginator
        ]);
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $ganaderia = $this->ganaderiaManager->getGanaderiaId($id);
        $form = $this->ganaderiaManager->getFormForGanaderia($ganaderia);
        if ($form == null) {
            $this->reportarError();
        } else {
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                if ($this->ganaderiaManager->formValid($form, $data)) {
                    $this->ganaderiaManager->updateGanaderia($ganaderia, $form);
                    return $this->redirect()->toRoute('gestionProductosServicios/gestionProductos/categorias');
                }
            } else {
                $this->ganaderiaManager->getFormEdited($form, $ganaderia);
            }
            return new ViewModel(array(
                'ganaderia' => $ganaderia,
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
        $ganaderia = $this->ganaderiaManager->getGanaderiaId($id);
        if ($ganaderia == null) {
            $this->reportarError();
        } else {
            $this->productoManager->eliminarGanaderias($id);
            $this->ganaderiaManager->removeGanaderia($id);
            return $this->redirect()->toRoute('ganaderia');
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
