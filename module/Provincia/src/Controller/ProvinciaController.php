<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Provincia\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Application\Entity\Post;
use DBAL\Entity\Provincia;
use Provincia\Form\ProvinciaForm;

class ProvinciaController extends AbstractActionController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Provincia manager.
     * @var User\Service\ProvinciaManager 
     */
    protected $provinciaManager;

    public function __construct($entityManager, $provinciaManager) {
        $this->entityManager = $entityManager;
        $this->provinciaManager = $provinciaManager;
    }

    private function buscarProvincia() {
        $id_provincia = (int) $this->params()->fromRoute('id_provincia', -1);
        $id_pais = (int) $this->params()->fromRoute('id_pais', -1);
        $provincia = $this->provinciaManager->getProvinciaId($id_pais, $id_provincia);
        return $provincia;
    }

    public function indexAction() {
//        return $this->procesarIndexAction();
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarIndexAction() {
        $provincias = $this->provinciaManager->getProvincias();
        return new ViewModel([
            'provincias' => $provincias
        ]);
    }

    public function addAction() {
        $view = $this->procesarAddAction();
        return $view;
    }

    private function procesarAddAction() {
        $form = $this->provinciaManager->createForm();
        $provincias = $this->provinciaManager->getProvincias();
        $paises = $this->provinciaManager->getPais();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
                        
            $provincia = $this->provinciaManager->getProvinciaFromForm($form, $data);
            return $this->redirect()->toRoute('provincia');
        }
        return new ViewModel([
            'form' => $form,
            'provincias' => $provincias,
            'paises' => $paises,
        ]);
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $provincia = $this->buscarProvincia();
        $form = $this->provinciaManager->getFormForProvincia($provincia);
        if ($form == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        } else {
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                if ($this->provinciaManager->formValid($form, $data)) {
                    $this->provinciaManager->updateProvincia($provincia, $form);
                    return $this->redirect()->toRoute('application', ['action' => 'view']);
                }
            } else {
                $this->provinciaManager->getFormEdited($form, $provincia);
            }
            return new ViewModel(array(
                'provincia' => $provincia,
                'form' => $form
            ));
        }
    }

    public function removeAction() {

        $view = $this->procesarRemoveAction();
        return $view;
    }

    public function procesarRemoveAction() {
        $provincia = $this->buscarProvincia();
        if ($provincia == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        } else {
            $this->provinciaManager->removeProvincia($provincia);
            return $this->redirect()->toRoute('application', ['action' => 'view']);
        }
    }

    public function viewAction() {
        /* $id = (int)$this->params()->fromRoute('id', -1);
          if ($id<1) {
          $this->getResponse()->setStatusCode(404);
          return;
          }

          // Find a user with such ID.
          $provincia = $this->entityManager->getRepository(Provincia::class)
          ->find($id_provincia);

          if ($provincia == null) {
          $this->getResponse()->setStatusCode(404);
          return;
          }

          return new ViewModel([
          'provincia' => $provincia
          ]); */


        return new ViewModel();
    }

}
