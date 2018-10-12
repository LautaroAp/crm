<?php
namespace Ejecutivo\Service;

use DBAL\Entity\Ejecutivo;
//use Zend\Crypt\Password\Bcrypt;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Math\Rand;
use Zend\Mail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

/**
 * This service is responsible for adding/editing ejecutivos
 * and changing ejecutivo password.
 */
class EjecutivoManager
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
    
    
    //La funcion getTabla() devuelve tabla de clientes sin filtro    
    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Ejecutivo::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);

        return ($paginator);
    }
    
    /**
     * This method adds a new ejecutivo.
     */
    
    public function addEjecutivo($data) 
    {
        
        // Create new Ejecutivo entity.
        $ejecutivo = new Ejecutivo();
        $ejecutivo->setApellido($data['apellido']);
        $ejecutivo->setNombre($data['nombre']);
        $ejecutivo->setMail($data['mail']);
        $ejecutivo->setUsuario($data['usuario']);
        $ejecutivo->setClave($data['clave']);        
                 
        // Add the entity to the entity manager.
        $this->entityManager->persist($ejecutivo);
        
        // Apply changes to database.
        $this->entityManager->flush();
        
        return $ejecutivo;
    }
    
    public function updateEjecutivo($ejecutivo, $data) 
    {
        /*
        // Do not allow to change user email if another user with such email already exits.
        if($user->getEmail()!=$data['email'] && $this->checkUserExists($data['email'])) {
            throw new \Exception("Another user with email address " . $data['email'] . " already exists");
        }*/
        
        $ejecutivo->setApellido($data['apellido']);
        $ejecutivo->setNombre($data['nombre']);
        $ejecutivo->setMail($data['mail']);
        $ejecutivo->setUsuario($data['usuario']);
        $ejecutivo->setClave($data['clave']);   
        
        // Apply changes to database.
        $this->entityManager->flush();

        return true;
    }
    
    public function removeEjecutivo($ejecutivo) 
    {
        
        $this->entityManager->remove($ejecutivo);
        
        $this->entityManager->flush();
    }
    
    public function recuperarEjecutivo($id_ejecutivo)
    {
        $ejecutivo = $this->entityManager->getRepository(Ejecutivo::class)
                    ->findOneBy(['id_ejecutivo' => $id_ejecutivo]);
        
        return $ejecutivo;
    }
    
}

