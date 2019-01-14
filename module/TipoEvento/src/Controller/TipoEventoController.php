<?php

/**
 * Esta clase es el controlador de la entidad TipoEvento.  
 * Se encarga de direccionar los datos entre las vistas y el manager
 * @author SoftHuella 
 */

namespace TipoEvento\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DBAL\Entity\CategoriaEvento;
use DBAL\Entity\Evento;


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

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $eventoManager;

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $categoriaManager;

    public function __construct($entityManager, $tipoeventoManager, $eventoManager, $categoriaManager) {
        $this->entityManager = $entityManager;
        $this->tipoeventoManager = $tipoeventoManager;
        $this->eventoManager = $eventoManager;
        $this->categoriaManager = $categoriaManager;
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
        $form = $this->tipoeventoManager->createForm();
        $tipoPersona = $this->params()->fromRoute('tipo');
        $paginator = $this->tipoeventoManager->getTabla($tipoPersona);
        $categorias= $this->tipoeventoManager->getCategoriaEventos();
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->tipoeventoManager->addTipoEvento($data, $tipoPersona);
            return $this->redireccionar($tipoPersona);
        }
        $_SESSION['TIPOEVENTO']['TIPO'] = $tipoPersona;
        return new ViewModel([
            'form' => $form,
            'tipoeventos' => $tipoeventos,
            'tipoeventos_pag' => $paginator,
            'tipoPersona'=> $tipoPersona,
            'categoriaeventos' => $categorias,
        ]);
    }

    private function redireccionar($tipo){
        if (strtoupper($tipo)==strtoupper("proveedor")){
            return $this->redirect()->toRoute('gestionProveedores/gestionActividadesProveedores/tipoeventoProveedor', ['tipo'=>'proveedor']);
        }elseif (strtoupper($tipo)==strtoupper("cliente")){
            return $this->redirect()->toRoute('gestionClientes/gestionActividadesClientes/tipoeventoCliente', ['tipo'=>'cliente']);
        }else{
            return $this->redirect()->toRoute('home');
        }
    }

    
    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $tipoevento = $this->tipoeventoManager->getTipoEventoId($id);
        $tipoPersona = $this->params()->fromRoute('tipo');
        $categorias= $this->tipoeventoManager->getCategoriaEventos();
        $form = $this->tipoeventoManager->getFormForTipoEvento($tipoevento);
        if ($form == null) {
            $this->reportarError();
        } else {
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                if ($this->tipoeventoManager->formValid($form, $data)) {
                    $this->tipoeventoManager->updateTipoEvento($tipoevento, $data);
                    return $this->redireccionar($tipoPersona);
                }
            } else {
                $this->tipoeventoManager->getFormEdited($form, $tipoevento);
            }
            $_SESSION['TIPOEVENTO']['TIPO'] = $tipoPersona;
            return new ViewModel(array(
                'tipoevento' => $tipoevento,
                'categoriaeventos' => $categorias,
                'tipoPersona'=> $tipoPersona,
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
            $this->removerDependencias($categoria->getTipo(), $id);
            $this->eventoManager->eliminarTipoEventos($id);
            
            $this->tipoeventoManager->removeTipoEvento($tipoevento);
            return $this->redireccionar($tipoevento->getTipo());
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
