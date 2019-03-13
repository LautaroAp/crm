<?php

namespace Servicio\Service;

use DBAL\Entity\Servicio;
use DBAL\Entity\Categoria;
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
    protected $bienesManager;
    private $tipo;
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $ivaManager, $categoriaManager, $proveedorManager,
    $bienesManager) {
        $this->entityManager = $entityManager;
        $this->ivaManager=$ivaManager;
        $this->categoriaManager=$categoriaManager;
        $this->proveedorManager= $proveedorManager;
        $this->bienesManager = $bienesManager;
        $this->tipo = "SERVICIO";
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
        $data['tipo']=$this->tipo;
        $bien = $this->bienesManager->addBien($data);
        $servicio->setBien($bien);
        return $servicio;
    }

    /**
     * This method updates data of an existing servicio.
     */
    public function updateServicio($servicio, $data) {
        $bien = $servicio->getBien();
        $data['tipo']=$this->tipo;
        $this->bienesManager->updateBien($bien, $data);
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    public function removeServicio($servicio) {
        $bien = $servicio->getBien();
        $this->bienesManager->removeBien($bien);
        $this->entityManager->remove($servicio);
        $this->entityManager->flush();
    }

    public function getListaProveedores(){
        return $this->proveedorManager->getListaProveedores();
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
