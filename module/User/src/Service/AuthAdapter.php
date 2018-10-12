<?php
namespace User\Service;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;
use User\Entity\User;
use DBAL\Entity\Ejecutivo;

/**
 * Adapter used for authenticating user. It takes login and password on input
 * and checks the database if there is a user with such login (email) and password.
 * If such user exists, the service returns its identity (email). The identity
 * is saved to session and can be retrieved later with Identity view helper provided
 * by ZF3.
 */
class AuthAdapter implements AdapterInterface
{
    /**
     * User email.
     * @var string 
     */
    private $usuario;
    
    /**
     * Password
     * @var string 
     */
    private $password;
    
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager 
     */
    private $entityManager;
        
    /**
     * Constructor.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * Sets user email.     
     */
    public function setUsuario($usuario) 
    {
        $this->usuario = $usuario;        
    }
    
    public function getUsuario() 
    {
        return $this->usuario;        
    }
    
    /**
     * Sets password.     
     */
    public function setPassword($password) 
    {
        $this->password = (string)$password;        
    }
    
    /**
     * Performs an authentication attempt.
     */
    public function authenticate()
    {    
        // Check the database if there is a user with such email.
        $user = $this->entityManager->getRepository(Ejecutivo::class)
                ->findOneBy(["usuario"=>$this->getUsuario()]);
        
        // If there is no such user, return 'Identity Not Found' status.
        if ($user == null) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND, 
                null, 
                ['El usuario no existe']);        
        }   
        
        if ($this->password == $user->getClave()){
            return new Result(
                    Result::SUCCESS, 
                    $this->usuario, 
                    ['Autenticacion exitosa']);        
        }             
        
        // If password check didn't pass return 'Invalid Credential' failure status.
        return new Result(
                Result::FAILURE_CREDENTIAL_INVALID, 
                null, 
                ['Clave incorrecta']);        
    }
}


