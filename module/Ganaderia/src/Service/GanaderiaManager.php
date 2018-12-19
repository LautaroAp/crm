<?php

namespace Ganaderia\Service;

use DBAL\Entity\Ganaderia;
use Ganaderia\Form\GanaderiaForm;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;

/**
 * This service is responsible for adding/editing Ganaderias
 * and changing Ganaderia password.
 */
class GanaderiaManager {

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

    public function getGanaderias() {
        $ganaderias = $this->entityManager->getRepository(Ganaderia::class)->findAll();
        return $ganaderias;
    }

    public function getGanaderiaId($id) {
        return $this->entityManager->getRepository(Ganaderia::class)
                        ->find($id);
    }

    public function getGanaderiaFromForm($form, $data) {
        $form->setData($data);
        if ($form->isValid()) {
            $data = $form->getData();
            $ganaderia = $this->addGanaderia($data);
        }
        return $ganaderia;
    }

    /**
     * This method adds a new Ganaderia.
     */
    public function addGanaderia($data) {
        $ganaderia = new Ganaderia();
        $ganaderia->setNombre($data['nombre']);
        $ganaderia->setDescripcion($data['descripcion']);

        if ($this->tryAddGanaderia($ganaderia)) {
            $_SESSION['MENSAJES']['ganaderia'] = 1;
            $_SESSION['MENSAJES']['ganaderia_msj'] = 'Categoría agregada correctamente';
        } else {
            $_SESSION['MENSAJES']['ganaderia'] = 0;
            $_SESSION['MENSAJES']['ganaderia_msj'] = 'Error al agregar categoría';
        }
        return $ganaderia;
    }

    public function createForm() {
        return new GanaderiaForm('create', $this->entityManager, null);
    }

    public function formValid($form, $data) {
        $form->setData($data);
        return $form->isValid();
    }

    public function getFormForGanaderia($ganaderia) {
        if ($ganaderia == null) {
            return null;
        }
        $form = new GanaderiaForm('update', $this->entityManager, $ganaderia);
        return $form;
    }

    public function getFormEdited($form, $ganaderia) {
        $form->setData(array(
            'nombre' => $ganaderia->getNombre(),
        ));
    }

    /**
     * This method updates data of an existing Ganaderia.
     */
    public function updateGanaderia($ganaderia, $form) {
        $data = $form->getData();
        $ganaderia->setNombre($data['nombre']);
        $ganaderia->setDescripcion($data['descripcion']);
        if ($this->tryUpdateGanaderia($ganaderia)) {
            $_SESSION['MENSAJES']['ganaderia'] = 1;
            $_SESSION['MENSAJES']['ganaderia_msj'] = 'Categoría editada correctamente';
        } else {
            $_SESSION['MENSAJES']['ganaderia'] = 0;
            $_SESSION['MENSAJES']['ganaderia_msj'] = 'Error al editar categoría';
        }
        return true;
    }

    public function removeGanaderia($id) {
        $ganaderia= $this->entityManager->getRepository(Ganaderia::class)
                        ->find($id);
        if ($this->tryRemoveGanaderia($ganaderia)) {
            $_SESSION['MENSAJES']['ganaderia'] = 1;
            $_SESSION['MENSAJES']['ganaderia_msj'] = 'Categoría eliminada correctamente';
        } else {
            $_SESSION['MENSAJES']['ganaderia'] = 0;
            $_SESSION['MENSAJES']['ganaderia_msj'] = 'Error al eliminar categoría';
        }
    }

    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Ganaderia::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    private function tryAddGanaderia($ganaderia) {
        try {
            $this->entityManager->persist($ganaderia);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateGanaderia($ganaderia) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryRemoveGanaderia($ganaderia) {
        try {
            $this->entityManager->remove($ganaderia);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    public function addDatos($id) {
        $entityManager = $this->entityManager;
        
        $ganaderia = $this->entityManager
                ->getRepository(Ganaderia::class)
                ->findOneBy(['id' => $id]);
        
        $ganaderia->setEmpresa('HSDDFKSDÑF');
        $entityManager->flush();

        return $ganaderia;
    }

}
