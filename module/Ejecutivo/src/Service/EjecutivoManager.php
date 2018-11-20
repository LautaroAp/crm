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
class EjecutivoManager {

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

    public function getTabla() {
        $query = $this->busquedaActivos();
        $adapter = new DoctrineAdapter(new ORMPaginator($query));
        $paginator = new Paginator($adapter);
        return $paginator;
    }

    public function busquedaActivos() {
        $entityManager = $this->entityManager;
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('E')
                ->from(Ejecutivo::class, 'E')
                ->where('E.activo = :state')->setParameter('state', 'S');
        return $queryBuilder->getQuery();
    }

    /**
     * This method adds a new ejecutivo.
     */
    public function addEjecutivo($data) {
        $ejecutivo = new Ejecutivo();
        $ejecutivo->setApellido($data['apellido']);
        $ejecutivo->setNombre($data['nombre']);
        $ejecutivo->setMail($data['mail']);
        $ejecutivo->setUsuario($data['usuario']);
        $ejecutivo->setClave($data['clave']);
        $ejecutivo->setActivo('S');
        if ($this->tryAddEjecutivo($ejecutivo)) {
            $_SESSION['MENSAJES']['ejecutivo'] = 1;
            $_SESSION['MENSAJES']['ejecutivo_msj'] = 'Ejecutivo eliminados correctamente';
        } else {
            $_SESSION['MENSAJES']['ejecutivo'] = 0;
            $_SESSION['MENSAJES']['ejecutivo_msj'] = 'Error al eliminar ejecutivo';
        }
        return $ejecutivo;
    }

    public function updateEjecutivo($ejecutivo, $data) {
        $ejecutivo->setApellido($data['apellido']);
        $ejecutivo->setNombre($data['nombre']);
        $ejecutivo->setMail($data['mail']);
        $ejecutivo->setUsuario($data['usuario']);
        $ejecutivo->setClave($data['clave']);
        if ($this->tryUpdateEjecutivo($ejecutivo)) {
            $_SESSION['MENSAJES']['ejecutivo'] = 1;
            $_SESSION['MENSAJES']['ejecutivo_msj'] = 'Ejecutivo editado correctamente';
        } else {
            $_SESSION['MENSAJES']['ejecutivo'] = 0;
            $_SESSION['MENSAJES']['ejecutivo_msj'] = 'Error al editar ejecutivo';
        }
        return true;
    }

    public function removeEjecutivo($ejecutivo) {
        $ejecutivo->inactivar();
        $this->entityManager->flush();
        $_SESSION['MENSAJES']['ejecutivo'] = 1;
        $_SESSION['MENSAJES']['ejecutivo_msj'] = 'Ejecutivo dado de Baja correctamente';
    }

    public function recuperarEjecutivo($id) {
        if (!is_null($this->entityManager)) {
            $ejecutivo = $this->entityManager
                    ->getRepository(Ejecutivo::class)
                    ->findOneById($id);
            return $ejecutivo;
        }
    }

    private function tryAddEjecutivo($ejecutivo) {
        try {
            $this->entityManager->persist($ejecutivo);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateEjecutivo($ejecutivo) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }
}
