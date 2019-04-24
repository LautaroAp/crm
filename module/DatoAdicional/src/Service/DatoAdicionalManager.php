<?php

namespace DatoAdicional\Service;

use DBAL\Entity\DatoAdicional;
use Zend\Paginator\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;



/**
 * Esta clase se encarga de obtener y modificar los datos de los tipos de eventos
 *
 */
class DatoAdicionalManager {

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
        return  $this->entityManager->getRepository(DatoAdicional::class)->findAll();
    }


    public function getDatoAdicionalId($id) {
        return $this->entityManager->getRepository(DatoAdicional::class)->findOneById($id);
    }

    
    /**
     * Si es "DATO" solo guarda los valores y se muestran de manera Normal.
     * Si es "REFERENCIA" guarda los ID y en el campo DATO el nombre de la PERSONA.
     */
    public function addDatoAdicional($data, $persona, $referencia_persona) {
        $datoAdicional = new DatoAdicional();
        $this->addData($datoAdicional, $data, $persona, $referencia_persona);
        $this->entityManager->persist($datoAdicional);
        $this->entityManager->flush();
        return $datoAdicional;
    }

    /**
     * This method updates data of an existing datoAdicional.
     */
    public function updateDatoAdicional($datoAdicional, $data, $persona, $referencia_persona) {
        $this->addData($datoAdicional, $data, $persona, $referencia_persona);
        $this->entityManager->flush();
    }

    private function addData($datoAdicional, $data, $persona, $referencia_persona) {

        $datoAdicional->setId_ficha_persona($persona);
        $datoAdicional->setId_referencia_persona($referencia_persona);

        $datoAdicional->setDato_adicional($data['dato_general']);
        $datoAdicional->setDescripcion($data['descripcion']);
        $datoAdicional->setTipo($data['tipo_dato_adicional']);

        return $datoAdicional;
    }
    

    public function removeDatoAdicional($datoAdicional) {
        $this->tryRemoveDatoAdicional($datoAdicional);
    }

    //VER DESPUES
    // public function eliminarDatoAdicional($id) {
    //     $entityManager = $this->entityManager;
    //     $eventos = $this->entityManager->getRepository(Evento::class)->findBy(['tipo'=>$id]);
    //     foreach ($eventos as $evento) {
    //         $evento->setTipo(null);
    //     }
    //     $entityManager->flush();
    // }


    private function tryAddDatoAdicional($datoAdicional) {
        try {
            $this->entityManager->persist($datoAdicional);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateDatoAdicional($datoAdicional) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryRemoveDatoAdicional($datoAdicional) {
        try {
            $this->entityManager->remove($datoAdicional);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }  
}
