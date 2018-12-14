<?php

namespace CategoriaServicio\Service;

use DBAL\Entity\CategoriaServicio;
use CategoriaServicio\Form\CategoriaServicioForm;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;

/**
 * This service is responsible for adding/editing categoriaServicios
 */
class CategoriaServicioManager {

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

    public function getCategoriaServicios() {
        $categoriaServicios = $this->entityManager->getRepository(CategoriaServicio::class)->findAll();
        return $categoriaServicios;
    }

    public function getCategoriaServicioId($id) {
        return $this->entityManager->getRepository(CategoriaServicio::class)
                        ->find($id);
    }

    public function getCategoriaServicioFromForm($form, $data) {
        $form->setData($data);
        if ($form->isValid()) {
            $data = $form->getData();
            $categoriaServicio = $this->addCategoriaServicio($data);
        }
        return $categoriaServicio;
    }

    /**
     * This method adds a new categoriaServicio.
     */
    public function addCategoriaServicio($data) {
        $categoriaServicio = new CategoriaServicio();
        $categoriaServicio->setNombre($data['nombre']);
        $categoriaServicio->setDescripcion($data['descripcion']);

        if ($this->tryAddCategoriaServicio($categoriaServicio)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría agregada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al agregar categoría';
        }
        return $categoriaServicio;
    }

    public function createForm() {
        return new CategoriaServicioForm('create', $this->entityManager, null);
    }

    public function formValid($form, $data) {
        $form->setData($data);
        return $form->isValid();
    }

    public function getFormForCategoriaServicio($categoriaServicio) {
        if ($categoriaServicio == null) {
            return null;
        }
        $form = new CategoriaServicioForm('update', $this->entityManager, $categoriaServicio);
        return $form;
    }

    public function getFormEdited($form, $categoriaServicio) {
        $form->setData(array(
            'nombre' => $categoriaServicio->getNombre(),
        ));
    }

    /**
     * This method updates data of an existing categoriaServicio.
     */
    public function updateCategoriaServicio($categoriaServicio, $form) {
        $data = $form->getData();
        $categoriaServicio->setNombre($data['nombre']);
        if ($this->tryUpdateCategoriaServicio($categoriaServicio)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría editada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al editar categoría';
        }
        return true;
    }

    public function removeCategoriaServicio($id) {
        $categoriaServicio= $this->entityManager->getRepository(CategoriaServicio::class)
                        ->find($id);
        if ($this->tryRemoveCategoriaServicio($categoriaServicio)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría eliminada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al eliminar categoría';
        }
    }

    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(CategoriaServicio::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    private function tryAddCategoriaServicio($categoriaServicio) {
        try {
            $this->entityManager->persist($categoriaServicio);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateCategoriaServicio($categoriaServicio) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryRemoveCategoriaServicio($categoriaServicio) {
        try {
            $this->entityManager->remove($categoriaServicio);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

}
