<?php

namespace Clientes\Service;

use DBAL\Entity\Cliente;
use DBAL\Entity\Usuario;
use Zend\Paginator\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

/**
 * This service is responsible for adding/editing users
 * and changing user password.
 */
class ClientesInactivosManager extends ClientesManager {

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

    public function getFiltrados($parametros) {
        $filtros = $this->limpiarParametros($parametros);
        $query = $this->busquedaPorFiltros2($filtros);
        $adapter = new DoctrineAdapter(new ORMPaginator($query));
        $this->total = COUNT($adapter);
        $paginator = new Paginator($adapter);
        return $paginator;
    }

    public function busquedaPorFiltros($filtros){
        $parametros = ['tipo'=>$this->tipo, 'estado'=>'S'];
        $query = $this->personaManager->buscarPersonas($parametros);

    }

    // public function busquedaPorFiltros($parametros) {
    //     $entityManager = $this->entityManager;
    //     $queryBuilder = $entityManager->createQueryBuilder();
    //     $queryBuilder->select('C')
    //             ->from(Cliente::class, 'C');
    //     $indices = array_keys($parametros);
    //     for ($i = 0; $i < count($indices); $i++) {
    //         $p = $i + 1;
    //         $nombreCampo = $indices[$i];
    //         $valorCampo = $parametros[$nombreCampo];
    //         if ($i == 0) {
    //             $queryBuilder->where("C.$nombreCampo = ?$p");
    //         } else {
    //             $queryBuilder->andWhere("C.$nombreCampo = ?$p");
    //         }
    //         $queryBuilder->setParameter("$p", $valorCampo);
    //     }
    //     $queryBuilder->andWhere('C.estado = :state')->setParameter('state', 'N');
    //     return $queryBuilder->getQuery();
    // }

    // public function busquedaPorFiltros2($parametros) {
    //     $entityManager = $this->entityManager;
    //     $queryBuilder = $entityManager->createQueryBuilder();
    //     $queryBuilder->select('C')
    //             ->from(Cliente::class, 'C')
    //             ->leftJoin(Usuario::class, 'U', "WITH", 'C.Id = U.id_cliente');
    //     $indices = array_keys($parametros);

    //     for ($i = 0; $i < count($indices); $i++) {
    //         $p = $i + 1;
    //         $nombreCampo = $indices[$i];
    //         $valorCampo = $parametros[$nombreCampo];

    //         if ($i == 0) {
    //             if ($nombreCampo == 'nombre') {
    //                 $queryBuilder->where("C.$nombreCampo LIKE ?$p");
    //                 $queryBuilder->orWhere("C.apellido LIKE ?$p");
    //                 $queryBuilder->orWhere("U.nombre LIKE ?$p");
    //             } else {
    //                 $queryBuilder->where("C.$nombreCampo LIKE ?$p");
    //                 if ($nombreCampo = !"empresa") {
    //                     $queryBuilder->orWhere("U.$nombreCampo LIKE ?$p");
    //                 }
    //             }
    //         } else {
    //             if ($nombreCampo == 'nombre') {
    //                 $queryBuilder->andWhere("C.$nombreCampo LIKE ?$p");
    //                 $queryBuilder->orWhere("C.apellido LIKE ?$p");
    //                 $queryBuilder->orWhere("U.nombre LIKE ?$p");
    //             } else {
    //                 $queryBuilder->andWhere("C.$nombreCampo LIKE ?$p");
    //                 if ($nombreCampo = !"empresa") {
    //                     $queryBuilder->orWhere("U.$nombreCampo LIKE ?$p");
    //                 }
    //             }
    //         }
    //         $queryBuilder->setParameter("$p", '%' . $valorCampo . '%');
    //     }
    //     $queryBuilder->andWhere('C.estado = :state')->setParameter('state', 'N');
    //     return $queryBuilder->getQuery();
    // }
}
