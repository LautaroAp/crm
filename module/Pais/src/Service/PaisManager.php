<?php
namespace Pais\Service;

use DBAL\Entity\Pais;
use Pais\Form\PaisForm;



/**
 * This service is responsible for adding/editing paiss
 * and changing pais password.
 */
class PaisManager
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
    
     public function getPaises(){
        $paiss=$this->entityManager->getRepository(Pais::class)->findAll();
        return $paiss;
    }
    
    public function getPaisId($id){
       return $this->entityManager->getRepository(Pais::class)
                ->find($id);
    }
  
    public function getPaisFromForm($form, $data){
        $form->setData($data);
            if($form->isValid()) {
                $data = $form->getData();
                $pais = $this->addPais($data);
            }
        return $pais;
    }
    /**
     * This method adds a new pais.
     */
    public function addPais($data) 
    {
        $pais = new Pais();
        $pais->setNombre($data['nombre_pais']);        
        $this->entityManager->persist($pais);
        $this->entityManager->flush();
        return $pais;
    }
    
    public function createForm(){
        return new PaisForm('create', $this->entityManager,null);
    }
    
   public function formValid($form, $data){
       $form->setData($data);
       return $form->isValid();  
    }
       
   
   public function getFormForPais($pais) {

        if ($pais == null) {
            return null;
        }
        $form = new PaisForm('update', $this->entityManager, $pais);
        return $form;
    }
    
    
    public function getFormEdited($form, $pais){
        $form->setData(array(
                    'nombre_pais'=>$pais->getNombre(),
                ));
    }

    /**
     * This method updates data of an existing pais.
     */
    public function updatePais($pais, $form) 
    {       
        $data = $form->getData();
        $pais->setNombre($data['nombre_pais']);           
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }
    
    public function removePais($pais)
    {
            $this->entityManager->remove($pais);
            $this->entityManager->flush();
           
    }
} 