<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Usuario\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use DBAL\Entity\Usuario;
use DBAL\Entity\Evento;
use Usuario\Form\UsuarioForm;

class UsuarioController extends AbstractActionController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    //private $usuarioManager;

    public function __construct($entityManager, $usuarioManager) {
        $this->entityManager = $entityManager;
        $this->usuarioManager = $usuarioManager;
    }

    public function getEntityManager() {
        if (null === $this->entityManager) {
            $this->entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->entityManager;
    }

    public function indexAction() {
        $usuarios = $this->getEntityManager()
                        ->getRepository(Usuario::class)->findAll();

        $paginator = $this->usuarioManager->getTabla();
        $mensaje = "";

        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);

        return new ViewModel([
            'usuarios' => $paginator,
            'mensaje' => $mensaje
        ]);
    }

    private function procesarAddAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
               
        // Create usuario form
        $form = new UsuarioForm('create', $this->entityManager);
        // Check if usuario has submitted the form
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data
            $data = $this->params()->fromPost();
            $form->setData($data);
            // Validate form
            if ($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();
                // Add usuario.
                $usuario = $this->usuarioManager->addUsuario($data);
                return $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $id]);
            }
        }
        return new ViewModel([
            'form' => $form,
            'id' => $id,
        ]);
    }

    public function addAction() {
        $view = $this->procesarAddAction();

        return $view;
    }

    public function viewAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        print($id);

        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Find a user with such ID.
        $usuario = $this->entityManager->getRepository(Usuario::class)
                ->find($id);

        if ($usuario == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel([
            'usuario' => $usuario
        ]);
    }

    private function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);

        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $usuario = $this->entityManager->getRepository(Usuario::class)
                ->find($id);
        if ($usuario == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        // Create user form
        $form = new UsuarioForm('update', $this->entityManager, $usuario);
        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data
            $data = $this->params()->fromPost();
            $form->setData($data);
            // Validate form
            if ($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();
                // Update the user.
                $this->usuarioManager->updateUsuario($usuario, $data);
                $idCliente = $usuario->getCliente()->getId();
                return $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $idCliente]);
            }
        } else {
            $form->setData(array(
                'nombre' => $usuario->getNombre(),
                'telefono' => $usuario->getNombre(),
                'mail' => $usuario->getMail(),
            ));
        }
        return new ViewModel(array(
            'usuario' => $usuario,
            'form' => $form,
            'id' => $usuario->getCliente()->getId(),
        ));
    }

    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    private function procesarDeleteAction() {
        if (!$this->getRequest()->isPost()) {

            $id = $this->params()->fromRoute('id', -1);
            if ($id < 1) {
                $this->getResponse()->setStatusCode(404);
                return;
            }

            $usuario = $this->usuarioManager->recuperarUsuario($id);
            if ($usuario == null) {
                $this->getResponse()->setStatusCode(404);
                return;
            }

            $this->usuarioManager->removeUsuario($usuario);
            // Redirect the user to "admin" page.
            $idCliente = $usuario->getCliente()->getId();
            return $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $idCliente]);
        } else {
            $view = new ViewModel();
            return $view;
        }
    }

    public function deleteAction() {
        $view = $this->procesarDeleteAction();
        return $view;
    }

}
