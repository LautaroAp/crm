<?php

namespace Categoria\Service;

use DBAL\Entity\Categoria;
use Categoria\Form\CategoriaForm;
use Zend\Paginator\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

/**
 * Esta clase se encarga de obtener y modificar los datos de los tipos de eventos
 *
 */
class CategoriaManager {

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

    
    public function getTabla($tipo) {
        $query = $this->getCategorias($tipo);
        $pag = new ORMPaginator($query);
        $pag->setUseOutputWalkers(true);
        $adapter = new DoctrineAdapter($pag);
        $this->total = COUNT($adapter);
        $paginator = new Paginator($adapter);
        return $paginator;
    }

    public function getCategorias($tipo){
        $entityManager = $this->entityManager;
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('C')
                ->from(Categoria::class, 'C');
        $nombreCampo="tipo";
        $t=1;
        $queryBuilder->where("C.$nombreCampo  LIKE ?$t");
        $queryBuilder->setParameter("$t",'%'.$tipo.'%');
        return $queryBuilder->getQuery();
    }


    public function getCategoriaId($id) {
        return $this->entityManager->getRepository(Categoria::class)
                        ->find($id);
    }

    // public function getCategoriaFromForm($form, $data) {
    //     // $form->setData($data);
    //     // if ($form->isValid()) {
    //     //     $data = $form->getData();
    //     //     $categoria = $this->addCategoria($data);
    //     // }
    //     $categoria = $this->addCategoria($data);
    //     return $categoria;
    // }

    /**
     * This method adds a new Categoriaevento.
     */
    public function addCategoria($data, $tipo) {
        $categoria = new Categoria();
        $categoria->setNombre($data['nombre']);
        $categoria->setDescripcion($data['descripcion']);
        $categoria->setTipo($tipo);
        $this->entityManager->persist($categoria);
        $this->entityManager->flush();

        // if ($this->tryAddCategoria($categoria)) {
        //     $_SESSION['MENSAJES']['categoria_evento'] = 1;
        //     $_SESSION['MENSAJES']['categoria_evento_msj'] = 'Tipo de actividad agregada correctamente';
        // } else {
        //     $_SESSION['MENSAJES']['categoria_evento'] = 0;
        //     $_SESSION['MENSAJES']['categoria_evento_msj'] = 'Error al agregar tipo de actividad';
        // }
        return $categoria;
    }

    public function createForm() {
        return new CategoriaForm('create', $this->entityManager, null);
    }

    public function formValid($form, $data) {
        $form->setData($data);
        return $form->isValid();
    }

    public function getFormForCategoria($categoria) {

        if ($categoria == null) {
            return null;
        }
        $form = new CategoriaForm('update', $this->entityManager, $categoria);
        return $form;
    }

    public function getFormEdited($form, $categoria) {
        $form->setData(array(
            'noombre' => $categoria->getNombre(),
        ));
    }

    /**
     * This method updates data of an existing Categoriaevento.
     */
    public function updateCategoria($categoria, $data) {
        $categoria->setNombre($data['nombre']);
        $categoria->setDescripcion($data['descripcion']);
        if ($this->tryUpdateCategoria($categoria)) {
            $_SESSION['MENSAJES']['categoria_evento'] = 1;
            $_SESSION['MENSAJES']['categoria_evento_msj'] = 'Tipo de actividad editada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_evento'] = 0;
            $_SESSION['MENSAJES']['categoria_evento_msj'] = 'Error al editar tipo de actividad';
        }
        return true;
    }

    public function removeCategoria($categoria) {
        if ($this->tryRemoveCategoria($categoria)) {
            $_SESSION['MENSAJES']['categoria_evento'] = 1;
            $_SESSION['MENSAJES']['categoria_evento_msj'] = 'Tipo de actividad eliminada correctamente';
        } else {
            $_SESSION['MENSAJES']['categoria_evento'] = 0;
            $_SESSION['MENSAJES']['categoria_evento_msj'] = 'Error al eliminar tipo de actividad';
        }
    }


    private function tryAddCategoria($categoria) {
        try {
            $this->entityManager->persist($categoria);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryUpdateCategoria($categoria) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function tryRemoveCategoria($categoria) {
        try {
            $this->entityManager->remove($categoria);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

}
