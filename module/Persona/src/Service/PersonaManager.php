<?php

namespace Persona\Service;

use DBAL\Entity\Persona;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;


/**
 * Esta clase se encarga de obtener y modificar los datos y personas.
 *
 */

class PersonaManager {

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
    public function __construct($entityManager, $viewRenderer, $config) {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
    }

    //La funcion getTabla() devuelve tabla de clientes sin filtro    
    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Persona::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);

        return ($paginator);
    }

    public function addPersona($data, $tipo) {
        $persona = new Persona();
        $persona=$this->setData($persona, $data,$tipo, "S");
        $this->tryAddPersona($persona);
        return $persona;
    }
    
    public function updatePersona($persona, $data) {
        $persona=$this->setData($persona, $data);

        if ($this->tryUpdatePersona($persona)) {
            $_SESSION['MENSAJES']['ficha_cliente'] = 1;
            $_SESSION['MENSAJES']['ficha_cliente_msj'] = 'Datos editados correctamente';
        } else {
            $_SESSION['MENSAJES']['ficha_cliente'] = 0;
            $_SESSION['MENSAJES']['ficha_cliente_msj'] = 'Error al editar datos';
        }
        return true;
    }

    private function setData($persona, $data, $tipo= null, $estado=null){
        $persona->setNombre($data['nombre'])
        ->setTelefono($data['telefono'])
        ->setEmail($data['email']);
        if (isset($tipo)){
            $persona->setTipo($tipo);
        }
        if (isset($estado)){
            $persona->setEstado($estado);
        }
        return $persona;
    }

    public function removePersona($persona) {
        if ($this->tryRemovePersona($persona)) {
            $_SESSION['MENSAJES']['ficha_cliente'] = 1;
            $_SESSION['MENSAJES']['ficha_cliente_msj'] = 'Datos eliminados correctamente';
        } else {
            $_SESSION['MENSAJES']['ficha_cliente'] = 0;
            $_SESSION['MENSAJES']['ficha_cliente_msj'] = 'Error al eliminar datos';
        }
    }

    private function tryAddPersona($persona) {
        try {
            $this->entityManager->persist($persona);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdatePersona($persona) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }
    
    private function tryRemovePersona($persona) {
        try {
            $this->entityManager->remove($persona);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }
           
    public function getPersona($id){
        return $this->entityManager->getRepository(Persona::class)
                ->find($id);
    }

    public function getPersonasTipo($tipo){
        // return $this->entityManager->findBy(['tipo' => $tipo]);
        return $this->entityManager->getRepository(Persona::class)->findBy(['tipo' => $tipo]);
    }

    public function getPersonas(){
        return $this->entityManager->getRepository(Persona::class)->findAll();
    }

    public function buscarPersonas($parametros, $tipos=null, $personas=null) {      
        $entityManager = $this->entityManager;
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('P')
                     ->from(Persona::class, 'P');
        $indices = array_keys($parametros);
        for ($i = 0; $i < count($indices); $i++) {
            $p = $i + 1;
            $nombreCampo = $indices[$i];
            $valorCampo = $parametros[$nombreCampo];
            if ($i == 0) {
                $queryBuilder->where("P.$nombreCampo LIKE ?$p");
            } else {
                    $queryBuilder->andWhere("P.$nombreCampo like ?$p");
            }
            $queryBuilder->setParameter("$p", '%'.$valorCampo.'%');
        }
        if (isset($personas)){
            $queryBuilder->andWhere('P IN (:personas)')
                ->setParameter('personas', $personas);
        }
        if (isset($tipos)){
            $queryBuilder->andWhere('P.tipo IN (:tipos)')
                ->setParameter('tipos', $tipos);
        }
        return $queryBuilder->getQuery();
    }


    public function getData($id){
        $persona = $this->getPersona($id);
        $arr = [
            'nombre' =>$persona->getNombre(),
            'telefono' =>$persona->getTelefono(),
            'email' => $persona->getMail(),
            'estado' =>$persona->getEstado(),
            'tipo' =>$persona->getTipo()
        ];
        return $arr;
    }
    
    public function cambiarEstado($persona, $estado = null){
        if (isset($estado)){
            $persona->setEstado($estado);
            return $estado;
        }
        else{
            if ($persona->getEstado() == "S") {
                $persona->setEstado("N");
            } else if ($persona->getEstado() == "N") {
                $persona->setEstado("S");
            }
            $this->entityManager->flush();
        }
        return $persona->getEstado();    
    }

    public function modificarEstadoCliente($cliente, $estado){
        $persona = $this->getPersona($cliente->getId());
        $persona->setEstado($estado);
    }
}
