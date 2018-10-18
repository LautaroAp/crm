<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace TipoEvento\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Application\Entity\Post;
use DBAL\Entity\TipoEvento;
use TipoEvento\Form\TipoEventoForm;

class TipoEventoController extends AbstractActionController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * TipoEvento manager.
     * @var User\Service\TipoEventoManager 
     */
    protected $tipoeventoManager;

    /* public function __construct($entityManager, $tipoeventoManager)
      {
      $this->entityManager = $entityManager;
      $this->tipoeventoManager = $tipoeventoManager;
      }
     */

    public function __construct($entityManager, $tipoeventoManager) {
        $this->entityManager = $entityManager;
        $this->tipoeventoManager = $tipoeventoManager;
    }

    public function indexAction() {
//        return $this->procesarIndexAction();
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarIndexAction() {
        $tipoeventos = $this->tipoeventoManager->getTipoEventos();
        return new ViewModel([
            'tipoeventos' => $tipoeventos
        ]);
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        $form = $this->tipoeventoManager->createForm();
        $tipoeventos = $this->tipoeventoManager->getTipoEventos();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $tipoevento = $this->tipoeventoManager->getTipoEventoFromForm($form, $data);
            return $this->redirect()->toRoute('tipoevento');
        }
        return new ViewModel([
            'form' => $form,
            'tipoeventos' => $tipoeventos,
        ]);
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $tipoevento = $this->tipoeventoManager->getTipoEventoId($id);
        $form = $this->tipoeventoManager->getFormForTipoEvento($tipoevento);
        if ($form == null) {
            $this->reportarError();
        } else {
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                if ($this->tipoeventoManager->formValid($form, $data)) {
                    $this->tipoeventoManager->updateTipoEvento($tipoevento, $form);
                    return $this->redirect()->toRoute('tipoevento');
                }
            } else {
                $this->tipoeventoManager->getFormEdited($form, $tipoevento);
            }
            return new ViewModel(array(
                'tipoevento' => $tipoevento,
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
        $tipoevento = $this->tipoeventoManager->getTipoEventoId($id);

        if ($tipoevento == null) {
            $this->reportarError();
        } else {
            $this->tipoeventoManager->removeTipoEvento($tipoevento);
            return $this->redirect()->toRoute('tipoevento');
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
