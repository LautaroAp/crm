<?php

namespace Pais\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class PaisController extends HuellaController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Pais manager.
     * @var User\Service\PaisManager 
     */
    protected $paisManager;

    public function __construct($entityManager, $paisManager) {
        $this->entityManager = $entityManager;
        $this->paisManager = $paisManager;
    }

    public function indexAction() {
//        return $this->procesarIndexAction();
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarIndexAction() {
        $paises = $this->paisManager->getPaises();
        return new ViewModel([
            'paises' => $paises
        ]);
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        $form = $this->paisManager->createForm();
        $paises = $this->paisManager->getPaises();

        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data
            $data = $this->params()->fromPost();
            $pais = $this->paisManager->getPaisFromForm($form, $data);
            return $this->redirect()->toRoute('pais');
        }
        return new ViewModel([
            'form' => $form,
            'paises' => $paises,
        ]);
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = $this->params()->fromRoute('id', -1);
        $pais = $this->paisManager->getPaisId($id);
        $form = $this->paisManager->getFormForPais($pais);
        if ($form == null) {
            $this->getResponse()->setStatusCode(404);
        } else {
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                if ($this->paisManager->formValid($form, $data)) {
                    $this->paisManager->updatePais($pais, $form);
                    return $this->redirect()->toRoute('pais');
                }
            } else {
                $this->paisManager->getFormEdited($form, $pais);
            }

            return new ViewModel(array(
                'pais' => $pais,
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
        $pais = $this->paisManager->getPaisId($id);

        if ($pais == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        } else {
            $this->paisManager->removePais($pais);
            return $this->redirect()->toRoute('pais');
        }
    }

    public function viewAction() {
        return new ViewModel();
    }
}
