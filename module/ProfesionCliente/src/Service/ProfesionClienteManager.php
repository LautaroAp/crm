<?php
namespace ProfesionCliente\Service;

use DBAL\Entity\ProfesionCliente;
use ProfesionCliente\Form\ProfesionClienteForm;



class ProfesionClienteManager
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
    
     public function getProfesionClientes(){
        $profesionclientes=$this->entityManager->getRepository(ProfesionCliente::class)->findAll();
        return $profesionclientes;
    }
    
    public function getProfesionClienteId($id){
       return $this->entityManager->getRepository(ProfesionCliente::class)
                ->find($id);
    }
  
    public function getProfesionClienteFromForm($form, $data){
        $form->setData($data);
            if($form->isValid()) {
                $data = $form->getData();
                $profesioncliente = $this->addProfesionCliente($data);
            }
        return $profesioncliente;
    }
    /**
     * This method adds a new profesioncliente.
     */
    public function addProfesionCliente($data) 
    {
        $profesioncliente = new ProfesionCliente();
        $profesioncliente->setNombre($data['nombre']);
        $this->entityManager->persist($profesioncliente);
        $this->entityManager->flush();
        return $profesioncliente;
    }
    
    public function createForm(){
        return new ProfesionClienteForm('create', $this->entityManager,null);
    }
    
   public function formValid($form, $data){
       $form->setData($data);
       return $form->isValid();  
    }
       
   
   public function getFormForProfesionCliente($profesioncliente) {

        if ($profesioncliente == null) {
            return null;
        }
        $form = new ProfesionClienteForm('update', $this->entityManager, $profesioncliente);
        return $form;
    }
    
    
    public function getFormEdited($form, $profesioncliente){
        $form->setData(array(
                    'nombre'=>$profesioncliente->getNombre()                  
                ));
    }

    /**
     * This method updates data of an existing profesioncliente.
     */
    public function updateProfesionCliente($profesioncliente, $form) 
    {       
        $data = $form->getData();
        $profesioncliente->setNombre($data['nombre']);
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }
    
    public function removeProfesionCliente($profesioncliente){
       

            $this->entityManager->remove($profesioncliente);
            $this->entityManager->flush();
           
    }
    //ver de borrar las profesionclientes 
} 