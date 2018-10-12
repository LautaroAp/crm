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

    public function busquedaPorFiltros($parametros) {
        $entityManager = $this->entityManager;
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('E')
                ->from(Evento::class, 'E');
        $queryBuilder->where('E.tipo = :tipo1')->setParameter('tipo1', 2);
        $queryBuilder->orWhere('E.tipo = :tipo2')->setParameter('tipo2', 8);
        $queryBuilder->orWhere('E.tipo = :tipo3')->setParameter('tipo3', 5);
        $indices = array_keys($parametros);
        for ($i = 0; $i < count($indices); $i++) {
            $p = $i + 1;
            $nombreCampo = $indices[$i];
        
            if ($nombreCampo == "ejecutivo") {
                $valorCampo = $this->entityManager->getRepository(Ejecutivo::class)->findOneBy(array('usuario' => $parametros[$nombreCampo]));
            }

            if ($nombreCampo == "apellido_cliente") {
                $nombreCampo = "cliente";
                $valorCampo = $this->entityManager->getRepository(Cliente::class)->findOneBy(array('apellido' => $parametros["apellido_cliente"]));
            }

            if ($nombreCampo == "fecha") {
                $valorCampo = $parametros[$nombreCampo];
            }
            if ($nombreCampo == "tipo") {
                $valorCampo = $this->entityManager->getRepository(TipoEvento::class)->findOneBy(array('id_tipo_evento' => $parametros[$nombreCampo]));
            }
            $queryBuilder->andWhere("E.$nombreCampo = ?$p");
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