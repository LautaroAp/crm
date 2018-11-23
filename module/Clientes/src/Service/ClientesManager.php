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
class ClientesManager {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    private $usuarioManager;

    /**
     * Constructs the service.
     */
    protected $total;

    public function __construct($entityManager, $usuarioManager) {
        $this->entityManager = $entityManager;
        $this->usuarioManager = $usuarioManager;
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

//La funcion getListaClientes() devuelve lista de clientes sin paginado
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
        $adapter = new SelectableAdapter($this->entityManager->getRepository(Cliente::class)); // An object repository implements Selectable
        $paginator = new Paginator($adapter);
        return ($paginator);
    }

    public function getTablaFiltrado($parametros) {
        $filtros = $this->limpiarParametros($parametros);
        $query = $this->busquedaPorFiltros2($filtros);
        
        $pag = new ORMPaginator($query);
        $pag->setUseOutputWalkers(true);
        $adapter = new DoctrineAdapter($pag);
        $this->total = COUNT($adapter);

        $paginator = new Paginator($adapter);
        return $paginator;
    }

    public function getClientesConUsuariosAdicionales($parametros) {
        $filtros = $this->limpiarParametros($parametros);
        $query2 = $this->usuarioManager->getClientes($filtros);
        $pag = new ORMPaginator($query2);
        $pag->setUseOutputWalkers(false);
        $adapter2 = new DoctrineAdapter($pag);
        $paginator = new Paginator($adapter2);
        return $paginator;
    }

    public function getTotal() {
        return $this->total;
    }

    private function getQueryClientes($clientes) {
        $entityManager = $this->entityManager;
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('C')
                ->from(Cliente::class, 'C');
        $p = 0;
        $queryBuilder->where("C.Id = ?$p")->setParameter("$p", $clientes[0]->getId());
        for ($i = 1; $i < count($clientes); $i++) {
            $p = $p + 1;
            $queryBuilder->orWhere("C.Id = ?$p")->setParameter("$p", $clientes[$i]->getId());
        }
        return $queryBuilder->getQuery();
    }

