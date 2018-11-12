<?php
namespace Evento\Service;

use DBAL\Entity\Evento;
use DBAL\Entity\Cliente;
use DBAL\Entity\Ejecutivo;
use DBAL\Entity\TipoEvento;



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
    
    
    public function getEventosFiltrados($parametros) {
        $filtros = $this->limpiarParametros($parametros);
        $query = $this->busquedaPorFiltros($filtros);
        $adapter = new DoctrineAdapter(new ORMPaginator($query));
        $paginator = new Paginator($adapter);
       
        return $paginator;
    }
    
    public function getTotalFiltrados($parametros) {
        $filtros = $this->limpiarParametros($parametros);
        $query = $this->busquedaPorFiltros($filtros);
        $adapter = new DoctrineAdapter(new ORMPaginator($query));
       
        return $adapter;
    }

    public function busquedaPorFiltros($parametros) {
        $entityManager = $this->entityManager;
        $emConfig = $this->entityManager->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $emConfig->addCustomDatetimeFunction('DAY', 'DoctrineExtensions\Query\Mysql\Day');
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('E')
                ->from(Evento::class, 'E');
        $indices = array_keys($parametros);
        for ($i = 0; $i < count($indices); $i++) {
            $p = $i + 1;
            $nombreCampo = $indices[$i];
            if ($nombreCampo == "tipo") {
                $valorCampo=$parametros['tipo'];
                if ($valorCampo=="Ventas"){   
                    $queryBuilder->where('E.tipo = :tipo1')->setParameter('tipo1', 10);
                    $queryBuilder->orWhere('E.tipo = :tipo2')->setParameter('tipo2', 11);
                }
                elseif ($valorCampo=="Cotizaciones"){
                    $queryBuilder->where('E.tipo = :tipo3')->setParameter('tipo3', 6);
                }
                 elseif ($valorCampo=="Soportes"){
                    $queryBuilder->where('E.tipo = :tipo4')->setParameter('tipo4', 7);
                    $queryBuilder->orWhere('E.tipo = :tipo5')->setParameter('tipo5', 8);
                    $queryBuilder->orWhere('E.tipo = :tipo6')->setParameter('tipo6', 9);

                }
                $valorCampo = $this->entityManager->getRepository(TipoEvento::class)->findOneBy(array('id_tipo_evento' => $parametros[$nombreCampo]));
            }
            if($nombreCampo=="ventas_m"){
                $valorCampo=$parametros['ventas_m'];
                $queryBuilder->andWhere('MONTH(E.fecha) = :month')->setParameter('month',$valorCampo);
            }
            if($nombreCampo=="ventas_y"){
                $valorCampo=$parametros['ventas_y'];
                $queryBuilder->andWhere('YEAR(E.fecha) = :year')->setParameter('year',$valorCampo);
            }
        }
        $queryBuilder ->orderBy('E.fecha', 'DESC');
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
    
    private function getValor($parametros, $nombreCampo){
        if ($nombreCampo=="ejecutivo"){
                print_r("el ejecutivo es " . $parametros[$nombreCampo]);
               
               return $this->entityManager->getRepository(Ejecutivo::class)->findOneBy(array('nomusr' => $parametros[$nombreCampo]));
        }
        
        if ($nombreCampo == "apellido_cliente"){
            $valor= $parametros[$nombreCampo];

            print_r("el apellido_cliente es " . $valor );
            $nombreCampo= "cliente";           

            return $this->entityManager->getRepository(Cliente::class)->findOneBy(array('apellido' => $parametros["apellido_cliente"]));
        }
        if ($nombreCampo == "nombre_cliente"){
            $nombreCampo="cliente";
            print_r("el nombre_cliente es " . $parametros[$nombreCampo]);

            return $this->entityManager->getRepository(Cliente::class)->findOneBy(array('nombre' => $parametros[$nombreCampo]));
        }
        if ($nombreCampo == "fecha"){
                print_r("fecha es " . $parametros[$nombreCampo]);

            return $parametros[$nombreCampo];
        }
        if ($nombreCampo=="tipo"){
            print_r("el tipo es " . $parametros[$nombreCampo]);

             return $this->entityManager->getRepository(TipoEvento::class)->findOneBy(array('nombre' => $parametros[$nombreCampo]));
        }
        
    }
    
    
    public function getTipoEvento($id=null){
        if (isset ($id)) {
            return $this->entityManager
                ->getRepository(TipoEvento::class)
                ->findOneBy(['id_tipo_evento'=>$id]);
        }
        return $this->entityManager
                ->getRepository(TipoEvento::class)
                ->findAll();
    }
 
} 