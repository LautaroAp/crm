<?php

namespace TipoFactura\Service;

use DBAL\Entity\TipoFactura;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;

/**
 * This service is responsible for adding/editing categoriaProductos
 * and changing categoriaProducto password.
 */
class TipoFacturaManager {

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

    public function getTipoFacturas() {
        $tipoFactura = $this->entityManager->getRepository(TipoFactura::class)->findAll();
        return $tipoFactura;
    }

    public function getTipoFactura($id) {
        return $this->entityManager->getRepository(TipoFactura::class)
                        ->find($id);
    }

    /**
     * This method adds a new categoriaProducto.
     */
    public function addTipoFactura($data) {
        $tipoFactura = new TipoFactura();
        $this->addData($tipoFactura, $data);
        if ($this->tryAddTipoFactura($tipoFactura)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría agregada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al agregar categoría';
        }
        return $tipoFactura;
    }

    /**
     * This method updates data of an existing categoriaProducto.
     */
    public function updateTipoFactura($tipoFactura, $data) {
        $this->addData($tipoFactura, $data);        
        if ($this->tryUpdateTipoFactura($tipoFactura)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría editada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al editar categoría';
        }
        return true;
    }

    private function addData($tipoFactura, $data) {
        $tipoFactura->setNombre($data['nombre']);
        $tipoFactura->setDescripcion($data['descripcion']);
    }

    public function removeTipoFactura($id) {
        $tipoFactura= $this->entityManager->getRepository(TipoFactura::class)
                        ->find($id);
        if ($this->tryRemoveTipoFactura($tipoFactura)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría eliminada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al eliminar categoría';
        }
    }

    private function tryAddTipoFactura($tipoFactura) {
        try {
            $this->entityManager->persist($tipoFactura);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            echo 'ERROR: ', $e->getMessage(),"\n" ;
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateTipoFactura($tipoFactura) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            echo 'ERROR: ', $e->getMessage(),"\n" ;
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryRemoveTipoFactura($tipoFactura) {
        try {
            $this->entityManager->remove($tipoFactura);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            echo 'ERROR: ', $e->getMessage(),"\n" ;
            $this->entityManager->rollBack();
            return false;
        }
    }

}
