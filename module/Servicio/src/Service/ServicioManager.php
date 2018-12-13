<?php

namespace Servicio\Service;

use DBAL\Entity\Servicio;
use Servicio\Form\ServicioForm;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
/**
 * Esta clase se encarga de obtener y modificar los datos de los servicios 
 * Actualmente sin uso
 */
class ServicioManager {

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
        // $servicio->setCategoria($data['categoria']);
        // $servicio->setProveedor($data['proveedor']);
        $servicio->setPrecio($data['precio_venta']);
        $servicio->setIva_gravado($data['iva_total']);
        // $servicio->setIva($data['iva']);
        $servicio->setPrecio($data['precio_venta']);
        $servicio->setDescuento($data['descuento']);
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

}
