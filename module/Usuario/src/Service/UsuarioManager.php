<?php

namespace Usuario\Service;

use DBAL\Entity\Usuario;
use DBAL\Entity\Cliente;
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
 * This service is responsible for adding/editing usuarios
 * and changing usuario password.
 */
class UsuarioManager {

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
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Usuario::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);

        return ($paginator);
    }

    /**
     * This method adds a new usuario.
     */
    public function addUsuario($data) {

        $usuario = new Usuario();
        $usuario->setNombre($data['nombre']);
        $usuario->setTelefono($data['telefono']);
        $usuario->setMail($data['mail']);
        $usuario->setSkype($data['skype']);
        $idCliente = $data['id'];
        
        $entityManager = $this->entityManager;
        $cliente = $this->entityManager
                ->getRepository(Cliente::class)
                ->findOneBy(['Id' => $idCliente]);
        
        $usuario->setId_cliente($cliente);
        // Add the entity to the entity manager.
        $this->entityManager->persist($usuario);
        // Apply changes to database.
        $this->entityManager->flush();

        return $usuario;
    }

    public function updateUsuario($usuario, $data) {

        $usuario->setNombre($data['nombre']);
        $usuario->setTelefono($data['telefono']);
        $usuario->setMail($data['mail']);
        $usuario->setSkype($data['skype']);
        // Apply changes to database.
        $this->entityManager->flush();

        return true;
    }

    public function removeUsuario($usuario) {

        $this->entityManager->remove($usuario);
        $this->entityManager->flush();
    }

    public function recuperarUsuario($id_usuario) {
        $usuario = $this->entityManager->getRepository(Usuario::class)
                ->findOneBy(['id_usuario' => $id_usuario]);

        return $usuario;
    }

}
