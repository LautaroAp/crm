<?php

namespace TipoEvento\Service;

use DBAL\Entity\TipoEvento;
use TipoEvento\Form\TipoEventoForm;
use Zend\Paginator\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DBAL\Entity\Categoria;


/**
 * Esta clase se encarga de obtener y modificar los datos de los tipos de eventos
 *
 */
class TipoEventoManager {

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

    public function getTipoEventos($tipo) {
        return  $this->getTipos($tipo)->getResult();
    }

    public function getTipoEventoId($id) {
        return $this->entityManager->getRepository(TipoEvento::class)
                        ->find($id);
    }

    public function getTodosTipos(){
        return $this->entityManager->getRepository(TipoEvento::class)->findAll();
    }
    
    /**
     * This method adds a new tipoevento.
     */
    public function addTipoEvento($data, $tipoPersona) {
        $tipoevento = new TipoEvento();
        $this->addData($tipoevento, $data, $tipoPersona);
        if ($this->tryAddTipoEvento($tipoevento)) {
            $_SESSION['MENSAJES']['tipo_evento'] = 1;
            $_SESSION['MENSAJES']['tipo_evento_msj'] = 'Tipo de actividad agregada correctamente';
        } else {
            $_SESSION['MENSAJES']['tipo_evento'] = 0;
            $_SESSION['MENSAJES']['tipo_evento_msj'] = 'Error al agregar tipo de actividad';
        }
        return $tipoevento;
    }

    /**
     * This method updates data of an existing tipoevento.
     */
    public function updateTipoEvento($tipoevento, $data) {
        $this->addData($tipoevento, $data);

        if ($this->tryUpdateTipoEvento($tipoevento)) {
            $_SESSION['MENSAJES']['tipo_evento'] = 1;
            $_SESSION['MENSAJES']['tipo_evento_msj'] = 'Tipo de actividad editada correctamente';
        } else {
            $_SESSION['MENSAJES']['tipo_evento'] = 0;
            $_SESSION['MENSAJES']['tipo_evento_msj'] = 'Error al editar tipo de actividad';
        }
        return true;
    }

    private function addData($tipoevento, $data, $tipoPersona=null) {
        $tipoevento->setNombre($data['nombre']);
        if ($data['categoria'] == "-1") {
            $tipoevento->setCategoria_evento(null);
        } else {
            $categoria_eve = $this->getCategoriaEventos($data['categoria']);
            $tipoevento->setCategoria_evento($categoria_eve);
        }
        if (isset($tipoPersona)){
            $tipoevento->setTipoPersona($tipoPersona);
        }
        $tipoevento->setDescripcion($data['descripcion']);
    }

    public function createForm() {
        return new TipoEventoForm('create', $this->entityManager, null);
    }

    public function formValid($form, $data) {
        $form->setData($data);
        return $form->isValid();
    }

    public function getFormForTipoEvento($tipoevento) {

        if ($tipoevento == null) {
            return null;
        }
        $form = new TipoEventoForm('update', $this->entityManager, $tipoevento);
        return $form;
    }

    public function getFormEdited($form, $tipoevento) {
        $form->setData(array(
            'noombre' => $tipoevento->getNombre(),
        ));
    }

    public function removeTipoEvento($tipoevento) {
        if ($this->tryRemoveTipoEvento($tipoevento)) {
            $_SESSION['MENSAJES']['tipo_evento'] = 1;
            $_SESSION['MENSAJES']['tipo_evento_msj'] = 'Tipo de actividad eliminada correctamente';
        } else {
            $_SESSION['MENSAJES']['tipo_evento'] = 0;
            $_SESSION['MENSAJES']['tipo_evento_msj'] = 'Error al eliminar tipo de actividad';
        }
    }

    public function eliminarTipoEventos($id) {
        $entityManager = $this->entityManager;
        $eventos = $this->entityManager->getRepository(Evento::class)->findBy(['tipo'=>$id]);
        foreach ($eventos as $evento) {
            $evento->setTipo(null);
        }
        $entityManager->flush();
    }

    public function eliminarCategoriaEventos($id) {
        $entityManager = $this->entityManager;
        $tipoeventos = $this->entityManager->getRepository(TipoEvento::class)->findBy(['categoria_evento'=>$id]);
        foreach ($tipoeventos as $tipoevento) {
            $tipoevento->setCategoria_evento(null);
        }
        $entityManager->flush();
    }

    public function getTabla($tipoPersona) {
        $query = $this->getTipos($tipoPersona);
        return $query->getResult();
    }

    public function getTipos($tipoPersona){
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('T')
                ->from(TipoEvento::class, 'T');
        $nombreCampo="tipoPersona";
        $t=1;
        $queryBuilder->where("T.$nombreCampo  LIKE ?$t");
        $queryBuilder->setParameter("$t",'%'.$tipoPersona.'%');
        return $queryBuilder->getQuery();
    }

    private function tryAddTipoEvento($tipoevento) {
        try {
            $this->entityManager->persist($tipoevento);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateTipoEvento($tipoevento) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryRemoveTipoEvento($tipoevento) {
        try {
            $this->entityManager->remove($tipoevento);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    public function getCategoriaEventos($id = null) {
        if (isset($id)) {
            return $this->entityManager
                            ->getRepository(Categoria::class)
                            ->findOneBy(['id' => $id, 'tipo'=>'evento']);
        }
        return $this->entityManager
                        ->getRepository(Categoria::class)
                        ->findBy(['tipo'=>'evento']);
    }
}
