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
        $categoriaProducto = new Iva();
        $categoriaProducto->setNombre($data['nombre']);
        if ($this->tryAddIva($categoriaProducto)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría agregada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al agregar categoría';
        }
        return $categoriaProducto;
    }

    public function createForm() {
        return new IvaForm('create', $this->entityManager, null);
    }

    public function formValid($form, $data) {
        $form->setData($data);
        return $form->isValid();
    }

    public function getFormForIva($categoriaProducto) {
        if ($categoriaProducto == null) {
            return null;
        }
        $form = new IvaForm('update', $this->entityManager, $categoriaProducto);
        return $form;
    }

    public function getFormEdited($form, $categoriaProducto) {
        $form->setData(array(
            'nombre' => $categoriaProducto->getNombre(),
        ));
    }

    /**
     * This method updates data of an existing categoriaProducto.
     */
    public function updateIva($categoriaProducto, $form) {
        $data = $form->getData();
        $categoriaProducto->setNombre($data['nombre']);
        if ($this->tryUpdateIva($categoriaProducto)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría editada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al editar categoría';
        }
        return true;
    }

    public function removeIva($id) {
        $categoriaProducto= $this->entityManager->getRepository(Iva::class)
                        ->find($id);
        if ($this->tryRemoveIva($categoriaProducto)) {
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

    private function tryAddIva($categoriaProducto) {
        try {
            $this->entityManager->persist($categoriaProducto);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateIva($categoriaProducto) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryRemoveIva($categoriaProducto) {
        try {
            $this->entityManager->remove($categoriaProducto);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

}
