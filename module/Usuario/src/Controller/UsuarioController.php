<?php

/**
 * Esta clase es el controlador de la entidad Usuario.  
 * Se encarga de direccionar los datos entre las vistas y el manager
 * @author SoftHuella 
 */

namespace Usuario\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Usuario\Form\UsuarioForm;

class UsuarioController extends AbstractActionController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    private $usuarioManager;

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
        return $this->procesarIndexAction();
    }

    private function procesarIndexAction(){
        $paginator = $this->usuarioManager->getTabla();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);
        return new ViewModel([
            'usuarios' => $paginator,
        ]);
    }
    private function procesarAddAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $form = new UsuarioForm('create', $this->entityManager);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();
                $this->usuarioManager->addUsuario($data);
                return $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $id]);
            }
        }
        return new ViewModel([
            'form' => $form,
            'id' => $id,
        ]);
    }

    public function addAction() {
        return $this->procesarAddAction();
    }

    private function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $usuario = $this->usuarioManager->getUsuario($id);
        if ($usuario == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $form = new UsuarioForm('update', $this->entityManager, $usuario);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->usuarioManager->updateUsuario($usuario, $data);
                $idCliente = $this->usuarioManager->getIdCliente($id);
                return $this->redirect()->toRoute('clientes/ficha', ['action' => 'ficha', 'id' => $usuario->getCliente()->getPersona()->getId()]);
            }
        } else {
            $this->setDataToForm($id,$form);
        }
        return new ViewModel(array(
            'usuario' => $usuario,
            'form' => $form,
            'id' => $this->usuarioManager->getIdCliente($id),
        ));
    }

    private function setDataToForm($id, $form){
       $data = $this->usuarioManager->getData($id);
       $form->setData(array(
                'nombre' => $data['nombre'],
                'telefono' => $data['telefono'],
                'mail' => $data['mail'],
                'skype'=> $data['skype'],
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
            $idCliente = $this->usuarioManager->getIdCliente($id);
            $this->usuarioManager->removeUsuario($usuario);           
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
