<?php

namespace CategoriaLicencia\Service;

use DBAL\Entity\CategoriaLicencia;
use CategoriaLicencia\Form\CategoriaLicenciaForm;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;

/**
 * This service is responsible for adding/editing categoriaLicencias
 */
class CategoriaLicenciaManager {

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

    public function getCategoriaLicencias() {
        $categoriaLicencias = $this->entityManager->getRepository(CategoriaLicencia::class)->findAll();
        return $categoriaLicencias;
    }

    public function getCategoriaLicencia($id) {
        return $this->entityManager->getRepository(CategoriaLicencia::class)
                        ->find($id);
    }

    public function getCategoriaLicenciaFromForm($form, $data) {
        $form->setData($data);
        if ($form->isValid()) {
            $data = $form->getData();
            $categoriaLicencia = $this->addCategoriaLicencia($data);
        }
        return $categoriaLicencia;
    }

    /**
     * This method adds a new categoriaLicencia.
     */
    public function addCategoriaLicencia($data) {
        $categoriaLicencia = new CategoriaLicencia();
        $categoriaLicencia->setNombre($data['nombre']);
        $categoriaLicencia->setDescripcion($data['descripcion']);

        if ($this->tryAddCategoriaLicencia($categoriaLicencia)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría agregada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al agregar categoría';
        }
        return $categoriaLicencia;
    }

    public function createForm() {
        return new CategoriaLicenciaForm('create', $this->entityManager, null);
    }

    public function formValid($form, $data) {
        $form->setData($data);
        return $form->isValid();
    }

    public function getFormForCategoriaLicencia($categoriaLicencia) {
        if ($categoriaLicencia == null) {
            return null;
        }
        $form = new CategoriaLicenciaForm('update', $this->entityManager, $categoriaLicencia);
        return $form;
    }

    public function getFormEdited($form, $categoriaLicencia) {
        $form->setData(array(
            'nombre' => $categoriaLicencia->getNombre(),
        ));
    }

    /**
     * This method updates data of an existing categoriaLicencia.
     */
    public function updateCategoriaLicencia($categoriaLicencia, $form) {
        $data = $form->getData();
        $categoriaLicencia->setNombre($data['nombre']);
        if ($this->tryUpdateCategoriaLicencia($categoriaLicencia)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría editada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al editar categoría';
        }
        return true;
    }

    public function removeCategoriaLicencia($id) {
        $categoriaLicencia= $this->entityManager->getRepository(CategoriaLicencia::class)
                        ->find($id);
        if ($this->tryRemoveCategoriaLicencia($categoriaLicencia)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría eliminada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al eliminar categoría';
        }
    }

    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(CategoriaLicencia::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    private function tryAddCategoriaLicencia($categoriaLicencia) {
        try {
            $this->entityManager->persist($categoriaLicencia);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateCategoriaLicencia($categoriaLicencia) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryRemoveCategoriaLicencia($categoriaLicencia) {
        try {
            $this->entityManager->remove($categoriaLicencia);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

}
