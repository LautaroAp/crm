<?php
namespace Provincia\Service;

use DBAL\Entity\Provincia;
use DBAL\Entity\Pais;
use Provincia\Form\ProvinciaForm;

/**
 * This service is responsible for adding/editing provincias
 * and changing provincia password.
 */
class ProvinciaManager
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
    
     public function getProvincias(){
        $provincias=$this->entityManager->getRepository(Provincia::class)->findAll();
        return $provincias;
    }
    
    public function getProvinciaId($id_pais, $id_provincia){
       return $this->entityManager->getRepository(Provincia::class)
                ->find($id_pais, $id_provincia);
    }
  
    public function getProvinciaFromForm($form, $data){
        $form->setData($data);
            if($form->isValid()) {
                $data = $form->getData();
                $provincia = $this->addProvincia($data);
            }
        return $provincia;
    }
    /**
     * This method adds a new provincia.
     */
    public function addProvincia($data) 
    {
        $provincia = new Provincia();
        $provincia->setVersion_provincia($data['version_provincia']);
        $provincia->setNombre_provincia($data['nombre_provincia']);        
        $this->entityManager->persist($provincia);
        $this->entityManager->flush();
        return $provincia;
    }
    
    public function createForm(){
        return new ProvinciaForm('create', $this->entityManager,null);
    }
    
   public function formValid($form, $data){
       $form->setData($data);
       return $form->isValid();  
    }
       
   
   public function getFormForProvincia($provincia) {
        if ($provincia == null) {
            return null;
        }
        $form = new ProvinciaForm('update', $this->entityManager, $provincia);
        return $form;
    }
    
    
    public function getFormEdited($form, $provincia){
        $form->setData(array(
                    'nombre_provincia'=>$provincia->getNombre_provincia(),
                ));
    }

    /**
     * This method updates data of an existing provincia.
     */
    public function updateProvincia($provincia, $form) 
    {       
        $data = $form->getData();
        $provincia->setNombre_provincia($data['nombre_provincia']);           
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }
    
    public function removeProvincia($provincia)
    {
            $this->entityManager->remove($provincia);
            $this->entityManager->flush();
           
    }
    
    public function getPais($id=null){
        if (isset ($id)) {
            return $this->entityManager
                ->getRepository(Pais::class)
                ->findOneBy(['id_pais'=>$id]);
        }
        return $this->entityManager
                ->getRepository(Pais::class)
                ->findAll();
    }
} 