<?php
namespace Clientes\Service;
use DBAL\Entity\Cliente;
use DBAL\Entity\Usuario;
use DBAL\Entity\Licencia;
use DBAL\Entity\Pais;
use DBAL\Entity\Provincia;
use DBAL\Entity\ProfesionCliente;
use DBAL\Entity\CategoriaCliente;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

/**
 * This service is responsible for adding/editing users
 * and changing user password.
 */
class ClientesInactivosManager extends ClientesManager{

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
    
    public function getInactivos(){
        $entityManager = $this->entityManager;
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder-> select('C')
                       ->from(Cliente::class, 'C');
        $queryBuilder->where('C.estado = :state') ->setParameter('state', 'N');                 
        $query = $queryBuilder->getQuery();
        $adapter = new DoctrineAdapter(new ORMPaginator($query));
        $paginator = new Paginator($adapter);
        return $paginator;        
    }
    
    public function getFiltrados($parametros){
        $filtros = $this->limpiarParametros($parametros);
        $estado = 'N';
        $query = $this->busquedaPorFiltros($filtros);
        $adapter = new DoctrineAdapter(new ORMPaginator($query));
        $paginator = new Paginator($adapter);
        return $paginator;
    }
    
    public function getTablaFiltrado($parametros) {
        $filtros = $this->limpiarParametros($parametros);
        $query = $this->busquedaPorFiltros($filtros); 
        $paginator = new Paginator($adapter);
        return $paginator;
    }

    public function busquedaPorFiltros($parametros) {
        $entityManager = $this->entityManager;
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('C')
                ->from(Cliente::class, 'C');
        $indices = array_keys($parametros);
        for ($i = 0; $i < count($indices); $i++) {
            $p = $i + 1;
            $nombreCampo = $indices[$i];
            $valorCampo = $parametros[$nombreCampo];
            if ($i == 0) {
                $queryBuilder->where("C.$nombreCampo = ?$p");
            } else {
                $queryBuilder->andWhere("C.$nombreCampo = ?$p");
            }
            $queryBuilder->andWhere('C.estado = :state') ->setParameter('state', 'N'); 
            $queryBuilder->setParameter("$p", $valorCampo);
        }
        return $queryBuilder->getQuery();
    }
}
