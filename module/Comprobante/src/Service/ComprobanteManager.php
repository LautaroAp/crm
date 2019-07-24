<?php

namespace Comprobante\Service;

use DBAL\Entity\Comprobante;
use DBAL\Entity\TipoComprobante;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;

/**
 * This service is responsible for adding/editing categoriaProductos
 * and changing categoriaProducto password.
 */
class ComprobanteManager {

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

    public function getComprobantes() {
        $comprobante = $this->entityManager->getRepository(Comprobante::class)->findAll();
        return $comprobante;
    }

    public function getComprobante($id) {
        return $this->entityManager->getRepository(Comprobante::class)
                        ->find($id);
    }


    public function getComprobanteTipo($id) {
        return $this->entityManager->getRepository(TipoComprobante::class)
                        ->find($id);
    }

    /**
     * This method adds a new categoriaProducto.
     */
    public function addComprobante($data) {
        $comprobante = new Comprobante();
        $this->addData($comprobante, $data);
        if ($this->tryAddComprobante($comprobante)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría agregada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al agregar categoría';
        }
        return $comprobante;
    }

    /**
     * This method updates data of an existing categoriaProducto.
     */
    public function updateComprobante($comprobante, $data) {
        $this->addData($comprobante, $data);        
        if ($this->tryUpdateComprobante($comprobante)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría editada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al editar categoría';
        }
        return true;
    }

    private function addData($comprobante, $data) {
        $comprobante->setNombre($data['nombre']);
        $comprobante->setDescripcion($data['descripcion']);
    }

    public function removeComprobante($id) {
        $comprobante= $this->entityManager->getRepository(Comprobante::class)
                        ->find($id);
        if ($this->tryRemoveComprobante($comprobante)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría eliminada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al eliminar categoría';
        }
    }

    private function tryAddComprobante($comprobante) {
        try {
            $this->entityManager->persist($comprobante);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            echo 'ERROR: ', $e->getMessage(),"\n" ;
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateComprobante($comprobante) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            echo 'ERROR: ', $e->getMessage(),"\n" ;
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryRemoveComprobante($comprobante) {
        try {
            $this->entityManager->remove($comprobante);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            echo 'ERROR: ', $e->getMessage(),"\n" ;
            $this->entityManager->rollBack();
            return false;
        }
    }

}
