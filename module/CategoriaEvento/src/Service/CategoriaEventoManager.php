<?php

namespace CategoriaEvento\Service;

use DBAL\Entity\CategoriaEvento;
use CategoriaEvento\Form\CategoriaEventoForm;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;


/**
 * Esta clase se encarga de obtener y modificar los datos de los tipos de eventos
 *
 */
class CategoriaEventoManager {

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

    public function getCategoriaEventos() {
        $categoriaEventos = $this->entityManager
                        ->getRepository(CategoriaEvento::class)->findAll();
        return $categoriaEventos;
    }

    public function getCategoriaEventoId($id) {
        return $this->entityManager->getRepository(CategoriaEvento::class)
                        ->find($id);
    }

    public function getCategoriaEventoFromForm($form, $data) {
        // $form->setData($data);
        // if ($form->isValid()) {
        //     $data = $form->getData();
        //     $categoriaEvento = $this->addCategoriaEvento($data);
        // }
        $categoriaEvento = $this->addCategoriaEvento($data);
        return $categoriaEvento;
    }

    /**
     * This method adds a new Categoriaevento.
     */
    public function addCategoriaEvento($data) {
        $categoriaEvento = new CategoriaEvento();
        $categoriaEvento->setNombre($data['nombre']);
        $categoriaEvento->setDescripcion($data['descripcion']);

        // print_r($data);
        // die();

        $this->entityManager->persist($categoriaEvento);
        $this->entityManager->flush();

        // if ($this->tryAddCategoriaEvento($categoriaEvento)) {
        //     $_SESSION['MENSAJES']['categoria_evento'] = 1;
        //     $_SESSION['MENSAJES']['categoria_evento_msj'] = 'Tipo de actividad agregada correctamente';
        // } else {
        //     $_SESSION['MENSAJES']['categoria_evento'] = 0;
        //     $_SESSION['MENSAJES']['categoria_evento_msj'] = 'Error al agregar tipo de actividad';
        // }
        return $categoriaEvento;
    }

    public function createForm() {
        return new CategoriaEventoForm('create', $this->entityManager, null);
    }

    public function formValid($form, $data) {
        $form->setData($data);
        return $form->isValid();
    }

    public function getFormForCategoriaEvento($categoriaEvento) {

        if ($categoriaEvento == null) {
            return null;
        }
        $form = new CategoriaEventoForm('update', $this->entityManager, $categoriaEvento);
        return $form;
    }

    public function getFormEdited($form, $categoriaEvento) {
        $form->setData(array(
            'noombre' => $categoriaEvento->getNombre(),
        ));
    }

    /**
     * This method updates data of an existing Categoriaevento.
     */
    public function updateCategoriaEvento($categoriaEvento, $data) {
        // $data = $form->getData();
        $categoriaEvento->setNombre($data['nombre']);
        $categoriaEvento->setDescripcion($data['descripcion']);

        if ($this->tryUpdateCategoriaEvento($categoriaEvento)) {
            $_SESSION['MENSAJES']['categoria_evento'] = 1;
            $_SESSION['MENSAJES']['categoria_evento_msj'] = 'Tipo de actividad editada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_evento'] = 0;
            $_SESSION['MENSAJES']['categoria_evento_msj'] = 'Error al editar tipo de actividad';
        }
        return true;
    }

    public function removeCategoriaEvento($categoriaEvento) {
        if ($this->tryRemoveCategoriaEvento($categoriaEvento)) {
            $_SESSION['MENSAJES']['categoria_evento'] = 1;
            $_SESSION['MENSAJES']['categoria_evento_msj'] = 'Tipo de actividad eliminada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_evento'] = 0;
            $_SESSION['MENSAJES']['categoria_evento_msj'] = 'Error al eliminar tipo de actividad';
        }
    }

    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(CategoriaEvento::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);

        return ($paginator);
    }

    private function tryAddCategoriaEvento($categoriaEvento) {
        try {
            $this->entityManager->persist($categoriaEvento);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateCategoriaEvento($categoriaEvento) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryRemoveCategoriaEvento($categoriaEvento) {
        try {
            $this->entityManager->remove($categoriaEvento);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

}
