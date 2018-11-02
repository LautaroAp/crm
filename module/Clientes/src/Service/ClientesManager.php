<?php

namespace Clientes\Service;

use DBAL\Entity\Cliente;
use DBAL\Entity\Usuario;
use DBAL\Entity\Licencia;
use DBAL\Entity\Pais;
use DBAL\Entity\Provincia;
use DBAL\Entity\ProfesionCliente;
use DBAL\Entity\CategoriaCliente;
use DBAL\Entity\TipoEvento;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

/**
 * This service is responsible for adding/editing users
 * and changing user password.
 */
class ClientesManager {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Constructs the service.
     */
    protected $total;

    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }

    private function getUsuarios($id_cliente) {
        $usuarios = $this->entityManager
                ->getRepository(Usuario::class)
                ->findBy(['id_cliente' => $id_cliente]);
        return $usuarios;
    }

    private function borrarUsuariosFromCliente($cliente) {
        $usuarios = $this->getUsuarios($cliente->getId());
        foreach ($usuarios as $usuario) {
            $this->entityManager->remove($usuario);
        }
        $this->entityManager->flush();
    }

//La funcion getListaClientes() devuelve lñista de clientes sin paginado
    public function getListaClientes() {
        $lista = $this->entityManager->getRepository(Cliente::class)->findAll();
        return $lista;
    }

    public function getCliente($Id) {
        return $this->entityManager
                        ->getRepository(Cliente::class)
                        ->findOneBy(['Id' => $Id]);
    }

