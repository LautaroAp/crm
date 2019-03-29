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
        return $this->entityManager->getRepository(FormaPago::class)
                        ->find($id);
    }

    
    /**
     * This method adds a new FormaPago.
     */
    public function addFormaPago($data, $tipoPersona) {
        $formapago = new FormaPago();
        $this->addData($formapago, $data, $tipoPersona);
        return $formapago;
    }

    /**
     * This method updates data of an existing formapago.
     */
    public function updateFormaPago($formapago, $data) {
        $formapago=$this->addData($formapago, $data);
        return $formapago;
    }

    private function addData($formapago, $data, $tipoPersona=null) {
        $formapago->setNombre($data['nombre']);
        if ($data['categoria'] == "-1") {
            $formapago->setCategoria_evento(null);
        } else {
            $categoria_eve = $this->getCategoriaEventos($data['categoria']);
            $formapago->setCategoria_evento($categoria_eve);
        }
        if (isset($tipoPersona)){
            $formapago->setTipoPersona($tipoPersona);
        }
        $formapago->setDescripcion($data['descripcion']);
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
