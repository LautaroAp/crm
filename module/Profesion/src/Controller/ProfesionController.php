<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Profesion\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

class ProfesionController extends HuellaController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Profesion manager.
     * @var User\Service\ProfesionManager 
     */
    protected $profesionManager;

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    protected $clientesManager;
    
    /* public function __construct($entityManager, $profesionManager)
      {
      $this->entityManager = $entityManager;
      $this->profesionManager = $profesionManager;
      }
     */

    public function __construct($entityManager, $profesionManager, $clientesManager) {
        $this->entityManager = $entityManager;
        $this->profesionManager = $profesionManager;
        $this->clientesManager = $clientesManager;
    }

    public function indexAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarIndexAction() {
        $profesiones = $this->profesionManager->getProfesiones();
        return new ViewModel([
            'profesiones' => $profesiones
        ]);
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        $profesiones = $this->profesionManager->getProfesiones();
        
        $paginator = $this->profesionManager->getTabla();
        $mensaje = "";

        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->profesionManager->addProfesion($data);
            return $this->redirect()->toRoute('profesion');
        }
        return new ViewModel([
            'profesiones' => $profesiones,
            'profesiones_pag' => $paginator,
            'mensaje' => $mensaje
        ]);
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $profesion = $this->profesionManager->getProfesionId($id);
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->profesionManager->updateProfesion($profesion, $data);
            return $this->redirect()->toRoute('profesion');
        } 
        return new ViewModel(array(
            'profesion' => $profesion,
        ));

    }

    public function removeAction() {
        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $profesion = $this->profesionManager->getProfesionId($id);
        if ($profesion == null) {
            $this->reportarError();
        } else {
            $this->clientesManager->eliminarProfesiones($id);
            $this->profesionManager->removeProfesion($profesion);
            return $this->redirect()->toRoute('profesion');
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
