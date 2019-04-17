<?php

namespace FormaEnvio\Service;

use DBAL\Entity\FormaEnvio;
use Zend\Paginator\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;



/**
 * Esta clase se encarga de obtener y modificar los datos de los tipos de eventos
 *
 */
class FormaEnvioManager {

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

    public function getFormasEnvio() {
        return  $this->entityManager->getRepository(FormaEnvio::class)->findAll();
    }


    public function getFormaEnvioId($id) {
        return $this->entityManager->getRepository(FormaEnvio::class)->findOneById($id);
    }

    
    /**
     * This method adds a new FormaEnvio.
     */
    public function addFormaEnvio($data) {
        $formaenvio = new FormaEnvio();
        $this->addData($formaenvio, $data);
        $this->entityManager->persist($formaenvio);
        $this->entityManager->flush();
        return $formaenvio;
    }

    /**
     * This method updates data of an existing formaenvio.
     */
    public function updateFormaEnvio($formaenvio, $data) {
        $this->addData($formaenvio, $data);
        $this->entityManager->flush();
    }

    private function addData($formaenvio, $data) {
        $formaenvio->setNombre($data['nombre']);
        if (isset($data['descripcion'])){
            $formaenvio->setDescripcion($data['descripcion']);
        }
    }
    

    public function removeFormaEnvio($formaenvio) {
        $this->tryRemoveFormaEnvio($formaenvio);
    }

    //VER DESPUES
    // public function eliminarFormaEnvio($id) {
    //     $entityManager = $this->entityManager;
    //     $eventos = $this->entityManager->getRepository(Evento::class)->findBy(['tipo'=>$id]);
    //     foreach ($eventos as $evento) {
    //         $evento->setTipo(null);
    //     }
    //     $entityManager->flush();
    // }


    private function tryAddFormaEnvio($formaenvio) {
        try {
            $this->entityManager->persist($formaenvio);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateFormaEnvio($formaenvio) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryRemoveFormaEnvio($formaenvio) {
        try {
            $this->entityManager->remove($formaenvio);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }  
}
