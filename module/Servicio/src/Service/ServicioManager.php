<?php

namespace Servicio\Service;

use DBAL\Entity\Servicio;
use DBAL\Entity\Categoria;
use Servicio\Form\ServicioForm;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
/**
 * Esta clase se encarga de obtener y modificar los datos de los servicios 
 * 
 */
class ServicioManager {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected $ivaManager;
    protected $categoriaManager;
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $ivaManager, $categoriaManager) {
        $this->entityManager = $entityManager;
        $this->ivaManager=$ivaManager;
        $this->categoriaManager=$categoriaManager;
    }

    public function getServicios() {
        $servicios = $this->entityManager->getRepository(Servicio::class)->findAll();
        return $servicios;
    }

    public function getServicioId($id) {
        return $this->entityManager->getRepository(Servicio::class)
                        ->find($id);
    }

    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Servicio::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }
    /**
     * This method adds a new servicio.
     */
    public function addServicio($data) {
        $servicio = new Servicio();
        $servicio=$this->setData($servicio, $data);
        $this->entityManager->persist($servicio);
        $this->entityManager->flush();
        return $servicio;
    }

    private function setData($servicio, $data){
        $servicio->setNombre($data['nombre']);
        $servicio->setDescripcion($data['descripcion']);
        if($data['categoria'] == "-1"){
            $servicio->setCategoria(null);
        } else {
            // Obtener Entidad con id y pasarla
            $servicio->setCategoria($this->categoriaManager->getCategoriaId($data['categoria']));
        }
        // $servicio->setProveedor($data['proveedor']);
        $servicio->setPrecio($data['precio_venta']);
        $servicio->setIva_gravado($data['iva_total']);
        $iva=$this->ivaManager->getIvaPorValor($data['iva']);
        $servicio->setIva($iva);
        $servicio->setPrecio($data['precio_venta']);
        $servicio->setDescuento($data['descuento']);
        $servicio->setPrecio_final_dto($data['precio_publico_dto']);
        $servicio->setPrecio_final_iva($data['precio_publico_iva']);
        $servicio->setPrecio_final_iva_dto($data['precio_publico_iva_dto']);
        //MONEDA
        return $servicio;
    }

    /**
     * This method updates data of an existing servicio.
     */
    public function updateServicio($servicio, $data) {
        $servicio=$this->setData($servicio, $data);
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    public function removeServicio($servicio) {
        $this->entityManager->remove($servicio);
        $this->entityManager->flush();
    }

    public function eliminarCategoriaServicios($id){
        $entityManager = $this->entityManager;
        $servicios = $this->entityManager->getRepository(Servicio::class)->findBy(['categoria'=>$id]);
        foreach ($servicios as $s) {
            $s->setCategoria(null);
        }
        $entityManager->flush();
    }

    public function getCategoriasServicio($tipo = null) {
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
        $servicios = $this->entityManager->getRepository(Servicio::class)->findBy(['iva'=>$id]);
        foreach($servicios as $servicio){
            $servicio->setIva(null);
        }
    }

}
