<?php
namespace Evento\Service;

use DBAL\Entity\Evento;
use Evento\Form\EventoForm;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

/**
 * This service is responsible for adding/editing eventos
 * and changing evento password.
 */
class EventoVentaManager extends EventoManager
{
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
    public function __construct($entityManager, $viewRenderer, $config) 
    {
        $this->entityManager = $entityManager;
        $this->viewRenderer = $viewRenderer;
        $this->config = $config;
    }
    

   
//    public function getEventos(){
//        $entityManager = $this->entityManager;
//        $queryBuilder = $entityManager->createQueryBuilder();
//       
//        $queryBuilder-> select('E')
//                       ->from(Evento::class, 'E');
//        $queryBuilder->where('E.tipo = :tipo') ->setParameter('tipo', 2);     
//        $queryBuilder->orWhere('E.tipo = :tipo') ->setParameter('tipo', 8); 
//        $queryBuilder->orWhere('E.tipo = :tipo') ->setParameter('tipo', 5); 
//              
//        $query = $queryBuilder->getQuery();
//        $adapter = new DoctrineAdapter(new ORMPaginator($query));
//        
//        $paginator = new Paginator($adapter);
//        return $paginator;        
//    }
    
    
    public function getEventos($parametros) {
        $filtros = $this->limpiarParametros($parametros);
        $query = $this->busquedaPorFiltros($filtros);
        $adapter = new DoctrineAdapter(new ORMPaginator($query));
        $paginator = new Paginator($adapter);
       
        return $paginator;
    }

    public function busquedaPorFiltros($parametros) {
        $entityManager = $this->entityManager;
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('E')
                ->from(Evento::class, 'E'); 
        $queryBuilder->orWhere('E.tipo = :tipo')->setParameter('tipo', 2);
        $queryBuilder->orWhere('E.tipo = :tipo')->setParameter('tipo', 8);
        $queryBuilder->orWhere('E.tipo = :tipo')->setParameter('tipo', 5);
        $indices = array_keys($parametros);
        for ($i = 0; $i < count($indices); $i++) {
            $p = $i + 1;
            $nombreCampo = $indices[$i];
            $valorCampo = $parametros[$nombreCampo];
            if ($i == 0) {
                $queryBuilder->andWhere("E.$nombreCampo = ?$p");
            }           

            $queryBuilder->setParameter("$p", $valorCampo);
        }
        return $queryBuilder->getQuery();
    }

    public function limpiarParametros($param) {
        foreach ($param as $filtro => $valor) {
            if (empty($valor)) {
                unset($param[$filtro]);
            } else {
                trim($param[$filtro]);
            }
        }
        return ($param);
    }
    

} 