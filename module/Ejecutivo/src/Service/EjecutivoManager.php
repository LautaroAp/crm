<?php

namespace Ejecutivo\Service;

use DBAL\Entity\Ejecutivo;
use Zend\Paginator\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

/**
 * This service is responsible for adding/editing ejecutivos
 * and changing ejecutivo password.
 */
class EjecutivoManager {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;
    protected $personaManager;
    protected $tipo;
    /**
     * Constructs the service.
     */


    public function __construct($entityManager, $personaManager) {
        $this->entityManager = $entityManager;
        $this->personaManager = $personaManager;
        $this->tipo = "EJECUTIVO";
    }
    
    public function getTabla() {
        $query = $this->getPersonasActivas();
        $personas = $query->getResult();
        $ejecutivos = $this->getEjecutivosFromPersonas($personas);
        $adapter = new DoctrineAdapter(new ORMPaginator($ejecutivos));
        $paginator = new Paginator($adapter);
        return $paginator;
    }

    private function getPersonasActivas(){
        $parametros= Array();
        $parametros+=['tipo'=> $this->tipo];
        $parametros+=['estado'=>"S"];
        $query = $this->personaManager->buscarPersonas($parametros);
        return $query;
    }

    protected function getEjecutivosFromPersonas($personas){
        $entityManager = $this->entityManager;
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('E')
                ->from(Ejecutivo::class, 'E');
        $p = 0;
        $queryBuilder->where('E.persona IN (:personas)')
                    ->setParameter('personas', $personas);
        return $queryBuilder->getQuery();
    }
    /**
     * This method adds a new ejecutivo.
     */
    public function addEjecutivo($data) {
        $ejecutivo = new Ejecutivo();
        $persona = $this->personaManager->addPersona($data, $this->tipo);
        $ejecutivo->setUsuario($data['usuario']);
        $ejecutivo->setClave($data['clave']);
        $ejecutivo->setPersona($persona);
        if ($this->tryAddEjecutivo($ejecutivo)) {
            $_SESSION['MENSAJES']['ejecutivo'] = 1;
            $_SESSION['MENSAJES']['ejecutivo_msj'] = 'Ejecutivo agregado correctamente';
        } else {
            $_SESSION['MENSAJES']['ejecutivo'] = 0;
            $_SESSION['MENSAJES']['ejecutivo_msj'] = 'Error al agregar ejecutivo';
        }
        return $ejecutivo;
    }

    public function updateEjecutivo($ejecutivo, $data) {
        $persona = $ejecutivo->getPersona();
        $this->personaManager->updatePersona($persona,$data);
        $ejecutivo->setUsuario($data['usuario']);
        $ejecutivo->setClave($data['clave']);
        if ($this->tryUpdateEjecutivo($ejecutivo)) {
            $_SESSION['MENSAJES']['ejecutivo'] = 1;
            $_SESSION['MENSAJES']['ejecutivo_msj'] = 'Ejecutivo editado correctamente';
        } else {
            $_SESSION['MENSAJES']['ejecutivo'] = 0;
            $_SESSION['MENSAJES']['ejecutivo_msj'] = 'Error al editar ejecutivo';
        }
        return true;
    }

    public function removeEjecutivo($ejecutivo) {
        $persona = $ejecutivo->getPersona();
        $this->personaManager->cambiarEstado($persona);
        $_SESSION['MENSAJES']['ejecutivo'] = 1;
        $_SESSION['MENSAJES']['ejecutivo_msj'] = 'Ejecutivo dado de Baja correctamente';
    }

    public function recuperarEjecutivo($id) {
        if (!is_null($this->entityManager)) {
            $ejecutivo = $this->entityManager
                    ->getRepository(Ejecutivo::class)
                    ->findOneById($id);
            return $ejecutivo;
        }
    }

    private function tryAddEjecutivo($ejecutivo) {
        try {
            $this->entityManager->persist($ejecutivo);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateEjecutivo($ejecutivo) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }
    
    public function getUsuario($id){
        $ejecutivo = $this->entityManager
                    ->getRepository(Ejecutivo::class)
                    ->findOneById($id);
        return $ejecutivo->getUsuario();
        
    }    public function getClave($id){
        $ejecutivo = $this->entityManager
                    ->getRepository(Ejecutivo::class)
                    ->findOneById($id);
        return $ejecutivo->getClave();
    }
    
    public function getData($id){
        $ejecutivo  = $this->entityManager
                    ->getRepository(Ejecutivo::class)
                    ->findOneById($id);
        $persona = $ejecutivo->getPersona();
        $data = [
            'nombre' =>$persona->getNombre(),
            'telefono' => $persona->getTelefono(),
            'mail' => $persona->getEmail(),
            'usuario' => $ejecutivo->getUsuario(),
            'clave' =>$ejecutivo->getClave()
        ];
       return $data;
    }

    public function getEjecutivoFromForm($form, $data) {
        $form->setData($data);
        // if ($form->isValid()) {
        //     $data = $form->getData();
        //     $ejecutivo = $this->addEjecutivo($data);
        // }
        $ejecutivo = $this->addEjecutivo($data);
        return $ejecutivo;
    }

    public function formValid($form, $data) {
        $form->setData($data);
        return $form->isValid();
    }

    public function getEjecutivos(){
        $ejecutivos = $this->entityManager->getRepository(Ejecutivo::class)->findAll();
        return $ejecutivos;
    }
}
