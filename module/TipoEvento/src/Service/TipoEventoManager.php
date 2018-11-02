<?php
namespace TipoEvento\Service;

use DBAL\Entity\TipoEvento;
use TipoEvento\Form\TipoEventoForm;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;

/**
 * This service is responsible for adding/editing tipoeventos
 * and changing tipoevento password.
 */
class TipoEventoManager
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
    
     public function getTipoEventos(){
        $tipoeventos = $this->entityManager
                            ->getRepository(TipoEvento::class)->findAll();
        return $tipoeventos;
    }
    
    public function getTipoEventoId($id){
       return $this->entityManager->getRepository(TipoEvento::class)
                ->find($id);
    }
  
    public function getTipoEventoFromForm($form, $data){
        $form->setData($data);
            if($form->isValid()) {
                $data = $form->getData();
                $tipoevento = $this->addTipoEvento($data);
            }
        return $tipoevento;
    }
    /**
     * This method adds a new tipoevento.
     */
    public function addTipoEvento($data) 
    {
        $tipoevento = new TipoEvento();
        $tipoevento->setNombre($data['nombre']);
        $this->entityManager->persist($tipoevento);
        $this->entityManager->flush();
        return $tipoevento;
    }
    
    public function createForm(){
        return new TipoEventoForm('create', $this->entityManager,null);
    }
    
   public function formValid($form, $data){
       $form->setData($data);
       return $form->isValid();  
    }
       
   
   public function getFormForTipoEvento($tipoevento) {

        if ($tipoevento == null) {
            return null;
        }
        $form = new TipoEventoForm('update', $this->entityManager, $tipoevento);
        return $form;
    }
    
    
    public function getFormEdited($form, $tipoevento){
        $form->setData(array(
                    'noombre'=>$tipoevento->getNombre(),
                ));
    }

    /**
     * This method updates data of an existing tipoevento.
     */
    public function updateTipoEvento($tipoevento, $form) 
    {       
        $data = $form->getData();
        $tipoevento->setNombre($data['nombre']);
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }
    
    public function removeTipoEvento($tipoevento){
            $this->entityManager->remove($tipoevento);
            $this->entityManager->flush();
    }
    
    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(TipoEvento::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);

        return ($paginator);
    }
    
} 