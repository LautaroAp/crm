<?php

namespace Proveedor\Service;

use DBAL\Entity\Proveedor;
use DBAL\Entity\Usuario;
use DBAL\Entity\Licencia;
use DBAL\Entity\Persona;
use DBAL\Entity\Pais;
use DBAL\Entity\Provincia;
use DBAL\Entity\Profesion;
use DBAL\Entity\CategoriaProveedor;
use DBAL\Entity\Categoria;
use DBAL\Entity\Iva;
use DBAL\Entity\Ganaderia;
use Zend\Paginator\Paginator;
use DoctrineModule\Paginator\Adapter\Selectable as SelectableAdapter;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;

/**
 * This service is responsible for adding/editing users
 * and changing user password.
 */
class ProveedorManager {

    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;
    protected $usuarioManager;
    protected $personaManager;
    protected $ganaderiaManager;
    protected $total;
    protected $tipo;
    
    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $usuarioManager, $personaManager, $ganaderiaManager) {
        $this->entityManager = $entityManager;
        $this->usuarioManager = $usuarioManager;
        $this->personaManager = $personaManager;
        $this->ganaderiaManager = $ganaderiaManager;
        $this->tipo = "PROVEEDOR";

    }


    private function borrarUsuariosFromProveedor($proveedor) {
        $usuarios = $this->getUsuarios($proveedor->getId());
        foreach ($usuarios as $usuario) {
            $this->entityManager->remove($usuario);
        }
        $this->entityManager->flush();
    }

    public function getProveedor($Id) {
        return $this->entityManager
                        ->getRepository(Proveedor::class)
                        ->findOneBy(['Id' => $Id]);
    }


    //esta funcion es usada por el controller para obtener todos los proveedors
    //que cumplen con los parametros
    public function getTablaFiltrado($parametros, $estado) {
        $filtros = $this->limpiarParametros($parametros);
        $query = $this->getProveedores($filtros, $estado);
        $pag = new ORMPaginator($query);
        $pag->setUseOutputWalkers(true);
        $adapter = new DoctrineAdapter($pag);
        $this->total = COUNT($adapter);

        $paginator = new Paginator($adapter);
        return $paginator;
    }

    public function getProveedores($parametros, $estado){
        $tipos= ['PROVEEDOR'];
        $parametros+=['estado'=>$estado];
        $params_proveedor=$this->diferenciarParametros($parametros,"PROVEEDOR");
        $params_persona=$this->diferenciarParametros($parametros,"PERSONA");
        if (in_array('busquedaAvanzada', $parametros)){
            // unset($parametros['busquedaAvanzada']);
            $proveedors= $this->buscarProveedor($params_proveedor)->getResult();
            $personas=$this->getPersonasFromProveedor($proveedors);
            $query = $this->personaManager->buscarPersonas($params_persona,$tipos,$personas);
        }
        else{
            $query = $this->personaManager->buscarPersonas($params_persona, $tipos);
        }
        return $query;
    }

    protected function getPersonasFromProveedor($proveedors){
        $salida=Array();
        foreach ($proveedors as $proveedor){
            array_push($salida,$proveedor->getPersona()->getId());
        }
        return $salida;
    }
    protected function diferenciarParametros($parametros, $tipo){
        $proveedor= ['empresa', 'categoria', 'pais'];
        $persona=['nombre', 'telefono', 'email', 'estado', 'tipo'];
        $salida=Array();
        foreach ($parametros as $filtro => $valor) {
            if ($tipo=='PROVEEDOR'){
                if (in_array($filtro,$proveedor)){
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
        $proveedor = $this->getProveedorIdPersona($persona->getId());
        $data = [
            'proveedor' =>$proveedor,
            'eventos' =>$persona->getEventos(),
            'persona'=>$proveedor->getPersona()
        ];
        return $data;
    }

    public function getTotal() {
        return $this->total;
    }

    public function buscarProveedores($parametros) {
        $entityManager = $this->entityManager;
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('C')
                ->from(Proveedor::class, 'C');
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

    public function addProveedor($data) {
        $proveedor = new Proveedor();
        $this->addDatosParticulares($proveedor, $data);
        $this->addDatosLaborales($proveedor, $data);
        $this->addDatosFacturacion($proveedor, $data);
        $persona = $this->personaManager->addPersona($data, $this->tipo);
        $proveedor->setPersona($persona);
        $this->tryAddProveedor($proveedor);
        return $proveedor;
    }


    private function tryAddProveedor($proveedor) {
        try {
            $this->entityManager->persist($proveedor);
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            echo $e;
            $this->entityManager->rollBack();
            return false;
        }
    }

    public function updateProveedor($data) {
        $proveedor = $this->getProveedor($data['id']);
        $this->addDatosParticulares($proveedor, $data);
        $this->addDatosLaborales($proveedor, $data);
        $this->addDatosFacturacion($proveedor, $data);
        $this->personaManager->updatePersona($proveedor->getPersona(), $data);

        if ($this->tryUpdateProveedor($proveedor)) {
            $_SESSION['MENSAJES']['ficha_proveedor'] = 1;
            $_SESSION['MENSAJES']['ficha_proveedor_msj'] = 'Proveedor modificado correctamente';
        } else {
            $_SESSION['MENSAJES']['ficha_proveedor'] = 0;
            $_SESSION['MENSAJES']['ficha_proveedor_msj'] = 'Error al modificar proveedor';
        }

        return true;
    }

    private function tryUpdateProveedor($proveedor) {
        try {
            $this->entityManager->flush();
            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollBack();
            return false;
        }
    }

    private function addDatosParticulares($proveedor, $data) {
        $pais = $this->getPais($data['pais']);
        $provincia = $this->getProvincia($data['provincia']);
        $categoria = $this->getCategoriaProveedor($data['categoria']);

        $proveedor->setPais($pais)
                ->setProvincia($provincia)
                ->setCiudad($data['ciudad'])
                ->setCategoria($categoria);
    }

    private function addDatosLaborales($proveedor, $data) {
        $proveedor->setEmpresa($data['empresa'])
                ->setCargo($data['cargo'])
                ->setActividad($data['actividad']);
        if ($data['profesion'] == "-1") {
            $proveedor->setProfesion(null);
        } else {
            $profesion = $this->getProfesion($data['profesion']);
            $proveedor->setProfesion($profesion);
        }
    }

    private function addDatosFacturacion($proveedor, $data) {
        $condicion_iva = $this->getCategoriaProveedor($data['condicion_iva']);

        $proveedor->setRazon_social($data['razon_social'])
                ->setDireccion_facturacion($data['direccion_facturacion'])
                ->setCondicion_iva($condicion_iva)
                ->setBanco($data['banco'])
                ->setCbu($data['cbu'])
                ->setCuit_cuil($data['cuit_cuil']);
    }

   
    public function deleteProveedor($id) {
        $entityManager = $this->entityManager;
        $proveedor = $this->entityManager
                ->getRepository(Proveedor::class)
                ->findOneBy(['Id' => $id]);
        $this->borrarUsuariosFromProveedor($proveedor);
        $entityManager->remove($proveedor);
        $entityManager->flush();
    }

    public function modificarEstado($id) {
        $entityManager = $this->entityManager;
        $proveedor = $this->entityManager
                ->getRepository(Proveedor::class)
                ->findOneBy(['Id' => $id]);
        $estado_nuevo = $this->personaManager->cambiarEstado($proveedor->getPersona());
        if ($estado_nuevo == "N"){
            $_SESSION['MENSAJES']['listado_proveedors_msj'] = 'Proveedor dado de Baja correctamente';
        } else if ($estado_nuevo == "S") {
            $_SESSION['MENSAJES']['listado_proveedors_msj'] = 'Proveedor dado de Alta correctamente';
        }
        $_SESSION['MENSAJES']['listado_proveedors'] = 1;
        return $estado_nuevo;
    }

    //La funcion getListaProveedor() devuelve lista de proveedors sin paginado
    //Usada en la generacion de backup
    public function getListaProveedor() {
        $lista = $this->entityManager->getRepository(Proveedor::class)->findAll();
        return $lista;
    }

    public function getCategoriaProveedor($id = null) {
        if (isset($id)) {
            return $this->entityManager
                            ->getRepository(Categoria::class)
                            ->findOneBy(['id' => $id]);
        }
        return $this->entityManager
                        ->getRepository(Categoria::class)
                        ->findAll();
    }

    public function getCategoriasProveedor($tipo = null) {
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
            $proveedor= $this->entityManager
                            ->getRepository(Proveedor::class)
                            ->findOneBy(['Id' => $id]);
            return $proveedor->getEventos();
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


    public function eliminarCategoriaProveedor($id) {
        $proveedors = $this->entityManager->getRepository(Proveedor::class)->findBy(['categoria'=>$id]);
        foreach ($proveedors as $proveedor) {
            $proveedor->setCategoria(null);
        }
        $this->entityManager->flush();
    }

    public function eliminarProfesiones($id) {
        $proveedors = $this->entityManager->getRepository(Proveedor::class)->findBy(['profesion'=>$id]);
        foreach ($proveedors as $proveedor) {
             $proveedor->setProfesion(null);
        }
        $this->entityManager->flush();
    }

    public function getProveedorIdPersona($id_persona){
        $proveedor= $this->entityManager
                    ->getRepository(Proveedor::class)
                    ->findOneBy(['persona' => $id_persona]);
        return $proveedor;
    }

    public function eliminarCondicionIva($id){
        $proveedors = $this->entityManager->getRepository(Proveedor::class)->findBy(['condicion_iva'=>$id]);
        foreach ($proveedors as $proveedor) {
             $proveedor->setCondicion_iva(null);
        }
        $this->entityManager->flush();
    }
   
}
