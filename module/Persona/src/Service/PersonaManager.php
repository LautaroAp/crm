<?php

namespace Persona\Service;

use DBAL\Entity\Persona;
use DBAL\Entity\Cliente;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;


/**
 * Esta clase se encarga de obtener y modificar los datos y personas adicionales.
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
        $persona=$this->setData($persona, $data,$tipo);
        if ($this->tryAddPersona($persona)) {
            $_SESSION['MENSAJES']['ficha_cliente'] = 1;
            $_SESSION['MENSAJES']['ficha_cliente_msj'] = 'Persona agregada correctamente';
        } else {
            $_SESSION['MENSAJES']['ficha_cliente'] = 0;
            $_SESSION['MENSAJES']['ficha_cliente_msj'] = 'Error al guardar datos';

        }
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

    private function setData($persona, $data, $tipo= null){
        $persona->setNombre($data['nombre'])
        ->setTelefono($data['telefono'])
        ->setEmail($data['mail'])
        ->setEstado("S");
        if (isset($tipo)){
            $persona->setTipo($tipo);
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

    public function buscarPersonas($parametros) {      
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
                if ($nombreCampo == 'nombre' || $nombreCampo == 'apellido') {
                    $queryBuilder->where("P.nombre LIKE ?$p");
                } else {
                    $queryBuilder->where("P.$nombreCampo LIKE ?$p");
                }
            } else {
                if ($nombreCampo == 'nombre' || $nombreCampo == 'apellido') {
                    $queryBuilder->andWhere("P.nombre LIKE ?$p");
                } else {
                    $queryBuilder->andWhere("P.$nombreCampo like ?$p");
                }
            }
            $queryBuilder->setParameter("$p", '%'.$valorCampo.'%');
        }
        return $queryBuilder->getQuery();
    }

    public function getData($id){
        $persona = $this->getPersona($id);
        $arr = [
            'nombre' =>$persona->getNombre(),
            'telefono' =>$persona->getTelefono(),
            'mail' => $persona->getMail(),
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
