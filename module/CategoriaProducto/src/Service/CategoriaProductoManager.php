<?php

namespace CategoriaProducto\Service;

use DBAL\Entity\CategoriaProducto;
use CategoriaProducto\Form\CategoriaProductoForm;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;

/**
 * This service is responsible for adding/editing categoriaProductos
 * and changing categoriaProducto password.
 */
class CategoriaProductoManager {

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

    public function getCategoriaProductos() {
        $categoriaProductos = $this->entityManager->getRepository(CategoriaProducto::class)->findAll();
        return $categoriaProductos;
    }

    public function getCategoriaProductoId($id) {
        return $this->entityManager->getRepository(CategoriaProducto::class)
                        ->find($id);
    }

    public function getCategoriaProductoFromForm($form, $data) {
        $form->setData($data);
        if ($form->isValid()) {
            $data = $form->getData();
            $categoriaProducto = $this->addCategoriaProducto($data);
        }
        return $categoriaProducto;
    }

    /**
     * This method adds a new categoriaProducto.
     */
    public function addCategoriaProducto($data) {
        $categoriaProducto = new CategoriaProducto();
        $categoriaProducto->setNombre($data['nombre']);
        if ($this->tryAddCategoriaProducto($categoriaProducto)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría agregada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al agregar categoría';
        }
        return $categoriaProducto;
    }

    public function createForm() {
        return new CategoriaProductoForm('create', $this->entityManager, null);
    }

    public function formValid($form, $data) {
        $form->setData($data);
        return $form->isValid();
    }

    public function getFormForCategoriaProducto($categoriaProducto) {
        if ($categoriaProducto == null) {
            return null;
        }
        $form = new CategoriaProductoForm('update', $this->entityManager, $categoriaProducto);
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
    public function updateCategoriaProducto($categoriaProducto, $form) {
        $data = $form->getData();
        $categoriaProducto->setNombre($data['nombre']);
        if ($this->tryUpdateCategoriaProducto($categoriaProducto)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría editada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al editar categoría';
        }
        return true;
    }

    public function removeCategoriaProducto($id) {
        $categoriaProducto= $this->entityManager->getRepository(CategoriaProducto::class)
                        ->find($id);
        if ($this->tryRemoveCategoriaProducto($categoriaProducto)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría eliminada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al eliminar categoría';
        }
    }

    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(CategoriaProducto::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    private function tryAddCategoriaProducto($categoriaProducto) {
        try {
            $this->entityManager->persist($categoriaProducto);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateCategoriaProducto($categoriaProducto) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryRemoveCategoriaProducto($categoriaProducto) {
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
