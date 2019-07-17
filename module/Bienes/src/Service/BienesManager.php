<?php

namespace Bienes\Service;

use DBAL\Entity\Bienes;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
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
    private $categoriaManager;
    private $proveedorManager;
    private $ivaManager;

    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $ivaManager, $categoriaManager, $proveedorManager) {
        $this->entityManager = $entityManager;
        $this->categoriaManager= $categoriaManager;
        $this->ivaManager= $ivaManager;
        $this->proveedorManager = $proveedorManager;
     
    }

    public function getBienes() {
        $bienes = $this->entityManager->getRepository(Bienes::class)->findAll();
        return $bienes;
    }

    public function getBienId($id) {
        return $this->entityManager->getRepository(Bienes::class)->find($id);
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
        if (isset($data['stock'])){
            $bien->setStock($data['stock']);
        }
        if (isset($data['unidad'])){
            $bien->setUnidad_medida($data['unidad']);
        }
        if (isset($data['codigo'])){
            $bien->setCodigo($data['codigo']);
        }
        if (isset($data['codigo_barras'])){
            $bien->setCodigo_barras($data['codigo_barras']);
        }
        if (isset($data['marca'])){
            $bien->setMarca($data['marca']);
        }
        // IMPUESTO
        $bien->setImporte_gravado(0);
        $bien->setImporte_no_gravado(0);
        $bien->setImporte_exento(0);
        if ($iva){
            switch ($iva->getValor()) {
                case 0.00:
                 $bien->setImpuesto("EXENTO");
                    $bien->setImporte_exento($data['precio_publico_dto']);;
                    break;
                default:
                    $bien->setImpuesto("GRAVADO");
                    $bien->setImporte_gravado($data['precio_publico_dto']);
                    break;
            }
        } else {
            $bien->setImpuesto("GRAVADO");
            $bien->setImporte_gravado($data['precio_publico_dto']);
        }

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
        return $bien;
    }

    public function remove($bien) {
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

    public function getBienesFiltrados($parametros) {
        $query = $this->busquedaPorFiltros($parametros);
        $adapter = new DoctrineAdapter(new ORMPaginator($query));
        $paginator = new Paginator($adapter);
        return $paginator;
    }
    
    public function getBienesFiltrados2($parametros){
        $bienes = [];
        if (COUNT($parametros)>1){
            $query = $this->busquedaPorFiltros($parametros);
            $bienes= $query->getResult();
        }
        else{
            $bienes = $this->getBienes();
        }
        $json ="";
        foreach ($bienes as $bien){
            $json .= $bien->getJson(). ',';
        }
        $json = substr($json, 0, -1);
        $json = '['.$json.']';
        return $json;
    }
    public function getTotalFiltrados($parametros) {
        $query = $this->busquedaPorFiltros($parametros);
        $adapter = new DoctrineAdapter(new ORMPaginator($query));
        return $adapter;
    }

    public function busquedaPorFiltros($parametros) {
        $entityManager = $this->entityManager;
        $emConfig = $this->entityManager->getConfiguration();
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('B')
                ->from(Bienes::class, 'B');
        $indices = array_keys($parametros);
        for ($i = 0; $i < count($indices); $i++) {
            $p = $i + 1;
            $nombreCampo = $indices[$i]; 
            if($nombreCampo=="tipo"){
                $valorCampo=$parametros[$nombreCampo]; 
                if ($valorCampo!="-1"){
                    $queryBuilder->andWhere('B.tipo = :tipo')->setParameter('tipo',$valorCampo);
                }
            }
            if($nombreCampo=="nombre"){
                $valorCampo=$parametros[$nombreCampo]; 
                $queryBuilder->andWhere("B.$nombreCampo LIKE ?$p")->setParameter("$p", '%'.$valorCampo.'%');
            }
        }
        $queryBuilder ->orderBy('B.nombre', 'DESC');
        return $queryBuilder->getQuery();
    }

    public function getBienesCategoria($idCategoria, $tipoBien){
        return $this->entityManager->getRepository(Bienes::class)->findBy(['categoria'=>$idCategoria, 'tipo'=>$tipoBien]);
    }
    
    public function addStock($bien, $cantidad){
        $bien->addStock($cantidad);
        $this->entityManager->flush();
    }
    
}
