<?php

namespace Clientes\Service;

/**
 * PARECIERA QUE ESTA CLASE NO ES NECESARIA
 */
class ClientesInactivosManager extends ClientesManager {



    /**
     * Constructs the service.
     */
    public function __construct($entityManager,$usuarioManager, 
        $personaManager) {
        parent::__construct($entityManager, $usuarioManager, $personaManager);
    }

    // public function getTablaFiltrado($parametros, $estado) {
    //     $filtros = $this->limpiarParametros($parametros);
    //     $query = $this->getClientes($filtros, $estado);
    //     $pag = new ORMPaginator($query);
    //     $pag->setUseOutputWalkers(true);
    //     $adapter = new DoctrineAdapter($pag);
    //     $this->total = COUNT($adapter);
    //     $paginator = new Paginator($adapter);
    //     return $paginator;
    // }

    // public function getInactivos($filtros){
    //     $filtros+=['tipo'=> $this->tipo];
    //     $filtros+=['estado'=>"N"];
    //     $query = $this->personaManager->buscarPersonas($filtros);
    //     return $query;
    // }
}
