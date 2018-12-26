<?php

/**
 * Esta clase es el controlador de la entidad Categoria.  
 * Se encarga de direccionar los datos entre las vistas y el manager
 * @author SoftHuella 
 */

namespace Categoria\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Categoria\Service\CategoriaManager;


class CategoriaController extends AbstractActionController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Categoria manager.
     * @var User\Service\CategoriaManager 
     */
    protected $categoriaManager;

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $tipoEventoManager;

    public function __construct($entityManager, $categoriaManager, $tipoEventoManager) {
        $this->entityManager = $entityManager;
        $this->categoriaManager = $categoriaManager;
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
        $tipo= $this->params()->fromRoute('tipo');
        $id=$this->params()->fromRoute('id');
        $form = $this->categoriaManager->createForm();
        $paginator = $this->categoriaManager->getTabla($tipo);
        $page = 1;
        if ($this->params()->fromRoute('id')) {
            $page = $this->params()->fromRoute('id');
        }
        $paginator->setCurrentPageNumber((int) $page)
                ->setItemCountPerPage(10);
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $this->categoriaManager->addCategoria($data, $tipo);
            // $this->categoriaManager->getCategoriaFromForm($form, $data);
            if ($tipo=="cliente"){
                return $this->redirect()->toRoute('gestionClientes/categoriacliente', ['tipo'=>'cliente']);
            }elseif ($tipo=="producto"){
                return $this->redirect()->toRoute('gestionClientes/categoriacliente', ['tipo'=>'cliente']);
            }elseif ($tipo=="licencia"){
                return $this->redirect()->toRoute('gestionClientes/categoriacliente', ['tipo'=>'cliente']);
            }elseif ($tipo=="servicio"){
                return $this->redirect()->toRoute('gestionClientes/categoriacliente', ['tipo'=>'cliente']);
            }elseif ($tipo=="evento"){
                return $this->redirect()->toRoute('gestionClientes/categoriacliente', ['tipo'=>'evento']);
            }elseif ($tipo=="iva"){
                return $this->redirect()->toRoute('gestionClientes/categoriacliente', ['tipo'=>'cliente']);
            }else{
                return $this->redirect()->toRoute('home');
            }
        }
        $_SESSION['CATEGORIA']['TIPO'] = $tipo;
        return new ViewModel([
            'categorias' => $paginator,
            'tipo'=>$tipo,
        ]);
    }


    public function editAction() {
        $view = $this->procesarEditAction();
        return $view;
    }

    public function procesarEditAction() {
        $id = (int) $this->params()->fromRoute('id', -1);
        $categoriaevento = $this->categoriaManager->getCategoriaId($id);
        $form = $this->categoriaManager->getFormForCategoria($categoriaevento);
        if ($form == null) {
            $this->reportarError();
        } else {
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                if ($this->categoriaManager->formValid($form, $data)) {
                    $this->categoriaManager->updateCategoria($categoriaevento, $data);
                    return $this->redirect()->toRoute('categoriaevento');
                }
            } else {
                $this->categoriaManager->getFormEdited($form, $categoriaevento);
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
        $categoriaevento = $this->categoriaManager->getCategoriaId($id);

        if ($categoriaevento == null) {
            $this->reportarError();
        } else {
            $this->tipoEventoManager->eliminarCategorias($id);
            $this->categoriaManager->removeCategoria($categoriaevento);
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
