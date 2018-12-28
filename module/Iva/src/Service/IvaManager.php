<?php

namespace Iva\Service;

use DBAL\Entity\Iva;
use Iva\Form\IvaForm;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;

/**
 * This service is responsible for adding/editing categoriaProductos
 * and changing categoriaProducto password.
 */
class IvaManager {

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

    public function getIvas() {
        $iva = $this->entityManager->getRepository(Iva::class)->findAll();
        return $iva;
    }

    public function getIva($id) {
        return $this->entityManager->getRepository(Iva::class)
                        ->find($id);
    }

    public function getIvaPorValor($valor){
        return $this->entityManager->getRepository(Iva::class)
                        ->findOneBy(['valor'=>$valor]);
    }
   
    /**
     * This method adds a new categoriaProducto.
     */
    public function addIva($data) {
        $iva = new Iva();
        $this->addData($iva, $data);
        if ($this->tryAddIva($iva)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría agregada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al agregar categoría';
        }
        return $iva;
    }

    /**
     * This method updates data of an existing categoriaProducto.
     */
    public function updateIva($iva, $data) {
        $this->addData($iva, $data);        
        if ($this->tryUpdateIva($iva)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría editada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al editar categoría';
        }
        return true;
    }

    private function addData($iva, $data) {
        $iva->setNombre($data['nombre']);
        $iva->setDescripcion($data['descripcion']);
        $iva->setValor($data['valor']);
    }

    public function removeIva($id) {
        $iva= $this->entityManager->getRepository(Iva::class)
                        ->find($id);
        if ($this->tryRemoveIva($iva)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría eliminada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al eliminar categoría';
        }
    }

    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Iva::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    private function tryAddIva($iva) {
        try {
            $this->entityManager->persist($iva);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateIva($iva) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryRemoveIva($iva) {
        try {
            $this->entityManager->remove($iva);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    // public function createForm() {
    //     return new IvaForm('create', $this->entityManager, null);
    // }

    // public function formValid($form, $data) {
    //     $form->setData($data);
    //     return $form->isValid();
    // }

    // public function getFormForIva($iva) {
    //     if ($iva == null) {
    //         return null;
    //     }
    //     $form = new IvaForm('update', $this->entityManager, $iva);
    //     return $form;
    // }

    // public function getFormEdited($form, $iva) {
    //     $form->setData(array(
    //         'nombre' => $iva->getNombre(),
    //     ));
    // }

}
