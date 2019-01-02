<?php
namespace Profesion\Service;

use DBAL\Entity\Profesion;
use Profesion\Form\ProfesionForm;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;


class ProfesionManager
{
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
    public function __construct($entityManager, $viewRenderer, $config) 
    {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
    }
    
     public function getProfesiones(){
        $profesiones=$this->entityManager->getRepository(Profesion::class)->findAll();
        return $profesiones;
    }
    
    public function getProfesionId($id){
       return $this->entityManager->getRepository(Profesion::class)
                ->find($id);
    }
  
   
    /**
     * This method adds a new profesioncliente.
     */
    public function addProfesion($data) 
    {
        $profesion = new Profesion();
        $this->addData($profesion, $data);
        if ($this->tryAddProfesion($profesion)) {
            $_SESSION['MENSAJES']['profesion_cliente'] = 1;
            $_SESSION['MENSAJES']['profesion_cliente_msj'] = 'Profesión agregada correctamente';
        } else {
            $_SESSION['MENSAJES']['profesion_cliente'] = 0;
            $_SESSION['MENSAJES']['profesion_cliente_msj'] = 'Error al agregar profesión';
        }
        return $profesion;
    }
    

    /**
     * This method updates data of an existing profesioncliente.
     */
    public function updateProfesion($profesion, $data) 
    {       
        $this->addData($profesion, $data);
        if ($this->tryUpdateProfesion($profesion)) {
            $_SESSION['MENSAJES']['profesion_cliente'] = 1;
            $_SESSION['MENSAJES']['profesion_cliente_msj'] = 'Profesión editada correctamente';
        } else {
            $_SESSION['MENSAJES']['profesion_cliente'] = 0;
            $_SESSION['MENSAJES']['profesion_cliente_msj'] = 'Error al editar profesión';
        }
        return true;
    }
    
    private function addData($profesion, $data){
        $profesion->setNombre($data['nombre']);
        $profesion->setDescripcion($data['descripcion']);
        return $profesion;
    }

    public function removeProfesion($profesion){
       if ($this->tryRemoveProfesion($profesion)) {
            $_SESSION['MENSAJES']['profesion_cliente'] = 1;
            $_SESSION['MENSAJES']['profesion_cliente_msj'] = 'Profesión eliminada correctamente';
        } else {
            $_SESSION['MENSAJES']['profesion_cliente'] = 0;
            $_SESSION['MENSAJES']['profesion_cliente_msj'] = 'Error al eliminar profesión';
        }           
    }
    
    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Profesion::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);

        return ($paginator);
    }
    
    private function tryAddProfesion($profesion) {
        try {
            $this->entityManager->persist($profesion);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateProfesion() {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }
    
    private function tryRemoveProfesion($profesion) {
        try {
            $this->entityManager->remove($profesion);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }
} 