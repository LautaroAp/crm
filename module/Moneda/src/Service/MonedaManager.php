<?php

namespace Moneda\Service;

use DBAL\Entity\Moneda;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;

/**
 * This service is responsible for adding/editing monedas
 * and changing moneda password.
 */
class MonedaManager {

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

    public function getMonedas() {
        $monedas = $this->entityManager->getRepository(Moneda::class)->findAll();
        return $monedas;
    }

    public function getMonedaId($id) {
        return $this->entityManager->getRepository(Moneda::class)
                        ->find($id);
    }


    /**
     * This method adds a new moneda.
     */
    public function addMoneda($data) {
        $moneda = new Moneda();
        $moneda->setNombre($data['nombre']);
        if ($this->tryAddMoneda($moneda)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría agregada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al agregar categoría';
        }
        return $moneda;
    }

    /**
     * This method updates data of an existing moneda.
     */
    public function updateMoneda($moneda, $data) {
        $moneda->setNombre($data['nombre']);
        if ($this->tryUpdateMoneda($moneda)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría editada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al editar categoría';
        }
        return true;
    }

    public function removeMoneda($id) {
        $moneda= $this->entityManager->getRepository(Moneda::class)
                        ->find($id);
        if ($this->tryRemoveMoneda($moneda)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría eliminada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al eliminar categoría';
        }
    }

    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Moneda::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    private function tryAddMoneda($moneda) {
        try {
            $this->entityManager->persist($moneda);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateMoneda($moneda) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryRemoveMoneda($moneda) {
        try {
            $this->entityManager->remove($moneda);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }


}
