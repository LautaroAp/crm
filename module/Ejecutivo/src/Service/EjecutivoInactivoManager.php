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
     * Constructs the service.
     */

    public function __construct($entityManager, $personaManager) {
        parent::__construct($entityManager,$personaManager);

    }
    
    //La funcion getTabla() devuelve tabla de ejecutivos inactivos

    public function getTabla() {
        $query = $this->getPersonasActivas();
        $personas = $query->getResult();
        $ejecutivos = $this->getEjecutivosFromPersonas($personas);
        $adapter = new DoctrineAdapter(new ORMPaginator($ejecutivos));
        $paginator = new Paginator($adapter);
        return $paginator;
    }

    private function getPersonasActivas(){
        $parametros= Array();
        $parametros+=['tipo'=> $this->tipo];
        $parametros+=['estado'=>"N"];
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
