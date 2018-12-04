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
class EjecutivoInactivoManager extends EjecutivoManager {

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

    //La funcion getTabla() devuelve tabla de ejecutivos inactivoss 
    public function getTabla() {
        $query = $this->busquedaInactivos();
        $adapter = new DoctrineAdapter(new ORMPaginator($query));
        $paginator = new Paginator($adapter);
        return $paginator;
    }

    public function busquedaInactivos() {
        $parametros = ['estado'=>'SN', 'tipo' => 'EJECUTIVO'];
        $query = $this->personaManager->buscarPersonas($parametros);
        return $query;
    }
    
    public function activarEjecutivo($id){
        $ejecutivo = $this->recuperarEjecutivo($id);
        $persona = $ejecutivo->getPersona();
        $this->personaManager->cambiarEstado($persona);
        $this->entityManager->flush();
    }
}
