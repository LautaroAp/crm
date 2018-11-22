<?php
namespace Servicio\Service;

use DBAL\Entity\Servicio;
use Servicio\Form\ServicioForm;

/**
 * This service is responsible for adding/editing servicios
 * and changing servicio password.
 */
class ServicioManager
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
    
     public function getServicios(){
        $servicios=$this->entityManager->getRepository(Servicio::class)->findAll();
        return $servicios;
    }
    
     public function getServicioId($id){
       return $this->entityManager->getRepository(Servicio::class)
                ->find($id);
    }
  
    public function getServicioFromForm($form, $data){
        $form->setData($data);
            if($form->isValid()) {
                $data = $form->getData();
                $servicio = $this->addServicio($data);
            }
        return $servicio;
    }
    /**
     * This method adds a new servicio.
     */
    public function addServicio($data) 
    {
        $servicio = new Servicio();
        $servicio->setDescripcion($data['descripcion']);
        $servicio->setCosto($data['costo']);
        $servicio->setCant_animales($data['cant_animales']);
        $this->entityManager->persist($servicio);
        $this->entityManager->flush();
        return $servicio;
    }
    
    public function createForm(){
        return new ServicioForm('create', $this->entityManager,null);
    }
    
   public function formValid($form, $data){
       $form->setData($data);
       return $form->isValid();  
    }
       
   
   public function getFormForServicio($servicio) {

        if ($servicio == null) {
            return null;
        }
        $form = new ServicioForm('update', $this->entityManager, $servicio);
        return $form;
    }
    
    
    public function getFormEdited($form, $servicio){
        $form->setData(array(
            'descripcion' => $servicio->getDescripcion(),
            'costo' => $servicio->getCosto(),
            'cant_animales' => $servicio->getCant_animales(),
        ));
    }

    /**
     * This method updates data of an existing servicio.
     */
    public function updateServicio($servicio, $form) 
    {
        $data = $form->getData();
        $servicio->setDescripcion($data['descripcion']);
        $servicio->setCosto($data['costo']);
        $servicio->setCant_animales($data['cant_animales']);

        // Apply changes to database.
        $this->entityManager->flush();
     
        return true;
    }
    
    public function removeServicio($servicio)
    {
            $this->entityManager->remove($servicio);
            $this->entityManager->flush();
           
    }
} 