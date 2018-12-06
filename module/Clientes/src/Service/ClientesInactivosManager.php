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
     * Constructs the service.
     */
    public function __construct($entityManager,$usuarioManager, 
        $personaManager) {
        parent::__construct($entityManager, $usuarioManager, $personaManager);
    }

    public function getTablaFiltrado($parametros) {
        $filtros = $this->limpiarParametros($parametros);
        $query = $this->getInactivos($filtros);
        $adapter = new DoctrineAdapter(new ORMPaginator($query));
        $this->total = COUNT($adapter);
        $paginator = new Paginator($adapter);
        return $paginator;
    }

    public function getInactivos($filtros){
        $filtros+=['tipo'=> $this->tipo];
        $filtros+=['estado'=>"N"];
        $query = $this->personaManager->buscarPersonas($filtros);
        return $query;
    }
}
