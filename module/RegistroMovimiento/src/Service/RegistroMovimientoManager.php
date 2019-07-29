<?php

namespace RegistroMovimiento\Service;

use DBAL\Entity\RegistroMovimiento;
use DBAL\Entity\Ejecutivo;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;

/**
 * This service is responsible for adding/editing registroMovimientos
 * and changing registroMovimiento password.
 */
class RegistroMovimientoManager {

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

    public function getRegistroMovimientos() {
        $registroMovimientos = $this->entityManager->getRepository(RegistroMovimiento::class)->findAll();
        return $registroMovimientos;
    }

    public function getRegistroMovimientoId($id) {
        return $this->entityManager->getRepository(RegistroMovimiento::class)
                        ->find($id);
    }


    /**
     * This method adds a new registroMovimiento.
     */
    public function addRegistroMovimiento($bien, $data, $descripcion) {
        $registroMovimiento = new RegistroMovimiento();
        $registroMovimiento->setBien($bien);

        $fecha = \DateTime::createFromFormat('d/m/Y', date('d/m/Y'));
        $registroMovimiento->setFecha($fecha);
        
        $ejecutivo = $this->entityManager->getRepository(Ejecutivo::class)
                ->findOneBy(['usuario' => $data['ejecutivo']]);
        $registroMovimiento->setEjecutivo($ejecutivo);

        $registroMovimiento->setDescripcion($descripcion);

        if ($this->tryAddRegistroMovimiento($registroMovimiento)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría agregada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al agregar categoría';
        }

        return $registroMovimiento;
    }

    /**
     * This method updates data of an existing registroMovimiento.
     */
    public function updateRegistroMovimiento($registroMovimiento, $data) {
        $registroMovimiento->setNombre($data['nombre']);
        $registroMovimiento->setDescripcion($data['descripcion']);
        if ($this->tryUpdateRegistroMovimiento($registroMovimiento)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría editada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al editar categoría';
        }
        return true;
    }

    public function removeRegistroMovimiento($id) {
        $registroMovimiento= $this->entityManager->getRepository(RegistroMovimiento::class)
                        ->find($id);
        if ($this->tryRemoveRegistroMovimiento($registroMovimiento)) {
            $_SESSION['MENSAJES']['categoria_cliente'] = 1;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Categoría eliminada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_cliente'] = 0;
            $_SESSION['MENSAJES']['categoria_cliente_msj'] = 'Error al eliminar categoría';
        }
    }

    public function removeBienRegistroMovimiento($bien) {
        $registroMovimientos = $this->entityManager->getRepository(RegistroMovimiento::class)->findAll();
        foreach ($registroMovimientos as $rm) {
            if($bien->getId() == $rm->getBien()->getId()){
                $this->removeRegistroMovimiento($rm->getId());
            }
        }
    }

    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(RegistroMovimiento::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    private function tryAddRegistroMovimiento($registroMovimiento) {
        try {
            $this->entityManager->persist($registroMovimiento);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateRegistroMovimiento($registroMovimiento) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryRemoveRegistroMovimiento($registroMovimiento) {
        try {
            $this->entityManager->remove($registroMovimiento);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }


}
