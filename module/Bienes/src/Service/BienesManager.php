<?php

namespace Bienes\Service;

use DBAL\Entity\Bienes;
use DBAL\Entity\Categoria;
use Bienes\Form\BienesForm;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
/**
 * Esta clase se encarga de obtener y modificar los datos de los servicios 
 * 
 */
class BienesManager {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    private $proveedorManager;
    private $ivaManager;
    private $categoriaManager;
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $ivaManager, $categoriaManager, $proveedorManager) {
        $this->entityManager = $entityManager;
        $this->proveedorManager= $proveedorManager;
        $this->ivaManager= $ivaManager;
        $this->categoriaManager= $categoriaManager;
    }

    public function getBienes() {
        $bienes = $this->entityManager->getRepository(Bienes::class)->findAll();
        return $bienes;
    }

    public function getBienId($id) {
        return $this->entityManager->getRepository(Bienes::class)
                        ->find($id);
    }

    public function getTabla($tipo) {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Bienes::class)->
        findBy(['tipo'=>$tipo])); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    private function getBienesTipo($tipo){
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('B')
                     ->from(Bienes::class, 'B')
                     ->where("B.tipo LIKE ?$t")->setParameter("$t", $tipo);
        return $queryBuilder->getQuery();
    }

    
    /**
     * This method adds a new servicio.
     */
    public function addBien($data) {
        $bien = new Bienes();
        $bien=$this->setData($bien, $data);
        $this->entityManager->persist($bien);
        $this->entityManager->flush();
        return $bien;
    }

    private function setData($bien, $data){
        print_r("<br>");
        print_r("<br>");
        print_r("<br>");

        print_r($data);
        // die();
        $bien->setNombre($data['nombre']);
        $bien->setDescripcion($data['descripcion']);
        if($data['categoria'] == "-1"){
            $bien->setCategoria(null);
        } else {
            // Obtener Entidad con id y pasarla
            $bien->setCategoria($this->categoriaManager->getCategoriaId($data['categoria']));
        }
        if($data['proveedor'] == "-1"){
            $bien->setProveedor(null);
        } else {            
            $prov=$this->proveedorManager->getProveedor($data['proveedor']);
            $bien->setProveedor($prov);
        }
        $bien->setPrecio($data['precio_venta']);
        $bien->setIva_gravado($data['iva_total']);
        $iva=$this->ivaManager->getIva($data['iva']);
        $bien->setIva($iva);
        $bien->setPrecio($data['precio_venta']);
        $bien->setDescuento($data['descuento']);
        $bien->setPrecio_final_dto($data['precio_publico_dto']);
        $bien->setPrecio_final_iva($data['precio_publico_iva']);
        $bien->setPrecio_final_iva_dto($data['precio_publico_iva_dto']);
        $bien->setTipo($data['tipo']);
        //MONEDA
        return $bien;
    }

    /**
     * This method updates data of an existing servicio.
     */
    public function updateBien($bien, $data) {
        $bien=$this->setData($bien, $data);
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    public function removeBien($bien) {
        $this->entityManager->remove($bien);
        $this->entityManager->flush();
    }


    public function eliminarCategoriaBienes($id){
        $entityManager = $this->entityManager;
        $bienes = $this->entityManager->getRepository(Bienes::class)->findBy(['categoria'=>$id]);
        foreach ($bienes as $s) {
            $s->setCategoria(null);
        }
        $entityManager->flush();
    }

    public function getCategoriasBienes($tipo = null) {
        if (isset($tipo)) {
            return $this->entityManager
                            ->getRepository(Categoria::class)
                            ->findBy(['tipo' => $tipo]);
        }
        return $this->entityManager
                        ->getRepository(Categoria::class)
                        ->findAll();
    }

    public function eliminarIvas($id){
        $bienes = $this->entityManager->getRepository(Bienes::class)->findBy(['iva'=>$id]);
        foreach($bienes as $bien){
            $bien->setIva(null);
        }
    }

}
