<?php

namespace Moneda\Service;

use DBAL\Entity\Moneda;
use Moneda\Form\MonedaForm;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;

/**
 * This service is responsible for adding/editing categoriaProductos
 * and changing categoriaProducto password.
 */
class MonedaManager {

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

    public function getMonedas() {
        $categoriaProductos = $this->entityManager->getRepository(Moneda::class)->findAll();
        return $categoriaProductos;
    }

    public function getMonedaId($id) {
        return $this->entityManager->getRepository(Moneda::class)
                        ->find($id);
    }

    public function getMonedaFromForm($form, $data) {
        $form->setData($data);
        if ($form->isValid()) {
            $data = $form->getData();
            $categoriaProducto = $this->addMoneda($data);
        }
        return $categoriaProducto;
    }

    /**
     * This method adds a new categoriaProducto.
     */
    public function addMoneda($data) {
        $categoriaProducto = new Moneda();
        $categoriaProducto->setNombre($data['nombre']);
        $categoriaProducto->setDescripcion($data['descripcion']);

        if ($this->tryAddMoneda($categoriaProducto)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría agregada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al agregar categoría';
        }
        return $categoriaProducto;
    }

    public function createForm() {
        return new MonedaForm('create', $this->entityManager, null);
    }

    public function formValid($form, $data) {
        $form->setData($data);
        return $form->isValid();
    }

    public function getFormForMoneda($categoriaProducto) {
        if ($categoriaProducto == null) {
            return null;
        }
        $form = new MonedaForm('update', $this->entityManager, $categoriaProducto);
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
    public function updateMoneda($categoriaProducto, $form) {
        $data = $form->getData();
        $categoriaProducto->setNombre($data['nombre']);
        if ($this->tryUpdateMoneda($categoriaProducto)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría editada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al editar categoría';
        }
        return true;
    }

    public function removeMoneda($id) {
        $categoriaProducto= $this->entityManager->getRepository(Moneda::class)
                        ->find($id);
        if ($this->tryRemoveMoneda($categoriaProducto)) {
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

    private function tryAddMoneda($categoriaProducto) {
        try {
            $this->entityManager->persist($categoriaProducto);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateMoneda($categoriaProducto) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryRemoveMoneda($categoriaProducto) {
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
