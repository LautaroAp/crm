<?php

/**
 * Clase actualmente sin uso
 */

namespace Servicio\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ServicioController extends AbstractActionController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Servicio manager.
     * @var User\Service\ServicioManager 
     */
    protected $servicioManager;

    public function __construct($entityManager, $servicioManager) {
        $this->entityManager = $entityManager;
        $this->servicioManager = $servicioManager;
    }

    public function indexAction() {
        return $this->procesarIndexAction();
    }

    private function procesarIndexAction() {

        $servicios = $this->servicioManager->getServicios();
        return new ViewModel([
            'servicios' => $servicios
        ]);
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        $form = $this->servicioManager->createForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->servicioManager->getServicioFromForm($form, $data);
            return $this->redirect()->toRoute('servicio', ['action' => 'index']);
        }
        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editAction() {
        return $this->procesarEditAction();
    }

    public function procesarEditAction() {
        $id = $this->params()->fromRoute('id', -1);
        $servicio = $this->servicioManager->getServicioId($id);
        $form = $this->servicioManager->getFormForServicio($servicio);

        if ($form == null) {
            $this->getResponse()->setStatusCode(404);
        } else {
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                if ($this->servicioManager->formValid($form, $data)) {
                    $this->servicioManager->updateServicio($servicio, $form);
                    return $this->redirect()->toRoute('servicio', ['action' => 'index']);
                }
            } else {
                $this->servicioManager->getFormEdited($form, $servicio);
            }

            return new ViewModel(array(
                'servicio' => $servicio,
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
        $servicio = $this->servicioManager->getServicioId($id);
        if ($servicio == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        } else {
            $this->servicioManager->removeServicio($servicio);
            return $this->redirect()->toRoute('servicio', ['action' => 'index']);
        }
    }

    public function viewAction() {
        return new ViewModel();
    }

}