//La funcion getTabla() devuelve tabla de clientes sin filtro    
    public function getTabla() {
        // Create the adapter
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Cliente::class)); // An object repository implements Selectable
        // Create the paginator itself
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    public function getTablaFiltrado($parametros) {
        $filtros = $this->limpiarParametros($parametros);
        $query = $this->busquedaPorFiltros($filtros);
        $adapter = new DoctrineAdapter(new ORMPaginator($query));
        $this->total = COUNT($adapter);
        $paginator = new Paginator($adapter);
        return $paginator;
    }

    public function getTotal() {
        return $this->total;
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
//            if($nombreCampo=='pais'){
//                $valorCampo = $this->getPais($parametros[$nombreCampo]);
//            }
//            elseif($nombreCampo=='provincia'){
//                $valorCampo = $this->getProvincia($parametros[$nombreCampo]);
//            }
//            else
            
                 $valorCampo = $parametros[$nombreCampo];
            
            if ($i == 0) {
                $queryBuilder->where("C.$nombreCampo = ?$p");
            } else {
                $queryBuilder->andWhere("C.$nombreCampo = ?$p");
            }
            $queryBuilder->setParameter("$p", $valorCampo);
        }
        $queryBuilder->andWhere('C.estado = :state')->setParameter('state', 'S');
        return $queryBuilder->getQuery();
    }
    
    public function getActivos() {

        $entityManager = $this->entityManager;
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('C')
                ->from(Cliente::class, 'C');
        $queryBuilder->where('C.estado = :state')->setParameter('state', 'S');
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

    public function addCliente($data) {
        $cliente = new Cliente();
        $cliente->setApellido($data['apellido']);
        $cliente->setNombre($data['nombre']);
        $cliente->setTelefono($data['telefono']);
        $cliente->setEmail($data['email']);
        $cliente->setSkype($data['skype']);
        $pais = $this->getPais($data['pais']);
        $cliente->setPais($pais);
        $provincia = $this->getProvincia($data['provincia']);
        $cliente->setProvincia($provincia);
        $cliente->setCiudad($data['ciudad']);
        $cliente->setEmpresa($data['empresa']);
        $categoria = $this->getCategoriaCliente($data['categoria']);
        $cliente->setCategoria($categoria);
        if($data['licencia'] == "-1"){
            $cliente->setLicencia(null);
        } else {
            $licencia = $this->getLicencia($data['licencia']);
            $cliente->setLicencia($licencia);
        }
        if($data['profesion'] == "-1"){
            $cliente->setProfesion(null);
        } else {
            $profesion = $this->getProfesionCliente($data['profesion']);
            $cliente->setProfesion($profesion);
        }
        $cliente->setActividad($data['actividad']);
        $cliente->setAnimales($data['animales']);
        $cliente->setEstablecimientos($data['establecimientos']);
        $cliente->setRazaManejo($data['raza_manejo']);
        $cliente->setEstado("S");
        if($data['version'] == "-1"){
            $cliente->setVersion(null);
        } else {
            $cliente->setVersion($data['version']);
        }
        $this->entityManager->persist($cliente);
        // Apply changes to database.
        $this->entityManager->flush();
        return $cliente;
    }

    public function updateCliente($data) {
        $cliente = $this->getCliente($data['id']);
        $cliente->setApellido($data['apellido']);
        $cliente->setNombre($data['nombre']);
        $cliente->setTelefono($data['telefono']);
        $cliente->setEmail($data['email']);
        $cliente->setSkype($data['skype']);
        $pais = $this->getPais($data['pais']);
        $cliente->setPais($pais);
        $provincia = $this->getProvincia($data['provincia']);
        $cliente->setProvincia($provincia);
        $cliente->setCiudad($data['ciudad']);
        $cliente->setEmpresa($data['empresa']);
        $categoria = $this->getCategoriaCliente($data['categoria']);
        $cliente->setCategoria($categoria);
        if($data['licencia'] == "-1"){
            $cliente->setLicencia(null);
        } else {
            $licencia = $this->getLicencia($data['licencia']);
            $cliente->setLicencia($licencia);
        }
        if($data['profesion'] == "-1"){
            $cliente->setProfesion(null);
        } else {
            $profesion = $this->getProfesionCliente($data['profesion']);
            $cliente->setProfesion($profesion);
        }
        $cliente->setActividad($data['actividad']);
        $cliente->setAnimales($data['animales']);
        $cliente->setEstablecimientos($data['establecimientos']);
        $cliente->setRazaManejo($data['raza_manejo']);
        if($data['version'] == "-1"){
            $cliente->setVersion(null);
        } else {
            $cliente->setVersion($data['version']);
        }
        // Apply changes to database.
        $this->entityManager->flush();
        return true;
    }

    public function deleteCliente($id) {
        $entityManager = $this->entityManager;
        $cliente = $this->entityManager
                ->getRepository(Cliente::class)
                ->findOneBy(['Id' => $id]);
        $this->borrarUsuariosFromCliente($cliente);
        $entityManager->remove($cliente);
        $entityManager->flush();
    }

    public function modificarEstado($id) {
        $entityManager = $this->entityManager;
        $cliente = $this->entityManager
                ->getRepository(Cliente::class)
                ->findOneBy(['Id' => $id]);
        $estado_nuevo="";
        if ($cliente->getEstado() == "S") {
            $cliente->setEstado("N");
            $estado_nuevo="N";
        } else if ($cliente->getEstado() == "N") {
            $cliente->setEstado("S");
            $estado_nuevo="S";
        }
        $entityManager->flush();
        return $estado_nuevo;
    }

    public function getCategoriaCliente($id = null) {
        if (isset($id)) {
            return $this->entityManager
                            ->getRepository(CategoriaCliente::class)
                            ->findOneBy(['id_categoria_cliente' => $id]);
        }
        return $this->entityManager
                        ->getRepository(CategoriaCliente::class)
                        ->findAll();
    }

    public function getProfesionCliente($id = null) {
        if (isset($id)) {
            return $this->entityManager
                            ->getRepository(ProfesionCliente::class)
                            ->findOneBy(['id_profesion' => $id]);
        }
        return $this->entityManager
                        ->getRepository(ProfesionCliente::class)
                        ->findAll();
    }

    public function getPais($id = null) {
        if (isset($id)) {
            return $this->entityManager
                            ->getRepository(Pais::class)
                            ->findOneBy(['id_pais' => $id]);
        }
        return $this->entityManager
                        ->getRepository(Pais::class)
                        ->findAll();
    }

    public function getProvincia($id = null) {
        if (isset($id)) {
            return $this->entityManager
                            ->getRepository(Provincia::class)
                            ->findOneBy(['id_provincia' => $id]);
        }
        return $this->entityManager
                        ->getRepository(Provincia::class)
                        ->findAll();
    }

    public function getLicencia($id = null) {
        if (isset($id)) {
            return $this->entityManager
                            ->getRepository(Licencia::class)
                            ->findOneBy(['id_licencia' => $id]);
        }
        return $this->entityManager
                        ->getRepository(Licencia::class)
                        ->findAll();
    }

    public function eliminarEventos($eventos_array) {
        $entityManager = $this->entityManager;
        $eventos = $this->entityManager
                ->getRepository(Cliente::class)
                ->findOneBy(['Id' => $id]);
        $this->borrarUsuariosFromCliente($cliente);
        $entityManager->remove($cliente);
        $entityManager->flush();
    }

    public function getProvincias($id_pais) {
        $provincias = $this->entityManager
                ->getRepository(Provincia::class)
                ->findBy(['pais' => $id_pais]);
        return $provincias;
    }

    public function getDatosClientes($data){
        $entityManager = $this->entityManager;
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select($data)
                ->from(Cliente::class, 'C')
                ->where('C.estado = :state')->setParameter('state', 'S');
        return $queryBuilder->getQuery();
    }
    
    public function eliminarLicenciaClientes($id) {
        $entityManager = $this->entityManager;     
        $clientes = $this->entityManager->getRepository(Cliente::class)->findAll();
        foreach ($clientes as $cliente) {
            if(!is_null($cliente->getLicencia())){
                if($cliente->getLicencia()->getId() == $id){
                    $cliente->setLicencia(null);
                }
            }
        }
        $entityManager->flush();
    }
    
    public function eliminarCategoriaClientes($id) {
        $entityManager = $this->entityManager;     
        $clientes = $this->entityManager->getRepository(Cliente::class)->findAll();
        foreach ($clientes as $cliente) {
            if(!is_null($cliente->getCategoria())){
                if($cliente->getCategoria()->getId() == $id){
                    $cliente->setCategoria(null);
                }
            }
        }
        $entityManager->flush();
    }
    
    public function eliminarProfesionClientes($id) {
        $entityManager = $this->entityManager;     
        $clientes = $this->entityManager->getRepository(Cliente::class)->findAll();
        foreach ($clientes as $cliente) {
            if(!is_null($cliente->getProfesion())){
                if($cliente->getProfesion()->getId() == $id){
                    $cliente->setProfesion(null);
                }
            }
        }
        $entityManager->flush();
    }
}
