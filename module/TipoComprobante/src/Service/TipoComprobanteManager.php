<?php

namespace TipoComprobante\Service;

use DBAL\Entity\TipoComprobante;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;

/**
 * This service is responsible for adding/editing categoriaProductos
 * and changing categoriaProducto password.
 */
class TipoComprobanteManager {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    private $comprobanteManager;
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $comprobanteManager) {
        $this->entityManager = $entityManager;
        $this->comprobanteManager = $comprobanteManager;
    }

    public function getTipoComprobantes($idComprobante=null) {
        if (isset($idComprobante)){
            $tipoComprobante = $this->entityManager->getRepository(TipoComprobante::class)->findBy(['comprobante'=>$idComprobante]); 
        }
        else{
            $tipoComprobante = $this->entityManager->getRepository(TipoComprobante::class)->findAll(); 
        }
        return $tipoComprobante;
    }

    public function getTipoComprobante($id) {
        return $this->entityManager->getRepository(TipoComprobante::class)
                        ->find($id);
    }

    /**
     * This method adds a new categoriaProducto.
     */
    public function addTipoComprobante($data) {
        $tipoComprobante = new TipoComprobante();
        $this->addData($tipoComprobante, $data);
        if ($this->tryAddTipoComprobante($tipoComprobante)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría agregada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al agregar categoría';
        }
        return $tipoComprobante;
    }

    /**
     * This method updates data of an existing categoriaProducto.
     */
    public function updateTipoComprobante($tipoComprobante, $data) {
        $this->addData($tipoComprobante, $data);        
        if ($this->tryUpdateTipoComprobante($tipoComprobante)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría editada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al editar categoría';
        }
        return true;
    }

    private function addData($tipoComprobante, $data) {

        $comprobante = $this->comprobanteManager->getComprobante($data['comprobante']);
      
        $tipoComprobante->setComprobante($comprobante);
        $tipoComprobante->setDescripcion($data['descripcion']);
        $tipoComprobante->setCodigo($data['codigo']);
        $tipoComprobante->setTipo(strtoupper($data['tipo'])); 
        
    }

    public function removeTipoComprobante($id) {
        $tipoComprobante= $this->entityManager->getRepository(TipoComprobante::class)
                        ->find($id);
        if ($this->tryRemoveTipoComprobante($tipoComprobante)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría eliminada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al eliminar categoría';
        }
    }

    private function tryAddTipoComprobante($tipoComprobante) {
        try {
            $this->entityManager->persist($tipoComprobante);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            echo 'ERROR: ', $e->getMessage(),"\n" ;
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateTipoComprobante($tipoComprobante) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            echo 'ERROR: ', $e->getMessage(),"\n" ;
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryRemoveTipoComprobante($tipoComprobante) {
        try {
            $this->entityManager->remove($tipoComprobante);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            echo 'ERROR: ', $e->getMessage(),"\n" ;
            $this->entityManager->rollBack();
            return false;
        }
    }

}
