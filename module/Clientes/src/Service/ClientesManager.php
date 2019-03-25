<?php

namespace Clientes\Service;

use DBAL\Entity\Cliente;
use DBAL\Entity\Usuario;
use DBAL\Entity\Licencia;
use DBAL\Entity\Pais;
use DBAL\Entity\Provincia;
use DBAL\Entity\Profesion;
use DBAL\Entity\Categoria;
use Zend\Paginator\Paginator;
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
    protected $entityManager;
    protected $usuarioManager;
    protected $personaManager;
    protected $total;
    protected $tipo;
    protected $transaccionManager;
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $usuarioManager, $personaManager, $transaccionManager) {
        $this->entityManager = $entityManager;
        $this->usuarioManager = $usuarioManager;
        $this->personaManager = $personaManager;
        $this->transaccionManager = $transaccionManager;
        $this->tipo = "CLIENTE";

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

    public function getCliente($Id) {
        return $this->entityManager
                        ->getRepository(Cliente::class)
                        ->findOneBy(['Id' => $Id]);
    }


    //esta funcion es usada por el controller para obtener todos los clientes
    //que cumplen con los parametros
    public function getTablaFiltrado($parametros, $estado) {
        $filtros = $this->limpiarParametros($parametros);
        $query = $this->getClientes($filtros, $estado);
        $pag = new ORMPaginator($query);
        $pag->setUseOutputWalkers(true);
        $adapter = new DoctrineAdapter($pag);
        $this->total = COUNT($adapter);

        $paginator = new Paginator($adapter);
        return $paginator;
    }

    public function getClientes($parametros, $estado){
        $tipos= ['CLIENTE','USUARIO'];
        $parametros+=['estado'=>$estado];
        $params_cliente=$this->diferenciarParametros($parametros,"CLIENTE");
        $params_persona=$this->diferenciarParametros($parametros,"PERSONA");
        if (in_array('busquedaAvanzada', $parametros)){
            // unset($parametros['busquedaAvanzada']);
            $clientes= $this->buscarClientes($params_cliente)->getResult();
            $personas=$this->getPersonasFromClientes($clientes);
            $query = $this->personaManager->buscarPersonas($params_persona,$tipos,$personas);
        }
        else{
            $query = $this->personaManager->buscarPersonas($params_persona, $tipos);
        }
        return $query;
    }

    protected function getPersonasFromClientes($clientes){
        $salida=Array();
        foreach ($clientes as $cliente){
            array_push($salida,$cliente->getPersona()->getId());
        }
        return $salida;
    }
    protected function diferenciarParametros($parametros, $tipo){
        $cliente= ['empresa', 'categoria', 'pais'];
        $persona=['nombre', 'telefono', 'email', 'estado', 'tipo'];
        $salida=Array();
        foreach ($parametros as $filtro => $valor) {
            if ($tipo=='CLIENTE'){
                if (in_array($filtro,$cliente)){
                    $salida+=[$filtro=>$valor];
                } 
            }else {
                if (in_array($filtro,$persona)){
                    $salida+=[$filtro=>$valor];
                } 
            }
        }
        return $salida;
    }

    protected function limpiarParametros($param) {
        foreach ($param as $filtro => $valor) {
            if ($filtro != 'busquedaAvanzada'){
                 if (empty($valor)) {
                unset($param[$filtro]);
                 } else {
                trim($param[$filtro]);
                 }
            }
            else{
                $param[$filtro]=true;
            }
        }
        return ($param);
    }

    public function getDataFicha($id_persona){
        $persona = $this->personaManager->getPersona($id_persona);
        $cliente=null;
        if($persona->getTipo()=="CLIENTE"){
            $cliente = $this->getClienteIdPersona($persona->getId());
        }
        else{
            $usuario= $this->usuarioManager->getUsuarioIdPersona($persona->getId());
            $cliente =$usuario->getCliente(); 
            $persona = $cliente->getPersona();
        }
        $data = [
            'cliente' =>$cliente,
            'eventos' =>$persona->getEventos(),
            'usuarios' => $cliente->getUsuarios(),
            'persona'=>$cliente->getPersona()
        ];
        return $data;
    }

    public function getTotal() {
        return $this->total;
    }

    public function buscarClientes($parametros) {
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
                if ($nombreCampo=="empresa"){
                    $queryBuilder->where("C.$nombreCampo  LIKE ?$p");
                }else{
                $queryBuilder->where("C.$nombreCampo  = ?$p");}
            } else {
                if ($nombreCampo=="empresa"){
                    $queryBuilder->andWhere("C.$nombreCampo  LIKE ?$p");
                }else{
                 $queryBuilder->andWhere("C.$nombreCampo = ?$p");}
            }
            if ($nombreCampo=="empresa"){
                $queryBuilder->setParameter("$p",'%'.$valorCampo.'%');}
            else{
                $queryBuilder->setParameter("$p",$valorCampo);}
        }
        return $queryBuilder->getQuery();
    }

    public function addCliente($data) {
        $cliente = new Cliente();
        $this->addDatosParticulares($cliente, $data);
        $this->addDatosLaborales($cliente, $data);
        $this->addDatosFacturacion($cliente, $data);
        $this->addDatosLicencia($cliente, $data);
        $this->addDatosGanaderos($cliente, $data);        
        $persona = $this->personaManager->addPersona($data, $this->tipo);
        $cliente->setPersona($persona);
        $this->tryAddCliente($cliente);
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

    public function updateCliente($cliente, $data) {
        $this->addDatosParticulares($cliente, $data);
        $this->addDatosLaborales($cliente, $data);
        $this->addDatosFacturacion($cliente, $data);
        $this->addDatosLicencia($cliente, $data);
        $this->addDatosGanaderos($cliente, $data);
        $this->personaManager->updatePersona($cliente->getPersona(), $data);
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

    private function addDatosParticulares($cliente, $data) {
        $pais = $this->getPais($data['pais']);
        $provincia = $this->getProvincia($data['provincia']);
        $categoria = $this->getCategoriaCliente($data['categoria']);

        $cliente->setSkype($data['skype'])
                ->setPais($pais)
                ->setProvincia($provincia)
                ->setCiudad($data['ciudad'])
                ->setCategoria($categoria);
    }

    private function addDatosLaborales($cliente, $data) {
        $cliente->setEmpresa($data['empresa'])
                ->setCargo($data['cargo'])
                ->setActividad($data['actividad']);
        if ($data['profesion'] == "-1") {
            $cliente->setProfesion(null);
        } else {
            $profesion = $this->getProfesion($data['profesion']);
            $cliente->setProfesion($profesion);
        }
    }

    private function addDatosFacturacion($cliente, $data) {
        $condicion_iva = $this->getCategoriaCliente($data['condicion_iva']);

        $cliente->setRazon_social($data['razon_social'])
                ->setDireccion_facturacion($data['direccion_facturacion'])
                ->setCondicion_iva($condicion_iva)
                ->setBanco($data['banco'])
                ->setCbu($data['cbu'])
                ->setCuit_cuil($data['cuit_cuil']);
    }

    private function addDatosLicencia($cliente, $data) {
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
    }

    private function addDatosGanaderos($cliente, $data) {
        $cliente->setAnimales($data['animales'])
                ->setEstablecimientos($data['establecimientos'])
                ->setRazaManejo($data['raza_manejo']);
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
        $estado_nuevo = $this->personaManager->cambiarEstado($cliente->getPersona());
        if ($estado_nuevo == "N"){
            $_SESSION['MENSAJES']['listado_clientes_msj'] = 'Cliente dado de Baja correctamente';
        } else if ($estado_nuevo == "S") {
            $_SESSION['MENSAJES']['listado_clientes_msj'] = 'Cliente dado de Alta correctamente';
        }
        $_SESSION['MENSAJES']['listado_clientes'] = 1;
        return $estado_nuevo;
    }

    //La funcion getListaClientes() devuelve lista de clientes sin paginado
    //Usada en la generacion de backup
    public function getListaClientes() {
        $lista = $this->entityManager->getRepository(Cliente::class)->findAll();
        return $lista;
    }

    public function getCategoriaCliente($id = null) {
        if (isset($id)) {
            return $this->entityManager
                            ->getRepository(Categoria::class)
                            ->findOneBy(['id' => $id]);
        }
        return $this->entityManager
                        ->getRepository(Categoria::class)
                        ->findAll();
    }

    public function getCategoriasCliente($tipo = null) {
        if (isset($tipo)) {
            return $this->entityManager
                            ->getRepository(Categoria::class)
                            ->findBy(['tipo' => $tipo]);
        }
        return $this->entityManager
                        ->getRepository(Categoria::class)
                        ->findAll();
    }

    public function getProfesion($id = null) {
        if (isset($id)) {
            return $this->entityManager
                            ->getRepository(Profesion::class)
                            ->findOneBy(['id_profesion' => $id]);
        }
        return $this->entityManager
                        ->getRepository(Profesion::class)
                        ->findAll();
    }

    public function getCondicionIva($tipo = null) {
        if (isset($tipo)) {
            return $this->entityManager
                            ->getRepository(Categoria::class)
                            ->findBy(['tipo' => $tipo]);
        }
        return $this->entityManager
                        ->getRepository(Categoria::class)
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
                            ->findOneBy(['id' => $id]);
        }
        return $this->entityManager
                        ->getRepository(Licencia::class)
                        ->findAll();
    }

    public function getProvincias($id_pais) {
        $provincias = $this->entityManager
                ->getRepository(Provincia::class)
                ->findBy(['pais' => $id_pais]);
        return $provincias;
    }

    public function eliminarLicenciaClientes($id) {
        $entityManager = $this->entityManager;
        $clientes = $this->entityManager->getRepository(Cliente::class)->findBy(['licencia'=>$id]);
        foreach ($clientes as $cliente) {
            $cliente->setLicencia(null)
                     ->setVersion(null);
        }
        $entityManager->flush();
    }

    public function eliminarCategoriaClientes($id) {
        $clientes = $this->entityManager->getRepository(Cliente::class)->findBy(['categoria'=>$id]);
        foreach ($clientes as $cliente) {
            $cliente->setCategoria(null);
        }
        $this->entityManager->flush();
    }

    public function eliminarProfesiones($id) {
        $clientes = $this->entityManager->getRepository(Cliente::class)->findBy(['profesion'=>$id]);
        foreach ($clientes as $cliente) {
             $cliente->setProfesion(null);
        }
        $this->entityManager->flush();
    }

    public function getClienteIdPersona($id_persona){
        $cliente= $this->entityManager
                    ->getRepository(Cliente::class)
                    ->findOneBy(['persona' => $id_persona]);
        return $cliente;
    }

    public function eliminarCondicionIva($id){
        $clientes = $this->entityManager->getRepository(Cliente::class)->findBy(['condicion_iva'=>$id]);
        foreach ($clientes as $cliente) {
             $cliente->setCondicion_iva(null);
        }
        $this->entityManager->flush();
    }

    public function getTransacciones($id_persona){
        return $this->transaccionManager->getTransaccionesPersona($id_persona);
    }
   
}