    public function busquedaPorUsuariosAdicionales($parametros) {
        $clientes = $this->usuarioManager->getClientesUsuarioAdicional($parametros);
        $query = $this->getQueryClientes($clientes);
        return $query;
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
                if ($nombreCampo == 'nombre' || $nombreCampo == 'apellido') {
                    $queryBuilder->where("C.$nombreCampo LIKE ?$p");
                } else {
                    $queryBuilder->where("C.$nombreCampo = ?$p");
                }
            } else {
                if ($nombreCampo == 'nombre' || $nombreCampo == 'apellido') {
                    $queryBuilder->andWhere("C.$nombreCampo LIKE ?$p");
                } else {
                    $queryBuilder->andWhere("C.$nombreCampo = ?$p");
                }
            }
            if ($nombreCampo == 'nombre' || $nombreCampo == 'apellido') {
                $queryBuilder->setParameter("$p", '%' . $valorCampo . '%');
            } else {
                $queryBuilder->setParameter("$p", $valorCampo);
            }
        }
        $queryBuilder->andWhere('C.estado = :state')->setParameter('state', 'S');
        return $queryBuilder->getQuery();
    }

    public function busquedaPorFiltros2($parametros) {
        $entityManager = $this->entityManager;
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('C')
                ->from(Cliente::class, 'C')
                ->leftJoin(Usuario::class, 'U', "WITH", 'C.Id = U.id_cliente');
        $indices = array_keys($parametros);

        for ($i = 0; $i < count($indices); $i++) {
            $p = $i + 1;
            $nombreCampo = $indices[$i];
            $valorCampo = $parametros[$nombreCampo];
            $this->comparaCamposParametros($i, $p, $nombreCampo, $indices, $parametros, $queryBuilder);
            $queryBuilder->setParameter("$p", '%' . $valorCampo . '%');
        }

        $queryBuilder->andWhere('C.estado = :state')->setParameter('state', 'S');
        return $queryBuilder->getQuery();
    }

    protected function comparaCamposParametros($i, $p, $nombreCampo, $indices, $parametros, $queryBuilder) {
        if ($i == 0) {
            if ($nombreCampo == 'nombre') {
                $queryBuilder->where("C.$nombreCampo LIKE ?$p");
                $queryBuilder->orWhere("C.apellido LIKE ?$p");
                $queryBuilder->orWhere("U.nombre LIKE ?$p");
            } else {
                $queryBuilder->where("C.$nombreCampo LIKE ?$p");
                if ($nombreCampo = !"empresa") {
                    $queryBuilder->orWhere("U.$nombreCampo LIKE ?$p");
                }
            }
        } else {
            if ($nombreCampo == 'nombre') {
                $queryBuilder->andWhere("C.$nombreCampo LIKE ?$p");
                $queryBuilder->orWhere("C.apellido LIKE ?$p");
                $queryBuilder->orWhere("U.nombre LIKE ?$p");
            } else {
                $queryBuilder->andWhere("C.$nombreCampo LIKE ?$p");
                if ($nombreCampo = !"empresa") {
                    $queryBuilder->orWhere("U.$nombreCampo LIKE ?$p");
                }
            }
        }
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
        // Datos Particulares
        $this->addClienteDatosParticulares($cliente, $data);
        // Datos Laborales
        $this->addClienteDatosLaborales($cliente, $data);
        // Datos de Licencia
        $this->addClienteDatosLicencia($cliente, $data);

        if ($this->tryAddCliente($cliente)) {
            $_SESSION['MENSAJES']['listado_clientes'] = 1;
            $_SESSION['MENSAJES']['listado_clientes_msj'] = 'Cliente agregado correctamente';
        } else {
            $_SESSION['MENSAJES']['listado_clientes'] = 0;
            $_SESSION['MENSAJES']['listado_clientes_msj'] = 'Error al agregar cliente';
        }
        return $cliente;
    }

    private function tryAddCliente($cliente) {
        try {
            $this->entityManager->persist($cliente);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    public function updateCliente($data) {
        $cliente = $this->getCliente($data['id']);
        // Datos Particulares
        $this->addClienteDatosParticulares($cliente, $data);
        // Datos Laborales
        $this->addClienteDatosLaborales($cliente, $data);
        // Datos de Licencia
        $this->addClienteDatosLicencia($cliente, $data);

        if ($this->tryUpdateCliente($cliente)) {
            $_SESSION['MENSAJES']['ficha_cliente'] = 1;
            $_SESSION['MENSAJES']['ficha_cliente_msj'] = 'Cliente modificado correctamente';
        } else {
            $_SESSION['MENSAJES']['ficha_cliente'] = 0;
            $_SESSION['MENSAJES']['ficha_cliente_msj'] = 'Error al modificar cliente';
        }
        return true;
    }

    private function tryUpdateCliente($cliente) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function addClienteDatosParticulares($cliente, $data) {
        $pais = $this->getPais($data['pais']);
        $provincia = $this->getProvincia($data['provincia']);
        $categoria = $this->getCategoriaCliente($data['categoria']);
        $cliente->setApellido($data['apellido'])
                ->setNombre($data['nombre'])
                ->setTelefono($data['telefono'])
                ->setEmail($data['email'])
                ->setSkype($data['skype'])
                ->setPais($pais)
                ->setProvincia($provincia)
                ->setCiudad($data['ciudad'])
                ->setCategoria($categoria)
                ->setEmpresa($data['empresa']);
    }

    private function addClienteDatosLaborales($cliente, $data) {
        if ($data['profesion'] == "-1") {
            $cliente->setProfesion(null);
        } else {
            $profesion = $this->getProfesionCliente($data['profesion']);
            $cliente->setProfesion($profesion);
        }
        $cliente->setActividad($data['actividad'])
                ->setAnimales($data['animales'])
                ->setEstablecimientos($data['establecimientos'])
                ->setRazaManejo($data['raza_manejo']);
    }

    private function addClienteDatosLicencia($cliente, $data) {
        if ($data['licencia'] == "-1") {
            $cliente->setLicencia(null);
        } else {
            $licencia = $this->getLicencia($data['licencia']);
            $cliente->setLicencia($licencia);
        }
        if ($data['version'] == "-1") {
            $cliente->setVersion(null);
        } else {
            $cliente->setVersion($data['version']);
        }
        $cliente->setEstado("S");
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
        $estado_nuevo = "";
        if ($cliente->getEstado() == "S") {
            $cliente->setEstado("N");
            $estado_nuevo = "N";
            $_SESSION['MENSAJES']['listado_clientes_msj'] = 'Cliente dado de Baja correctamente';
        } else if ($cliente->getEstado() == "N") {
            $cliente->setEstado("S");
            $estado_nuevo = "S";
            $_SESSION['MENSAJES']['listado_clientes_msj'] = 'Cliente dado de Alta correctamente';
        }
        $entityManager->flush();
        $_SESSION['MENSAJES']['listado_clientes'] = 1;
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
    
    public function getEventos($id = null) {
        if (isset($id)) {
            $cliente= $this->entityManager
                            ->getRepository(Cliente::class)
                            ->findOneBy(['Id' => $id]);
            return $cliente->getEventos();
        }
        return array();
        
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

    public function getDatosClientes($data) {
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
            if (!is_null($cliente->getLicencia())) {
                if ($cliente->getLicencia()->getId() == $id) {
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
            if (!is_null($cliente->getCategoria())) {
                if ($cliente->getCategoria()->getId() == $id) {
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
            if (!is_null($cliente->getProfesion())) {
                if ($cliente->getProfesion()->getId() == $id) {
                    $cliente->setProfesion(null);
                }
            }
        }
        $entityManager->flush();
    }

    public function getUsuariosCliente($Id){
        $cliente= $this->entityManager
                        ->getRepository(Cliente::class)
                        ->findOneBy(['Id' => $Id]);
        return $cliente->getUsuarios();
    }
}
