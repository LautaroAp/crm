<?php

namespace Banco\Service;

use DBAL\Entity\Banco;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;

/**
 * This service is responsible for adding/editing bancos
 * and changing banco password.
 */
class BancoManager {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Constructs the service.
     */
    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    public function getBancos() {
        $bancos = $this->entityManager->getRepository(Banco::class)->findAll();
        return $bancos;
    }

    public function getBancoId($id) {
        return $this->entityManager->getRepository(Banco::class)
                        ->find($id);
    }


    /**
     * This method adds a new banco.
     */
    public function addBanco($data) {
        $banco = new Banco();
        $banco->setNombre($data['nombre']);
        $banco->setDescripcion($data['descripcion']);
        if ($this->tryAddBanco($banco)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría agregada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al agregar categoría';
        }
        return $banco;
    }

    /**
     * This method updates data of an existing banco.
     */
    public function updateBanco($banco, $data) {
        $banco->setNombre($data['nombre']);
        $banco->setDescripcion($data['descripcion']);
        if ($this->tryUpdateBanco($banco)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría editada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al editar categoría';
        }
        return true;
    }

    public function removeBanco($id) {
        $banco= $this->entityManager->getRepository(Banco::class)
                        ->find($id);
        if ($this->tryRemoveBanco($banco)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría eliminada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al eliminar categoría';
        }
    }

    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Banco::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    private function tryAddBanco($banco) {
        try {
            $this->entityManager->persist($banco);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateBanco($banco) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryRemoveBanco($banco) {
        try {
            $this->entityManager->remove($banco);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }


}
