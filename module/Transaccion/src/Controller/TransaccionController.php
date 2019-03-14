<?php

/**
 * Clase actualmente sin uso
 */

namespace Transaccion\Controller;

use Application\Controller\HuellaController;
use Zend\View\Model\ViewModel;

abstract class TransaccionController extends HuellaController {

    /**
     * @var DoctrineORMEntityManager
     */
    protected $entityManager;

    /**
     * Transaccion manager.
     * @var User\Service\TransaccionManager 
     */
    protected $transaccionManager;

    public function __construct($entityManager, $transaccionManager) {
        $this->entityManager = $entityManager;
        $this->transaccionManager = $transaccionManager;
    }

    public function indexAction(){
        print_r ("hola");
    }
    public abstract function addAction();
    public abstract function editAction();
    public abstract function removeAction();

    // public function procesarRemoveAction() {
    //     $id = (int) $this->params()->fromRoute('id', -1);
    //     $transaccion = $this->transaccionManager->getTransaccionId($id);
    //     if ($transaccion == null) {
    //         $this->getResponse()->setStatusCode(404);
    //         return;
    //     } else {
    //         $this->transaccionManager->remove($transaccion);
    //         return $this->redirect()->toRoute('home');
    //     }
    // }

    public abstract function getManager();


}
