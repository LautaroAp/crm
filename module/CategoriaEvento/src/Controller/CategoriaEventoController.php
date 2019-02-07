<?php

/**
 * Esta clase es el controlador de la entidad CategoriaEvento.  
 * Se encarga de direccionar los datos entre las vistas y el manager
 * @author SoftHuella 
 */

namespace CategoriaEvento\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;
use CategoriaEvento\Service\CategoriaEventoManager;


class CategoriaEventoController extends HuellaController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * CategoriaEvento manager.
     * @var User\Service\CategoriaEventoManager 
     */
    protected $categoriaEventoManager;

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $tipoEventoManager;

    public function __construct($entityManager, $categoriaEventoManager, $tipoEventoManager) {
        $this->entityManager = $entityManager;
        $this->categoriaEventoManager = $categoriaEventoManager;
        $this->tipoEventoManager = $tipoEventoManager;
    }

    public function indexAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        $form = $this->categoriaEventoManager->createForm();
        $categoriaeventos = $this->categoriaEventoManager->getCategoriaEventos();
        $paginator = $this->categoriaEventoManager->getTabla();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->categoriaEventoManager->getCategoriaEventoFromForm($form, $data);
            return $this->redirect()->toRoute('categoriaevento');
        }
        return new ViewModel([
            'form' => $form,
            'categoriaeventos' => $categoriaeventos,
            'categoriaeventos_pag' => $paginator,
        ]);
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $categoriaevento = $this->categoriaEventoManager->getCategoriaEventoId($id);
        $form = $this->categoriaEventoManager->getFormForCategoriaEvento($categoriaevento);
        if ($form == null) {
            $this->reportarError();
        } else {
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                if ($this->categoriaEventoManager->formValid($form, $data)) {
                    $this->categoriaEventoManager->updateCategoriaEvento($categoriaevento, $data);
                    return $this->redirect()->toRoute('categoriaevento');
                }
            } else {
                $this->categoriaEventoManager->getFormEdited($form, $categoriaevento);
            }
            return new ViewModel(array(
                'categoriaevento' => $categoriaevento,
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
        $categoriaevento = $this->categoriaEventoManager->getCategoriaEventoId($id);

        if ($categoriaevento == null) {
            $this->reportarError();
        } else {
            $this->tipoEventoManager->eliminarCategoriaEventos($id);
            $this->categoriaEventoManager->removeCategoriaEvento($categoriaevento);
            return $this->redirect()->toRoute('categoriaevento');
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
