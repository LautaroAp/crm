<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Licencia\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Application\Entity\Post;
use DBAL\Entity\Licencia;
use DBAL\Entity\Cliente;
use Licencia\Form\LicenciaForm;

class LicenciaController extends AbstractActionController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Licencia manager.
     * @var User\Service\LicenciaManager 
     */
    protected $licenciaManager;
    
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    protected $clientesManager;

    /* public function __construct($entityManager, $licenciaManager)
      {
      $this->entityManager = $entityManager;
      $this->licenciaManager = $licenciaManager;
      }
     */

    public function __construct($entityManager, $licenciaManager, $clientesManager) {
        $this->entityManager = $entityManager;
        $this->licenciaManager = $licenciaManager;
        $this->clientesManager = $clientesManager;
    }

    public function indexAction() {
        return $this->procesarIndexAction();
    }

    private function procesarIndexAction() {
        $paginator = $this->licenciaManager->getTabla();
        $mensaje = "";

        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);

        return new ViewModel([
            'licencias' => $paginator,
            'mensaje' => $mensaje
        ]);
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        $form = $this->licenciaManager->createForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $licencia = $this->licenciaManager->getLicenciaFromForm($form, $data);

            return $this->redirect()->toRoute('licencia', ['action' => 'index']);
        }
        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $licencia = $this->licenciaManager->getLicenciaId($id);

        $form = $this->licenciaManager->getFormForLicencia($licencia);
        if ($form == null) {
            $this->reportarError();
        } else {
            if ($this->getRequest()->isPost()) {

                $data = $this->params()->fromPost();

                if ($this->licenciaManager->formValid($form, $data)) {

                    $this->licenciaManager->updateLicencia($licencia, $form);
                    return $this->redirect()->toRoute('licencia', ['action' => 'index']);
                }
            } else {

                $this->licenciaManager->getFormEdited($form, $licencia);
            }
            return new ViewModel(array(
                'licencia' => $licencia,
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
        $licencia = $this->licenciaManager->getLicenciaId($id);

        if ($licencia == null) {
            $this->reportarError();
        } else {
            $this->clientesManager->eliminarLicenciaClientes($licencia->getId());
            $this->licenciaManager->removeLicencia($licencia);
            return $this->redirect()->toRoute('licencia', ['action' => 'index']);
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
