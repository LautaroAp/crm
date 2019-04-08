<?php

namespace FormaPago\Service;

use DBAL\Entity\FormaPago;
use Zend\Paginator\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;



/**
 * Esta clase se encarga de obtener y modificar los datos de los tipos de eventos
 *
 */
class FormaPagoManager {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * PHP template renderer.
     * @var type 
     */
    private $viewRenderer;

    /**
     * Application config.
     * @var type 
     */
    private $config;

    /**
     * Constructs the service.
     */
    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    public function getFormasPago() {
        return  $this->entityManager->getRepository(FormaPago::class)->findAll();
    }


    public function getFormaPagoId($id) {
        return $this->entityManager->getRepository(FormaPago::class)->findOneById($id);
    }

    
    /**
     * This method adds a new FormaPago.
     */
    public function addFormaPago($data) {
        $formapago = new FormaPago();
        $this->addData($formapago, $data);
        $this->entityManager->persist($formapago);
        $this->entityManager->flush();
        return $formapago;
    }

    /**
     * This method updates data of an existing formapago.
     */
    public function updateFormaPago($formapago, $data) {
        $formapago=$this->addData($formapago, $data);
        return $formapago;
    }

    private function addData($formapago, $data) {
        $formapago->setNombre($data['nombre']);
        if (isset($data['descripcion'])){
            $formapago->setDescripcion($data['descripcion']);
        }
        if (isset($data['bonificacion']) and ($data['bonificacion']!="")){
            $formapago->setBonificacion($data['bonificacion']);
        }
        if (isset($data['recargo']) and ($data['recargo']!="")){
            $formapago->setRecargo($data['recargo']);
        }
        return $formapago;
    }
    

    public function removeFormaPago($formapago) {
        $this->tryRemoveFormaPago($formapago);
    }

    //VER DESPUES
    // public function eliminarFormaPago($id) {
    //     $entityManager = $this->entityManager;
    //     $eventos = $this->entityManager->getRepository(Evento::class)->findBy(['tipo'=>$id]);
    //     foreach ($eventos as $evento) {
    //         $evento->setTipo(null);
    //     }
    //     $entityManager->flush();
    // }


    private function tryAddFormaPago($formapago) {
        try {
            $this->entityManager->persist($formapago);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateFormaPago($formapago) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryRemoveFormaPago($formapago) {
        try {
            $this->entityManager->remove($formapago);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }  
}
