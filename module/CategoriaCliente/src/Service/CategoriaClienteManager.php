<?php

namespace CategoriaCliente\Service;

use DBAL\Entity\CategoriaCliente;
use CategoriaCliente\Form\CategoriaClienteForm;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;

/**
 * This service is responsible for adding/editing categoriaclientes
 * and changing categoriacliente password.
 */
class CategoriaClienteManager {

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

    public function getCategoriaClientes() {
        $categoriaclientes = $this->entityManager->getRepository(CategoriaCliente::class)->findAll();
        return $categoriaclientes;
    }

    public function getCategoriaClienteId($id) {
        return $this->entityManager->getRepository(CategoriaCliente::class)
                        ->find($id);
    }

    public function getCategoriaClienteFromForm($form, $data) {
        $form->setData($data);
        if ($form->isValid()) {
            $data = $form->getData();
            $categoriacliente = $this->addCategoriaCliente($data);
        }
        return $categoriacliente;
    }

    /**
     * This method adds a new categoriacliente.
     */
    public function addCategoriaCliente($data) {
        $categoriacliente = new CategoriaCliente();
        $categoriacliente->setNombre($data['nombre']);
        if ($this->tryAddCategoriaCliente($categoriacliente)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría agregada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al agregar categoría';
        }
        return $categoriacliente;
    }

    public function createForm() {
        return new CategoriaClienteForm('create', $this->entityManager, null);
    }

    public function formValid($form, $data) {
        $form->setData($data);
        return $form->isValid();
    }

    public function getFormForCategoriaCliente($categoriacliente) {

        if ($categoriacliente == null) {
            return null;
        }
        $form = new CategoriaClienteForm('update', $this->entityManager, $categoriacliente);
        return $form;
    }

    public function getFormEdited($form, $categoriacliente) {
        $form->setData(array(
            'nombre' => $categoriacliente->getNombre(),
        ));
    }

    /**
     * This method updates data of an existing categoriacliente.
     */
    public function updateCategoriaCliente($categoriacliente, $form) {
        $data = $form->getData();
        $categoriacliente->setNombre($data['nombre']);
        if ($this->tryUpdateCategoriaCliente($categoriacliente)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría editada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al editar categoría';
        }
        return true;
    }

    public function removeCategoriaCliente($categoriacliente) {
        if ($this->tryRemoveCategoriaCliente($categoriacliente)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría eliminada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al eliminar categoría';
        }
    }

    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(CategoriaCliente::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);

        return ($paginator);
    }

    private function tryAddCategoriaCliente($categoriacliente) {
        try {
            $this->entityManager->persist($categoriacliente);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateCategoriaCliente($categoriacliente) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryRemoveCategoriaCliente($categoriacliente) {
        try {
            $this->entityManager->remove($categoriacliente);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

}
